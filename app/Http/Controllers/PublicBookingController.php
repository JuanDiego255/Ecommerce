<?php

// app/Http/Controllers/PublicBookingController.php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Support\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class PublicBookingController extends Controller
{
    public function cancel(Request $request, Cita $cita)
    {
        $tenantId = tenant('id') ?? config('app.name'); // ajusta según tu tenancy
        $set = TenantSettings::get($tenantId);

        if (!$set->allow_online_cancel) {
            return $this->deny('Las cancelaciones en línea no están habilitadas.');
        }

        $tz = config('app.timezone', 'America/Costa_Rica');
        $nowLocal = Carbon::now($tz);
        $startLocal = $cita->starts_at->timezone($tz);

        $diff = $startLocal->diffInHours($nowLocal, false); // negativo si falta tiempo
        if ($diff > (-1 * (int)$set->cancel_window_hours)) {
            return $this->deny("Solo puedes cancelar hasta {$set->cancel_window_hours} horas antes.");
        }

        // Cambia estado
        $cita->update(['status' => 'cancelled']);

        // (Opcional) registrar fee no-show si aplica (aquí solo informativo)
        // if ($set->no_show_fee_cents > 0) { ... }

        // Email al cliente ya sale por Observer si lo mantienes; si no, envíalo aquí.
        // Mail::send('emails.cancelled', [...], fn(...) => ...);

        return view('public.booking.cancel_done', ['cita' => $cita]);
    }

    public function reschedule(Request $request, Cita $cita)
    {
        $tenantId = tenant('id') ?? config('app.name');
        $set = TenantSettings::get($tenantId);

        if (!$set->allow_online_reschedule) {
            return $this->deny('La reprogramación en línea no está habilitada.');
        }

        $tz = config('app.timezone', 'America/Costa_Rica');
        $nowLocal = Carbon::now($tz);
        $startLocal = $cita->starts_at->timezone($tz);

        $diff = $startLocal->diffInHours($nowLocal, false);
        if ($diff > (-1 * (int)$set->reschedule_window_hours)) {
            return $this->deny("Solo puedes reprogramar hasta {$set->reschedule_window_hours} horas antes.");
        }

        // Aquí puedes redirigir a tu landing de reprogramación con el ID de la cita,
        // o mostrar un selector de nueva fecha/hora. Por ahora, solo redirecciono a una vista.
        return view('public.booking.reschedule_picker', ['cita' => $cita]);
    }

    private function deny(string $msg)
    {
        return response()->view('public.booking.denied', ['message' => $msg], 403);
    }
}
