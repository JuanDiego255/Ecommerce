@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Suscriptores</li>
@endsection
@section('content')
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
        <div class="card p-2">
            <div class="table-responsive">
                <table id="table" class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Acciones') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Nombre Tutor') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Nombre Bebé') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('E-mail') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Teléfono') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Cumpleaños') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suscriptors as $item)
                            <tr>
                                <td class="align-middle">
                                    <center>
                                        <form style="display:inline" action="{{ url('suscriptor/delete/' . $item->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-accion text-white btn-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar suscriptor" data-container="body"
                                                data-animation="true" type="submit"><i class="material-icons opacity-10">
                                                    delete
                                                </i>
                                            </button>
                                        </form>
                                    </center>
                                </td>
                                
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">
                                        {{ $item->tutor_name }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">
                                        {{ $item->name }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">
                                        {{ $item->email }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">
                                        {{ $item->telephone }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">
                                        {{ $item->birthday }}</p>
                                </td>
                                
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
