@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Meta tags</li>
@endsection
@section('content')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">filter_list</span></div>
        <span class="card-h-title">Filtros</span>
        <div class="card-h-actions">
            <a href="{{ url('metatag/agregar') }}" class="btn btn-primary btn-sm">
                <span class="material-icons">add</span> Nueva sección
            </a>
        </div>
    </div>
    <div class="s-card-body" style="display:grid;grid-template-columns:1fr 180px;gap:12px;">
        <div>
            <label class="filter-label">Filtrar</label>
            <input value="" placeholder="Escribe para filtrar...." type="text"
                class="filter-input" name="searchfor" id="searchfor">
        </div>
        <div>
            <label class="filter-label">Mostrar</label>
            <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                <option value="5">5 Registros</option>
                <option value="10">10 Registros</option>
                <option selected value="15">15 Registros</option>
                <option value="50">50 Registros</option>
            </select>
        </div>
    </div>
</div>
    <div class="container">
        <div class="card p-2">
            <div class="table-responsive">
                <table id="table" class="table align-items-center mb-0" style="width: 95%;">
                    <thead class="">
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Acciones') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Sección') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Title') }}
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Meta Keywords') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('OG Title') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('OG Image') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('URL Canonical') }}</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Type') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($metatags as $tag)
                            <tr>
                                <td class="align-middle">
                                    <center>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"
                                            data-container="body" data-animation="true" class="btn btn-accion"
                                            style="text-decoration: none;" href="{{ url('metatag/edit/' . $tag->id) }}"><i
                                                class="material-icons opacity-10">edit</i></a>
                                        <form method="post" action="{{ url('/delete-metatag/' . $tag->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button class="btn btn-admin-delete text-white btn-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"
                                                data-container="body" data-animation="true" type="submit"> <i
                                                    class="material-icons opacity-10">
                                                    delete

                                                </i>
                                            </button>
                                        </form>
                                    </center>
                                </td>
                                <td class="align-middle text-center">{{ $tag->section }}</td>
                                <td class="align-middle text-center">{{ $tag->title }}</td>
                                <td class="align-middle text-center">{{ $tag->meta_keywords }}</td>
                                <td class="align-middle text-center">{{ $tag->meta_og_title }}</td>
                                <td class="align-middle text-center">{{ $tag->url_image_og }}</td>
                                <td class="align-middle text-center">{{ $tag->url_canonical }}</td>
                                <td class="align-middle text-center">{{ $tag->meta_type }}</td>
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
