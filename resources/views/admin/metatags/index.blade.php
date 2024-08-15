@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>{{ __('Gestiona los SEO Tools para posicionar su sitio web') }}</strong>
        </h2>

        <hr class="hr-servicios">
        <a href="{{ url('metatag/agregar') }}" class="btn btn-velvet">{{ __('Nueva sección') }}</a>
        <div class="card mt-3 mb-3">
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Filtrar</label>
                            <input value="" placeholder="Escribe para filtrar...." type="text"
                                class="form-control form-control-lg" name="searchfor" id="searchfor">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Mostrar</label>
                            <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                                autocomplete="recordsPerPage">
                                <option value="5">5 Registros</option>
                                <option value="10">10 Registros</option>
                                <option selected value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <center>
            <div class="card w-100 mb-4">
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
                                                data-container="body" data-animation="true" class="btn btn-velvet"
                                                style="text-decoration: none;"
                                                href="{{ url('metatag/edit/' . $tag->id) }}"><i
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
        </center>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
