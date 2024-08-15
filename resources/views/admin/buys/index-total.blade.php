@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="font-title text-center">Registro de ventas</h1>
    <div class="container">
        <div class="card mt-3 mb-3">
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-md-3">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Filtrar</label>
                            <input value="" placeholder="Escribe para filtrar por alguna columna...." type="text"
                                class="form-control form-control-lg" name="searchfor" id="searchfor">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Fecha Inicial</label>
                            <input value="" type="date" class="form-control form-control-lg"
                                name="searchfordateini" id="searchfordateini">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Fecha Final</label>
                            <input value="" type="date" class="form-control form-control-lg"
                                name="searchfordatefin" id="searchfordatefin">
                        </div>
                    </div>
                    <div class="col-md-3">
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
            <div class="col-md-4">
                <div class="card">
                    <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva }}">
                    <div class="card-body" id="totalCard">
                        <h5 class="card-title">Total de Precio y Envío</h5>
                        <p class="card-text text-danger">
                            Ventas totales: <span id="totalVentas">{{ $totalVentas }}</span>
                        </p>
                        <p class="card-text text-danger">
                            Total de productos: <span id="totalProductos">{{ $totalDetails }}</span>
                        </p>
                        <p class="card-text text-info">
                            Ventas: ₡<span id="totalPrecio">{{ number_format($totalPrecio, 2) }}</span>
                        </p>
                        @if ($iva > 0)
                            <p class="card-text text-info">
                                I.V.A: ₡<span id="totalIva">{{ number_format($totalIva, 2) }}</span>
                        @endif
                        <p class="card-text text-info">
                            Envíos: ₡<span id="totalEnvio">{{ number_format($totalEnvio, 2) }}</span>
                        </p>
                        <hr class="dark horizontal text-danger">
                        <p class="card-text text-success">
                            Total: ₡<span id="total">{{ number_format($totalPrecio + $totalEnvio, 2) }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-2">
                    <div class="table-responsive">
                        <table id="buys" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Fecha</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Ventas</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Envío</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Tipo</th>
                                    @if ($iva > 0)
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            I.V.A</th>
                                    @endif
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Productos</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        Cupón Aplicado</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($buys as $buy)
                                    <tr>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">{{ $buy->created_at->format('Y-m-d') }}</p>
                                        </td>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">
                                                ₡{{ number_format($buy->total_buy + $buy->credit_used) }}</p>
                                        </td>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">
                                                ₡{{ number_format($buy->total_delivery) }}</p>
                                        </td>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">
                                                @if ($buy->kind_of == 'F')
                                                    Tienda Física
                                                @else
                                                    Sitio Web
                                                @endif
                                            </p>
                                        </td>
                                        @if ($iva > 0)
                                            <td class="align-middle text-xxs text-center">
                                                <p class=" font-weight-bold mb-0">
                                                    ₡{{ number_format($buy->total_iva) }}</p>
                                            </td>
                                        @endif
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">
                                                {{ $buy->details_count }}</p>
                                        </td>
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">
                                                ₡{{ number_format($buy->credit_used) }}</p>
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        function setFirstAndLastDayOfMonth() {
            var currentDate = getCurrentDate();
            var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

            $('#searchfordateini').val(formatDate(firstDayOfMonth));
            $('#searchfordatefin').val(formatDate(lastDayOfMonth));
        }

        function getCurrentDate() {
            return new Date();
        }

        function formatDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1; // Los meses en JavaScript se indexan desde 0
            var year = date.getFullYear();

            // Asegurar que los días y los meses tengan dos dígitos
            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }

            return year + '-' + month + '-' + day;
        }

        // Llama a la función para establecer la fecha actual al cargar la página
        $(document).ready(function() {
            setFirstAndLastDayOfMonth();
        });

        var dataTable = $('#buys').DataTable({
            searching: true,
            lengthChange: false,
            pageLength: 15,
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-table',
                    messageTop: 'Mi reporte personalizado de Excel',
                    title: 'Reporte Excel'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-table',
                    messageTop: 'Mi reporte personalizado de PDF',
                    // Opcionalmente, puedes agregar más configuración como la personalización del título:
                    title: 'Reporte PDF'
                }
            ],
            dom: 'Bfrtip', // Para colocar los botones
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": "Último",
                    "sNext": ">>",
                    "sPrevious": "<<"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

        $('#recordsPerPage').on('change', function() {
            var recordsPerPage = parseInt($(this).val(), 15);
            dataTable.page.len(recordsPerPage).draw();
        });

        // Captura el evento input en el campo de búsqueda
        $('#searchfor').on('input', function() {
            var searchTerm = $(this).val();
            dataTable.search(searchTerm).draw();
            calcularTotal();
        });

        // Captura el evento input en el campo de búsqueda
        $('#searchfordateini, #searchfordatefin').on('change', function() {
            var startDate = $('#searchfordateini').val();
            var endDate = $('#searchfordatefin').val();

            // Filtra los datos basándose en el rango de fechas seleccionado
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var rowData = dataTable.row(dataIndex).data();
                    var rowDataDate = $(rowData[0]).text();

                    // Comprueba si la fecha está dentro del rango seleccionado
                    return (rowDataDate >= startDate && rowDataDate <= endDate);
                }
            );

            // Redibujar la tabla con los datos filtrados
            dataTable.draw();

            // Eliminar la función de filtro después de realizar la búsqueda
            $.fn.dataTable.ext.search.pop();
            calcularTotal();
        });

        function calcularTotal() {
            var totalPrecio = 0;
            var totalEnvio = 0;
            var totalVentas = 0;
            var totalProductos = 0;
            let iva = parseFloat(document.getElementById("iva_tenant").value);
            var totalIva = 0;


            $('#buys tbody tr').each(function() {
                var precio = parseFloat($(this).find('td:eq(1)').text().replace(/[^0-9.-]+/g, ""));
                var envio = parseFloat($(this).find('td:eq(2)').text().replace(/[^0-9.-]+/g, ""));
                var productos;
                var iva_table;
                if (iva > 0) {
                    iva_table = parseFloat($(this).find('td:eq(4)').text().replace(/[^0-9.-]+/g, ""));
                    productos = parseFloat($(this).find('td:eq(5)').text().replace(/[^0-9.-]+/g, ""));
                } else {
                    productos = parseFloat($(this).find('td:eq(4)').text().replace(/[^0-9.-]+/g, ""));
                }
                if (!isNaN(precio)) {
                    totalVentas++;
                }
                precio = isNaN(precio) ? 0 : precio;
                envio = isNaN(envio) ? 0 : envio;
                productos = isNaN(productos) ? 0 : productos;
                if (iva > 0) {
                    iva_table = isNaN(iva_table) ? 0 : iva_table;
                }

                totalPrecio += precio;
                totalEnvio += envio;
                totalProductos += productos;
                if (iva > 0) {
                    totalIva += iva_table;
                }


            });

            $('#totalPrecio').text(totalPrecio.toFixed(2));
            $('#totalEnvio').text(totalEnvio.toFixed(2));
            $('#totalVentas').text(totalVentas.toFixed(0));
            $('#totalProductos').text(totalProductos.toFixed(0));
            $('#total').text((totalPrecio + totalEnvio).toFixed(2));
            if (iva > 0) {
                $('#totalIva').text(totalIva.toFixed(2));
            }
        }
    </script>
@endsection
