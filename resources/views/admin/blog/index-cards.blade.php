@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Tarjetas relacionadas con este blog') }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('blog-add/' . $id . '/add-card') }}" class="btn btn-velvet w-100">{{ __('Nueva tarjeta') }}</a>
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
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Tarjeta') }}</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Descripción') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cards as $item)
                                <tr>
                                    <td class="align-middle text-center">
                                        <form name="delete-card{{ $item->id }}" id="delete-card{{ $item->id }}"
                                            method="post" action="{{ url('/delete-card/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-card{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar esta tarjeta?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('blog/' . $item->id . '/' . $id . '/edit-card') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
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
                                                <h4 class="mb-0 text-lg">{{ $item->title }}</h4>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-muted mb-0">{{ $item->description }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog/indexadmin') }}" class="btn btn-velvet w-25">{{ __('Ir a blogs') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
