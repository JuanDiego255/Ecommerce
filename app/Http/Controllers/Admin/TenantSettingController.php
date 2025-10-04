<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use App\Support\TenantSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TenantSettingController extends Controller
{
    public function index()
    {
        $tenantId = TenantInfo::first()->tenant;
        $settings_barber = TenantSetting::where('tenant_id', $tenantId)->first();
        // Para la vista mostramos el no_show_fee en colones (no en céntimos)
        $noShowFeeColones = (int) round(($settings_barber->no_show_fee_cents ?? 0) / 100);

        return view('admin.settings.policies', [
            'settings_barber' => $settings_barber,
            'noShowFeeColones' => $noShowFeeColones,
            'titulo' => 'Políticas de Citas',
        ]);
    }

    public function update(Request $request)
    {
        $tenantId = TenantInfo::first()->tenant;
        $data = $request->validate([
            'cancel_window_hours'     => 'required|integer|min:0|max:168',
            'reschedule_window_hours' => 'required|integer|min:0|max:168',
            'allow_online_cancel'     => 'nullable|boolean',
            'allow_online_reschedule' => 'nullable|boolean',
            'no_show_fee_colones'     => 'nullable|integer|min:0|max:10000000',
            'email_bcc'               => 'nullable|email',
        ]);

        // Normalizar booleans (checkboxes)
        $data['allow_online_cancel']     = $request->boolean('allow_online_cancel');
        $data['allow_online_reschedule'] = $request->boolean('allow_online_reschedule');

        // Convertir colones → céntimos
        $noShowFeeCents = isset($data['no_show_fee_colones'])
            ? (int) $data['no_show_fee_colones'] * 100
            : 0;

        $settings = TenantSetting::firstOrCreate(['tenant_id' => $tenantId]);

        $settings->fill([
            'cancel_window_hours'     => $data['cancel_window_hours'],
            'reschedule_window_hours' => $data['reschedule_window_hours'],
            'allow_online_cancel'     => $data['allow_online_cancel'],
            'allow_online_reschedule' => $data['allow_online_reschedule'],
            'no_show_fee_cents'       => $noShowFeeCents,
            'email_bcc'               => $data['email_bcc'] ?? null,
        ])->save();

        // Limpiar caché de helper
        Cache::forget("tenant_settings:{$tenantId}");

        return back()->with('ok', 'Políticas guardadas correctamente.');
    }
}
