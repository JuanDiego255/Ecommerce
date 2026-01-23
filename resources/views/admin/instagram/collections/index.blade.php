@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>{{ __('Colecciones de Instagram') }}</strong></h2>
            <a href="{{ url('/instagram/collections/create') }}" class="btn btn-accion">{{ __('+ Nueva colección') }}</a>
        </div>

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                @if ($collections->count() == 0)
                    <div class="text-muted">{{ __('Aún no hay colecciones.') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Status</th>
                                    <th>Creada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($collections as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->name }}</td>
                                        <td>{{ $c->status }}</td>
                                        <td>{{ $c->created_at }}</td>
                                        <td>
                                            <a class="btn btn-accion btn-sm"
                                                href="{{ url('/instagram/collections/' . $c->id . '/edit') }}">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
