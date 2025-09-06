<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbero;
use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function barberoCalendar(Barbero $barbero)
    {
        // Preparar business hours / slot y work days para la vista
        $workDays   = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        $slot       = (int)($barbero->slot_minutes ?? 30);
        $workStart  = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd    = substr($barbero->work_end   ?? '18:00', 0, 5);

        return view('admin.calendars.barbero', compact('barbero', 'workDays', 'slot', 'workStart', 'workEnd'));
    }

    public function barberoEvents(Request $request, Barbero $barbero)
    {
        $rangeStart = \Carbon\Carbon::parse($request->query('start', now()->startOfWeek()));
        $rangeEnd   = \Carbon\Carbon::parse($request->query('end',   now()->endOfWeek()));
        $tz = config('app.timezone', 'America/Costa_Rica');

        // 1) Citas
        $citas = \App\Models\Cita::with('barbero')
            ->where('barbero_id', $barbero->id)
            ->where(function ($q) use ($rangeStart, $rangeEnd) {
                $q->whereBetween('starts_at', [$rangeStart, $rangeEnd])
                    ->orWhereBetween('ends_at',   [$rangeStart, $rangeEnd])
                    ->orWhere(function ($q2) use ($rangeStart, $rangeEnd) {
                        $q2->where('starts_at', '<', $rangeStart)
                            ->where('ends_at',   '>', $rangeEnd);
                    });
            })
            ->get();

        $statusColors = [
            'pending'   => '#9CA3AF',
            'confirmed' => '#0ea5e9',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
        ];

        $events = [];
        foreach ($citas as $c) {
            $cStart = $c->starts_at->copy()->setTimezone($tz)->format('Y-m-d\TH:i:s');
            $cEnd   = $c->ends_at->copy()->setTimezone($tz)->format('Y-m-d\TH:i:s');

            $events[] = [
                'id'    => $c->id,
                'title' => $c->cliente_nombre . ' (₡' . number_format((int)$c->total_cents / 100, 0, ',', '.') . ')',
                'start' => $cStart,
                'end'   => $cEnd,
                'color' => $statusColors[$c->status] ?? '#9CA3AF',
                'extendedProps' => ['type' => 'cita', 'status' => $c->status],
            ];
        }

        // 2) Bloques
        $bloques = $barbero->bloques()
            ->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->orderBy('date')->get();

        foreach ($bloques as $b) {
            $bStart = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->start_time, 0, 5), $tz)
                ->format('Y-m-d\TH:i:s');
            $bEnd   = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->end_time, 0, 5), $tz)
                ->format('Y-m-d\TH:i:s');

            $events[] = [
                'id'      => 'bl-' . $b->id,
                'title'   => 'No disponible' . ($b->motivo ? ' — ' . $b->motivo : ''),
                'start'   => $bStart,
                'end'     => $bEnd,
                'color'   => '#f59e0b',
                'display' => 'block',
                'extendedProps' => [
                    'type' => 'bloque',
                    'block_id' => $b->id
                ],
            ];
        }

        // 3) Excepciones (día completo, fondo)
        $excepciones = $barbero->excepciones()
            ->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->get();

        foreach ($excepciones as $ex) {
            $events[] = [
                'id'      => 'ex-' . $ex->id,
                'start'   => \Carbon\Carbon::parse($ex->date, $tz)->toDateString(),
                'end'     => \Carbon\Carbon::parse($ex->date, $tz)->addDay()->toDateString(), // end exclusivo
                'display' => 'background',
                'color'   => '#ffe2e2',
                'extendedProps' => ['type' => 'excepcion', 'motivo' => $ex->motivo],
                'allDay'  => true,
            ];
        }

        return response()->json($events);
    }

    // app/Http/Controllers/Admin/CalendarController.php

    public function reschedule(Request $request, \App\Models\Cita $cita)
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date|after:start',
        ]);

        $tz = config('app.timezone', 'America/Costa_Rica');

        // 1) Parseo en horario local del negocio
        $startStr = $request->input('start');
        $endStr   = $request->input('end');

        $newStartLocal = \Carbon\Carbon::parse($startStr)->setTimezone($tz);
        $newEndLocal   = \Carbon\Carbon::parse($endStr)->setTimezone($tz);

        // 2) Validaciones (laborables, horario, excepciones, bloques, solapes, buffer)
        $barbero  = $cita->barbero()->firstOrFail();
        $workDays = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        $dayIdx   = $newStartLocal->dayOfWeek; // 0..6

        if (!in_array($dayIdx, $workDays)) {
            return response()->json(['ok' => false, 'msg' => 'Fuera de días laborables'], 422);
        }

        $workStart = substr($barbero->work_start ?? '09:00', 0, 5);
        $workEnd   = substr($barbero->work_end   ?? '18:00', 0, 5);

        $ws = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $newStartLocal->toDateString() . ' ' . $workStart, $tz);
        $we = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $newStartLocal->toDateString() . ' ' . $workEnd,   $tz);

        if ($newStartLocal->lt($ws) || $newEndLocal->gt($we)) {
            return response()->json(['ok' => false, 'msg' => 'Fuera del horario laboral'], 422);
        }

        // Excepciones de día completo
        $isException = $barbero->excepciones()
            ->whereDate('date', $newStartLocal->toDateString())
            ->exists();
        if ($isException) {
            return response()->json(['ok' => false, 'msg' => 'Día bloqueado por excepción'], 422);
        }

        // Bloques horarios de ese día
        $bloques = $barbero->bloques()
            ->whereDate('date', $newStartLocal->toDateString())
            ->get();

        // Otras citas del día (del mismo barbero)
        $citas = \App\Models\Cita::where('barbero_id', $barbero->id)
            ->whereDate('starts_at', $newStartLocal->toDateString())
            ->where('id', '!=', $cita->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get(['starts_at', 'ends_at']);

        $bufferMin = (int)($barbero->buffer_minutes ?? 0);
        $candStart = $newStartLocal->copy();
        $candEnd   = $newEndLocal->copy()->addMinutes($bufferMin);

        $overlaps = fn($A1, $A2, $B1, $B2) => ($A1 < $B2) && ($A2 > $B1);

        foreach ($citas as $c) {
            // convertir desde UTC a local para comparar correctamente
            $cStartLocal = $c->starts_at->copy()->timezone($tz);
            $cEndLocal   = $c->ends_at->copy()->timezone($tz);
            if ($overlaps($candStart, $candEnd, $cStartLocal, $cEndLocal)) {
                return response()->json(['ok' => false, 'msg' => 'Choque con otra cita'], 422);
            }
        }

        foreach ($bloques as $b) {
            $bStartLocal = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->start_time, 0, 5), $tz);
            $bEndLocal   = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->end_time, 0, 5),   $tz);
            if ($overlaps($candStart, $candEnd, $bStartLocal, $bEndLocal)) {
                return response()->json(['ok' => false, 'msg' => 'Dentro de un bloque no disponible'], 422);
            }
        }

        // (Opcional) política de ventana mínima para reprogramar (por tenant)
        // $set = \App\Support\TenantSettings::get(tenant('id') ?? config('app.name'));
        // $hoursToStart = $cita->starts_at->timezone($tz)->diffInHours(now($tz), false);
        // if ($hoursToStart > (-1 * (int)$set->reschedule_window_hours)) {
        //     return response()->json(['ok'=>false,'msg'=>"Solo reprogramable hasta {$set->reschedule_window_hours} h antes."], 422);
        // }

        // 3) Guardar en UTC (consistencia)
        $newStartUtc = $newStartLocal->copy()->timezone('UTC');
        $newEndUtc   = $newEndLocal->copy()->timezone('UTC');

        $cita->update([
            'starts_at' => $newStartLocal,
            'ends_at'   => $newEndLocal,
        ]);

        // (Opcional) lanzar notificación de reprogramación aquí si no usas Observer para 'updated'
        // Mail::to(...)->queue(new \App\Mail\AppointmentUpdated($cita));

        return response()->json(['ok' => true]);
    }



    public function quickBlock(Request $request, \App\Models\Barbero $barbero)
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date|after:start',
            'motivo' => 'nullable|string|max:120',
        ]);
        $tz = config('app.timezone', 'America/Costa_Rica');
        $start = \Carbon\Carbon::parse($request->input('start'), $tz);
        $end   = \Carbon\Carbon::parse($request->input('end'),   $tz);

        if ($start->toDateString() !== $end->toDateString()) {
            return response()->json(['ok' => false, 'msg' => 'El bloque debe ser en el mismo día'], 422);
        }

        // Validaciones mínimas (puedes reciclar las de arriba si quieres)
        $workDays = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        if (!in_array($start->dayOfWeek, $workDays)) {
            return response()->json(['ok' => false, 'msg' => 'Fuera de días laborables'], 422);
        }
        $isException = $barbero->excepciones()->whereDate('date', $start->toDateString())->exists();
        if ($isException) {
            return response()->json(['ok' => false, 'msg' => 'Día bloqueado por excepción'], 422);
        }

        // Guardar bloque
        $barbero->bloques()->create([
            'date' => $start->toDateString(),
            'start_time' => $start->format('H:i:00'),
            'end_time'   => $end->format('H:i:00'),
            'motivo' => $request->input('motivo'),
        ]);

        return response()->json(['ok' => true]);
    }
}
