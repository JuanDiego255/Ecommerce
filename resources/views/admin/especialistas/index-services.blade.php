@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <input type="hidden" name="especialista_id" id="especialista_id" value="{{ $especialista->id }}">
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar servicios del especialista ' . $especialista->nombre) }}</strong>
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
                        <div class="flex1 search-flex w-50">
                            <div class="row">

                                <div class="col-md-5">
                                    <div class="input-group input-group-lg input-group-static w-100">
                                        <label>Agregar Servicios</label>
                                        <input value="" placeholder="Porcentaje (Requerido)" type="text"
                                            class="form-control form-control-lg" name="porcentaje" id="porcentaje">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="search-products">
                                        <select id="search-select" class="form-control select2" placeholder="Search..."
                                            name="search">
                                            <option value="">Select an option</option>
                                            <option value="1">Option 1</option>
                                            <option value="2">Option 2</option>
                                            <option value="3">Option 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="table-services">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Servicio') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Porcentaje') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--  @foreach ($services as $item)
                                <tr>
                                    <td class="align-middle">
                                        <form method="post"
                                            action="{{ url('/especialistas/destroy/service/' . $item->service_id . '/' . $especialista->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este servicio?')"
                                                class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                        </form>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->nombre }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            var tableServices = $('#table-services').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true, // Carga los datos desde el servidor
                ajax: {
                    url: "/especialistas/service/list/{{ $especialista->id }}", // Ruta en Laravel
                    type: "GET"
                },
                columns: [{
                        data: "acciones",
                        orderable: false,
                        searchable: false
                    }, // Acciones
                    {
                        data: "nombre"
                    }, // Servicio
                    {
                        data: "porcentaje"
                    }
                ],
                dom: 'Bfrtip',
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
                        title: 'Reporte PDF'
                    }
                ],
                language: {
                    sProcessing: "Procesando...",
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
            $('#recordsPerPage').on('change', function() {
                var recordsPerPage = parseInt($(this).val());
                tableServices.page.len(recordsPerPage).draw();
            });
            $('#searchfor').on('input', function() {
                var searchTerm = $(this).val();
                tableServices.search(searchTerm).draw();
            });
            var especialista_id = document.getElementById('especialista_id').value;
            $('#search-select').select2({
                placeholder: "BUSCAR SERVICIOS...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/get/products/select/' + especialista_id,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term // Envía el término de búsqueda al servidor
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(buy) {
                                return {
                                    id: buy.service_id,
                                    text: buy.name
                                };
                            })
                        };
                    }
                }
            });
            $('#search-select').on('change', function(e) {
                var selectedId = $(this).val();
                var porcentaje = document.getElementById('porcentaje').value;
                if (porcentaje == "") {
                    Swal.fire({
                        title: "Debes agregar el porcentaje del servicio",
                        icon: "warning",
                    });
                    return;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/especialistas/service/store/",
                    data: {
                        'clothing_id': selectedId,
                        'especialista_id': especialista_id,
                        'porcentaje': porcentaje,
                    },
                    success: function(response) {
                        tableServices.ajax.reload();
                        $('#porcentaje').val('');
                    }
                });
            });
        });
    </script>
@endsection
