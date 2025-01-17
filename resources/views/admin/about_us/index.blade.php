@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>{{ __('Acerca De') }} </strong>
        </h2>

        <hr class="hr-servicios">
        <div class="card mt-3 mb-4">
            <div class="card-body text-center">
                <h4 class="text-muted">Desarrollador: Maykel Garita Zuñiga</h4>
                <h4 class="text-muted">Lenguaje de programación: PHP</h4>
                <h4 class="text-muted">Versión del sistema: 1.0</h4>
                <h4 class="text-muted">Fecha de Versión: 15-Ene-2025</h4>
                <h4 class="text-muted">Propietario: Ana Campos Villegas</h4>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
