@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Departamentos</li>
@endsection
@section('content')
    <div class="d-flex gap-2 mb-0">
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-department-modal"
            class="btn btn-primary btn-sm">
            <span class="material-icons">add</span> {{ __('Nuevo departamento') }}
        </button>
    </div>
    @include('admin.departments.add')
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

    <div class="container">
            <div class="table-responsive">
                <table id="table" class="table align-items-center mb-0">
                    <thead>
                        <tr>

                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('departamento') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $item)
                            <tr>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $item->department }}</p>
                                </td>
                                <td class="align-middle">
                                    <center>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-department-modal{{ $item->id }}"
                                            class="btn btn-accion"
                                            style="text-decoration: none;">{{ __('Editar') }}</button>

                                        <form method="post" action="{{ url('/delete/department/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este departamento?')"
                                                class="btn btn-accion"
                                                style="text-decoration: none;">{{ __('Borrar') }}</button>
                                        </form>
                                        <a href="{{ url('categories/' . $item->id) }}"
                                            class="btn btn-accion">{{ __('Ver categorías') }}</a>
                                    </center>
                                </td>
                                @include('admin.departments.edit')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
