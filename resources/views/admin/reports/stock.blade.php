@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
    <h1 class="font-title text-center">Reporte de inventario</h1>
    <div class="container">
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

                        <table class="table align-items-center mb-0" id="stock">
                            <thead>
                                <tr>
                                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Producto') }}
                                    </th>
                                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Código') }}
                                    </th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">
                                        {{ __('Precio') }}</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">
                                        {{ __('Stock') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clothings as $item)
                                    @php
                                        $stockPerSize = explode(',', $item->stock_per_size);
                                        $attrPerItem = explode(',', $item->attr_id_per_size);
                                        $attr = explode(',', $item->available_attr);
                                    @endphp
                                    <tr>
                                        <td class="align-middle text-sm">
                                            <p class="text-dark mb-0">{{ $item->name }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-sm">
                                            <p class="text-dark mb-0">{{ $item->code }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-success mb-0">₡{{ number_format($item->price) }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-success mb-0">
                                                {{ $item->manage_stock == 0 ? 'No maneja' : $item->total_stock }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">Total:</p>
                                    </th>
                                    <th class="align-middle text-center">
                                    </th>
                                    <th class="align-middle text-center">
                                    </th>
                                    <th class="align-middle text-center">
                                        <p class=" font-weight-bold mb-0" id="totalSales"></p>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#searchfor').on('input', function() {
            var searchTerm = $(this).val();
            dataTable.search(searchTerm).draw();
        });
        var dataTable = $('#stock').DataTable({
            searching: true,
            lengthChange: false,
            pageLength: 15,
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-table',
                    messageTop: 'Reporte de inventario - Detalla todos los productos y su cantidad disponible.',
                    title: 'Reporte de inventario',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-table',
                    messageTop: 'Reporte de inventario - Detalla todos los productos y su cantidad disponible.',
                    title: 'Reporte de inventario',
                    footer: true,
                    customize: function(doc) {
                        // Verificar que doc.content existe
                        if (doc.content && Array.isArray(doc.content)) {
                            // Buscar la tabla en el contenido
                            doc.content.forEach(function(contentItem) {
                                if (contentItem.table && contentItem.table.body) {
                                    // Reemplazar el símbolo ₡ con el equivalente Unicode
                                    contentItem.table.body.forEach(function(row) {
                                        row.forEach(function(cell, index) {
                                            if (typeof cell.text === 'string') {
                                                cell.text = cell.text.replace(/₡/g,
                                                    ''
                                                ); // Reemplazar ₡ con Unicode
                                            }
                                        });
                                    });
                                }
                            });
                        }
                    }
                }
            ],
            dom: 'Bfrtip', // Para colocar los botones
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "<<",
                    sLast: "Último",
                    sNext: ">>",
                    sPrevious: "<<"
                }
            },

            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Función para limpiar el símbolo de colones y separadores
                var cleanNumber = function(value) {
                    if (!value) {
                        return 0; // Si el valor es nulo, indefinido o vacío, retornar 0
                    }
                    if (typeof value !== 'string') {
                        value = value.toString(); // Asegurarse de que sea una cadena
                    }
                    // Eliminar símbolo de colones y separadores de miles
                    return parseFloat(value.replace(/[₡,]/g, '')) || 0;
                };

                // Calcula el total de la columna de ventas (extrae texto y limpia)
                var totalSales = api
                    .column(3, {
                        page: 'current'
                    })
                    .data()
                    .map(function(data) {
                        // Extraer solo el texto, sin etiquetas HTML
                        return $(data).text();
                    })
                    .reduce(function(a, b) {
                        return cleanNumber(a) + cleanNumber(b);
                    }, 0);


                $(api.column(3).footer()).html(`${totalSales}`);
            }
        });
        $('#recordsPerPage').on('change', function() {
            var recordsPerPage = parseInt($(this).val());
            dataTable.page.len(recordsPerPage).draw();
        });
    </script>
@endsection
