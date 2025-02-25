@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Informe de ventas según el arqueo de caja - fecha: ' . $fechaCostaRica) }}</strong>
        </h2>
    </center>
    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor">
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Fecha</label>
                        <input value="{{ $fechaCostaRica }}" type="date" class="form-control form-control-lg"
                            name="fecha_caja" id="fecha_caja">
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
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Servicio') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Monto Venta') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Porcentaje') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Monto Venta Producto') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Tipo Pago') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Fecha') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subtotalMontoVenta = 0;
                                $subtotalMontoProducto = 0;
                                $especialistaAnterior = null;
                            @endphp

                            @foreach ($ventas as $index => $item)
                                @if ($especialistaAnterior !== null && $item->nombre !== $especialistaAnterior)
                                    <!-- Fila de subtotal con el mismo número de columnas -->
                                    <tr class="bg-light">
                                        <td></td>
                                        <td></td>
                                        <td class="text-end font-weight-bold">Subtotal:</td>

                                        <td class="text-success font-weight-bold">₡{{ number_format($subtotalMontoVenta) }}
                                        </td>
                                        <td></td>
                                        <td class="text-success font-weight-bold">
                                            ₡{{ number_format($subtotalMontoProducto) }}</td>
                                        <td></td>

                                        <td></td> <!-- Asegurar el mismo número de columnas -->
                                    </tr>

                                    @php
                                        // Reiniciar subtotales para el nuevo especialista
                                        $subtotalMontoVenta = 0;
                                        $subtotalMontoProducto = 0;
                                    @endphp
                                @endif

                                <!-- Fila de datos -->
                                <tr>
                                    <td class="align-middle"></td>
                                    <td class="align-middle text-sm">
                                        <p class="text-dark mb-0">{{ $item->nombre }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-dark mb-0">{{ $item->servicio }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">₡{{ number_format($item->monto_venta) }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-danger mb-0">{{ $item->porcentaje }}%</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">₡{{ number_format($item->monto_producto_venta) }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-dark mb-0">{{ $item->tipo }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-dark mb-0">{{ $item->created_at }}</p>
                                    </td>
                                </tr>

                                @php
                                    // Acumular subtotales
                                    $subtotalMontoVenta += $item->monto_venta;
                                    $subtotalMontoProducto += $item->monto_producto_venta;
                                    $especialistaAnterior = $item->nombre;
                                @endphp
                            @endforeach

                            <!-- Última fila de subtotales al final del bucle -->
                            @if ($especialistaAnterior !== null)
                                <tr class="bg-light">
                                    <td></td>
                                    <td></td>
                                    <td class="text-end font-weight-bold">Subtotal:</td>
                                    <td class="text-success font-weight-bold">₡{{ number_format($subtotalMontoVenta) }}
                                    </td>
                                    <td></td>
                                    <td class="text-success font-weight-bold">₡{{ number_format($subtotalMontoProducto) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Resumen de Ventas por Tipo de Pago (Entradas)</h5>
                    <ul class="list-group">
                        @foreach ($ventasEntrada as $venta)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $venta->tipo_pago }}</span>
                                <span class="fw-bold text-success">₡{{ number_format($venta->total_venta) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Resumen de Salidas por Especialista</h5>
                    <ul class="list-group">
                        @php
                            $totalGeneralVenta = 0;
                            $totalGeneralEspecialista = 0;
                            $totalGeneralClinica = 0;
                        @endphp

                        @foreach ($ventasPorEspecialista as $venta)
                            @php
                                $montoClinica = $venta->total_venta - $venta->total_especialista;
                                $totalGeneralVenta += $venta->total_venta;
                                $totalGeneralEspecialista += $venta->total_especialista;
                                $totalGeneralClinica += $montoClinica;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $venta->especialista }}</strong>
                                </div>
                                <div>
                                    <span class="text-success fw-bold">₡{{ number_format($venta->total_venta) }}</span>
                                    <br>
                                    <small class="text-primary">Especialista:
                                        ₡{{ number_format($venta->total_especialista) }}</small>
                                    <br>
                                    <small class="text-danger">Clínica: ₡{{ number_format($montoClinica) }}</small>
                                </div>
                            </li>
                        @endforeach

                        <!-- TOTAL GENERAL -->
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong>Total General</strong>
                            <div>
                                <span class="text-success fw-bold">₡{{ number_format($totalGeneralVenta) }}</span>
                                <br>
                                <small class="text-primary">Especialistas:
                                    ₡{{ number_format($totalGeneralEspecialista) }}</small>
                                <br>
                                <small class="text-danger">Clínica: ₡{{ number_format($totalGeneralClinica) }}</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#fecha_caja').on('change', function() {
                // Aquí va el código que quieres ejecutar cuando cambie la fecha
                let selectedDate = $(this).val();
                window.location.href = '/list-esp/ventas/' + selectedDate;
            });
        });
    </script>
@endsection
