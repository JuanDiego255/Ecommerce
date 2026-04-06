@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Informe de ventas</li>
@endsection
@section('content')
    <div class="page-header mb-3">
        <h4 class="mb-0">Informe de ventas — {{ $fechaCostaRica }}</h4>
    </div>

    <div class="surface p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="filter-label">Filtrar</label>
                <input type="text" class="filter-input" id="searchfor" placeholder="Escribe para filtrar...">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" class="filter-input">
                    <option value="5">5 Registros</option>
                    <option value="10">10 Registros</option>
                    <option selected value="15">15 Registros</option>
                    <option value="50">50 Registros</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="filter-label">Fecha inicio</label>
                <input type="date" class="filter-input" id="fecha_caja" name="fecha_caja" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Fecha final</label>
                <input type="date" class="filter-input" id="fecha_caja_fin" name="fecha_caja_fin" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Especialista</label>
                <select id="select_especialista" name="select_especialista" class="filter-input">
                    <option @if ($id != 0) selected @endif value="0">Todos</option>
                    @foreach ($especialistas as $esp)
                        <option @if ($id != 0 && $id == $esp->id) selected @endif value="{{ $esp->id }}">
                            {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Table 1: Specialist Sales --}}
    <div class="surface mb-3">
        <div class="surface-title px-3 pt-3 pb-2">Ventas de especialistas — Paquetes y tarjetas de regalo</div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Servicio</th>
                        <th>Monto Venta</th>
                        <th>Porcentaje</th>
                        <th>Monto Esp</th>
                        <th>Monto Venta Producto</th>
                        <th>Cliente</th>
                        <th>Tipo Pago</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotalMontoVenta = 0;
                        $subtotalMontoProducto = 0;
                        $subtotalMontoEsp = 0;
                        $especialistaAnterior = null;
                    @endphp

                    @foreach ($ventas as $item)
                        @php $nombreActual = $item->nombre ?? 'Paquetes y Tarjetas de regalo'; @endphp

                        @if ($especialistaAnterior !== null && $nombreActual !== $especialistaAnterior)
                            <tr style="background:var(--surface-bg,#f8f9fa);">
                                <td></td><td></td>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                                <td></td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoEsp) }}</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoProducto) }}</td>
                                <td></td><td></td><td></td>
                            </tr>
                            @php $subtotalMontoVenta = 0; $subtotalMontoProducto = 0; $subtotalMontoEsp = 0; @endphp
                        @endif

                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $nombreActual }}</td>
                            <td class="align-middle">{!! str_replace(',', '<br>', $item->servicios) !!}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->monto_venta) }}</td>
                            <td class="align-middle text-danger">{{ $item->porcentaje }}%</td>
                            <td class="align-middle text-success">₡{{ number_format($item->monto_especialista) }}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->monto_producto_venta) }}</td>
                            <td class="align-middle">{{ $item->nombre_cliente }}</td>
                            <td class="align-middle">{{ $item->tipo }}</td>
                            <td class="align-middle">{{ $item->created_at }}</td>
                        </tr>

                        @php
                            $subtotalMontoVenta += $item->monto_venta;
                            $subtotalMontoProducto += $item->monto_producto_venta;
                            $subtotalMontoEsp += $item->monto_especialista;
                            $especialistaAnterior = $nombreActual;
                        @endphp
                    @endforeach

                    @if ($especialistaAnterior !== null)
                        <tr style="background:var(--surface-bg,#f8f9fa);">
                            <td></td><td></td>
                            <td class="text-end fw-semibold">Subtotal:</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                            <td></td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoEsp) }}</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoProducto) }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Table 2: Estudiantes mensualidades --}}
    <div class="surface mb-3">
        <div class="surface-title px-3 pt-3 pb-2">Mensualidades de los Estudiantes</div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="estudiantes_table">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Monto Pago</th>
                        <th>Descuento</th>
                        <th>Tipo Pago</th>
                        <th>Tipo Venta</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotalMontoVenta = 0;
                        $subtotalDescuento = 0;
                        $estudianteAnterior = null;
                    @endphp

                    @foreach ($ventasEstudiantes as $item)
                        @if ($estudianteAnterior !== null && $item->nombre !== $estudianteAnterior)
                            <tr style="background:var(--surface-bg,#f8f9fa);">
                                <td></td><td></td>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalDescuento) }}</td>
                                <td></td><td></td><td></td>
                            </tr>
                            @php $subtotalMontoVenta = 0; $subtotalDescuento = 0; @endphp
                        @endif

                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $item->nombre }}</td>
                            <td class="align-middle">{{ $item->curso }}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->monto_pago) }}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->descuento) }}</td>
                            <td class="align-middle">{{ $item->tipo }}</td>
                            <td class="align-middle">{{ $item->tipo_venta == '1' ? 'Mensualidad' : 'Otro' }}</td>
                            <td class="align-middle">{{ $item->created_at }}</td>
                        </tr>

                        @php
                            $subtotalMontoVenta += $item->monto_pago;
                            $subtotalDescuento += $item->descuento;
                            $estudianteAnterior = $item->nombre;
                        @endphp
                    @endforeach

                    @if ($estudianteAnterior !== null)
                        <tr style="background:var(--surface-bg,#f8f9fa);">
                            <td></td><td></td>
                            <td class="text-end fw-semibold">Subtotal:</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalDescuento) }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Table 3: Yoga mensualidades --}}
    <div class="surface mb-3">
        <div class="surface-title px-3 pt-3 pb-2">Mensualidades de Clases de Yoga</div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="yoga_table">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Monto Pago</th>
                        <th>Descuento</th>
                        <th>Tipo Pago</th>
                        <th>Tipo Venta</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotalMontoVenta = 0;
                        $subtotalDescuento = 0;
                        $estudianteAnterior = null;
                    @endphp

                    @foreach ($ventasEstudiantesYoga as $item)
                        @if ($estudianteAnterior !== null && $item->nombre !== $estudianteAnterior)
                            <tr style="background:var(--surface-bg,#f8f9fa);">
                                <td></td><td></td>
                                <td class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                                <td class="fw-semibold text-success">₡{{ number_format($subtotalDescuento) }}</td>
                                <td></td><td></td><td></td>
                            </tr>
                            @php $subtotalMontoVenta = 0; $subtotalDescuento = 0; @endphp
                        @endif

                        <tr>
                            <td class="align-middle"></td>
                            <td class="align-middle">{{ $item->nombre }}</td>
                            <td class="align-middle">{{ $item->curso }}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->monto_pago) }}</td>
                            <td class="align-middle text-success">₡{{ number_format($item->descuento) }}</td>
                            <td class="align-middle">{{ $item->tipo }}</td>
                            <td class="align-middle">{{ $item->tipo_venta == '1' ? 'Mensualidad' : 'Sesión' }}</td>
                            <td class="align-middle">{{ $item->created_at }}</td>
                        </tr>

                        @php
                            $subtotalMontoVenta += $item->monto_pago;
                            $subtotalDescuento += $item->descuento;
                            $estudianteAnterior = $item->nombre;
                        @endphp
                    @endforeach

                    @if ($estudianteAnterior !== null)
                        <tr style="background:var(--surface-bg,#f8f9fa);">
                            <td></td><td></td>
                            <td class="text-end fw-semibold">Subtotal:</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalMontoVenta) }}</td>
                            <td class="fw-semibold text-success">₡{{ number_format($subtotalDescuento) }}</td>
                            <td></td><td></td><td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary: Specialist breakdown --}}
    @php
        $acumuladoTotalVentas = 0;
        $acumuladoTotalEspecialistas = 0;
        $acumuladoTotalClinica = 0;
        $acumuladoTotalProd = 0;
        $acumuladoTotalDescuentos = 0;
        $totalGeneralVenta = 0;
        $totalGeneralEspecialista = 0;
        $totalGeneralClinica = 0;
        $totalGeneralProducto = 0;
    @endphp

    <div class="surface mb-3">
        <div class="surface-title px-3 pt-3 pb-2">Resumen de Salidas por Especialista</div>
        <div class="p-3">
            <div class="row g-3">
                @foreach ($ventasPorEspecialista as $venta)
                    @php
                        $montoClinica = $venta->total_clinica;
                        $totalGeneralVenta += $venta->total_venta + $venta->total_producto;
                        $totalGeneralEspecialista += $venta->total_especialista;
                        $totalGeneralClinica += $montoClinica;
                        $totalGeneralProducto += $venta->total_producto;
                    @endphp
                    <div class="col-md-3">
                        <div class="surface p-3 h-100">
                            <p class="fw-semibold mb-2">{{ $venta->especialista ?? 'Paquetes y Tarjetas de regalo' }}</p>
                            <hr class="my-1">
                            <span class="text-success fw-bold">₡{{ number_format($venta->total_venta + $venta->total_producto) }}</span><br>
                            <small class="text-primary">Especialista: ₡{{ number_format($venta->total_especialista) }}</small><br>
                            <small class="text-danger">Clínica: ₡{{ number_format($montoClinica) }}</small><br>
                            <small class="text-info">Prod Vendido: ₡{{ number_format($venta->total_producto) }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="surface mt-3 p-3">
                <p class="fw-semibold mb-1">Total General</p>
                <span class="text-success fw-bold">₡{{ number_format($totalGeneralVenta) }}</span><br>
                <small class="text-primary">Especialistas: ₡{{ number_format($totalGeneralEspecialista) }}</small><br>
                <small class="text-danger">Clínica: ₡{{ number_format($totalGeneralClinica) }}</small><br>
                <small class="text-info">Productos: ₡{{ number_format($totalGeneralProducto) }}</small>
            </div>
        </div>
    </div>

    @php
        $acumuladoTotalVentas += $totalGeneralVenta;
        $acumuladoTotalEspecialistas += $totalGeneralEspecialista;
        $acumuladoTotalClinica += $totalGeneralClinica;
        $acumuladoTotalProd += $totalGeneralProducto;
    @endphp

    <div class="row g-3 mb-3">
        {{-- Ingresos estudiantes --}}
        <div class="col-md-4">
            <div class="surface p-3 h-100">
                <p class="surface-title mb-2">Ingresos Por Pagos de Estudiantes</p>
                @php $totalGeneralClinica = 0; $totalGeneralDesc = 0; @endphp
                @foreach ($ventasEstudiantesSum as $venta)
                    @php
                        $montoClinica = $venta->total_venta;
                        $montoDesc = $venta->total_descuento;
                        $totalGeneralClinica += $montoClinica;
                        $totalGeneralDesc += $montoDesc;
                    @endphp
                    <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                        <strong>{{ $venta->nombre }}</strong>
                        <div class="text-end">
                            <small class="text-success d-block">Ingreso: ₡{{ number_format($montoClinica) }}</small>
                            <small class="text-danger">Descuento: ₡{{ number_format($montoDesc) }}</small>
                        </div>
                    </div>
                @endforeach

                <p class="surface-title mt-3 mb-2">Ingreso Por Matrículas</p>
                @foreach ($ventasPorMatricula as $venta)
                    @php $totalGeneralClinica += $venta->total_venta; @endphp
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <small class="text-success">Ingreso: ₡{{ number_format($venta->total_venta) }}</small>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-start pt-2 mt-1" style="background:var(--surface-bg,#f8f9fa);border-radius:8px;padding:.5rem .75rem;">
                    <strong>Total General</strong>
                    <div class="text-end">
                        <small class="text-success d-block">Clínica: ₡{{ number_format($totalGeneralClinica) }}</small>
                        <small class="text-danger">Descuento: ₡{{ number_format($totalGeneralDesc) }}</small>
                    </div>
                </div>
            </div>
        </div>

        @php
            $acumuladoTotalVentas += $totalGeneralClinica;
            $acumuladoTotalClinica += $totalGeneralClinica;
            $acumuladoTotalDescuentos += $totalGeneralDesc;
        @endphp

        {{-- Tipo de pago --}}
        <div class="col-md-4">
            <div class="surface p-3 h-100">
                <p class="surface-title mb-2">Ventas por Tipo de Pago (Entradas)</p>
                @php $totalVentasEntrada = 0; @endphp
                @foreach ($ventasEntrada as $venta)
                    @php $totalVentasEntrada += $venta->total_venta; @endphp
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>{{ $venta->tipo_pago }}</span>
                        <span class="fw-bold text-success">₡{{ number_format($venta->total_venta) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Acumulado general --}}
        <div class="col-md-4">
            <div class="surface p-3 h-100">
                <p class="surface-title mb-2">Acumulado General</p>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <strong>Total Ventas</strong>
                    <span class="text-success fw-bold">₡{{ number_format($acumuladoTotalVentas) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <strong>Total Especialistas</strong>
                    <span class="text-primary fw-bold">₡{{ number_format($acumuladoTotalEspecialistas) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <strong>Total Clínica</strong>
                    <span class="text-danger fw-bold">₡{{ number_format($acumuladoTotalClinica) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <strong>Total Productos</strong>
                    <span class="text-warning fw-bold">₡{{ number_format($acumuladoTotalProd) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <strong>Total Descuentos</strong>
                    <span class="text-warning fw-bold">₡{{ number_format($acumuladoTotalDescuentos) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- No incluir datatables.js: ese asset auto-inicializa #table globalmente y causa conflicto --}}
    <script>
        $(document).ready(function() {
            var dtConfig = {
                searching: true,
                lengthChange: false,
                pageLength: 15,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-table',
                        title: 'Reporte Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-table',
                        title: 'Reporte PDF'
                    }
                ],
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sSearch: "Buscar:",
                    oPaginate: { sFirst: "<<", sLast: "Último", sNext: ">>", sPrevious: "<<" }
                }
            };

            var dtMain = $('#table').DataTable(dtConfig);
            var dtEst = $('#estudiantes_table').DataTable(dtConfig);
            var dtYoga = $('#yoga_table').DataTable(dtConfig);

            $('#recordsPerPage').on('change', function() {
                var n = parseInt($(this).val());
                dtMain.page.len(n).draw();
                dtEst.page.len(n).draw();
                dtYoga.page.len(n).draw();
            });
            $('#searchfor').on('input', function() {
                var s = $(this).val();
                dtMain.search(s).draw();
                dtEst.search(s).draw();
                dtYoga.search(s).draw();
            });

            $('#fecha_caja').on('change', function() {
                var fechaFin = $('#fecha_caja_fin').val();
                var esp = $('#select_especialista').val();
                window.location.href = '/list-esp/ventas/' + $(this).val() + '/' + fechaFin + '/' + esp;
            });
            $('#fecha_caja_fin').on('change', function() {
                var fechaIni = $('#fecha_caja').val();
                var esp = $('#select_especialista').val();
                window.location.href = '/list-esp/ventas/' + fechaIni + '/' + $(this).val() + '/' + esp;
            });
            $('#select_especialista').on('change', function() {
                var fechaIni = $('#fecha_caja').val();
                var fechaFin = $('#fecha_caja_fin').val();
                window.location.href = '/list-esp/ventas/' + fechaIni + '/' + fechaFin + '/' + $(this).val();
            });
        });
    </script>
@endsection
