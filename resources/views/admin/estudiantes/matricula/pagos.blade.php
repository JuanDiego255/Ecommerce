@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Gestión de pagos del curso ') . $info_estudiante->curso . __(' del estudiante ') . $info_estudiante->nombre_estudiante }}</strong>
        </h2>
    </center>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-pago-modal" class="btn btn-velvet">
        {{ __('Nuevo pago') }}</button>
    @include('admin.estudiantes.matricula.pagos.add')
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
                                    {{ __('Acciones') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Monto pagado') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Descuento') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Tipo de pago') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Tipo Venta') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Detalle') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Fecha pago') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagos_matricula as $item)
                                <tr>
                                    <td class="align-middle">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-pago-modal{{ $item->id }}" class="btn btn-velvet"
                                            style="text-decoration: none;">Editar</button>

                                        <form method="post" action="{{ url('/delete/matricula/pago/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este pago?')"
                                                class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                        </form>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success font-weight-bold mb-0">
                                            ₡{{ number_format($item->monto_pago) }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success font-weight-bold mb-0">
                                            ₡{{ number_format($item->descuento) }}</p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $item->tipo_pago }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $item->tipo_venta == 1 ? 'Mensualidad' : 'Otro' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $item->detalle }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $item->fecha_pago }}
                                        </p>
                                    </td>
                                    @include('admin.estudiantes.matricula.pagos.edit')
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
            <a href="{{ url('/list/matricula') . '/' . $info_estudiante->estudiante_id }}"
                class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            const tipoVenta = document.getElementById("tipo_venta");
            const detalleDiv = document.querySelector(".div_detalle");

            tipoVenta.addEventListener("change", function() {
                if (this.value == "2") {
                    detalleDiv.classList.remove("d-none");
                    $('#detalle').val('');
                    $('#monto_pago').val('');
                } else {
                    detalleDiv.classList.add("d-none");
                }
            });
        });
    </script>
@endsection
