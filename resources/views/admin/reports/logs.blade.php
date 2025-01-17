@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
    <h1 class="font-title text-center">Reporte de {{ $type == 'log' ? 'Ingresos y salidas' : 'Movimientos' }}</h1>
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
                    {{--  <div class="col-md-4">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Reporte</label>
                            <select id="selectType" name="selectType" class="form-control form-control-lg"
                                autocomplete="selectType">
                                <option value="{{ $type }}" selected>
                                    {{ $type == 'log' ? 'Ingresos y salidas' : 'Movimientos' }}
                                </option>
                                <option value="log">Ingresos y salidas</option>
                                <option value="movement">Movimientos</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
            <input type="hidden" value="{{ $type }}" name="type" id="type">
            <div class="col-md-12">
                <div class="card p-2">
                    <div class="table-responsive">
                        @if ($type == 'log')
                            <table class="table align-items-center mb-0" id="logs">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Usuario') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Hora de entrada al sistema') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Hora de salida del sistema') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $item)
                                        <tr>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->entry_date }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->exit_date }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table class="table align-items-center mb-0" id="logs">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Usuario') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Acción') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Fecha Ejecución') }}
                                        </th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                            {{ __('Modelo de BD afectado') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $item)
                                        @php
                                            $detail = json_decode($item->detail, true); // Decodifica el JSON en un array
                                            $model = '';
                                            // Verifica que 'model' exista y extrae el valor
                                            if (isset($detail['model'])) {
                                                $model = basename(str_replace('\\', '/', $detail['model']));
                                            }
                                        @endphp
                                        <tr>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">
                                                    @switch($item->action)
                                                        @case('insert')
                                                            Inserción de datos
                                                        @break

                                                        @case('update')
                                                            Actualización de datos
                                                        @break

                                                        @default
                                                            Eliminación de registro
                                                    @endswitch
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $item->created_at }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <p class="text-dark mb-0">{{ $model }}
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
        var textReport = type == "log" ? 'Reporte de Ingresos y Salidas de usuarios' :
            'Reporte de movimientos de los usuarios.';
        var textTitle = type == "log" ? 'Reporte de Ingresos y salidas' : 'Reporte de Movimientos';
        var dataTable = $('#logs').DataTable({
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
        $('#recordsPerPage').on('change', function() {
            var recordsPerPage = parseInt($(this).val());
            dataTable.page.len(recordsPerPage).draw();
        });
        $(document).ready(function() {
            $('#selectType').on('change', function(e) {
                var selectedId = $(this).val();
                if (selectedId) {
                    window.location.href = '/report/logs/' + selectedId;
                }
            });
        });
    </script>
@endsection
