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

        <div class="card mt-3">
            <div class="card-body">

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
