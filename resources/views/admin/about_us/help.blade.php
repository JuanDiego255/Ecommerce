@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>{{ __('Ayuda') }} </strong>
        </h2>

        <hr class="hr-servicios">
        <div class="card mt-3 mb-4">
            <div class="card-body text-center">
                <h4 class="text-muted">Descargar manual de usuario</h4>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
