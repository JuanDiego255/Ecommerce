@extends('layouts.frontbarber')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="mb-3">{{ $type === 'success' ? '¡Listo!' : 'Aviso' }}</h4>
                <p class="mb-0">{{ $msg }}</p>
                <div class="mt-3">
                    <a href="{{ url('/') }}" class="btn btn-velvet">Ir al inicio</a>
                </div>
            </div>
        </div>
    </div>
@endsection
