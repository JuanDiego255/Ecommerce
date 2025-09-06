@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administra los resultados médicos desde acá') }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('blog-result/' . $blog_id . '/add') }}"
                class="btn btn-accion w-100">{{ __('Agregar nuevo resultado') }}</a>
        </div>
    </div>
    <div class="card mt-3">
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
                            <option selected value="15">15 Registros</option>
                            <option value="50">50 Registros</option>
                        </select>

                    </div>
                </div>

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
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Antes') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Despues') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $item)
                                <tr>
                                    <td class="align-middle text-center">
                                        <form name="delete-result{{ $item->id }}" id="delete-result{{ $item->id }}"
                                            method="post" action="{{ url('/delete-result/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-result{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este resultado?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/blog/' . $item->id . '/' . $blog_id . '/edit-result') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ route('file', $item->before_image) }}">
                                                    <img src="{{ route('file', $item->before_image) }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ route('file', $item->after_image) }}">
                                                    <img src="{{ route('file', $item->after_image) }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
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
