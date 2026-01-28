@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <h2 class="text-center font-title"><strong>{{ __('Gestión de API de Instagram') }}</strong></h2>

        {{-- Navegación rápida --}}
        <div class="row mt-3 mb-4">
            <div class="col-md-3 mb-3">
                <a href="{{ url('/instagram/posts') }}" class="card text-decoration-none h-100">
                    <div class="card-body text-center">
                        <h5 class="mb-2">📸 Publicaciones</h5>
                        <p class="text-muted small mb-0">Ver y gestionar posts individuales</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ url('/instagram/collections') }}" class="card text-decoration-none h-100">
                    <div class="card-body text-center">
                        <h5 class="mb-2">🎠 Colecciones</h5>
                        <p class="text-muted small mb-0">Organizar carruseles con drag & drop</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ url('/instagram/caption-templates') }}" class="card text-decoration-none h-100">
                    <div class="card-body text-center">
                        <h5 class="mb-2">✨ Plantillas</h5>
                        <p class="text-muted small mb-0">Captions variados con Spintax</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ url('/instagram/caption-settings') }}" class="card text-decoration-none h-100">
                    <div class="card-body text-center">
                        <h5 class="mb-2">⚙️ Configuración</h5>
                        <p class="text-muted small mb-0">Hashtags, CTAs y opciones</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-3">Cuenta conectada</h5>

                @if ($account)
                    <div><strong>Conectado:</strong> {{ $account->instagram_username ?? 'N/A' }}</div>
                    <div><strong>Tipo:</strong> {{ $account->account_type ?? 'N/D' }}</div>

                    <form method="POST" action="{{ route('instagram.disconnect', $account->id) }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-accion" onclick="return confirm('¿Desconectar cuenta?')">Desconectar</button>
                    </form>
                @else
                    <div class="text-danger"><strong>No hay cuenta conectada.</strong></div>

                    <a href="{{ route('instagram.connect') }}" class="btn btn-accion mt-3">
                        Conectar Instagram
                    </a>
                @endif

            </div>
        </div>

    </div>
@endsection
