@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Ventas</li>
@endsection
@section('content')
    @include('admin.ventas.anular')
    @include('admin.ventas.change-arqueo')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Ventas realizadas</h4>
        <a href="{{ url('ventas/especialistas/0') }}" class="s-btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva venta
        </a>
    </div>

    <div class="surface p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <label class="filter-label">Filtrar</label>
                <input type="text" class="filter-input" id="searchfor" placeholder="Escribe para filtrar...">
            </div>
            <div class="col-md-6">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" class="filter-input">
                    <option value="5">5 Registros</option>
                    <option value="10">10 Registros</option>
                    <option selected value="15">15 Registros</option>
                    <option value="50">50 Registros</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table id="table_ventas" class="table align-items-center mb-0">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Especialista</th>
                        <th>Servicio</th>
                        <th>Monto Venta</th>
                        <th>Monto Clínica</th>
                        <th>Monto Esp</th>
                        <th>Monto Prod</th>
                        <th>Porcentaje</th>
                        <th>Tipo</th>
                        <th>Cambio Arqueo</th>
                        <th>Nota anulación</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var tableVentas = $('#table_ventas').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true,
                ajax: { url: "/ajax/ventas", type: "GET" },
                columns: [
                    { data: 'acciones', orderable: false, searchable: false },
                    { data: 'nombre', name: 'especialistas.nombre' },
                    { data: 'servicios', searchable: false },
                    { data: 'monto_venta' },
                    { data: 'monto_clinica' },
                    { data: 'monto_especialista' },
                    { data: 'monto_producto_venta' },
                    { data: 'porcentaje' },
                    { data: 'tipo', name: 'tipo_pagos.tipo' },
                    { data: 'justificacion_arqueo' },
                    { data: 'nota_anulacion' },
                    { data: 'created_at' },
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
                tableVentas.page.len(parseInt($(this).val())).draw();
            });
            $('#searchfor').on('input', function() {
                tableVentas.search($(this).val()).draw();
            });
        });

        function abrirAnularModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('anularModal'));
            const form = document.getElementById('anularForm');
            form.action = '/anular/venta/' + id;
            document.getElementById('nota_anulacion_input').value = '';
            modal.show();
        }

        function abrirCambioArqueoModal(ventaId, createdAt) {
            const modal = new bootstrap.Modal(document.getElementById('changeArqueoModal'));
            const form = document.getElementById('changeArqueoForm');
            form.action = '/cambiar/venta/' + ventaId;

            document.getElementById('justificacionArqueoInput').value = '';
            const select = document.getElementById('arqueoSelect');
            select.innerHTML = '<option>Cargando...</option>';

            fetch(`/api/arqueos-validos?fecha=${createdAt}`)
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '';
                    if (data.length === 0) {
                        select.innerHTML = '<option disabled selected>No hay arqueos disponibles</option>';
                    } else {
                        data.forEach((arqueo, index) => {
                            const option = document.createElement('option');
                            option.value = arqueo.id;
                            option.text = `Inicio: ${arqueo.fecha_ini} - Cierre: ${arqueo.fecha_fin}`;
                            if (index === 0) option.selected = true;
                            select.appendChild(option);
                        });
                    }
                });

            modal.show();
        }
    </script>
@endsection
