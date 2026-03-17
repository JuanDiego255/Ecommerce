@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Tipos de pago</li>
@endsection
@section('content')
    <div class="d-flex gap-2 mb-0">
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-tipo-modal" class="btn btn-primary btn-sm">
            <span class="material-icons">add</span> {{ __('Nuevo tipo') }}
        </button>
    </div>
    @include('admin.tipo_pagos.add')
    <div class="s-card">
        <div class="s-card-header">
            <div class="card-h-icon"><span class="material-icons">tune</span></div>
            <span class="card-h-title">Filtros</span>
        </div>
        <div class="s-card-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
            <div>
                <label class="filter-label">Buscar</label>
                <input value="" placeholder="Escribe para filtrar..." type="text"
                    class="filter-input" name="searchfor" id="searchfor">
            </div>
            <div>
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                    <option value="5">5 registros</option>
                    <option value="10">10 registros</option>
                    <option selected value="15">15 registros</option>
                    <option value="50">50 registros</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Tipo') }}
                                </th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tipos as $item)
                                <tr>
                                    <td class="align-middle">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-tipo-modal{{ $item->id }}" class="btn btn-accion"
                                            style="text-decoration: none;">Editar</button>

                                        <form method="post" action="{{ url('/delete/tipo_pago/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este tipo de pago?')"
                                                class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                        </form>

                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->tipo }}
                                        </p>
                                    </td>                                    
                                    @include('admin.tipo_pagos.edit')
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
