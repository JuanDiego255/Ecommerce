@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title"><strong>{{ $titulo ?? 'Políticas de Citas' }}</strong></h2>
    </center>

    @if (session('ok'))
        <div class="alert alert-success mt-3">{{ session('ok') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('settings.policies.update') }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('cancel_window_hours', $settings_barber->cancel_window_hours) !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Horas mínimas para cancelar</label>
                            <input type="number" name="cancel_window_hours" id="cancel_window_hours"
                                class="form-control form-control-lg @error('cancel_window_hours') is-invalid @enderror"
                                value="{{ old('cancel_window_hours', $settings_barber->cancel_window_hours) }}" min="0"
                                max="168">
                            @error('cancel_window_hours')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('reschedule_window_hours', $settings_barber->reschedule_window_hours) !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Horas mínimas para reprogramar</label>
                            <input type="number" name="reschedule_window_hours" id="reschedule_window_hours"
                                class="form-control form-control-lg @error('reschedule_window_hours') is-invalid @enderror"
                                value="{{ old('reschedule_window_hours', $settings_barber->reschedule_window_hours) }}"
                                min="0" max="168">
                            @error('reschedule_window_hours')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" id="allow_online_cancel"
                                name="allow_online_cancel" value="1"
                                {{ old('allow_online_cancel', $settings_barber->allow_online_cancel) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_online_cancel">Permitir cancelación en línea</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check my-3">
                            <input class="form-check-input" type="checkbox" id="allow_online_reschedule"
                                name="allow_online_reschedule" value="1"
                                {{ old('allow_online_reschedule', $settings_barber->allow_online_reschedule) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_online_reschedule">Permitir reprogramación en
                                línea</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($noShowFeeColones) && $noShowFeeColones !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Cargo por no-presentarse (%)</label>
                            <input type="number" name="no_show_fee_colones" id="no_show_fee_colones"
                                class="form-control form-control-lg @error('no_show_fee_colones') is-invalid @enderror"
                                value="{{ old('no_show_fee_colones', $noShowFeeColones) }}" min="0">
                            @error('no_show_fee_colones')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('email_bcc', $settings_barber->email_bcc) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">BCC correo notificaciones (opcional)</label>
                            <input type="email" name="email_bcc" id="email_bcc"
                                class="form-control form-control-lg @error('email_bcc') is-invalid @enderror"
                                value="{{ old('email_bcc', $settings_barber->email_bcc) }}">
                            @error('email_bcc')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <center>
                    <button type="submit" class="btn btn-accion">Guardar</button>
                </center>
            </form>
        </div>
    </div>
@endsection
