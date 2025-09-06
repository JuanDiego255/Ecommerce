@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar servicios') }}</strong>
        </h2>
    </center>

    <button type="button" data-bs-toggle="modal" data-bs-target="#add-servicio-modal" class="btn btn-accion">
        {{ __('Nuevo servicio') }}
    </button>

    {{-- Modal crear --}}
    @include('admin.servicios.add')

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

            @if (session('ok'))
                <div class="alert alert-success mb-0 mt-2">{{ session('ok') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mb-0 mt-2">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Duración (min)') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Precio base (₡)') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Estado') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="align-middle">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-servicio-modal{{ $item->id }}" class="btn btn-outline-accion"
                                            style="text-decoration: none;">
                                            {{ __('Editar') }}
                                        </button>

                                        <form method="post" action="{{ url('/servicios/destroy/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }} {{ method_field('DELETE') }}
                                            <button type="submit" onclick="return confirm('¿Deseas borrar este servicio?')"
                                                class="btn btn-outline-accion" style="text-decoration: none;">
                                                {{ __('Borrar') }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->nombre }}</p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">{{ (int) ($item->duration_minutes ?? 0) }}</p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">
                                            ₡{{ number_format((int) ($item->base_price_cents ?? 0) / 100, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        {!! $item->activo
                                            ? '<span class="badge bg-success">Activo</span>'
                                            : '<span class="badge bg-secondary">Inactivo</span>' !!}
                                    </td>

                                    {{-- Modal editar --}}
                                    @include('admin.servicios.edit', ['servicio' => $item])
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-2">
                    {{ $items->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
