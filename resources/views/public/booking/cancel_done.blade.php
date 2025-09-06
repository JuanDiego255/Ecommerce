@extends('layouts.public')
@section('content')
    <div class="container py-5">
        <h3>Tu cita fue cancelada</h3>
        <p>Gracias por avisarnos. Si deseas agendar una nueva cita, <a href="{{ url('/') }}">regresa al inicio</a>.</p>
    </div>
@endsection
