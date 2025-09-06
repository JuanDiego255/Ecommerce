@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar arqueos') }}</strong>
        </h2>
    </center>
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
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Fecha Apertura') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Fecha Final') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Estado') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Abierta Por') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arqueos as $item)
                                <tr>
                                    <td class="align-middle">
                                        @if (!$item->estado == 0)
                                            <form method="post" action="{{ url('/close/cajas/' . $item->caja_id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                <button type="submit" data-bs-toggle="modal"
                                                    onclick="return confirm('Deseas cerrar esta caja?')"
                                                    class="btn btn-admin-open"
                                                    style="text-decoration: none;">Cerrar</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->fecha_ini }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->fecha_fin }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->estado == 1 ? 'Abierta' : 'Cerrada' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->name }}
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
            <a href="{{ url('/cajas/') }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
