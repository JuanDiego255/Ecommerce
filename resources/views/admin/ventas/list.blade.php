@extends('layouts.admin')
@section('content')
    <h2 class="text-center font-title"><strong>Ventas realizadas</strong>
    </h2>

    <hr class="hr-servicios">
    <div class="col-md-12 mb-2">
        <a href="{{ url('ventas/especialistas/0') }}" class="btn btn-velvet w-25">{{ __('Nueva venta') }}</a>
    </div>
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
                            <option selected value="15">15 Registros</option>
                            <option value="50">50 Registros</option>
                        </select>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card p-2">
        <div class="table-responsive">
            <table id="table" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Acciones</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Especialista</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Servicio</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Venta</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Clínica</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Monto Esp</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Porcentaje</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $item)
                        <tr>
                            <td class="align-middle">
                                {{-- <form name="delete-venta{{ $item->id }}" id="delete-venta{{ $item->id }}"
                                    method="post" action="{{ url('/delete-venta/' . $item->id) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button form="delete-user{{ $user->id }}" type="submit"
                                    onclick="return confirm('Deseas borrar este usuario?')"
                                    class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Eliminar">
                                    <i class="material-icons text-lg">delete</i>
                                </button> --}}
                                <a class="btn btn-link text-velvet me-auto border-0"
                                    href="{{ url('/ventas/especialistas/' . $item->id) }}" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Editar">
                                    <i class="material-icons text-lg">edit</i>
                                </a>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $item->nombre }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $item->name }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">₡{{ number_format($item->monto_venta) }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">₡{{ number_format($item->monto_clinica) }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">₡{{ number_format($item->monto_especialista) }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">%{{ $item->porcentaje }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $item->created_at->format('d/m/Y') }}                                </p>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
