@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">{{ $titulo ?? 'Políticas de citas' }}</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <p class="page-header-title">{{ $titulo ?? 'Políticas de citas' }}</p>
        <p class="page-header-sub">Cancelaciones, reprogramaciones y cargos por no-presentarse</p>
    </div>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('settings.policies.update') }}" method="POST">
            @csrf @method('PUT')

            <p class="surface-title mb-3">Ventanas de tiempo</p>
            <div class="settings-grid mb-4">
                <div class="settings-field">
                    <label class="filter-label" for="cancel_window_hours">
                        Horas mínimas para cancelar
                    </label>
                    <input type="number" name="cancel_window_hours" id="cancel_window_hours"
                        class="filter-input @error('cancel_window_hours') is-invalid @enderror"
                        value="{{ old('cancel_window_hours', $settings_barber->cancel_window_hours) }}"
                        min="0" max="168" placeholder="Ej: 24">
                    @error('cancel_window_hours')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="reschedule_window_hours">
                        Horas mínimas para reprogramar
                    </label>
                    <input type="number" name="reschedule_window_hours" id="reschedule_window_hours"
                        class="filter-input @error('reschedule_window_hours') is-invalid @enderror"
                        value="{{ old('reschedule_window_hours', $settings_barber->reschedule_window_hours) }}"
                        min="0" max="168" placeholder="Ej: 12">
                    @error('reschedule_window_hours')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="no_show_fee_colones">
                        Cargo por no-presentarse (%)
                    </label>
                    <input type="number" name="no_show_fee_colones" id="no_show_fee_colones"
                        class="filter-input @error('no_show_fee_colones') is-invalid @enderror"
                        value="{{ old('no_show_fee_colones', $noShowFeeColones) }}"
                        min="0" placeholder="Ej: 50">
                    @error('no_show_fee_colones')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="email_bcc">
                        BCC correo notificaciones
                        <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span>
                    </label>
                    <input type="email" name="email_bcc" id="email_bcc"
                        class="filter-input @error('email_bcc') is-invalid @enderror"
                        value="{{ old('email_bcc', $settings_barber->email_bcc) }}"
                        placeholder="notificaciones@ejemplo.com">
                    @error('email_bcc')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <p class="surface-title mb-3">Permisos de clientes</p>
            <div class="mb-4">
                <div class="settings-check-row">
                    <div style="flex:1;">
                        <div class="settings-check-label">Cancelación en línea</div>
                        <div class="settings-check-sub">Permite que los clientes cancelen citas desde el portal</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch"
                            id="allow_online_cancel" name="allow_online_cancel" value="1"
                            {{ old('allow_online_cancel', $settings_barber->allow_online_cancel) ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="settings-check-row">
                    <div style="flex:1;">
                        <div class="settings-check-label">Reprogramación en línea</div>
                        <div class="settings-check-sub">Permite que los clientes cambien la fecha/hora de su cita</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch"
                            id="allow_online_reschedule" name="allow_online_reschedule" value="1"
                            {{ old('allow_online_reschedule', $settings_barber->allow_online_reschedule) ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="s-btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
