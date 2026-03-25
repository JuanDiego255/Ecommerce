@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Configuración de correo</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <p class="page-header-title">Configuración de correo</p>
        <p class="page-header-sub">Credenciales SMTP para el envío de notificaciones</p>
    </div>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('settings.email.update') }}" method="POST">
            @csrf @method('PUT')

            <p class="surface-title mb-3">Cuenta de envío</p>
            <div class="settings-grid mb-4">
                <div class="settings-field">
                    <label class="filter-label" for="username">Nombre de usuario</label>
                    <input type="text" name="username" id="username"
                        class="filter-input @error('username') is-invalid @enderror"
                        value="{{ old('username', $setting_email->username ?? '') }}"
                        autocomplete="username" placeholder="usuario@gmail.com">
                    @error('username')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="from_address">Dirección de correo remitente</label>
                    <input type="email" name="from_address" id="from_address"
                        class="filter-input @error('from_address') is-invalid @enderror"
                        value="{{ old('from_address', $setting_email->from_address ?? '') }}"
                        placeholder="noreply@minegocio.com">
                    @error('from_address')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="from_name">Nombre remitente (titular)</label>
                    <input type="text" name="from_name" id="from_name"
                        class="filter-input @error('from_name') is-invalid @enderror"
                        value="{{ old('from_name', $setting_email->from_name ?? '') }}"
                        placeholder="Mi Negocio">
                    @error('from_name')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="settings-field">
                    <label class="filter-label" for="password">Contraseña SMTP</label>
                    <input type="password" name="password" id="password"
                        class="filter-input @error('password') is-invalid @enderror"
                        value="{{ old('password', $setting_email->password ?? '') }}"
                        autocomplete="new-password" placeholder="••••••••">
                    @error('password')
                        <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="s-btn-primary">Guardar configuración</button>
            </div>
        </form>
    </div>
</div>
@endsection
