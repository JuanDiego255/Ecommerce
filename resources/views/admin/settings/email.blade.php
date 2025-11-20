@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title"><strong>Configuración para envío de correos</strong></h2>
    </center>

    @if (session('ok'))
        <div class="alert alert-success mt-3 text-white">{{ session('ok') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('settings.email.update') }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('username', isset($setting_email->username) ? $setting_email->username : '') !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Nombre de usuario</label>
                            <input type="text" name="username" id="username"
                                class="form-control form-control-lg @error('cancel_window_hours') is-invalid @enderror"
                                value="{{ old('username', isset($setting_email->username) ? $setting_email->username : '') }}"
                                min="0" max="168">
                            @error('username')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('from_address', isset($setting_email->from_address) ? $setting_email->from_address : '') !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Dirección de correo</label>
                            <input type="text" name="from_address" id="from_address"
                                class="form-control form-control-lg @error('cancel_window_hours') is-invalid @enderror"
                                value="{{ old('from_address', isset($setting_email->from_address) ? $setting_email->from_address : '') }}"
                                min="0" max="168">
                            @error('from_address')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('from_name', isset($setting_email->from_name) ? $setting_email->from_name : '') !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Titular</label>
                            <input type="text" name="from_name" id="from_name"
                                class="form-control form-control-lg @error('cancel_window_hours') is-invalid @enderror"
                                value="{{ old('from_name', isset($setting_email->from_name) ? $setting_email->from_name : '') }}"
                                min="0" max="168">
                            @error('from_name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="input-group input-group-lg input-group-outline {{ old('password', isset($setting_email->password) ? $setting_email->password : '') !== null ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                value="{{ old('password', isset($setting_email->password) ? $setting_email->password : '') }}"
                                min="0" max="168">
                            @error('password')
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
