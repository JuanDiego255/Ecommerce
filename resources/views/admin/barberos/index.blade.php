@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar barberos') }}</strong>
        </h2>
    </center>

    <button type="button" data-bs-toggle="modal" data-bs-target="#add-barbero-modal" class="btn btn-accion">
        {{ __('Nuevo barbero') }}
    </button>

    {{-- Modal crear --}}
    @include('admin.barberos.add')

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

                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Salario base') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Monto por servicio') }}
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barberos as $item)
                                <tr>


                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->nombre }}</p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">
                                            ₡{{ number_format($item->salario_base ?? 0) }}
                                        </p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">
                                            ₡{{ number_format($item->monto_por_servicio ?? 0) }}
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('barberos.show', ['barbero' => $item->id, 'tab' => 'info', 'back' => url()->current()]) }}"
                                            class="btn btn-outline-accion">Perfil</a>
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
