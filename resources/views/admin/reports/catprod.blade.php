@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
    <h1 class="font-title text-center">Reporte de Categorías/Productos</h1>
    <div class="container">
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
                            <label>Reporte</label>
                            <select id="selectType" name="selectType" class="form-control form-control-lg"
                                autocomplete="selectType">
                                <option value="{{ $type }}" selected>{{ $type == 1 ? 'Productos' : 'Categorías' }}
                                </option>
                                <option value="1">Productos</option>
                                <option value="2">Categorías</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
            <input type="hidden" value="{{ $type }}" name="type" id="type">
            <div class="col-md-12">
                <div class="card p-2">
                    <div class="table-responsive">
                        @if ($type == 1)
                            <table class="table align-items-center mb-0" id="stock">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Producto') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Código') }}
                                        </th>
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
                                                <p class="text-success mb-0">
                                                    {{ $item->manage_stock == 0 ? 'No maneja' : $item->total_stock }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table class="table align-items-center mb-0" id="categories">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Categorías') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Descripción') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $item)
                                        <tr>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->description }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var type = $('#type').val();
        var textReport = type == 1 ? 'Reporte de productos - Detalla todos los productos y su cantidad disponible.' :
            'Reporte de categorías - Detalla todas las categorías activas.';
        var textTitle = type == 1 ? 'Reporte de prouctos' : 'Reporte de categorías';
        var table = type == 1 ? 'stock' : 'categories';

        var dataTable = $('#' + table).DataTable({
            searching: true,
            lengthChange: false,
            pageLength: 15,
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-table',
                    messageTop: textReport,
                    title: textTitle,
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-table',
                    messageTop: textReport,
                    title: textTitle,
                    footer: true
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
            }
        });

        $('#searchfor').on('input', function() {
            var searchTerm = $(this).val();
            dataTable.search(searchTerm).draw();
        });
        $(document).ready(function() {
            $('#selectType').on('change', function(e) {
                var selectedId = $(this).val();
                if (selectedId) {
                    window.location.href = '/report/cat-prod/' + selectedId;
                }
            });
        });
    </script>
@endsection