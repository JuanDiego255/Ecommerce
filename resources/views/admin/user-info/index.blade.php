@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Gestiona los profesionales') }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('new-user-info') }}" class="btn btn-velvet w-100">{{ __('Agregar nuevo profesional') }}</a>
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
                            <option selected value="25">25 Registros</option>
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
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}
                                </th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>

                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0 text-lg">{!! $item->name !!}</h4>

                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <form name="delete-profesional{{ $item->id }}"
                                            id="delete-profesional{{ $item->id }}" method="post"
                                            action="{{ url('/delete-user-info/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-profesional{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este profesional?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/edit-user-info/' . $item->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
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
