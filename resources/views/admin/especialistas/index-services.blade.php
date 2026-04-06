@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/especialistas/') }}">Especialistas</a></li>
    <li class="breadcrumb-item active">Servicios</li>
@endsection
@section('content')
    <input type="hidden" name="especialista_id" id="especialista_id" value="{{ $especialista->id }}">

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Servicios — {{ $especialista->nombre }}</h4>
        <a href="{{ url('/especialistas/') }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
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
                <label class="filter-label">Porcentaje</label>
                <input type="text" class="filter-input" id="porcentaje" placeholder="Ej: 30">
            </div>
            <div class="col-md-4">
                <label class="filter-label">Agregar servicio</label>
                <select id="search-select" class="filter-input select2" name="search">
                    <option value="">Seleccionar servicio...</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table-services">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Servicio</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
                serverSide: true,
                ajax: {
                    url: "/especialistas/service/list/{{ $especialista->id }}",
                    type: "GET"
                },
                columns: [
                    { data: "acciones", orderable: false, searchable: false },
                    { data: "nombre" },
                    { data: "porcentaje" }
                ],
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
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sSearch: "Buscar:",
                    oPaginate: { sFirst: "<<", sLast: "Último", sNext: ">>", sPrevious: "<<" }
                }
            });

            $('#recordsPerPage').on('change', function() {
                tableServices.page.len(parseInt($(this).val())).draw();
            });
            $('#searchfor').on('input', function() {
                tableServices.search($(this).val()).draw();
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
                    data: function(params) { return { search: params.term }; },
                    processResults: function(data) {
                        return {
                            results: data.map(function(buy) {
                                return { id: buy.service_id, text: buy.name };
                            })
                        };
                    }
                }
            });

            $('#search-select').on('change', function() {
                var selectedId = $(this).val();
                var porcentaje = document.getElementById('porcentaje').value;
                if (porcentaje == "") {
                    Swal.fire({ title: "Debes agregar el porcentaje del servicio", icon: "warning" });
                    return;
                }
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/especialistas/service/store-new",
                    data: { 'clothing_id': selectedId, 'especialista_id': especialista_id, 'porcentaje': porcentaje },
                    success: function() {
                        tableServices.ajax.reload();
                        $('#porcentaje').val('');
                    }
                });
            });
        });
    </script>
@endsection
