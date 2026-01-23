<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Barbero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AutoBookingController extends Controller
{
    // ✅ Aceptar propuesta automática
    public function accept(Request $request, Cita $cita)
    {
        // Reglas básicas
        if (!$cita->is_auto || $cita->status !== 'pending') {
            return $this->backWithMsg('Esta propuesta ya no está disponible.', 'danger');
        }
        if ($cita->hold_expires_at && now()->greaterThan($cita->hold_expires_at)) {
            return $this->backWithMsg('La propuesta venció. Vuelve a solicitar una nueva.', 'danger');
        }

        // Confirmar
        $cita->update([
            'status' => 'confirmed',
            // opcional: limpiar hold_expires_at
        ]);

        // (Opcional) enviar email de confirmación normal
        // Mail::to($cita->cliente_email)->queue(new \App\Mail\AppointmentApproved($cita));

        return $this->backWithMsg('¡Tu cita fue confirmada!', 'success');
    }

    // ❌ Rechazar propuesta automática
    public function decline(Request $request, Cita $cita)
    {
        if ($cita->status === 'completed') {
            return $this->backWithMsg('Esta propuesta ya no está disponible.', 'danger');
        }

        $cita->update(['status' => 'cancelled']);

        return $this->backWithMsg('Has rechazado la propuesta. ¡Gracias por avisar!', 'success');
    }

    // 🕑 Formulario para reprogramar (mismo estilo del booking público)
    public function reschedForm(Request $request, Cita $cita)
    {
        if (!$cita->is_auto || !in_array($cita->status, ['pending', 'confirmed'])) {
            return $this->backWithMsg('Esta cita no se puede reprogramar.', 'danger');
        }
        // Opcional: chequear ventana de reprogramación por políticas
        // (p.ej. must be >= resched_hours antes)

        $barbero = $cita->barbero;
        if (!$barbero) {
            return $this->backWithMsg('No se encontró el barbero.', 'danger');
        }

        // Datos para la vista:
        // - servicios disponibles para ese barbero, si quieres permitir cambiarlos
        // - fecha/hora preseleccionadas (cita actual)
        $tz = config('app.timezone', 'America/Costa_Rica');

        // Si ya tienes un “helper” para construir slots disponibles en una fecha, lo consumes vía AJAX.
        // Aquí solo cargamos lo básico para montar el form.
        $payload = [
            'cita'     => $cita,
            'barbero'  => $barbero,
            'tz'       => $tz,
            // Si usas select de fecha/hora via AJAX, no necesitas precargar mucho más aquí
        ];

        return view('auto_book.resched', $payload);
    }

    // 🔁 Aplicar la reprogramación
    public function reschedApply(Request $request, Cita $cita)
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date|after:start',
        ]);

        if (!$cita->is_auto || !in_array($cita->status, ['pending', 'confirmed'])) {
            return back()->with('alert', ['type' => 'danger', 'msg' => 'Esta cita no se puede reprogramar.']);
        }

        $tz = config('app.timezone', 'America/Costa_Rica');
        $barbero = $cita->barbero;
        if (!$barbero) {
            return back()->with('alert', ['type' => 'danger', 'msg' => 'No se encontró el barbero.']);
        }

        // Parse de fechas a TZ local
        $startLocal = Carbon::parse($request->input('start'))->setTimezone($tz);
        $endLocal   = Carbon::parse($request->input('end'))->setTimezone($tz);

        // —— VALIDACIONES de disponibilidad (idénticas a tu Calendar/Booking) ——
        // días laborables, horario, excepciones, bloques, solape citas, buffer
        // Reutiliza los mismos métodos que ya usas:
        if (!$this->passesAvailabilityRules($barbero, $startLocal, $endLocal, $cita->id)) {
            return back()->with('alert', ['type' => 'danger', 'msg' => 'Ese horario no está disponible. Intenta con otro.']);
        }

        // Validar que el slot no esté reservado por un cliente con auto-booking fijo
        $dayOfWeek = $startLocal->dayOfWeek; // 0=domingo, 1=lunes, ..., 6=sábado
        $time24 = $startLocal->format('H:i');
        $clientEmail = $cita->cliente_email;

        $blockedByAutoBooking = \App\Models\Client::where('auto_book_opt_in', true)
            ->where('preferred_barbero_id', $barbero->id)
            ->where('email', '!=', $clientEmail) // Excluir al cliente que está reprogramando
            ->get()
            ->filter(function ($client) use ($dayOfWeek, $time24) {
                // Verificar que preferred_days contenga el día de la semana
                $preferredDays = $client->preferred_days;
                if (!is_array($preferredDays) || !in_array($dayOfWeek, $preferredDays)) {
                    return false;
                }

                // Verificar que la hora coincida
                $preferredStart = $client->preferred_start;
                if (!$preferredStart) {
                    return false;
                }

                // Normalizar la hora preferida a formato H:i
                $preferredStartNormalized = substr($preferredStart, 0, 5);

                return $preferredStartNormalized === $time24;
            })
            ->first();

        if ($blockedByAutoBooking) {
            return back()->with('alert', [
                'type' => 'danger',
                'msg' => sprintf(
                    'Este horario está reservado de forma fija para %s. Por favor selecciona otro horario.',
                    $blockedByAutoBooking->nombre
                )
            ]);
        }

        // Guardar
        $cita->update([
            'starts_at' => $startLocal, // si guardas UTC: ->copy()->timezone('UTC')
            'ends_at'   => $endLocal,
            'status'    => 'confirmed', // si quieres confirmar de una vez al reprogramar
            // opcional: limpiar hold_expires_at
        ]);

        // (Opcional) correo de confirmación
        // Mail::to($cita->cliente_email)->queue(new \App\Mail\AppointmentApproved($cita));

        return redirect()->route('citas.show', $cita->id)
            ->with('ok', 'Tu cita fue reprogramada y confirmada.');
    }

    // —— Helpers ——
    protected function passesAvailabilityRules(Barbero $barbero, Carbon $startLocal, Carbon $endLocal, int $ignoreCitaId = 0): bool
    {
        // Copia aquí la lógica que ya usamos en CalendarController::reschedule()
        // (workDays, horario base, excepciones, bloques, buffer, solape con citas)
        // Devuelve true/false.

        $tz = config('app.timezone', 'America/Costa_Rica');

        $workDays  = $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5];
        if (!in_array($startLocal->dayOfWeek, $workDays)) return false;

        $ws = Carbon::createFromFormat('Y-m-d H:i', $startLocal->toDateString() . ' ' . substr($barbero->work_start ?? '09:00', 0, 5), $tz);
        $we = Carbon::createFromFormat('Y-m-d H:i', $startLocal->toDateString() . ' ' . substr($barbero->work_end ?? '18:00', 0, 5),   $tz);
        if ($startLocal->lt($ws) || $endLocal->gt($we)) return false;

        // excepción del día
        if ($barbero->excepciones()->whereDate('date', $startLocal->toDateString())->exists()) return false;

        // bloques y citas del día
        $bloques = $barbero->bloques()->whereDate('date', $startLocal->toDateString())->get();

        $citas = \App\Models\Cita::where('barbero_id', $barbero->id)
            ->whereDate('starts_at', $startLocal->toDateString())
            ->where('id', '!=', $ignoreCitaId)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get(['starts_at', 'ends_at']);

        $bufferMin = (int)($barbero->buffer_minutes ?? 0);
        $candStart = $startLocal->copy();
        $candEnd   = $endLocal->copy()->addMinutes($bufferMin);

        $overlaps = fn($A1, $A2, $B1, $B2) => ($A1 < $B2) && ($A2 > $B1);

        foreach ($citas as $c) {
            $cStart = $c->starts_at->copy()->timezone($tz);
            $cEnd   = $c->ends_at->copy()->timezone($tz);
            if ($overlaps($candStart, $candEnd, $cStart, $cEnd)) return false;
        }
        foreach ($bloques as $b) {
            $bStart = Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->start_time, 0, 5), $tz);
            $bEnd   = Carbon::createFromFormat('Y-m-d H:i', $b->date . ' ' . substr($b->end_time, 0, 5),   $tz);
            if ($overlaps($candStart, $candEnd, $bStart, $bEnd)) return false;
        }

        return true;
    }

    protected function backWithMsg(string $msg, string $type = 'info')
    {
        // Si viene desde enlace de email, regrésalo a una vista “gracias” simple
        return view('auto_book.feedback', ['type' => $type, 'msg' => $msg]);
    }
}
