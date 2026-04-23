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

    {{-- Panel de filtros --}}
    <div class="surface p-3 mb-3">
        <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.75rem;">
            <i class="fas fa-filter me-1"></i> Filtros
        </p>
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="filter-label">Desde</label>
                <input type="date" class="filter-input" id="filter_desde"
                    value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Hasta</label>
                <input type="date" class="filter-input" id="filter_hasta"
                    value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Especialista</label>
                <select class="filter-input" id="filter_especialista">
                    <option value="">Todos</option>
                    @foreach($especialistas as $esp)
                        <option value="{{ $esp->id }}">{{ $esp->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="filter-label">Estado</label>
                <select class="filter-input" id="filter_estado">
                    <option value="todos">Todos</option>
                    <option value="activas" selected>Solo activas</option>
                    <option value="anuladas">Solo anuladas</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" class="filter-input">
                    <option value="10">10</option>
                    <option selected value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="button" id="btn_aplicar_filtros" class="s-btn-primary w-100" title="Aplicar filtros">
                    <i class="fas fa-search"></i>
                </button>
                <button type="button" id="btn_limpiar_filtros" class="s-btn-sec w-100" title="Limpiar filtros">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="row g-2 mt-1">
            <div class="col-md-4">
                <label class="filter-label">Buscar texto</label>
                <input type="text" class="filter-input" id="searchfor" placeholder="Especialista, servicio, tipo de pago...">
            </div>
        </div>
    </div>

    {{-- Tarjetas de totales (se actualizan con los filtros) --}}
    <div class="row g-2 mb-3" id="summary_cards">
        <div class="col-6 col-md-3">
            <div class="surface p-3 text-center">
                <p style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:4px;">
                    Ventas activas
                </p>
                <span class="fw-bold" id="sum_registros" style="font-size:1.25rem;color:#2d3748;">—</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="surface p-3 text-center">
                <p style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:4px;">
                    Total vendido
                </p>
                <span class="fw-bold" id="sum_venta" style="font-size:1.1rem;color:#2d3748;">—</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="surface p-3 text-center">
                <p style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#48bb78;margin-bottom:4px;">
                    Total clínica
                </p>
                <span class="fw-bold text-success" id="sum_clinica" style="font-size:1.1rem;">—</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="surface p-3 text-center">
                <p style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#5e72e4;margin-bottom:4px;">
                    Total especialistas
                </p>
                <span class="fw-bold text-primary" id="sum_especialista" style="font-size:1.1rem;">—</span>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="surface">
        <div class="table-responsive">
            <table id="table_ventas" class="table align-items-center mb-0">
                <thead class="thead-lite">
                    <tr>
                        <th style="width:100px;">Acciones</th>
                        <th>Especialista</th>
                        <th>Servicio(s)</th>
                        <th>Monto Venta</th>
                        <th>Clínica</th>
                        <th>Especialista</th>
                        <th>Producto</th>
                        <th style="width:60px;">%</th>
                        <th>Tipo pago</th>
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

            // ── Fechas por defecto (mes actual) ──────────────────────────────
            const hoy = new Date().toISOString().split('T')[0];
            const primerDiaMes = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                .toISOString().split('T')[0];

            function getFiltros() {
                return {
                    fecha_desde:     $('#filter_desde').val()         || '',
                    fecha_hasta:     $('#filter_hasta').val()          || '',
                    especialista_id: $('#filter_especialista').val()   || '',
                    estado:          $('#filter_estado').val()         || 'todos',
                };
            }

            // ── DataTable ────────────────────────────────────────────────────
            var tableVentas = $('#table_ventas').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true,
                ajax: {
                    url: "/ajax/ventas",
                    type: "GET",
                    data: function(d) {
                        var f = getFiltros();
                        d.fecha_desde     = f.fecha_desde;
                        d.fecha_hasta     = f.fecha_hasta;
                        d.especialista_id = f.especialista_id;
                        d.estado          = f.estado;
                    }
                },
                columns: [
                    { data: 'acciones',             orderable: false, searchable: false },
                    { data: 'nombre',               name: 'especialistas.nombre' },
                    { data: 'servicios',            searchable: false, orderable: false },
                    { data: 'monto_venta',          searchable: false },
                    { data: 'monto_clinica',        searchable: false },
                    { data: 'monto_especialista',   searchable: false },
                    { data: 'monto_producto_venta', searchable: false },
                    { data: 'porcentaje',           searchable: false },
                    { data: 'tipo',                 name: 'tipo_pagos.tipo' },
                    { data: 'justificacion_arqueo', searchable: false, orderable: false },
                    { data: 'nota_anulacion',       searchable: false, orderable: false },
                    { data: 'created_at',           name: 'venta_especialistas.created_at' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-table',
                        title: function() {
                            return 'Ventas ' + ($('#filter_desde').val() || '') + ' al ' + ($('#filter_hasta').val() || '');
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-table',
                        title: function() {
                            return 'Ventas ' + ($('#filter_desde').val() || '') + ' al ' + ($('#filter_hasta').val() || '');
                        }
                    }
                ],
                language: {
                    sProcessing:   "Cargando...",
                    sZeroRecords:  "No se encontraron ventas con los filtros aplicados",
                    sEmptyTable:   "Sin ventas registradas",
                    sInfo:         "Mostrando _START_ a _END_ de _TOTAL_ ventas",
                    sInfoEmpty:    "Mostrando 0 a 0 de 0 ventas",
                    sInfoFiltered: "(filtrado de _MAX_ total)",
                    sSearch:       "Buscar:",
                    oPaginate: { sFirst: "<<", sLast: "Último", sNext: ">", sPrevious: "<" }
                }
            });

            // ── Búsqueda de texto libre ───────────────────────────────────────
            $('#recordsPerPage').on('change', function() {
                tableVentas.page.len(parseInt($(this).val())).draw();
            });
            $('#searchfor').on('input', function() {
                tableVentas.search($(this).val()).draw();
            });

            // ── Totales ───────────────────────────────────────────────────────
            function cargarResumen() {
                var f = getFiltros();
                // Los totales siempre excluyen anuladas (son fondos reales)
                $.get('/ajax/ventas/resumen', {
                    fecha_desde:     f.fecha_desde,
                    fecha_hasta:     f.fecha_hasta,
                    especialista_id: f.especialista_id
                }, function(data) {
                    var fmt = function(n) {
                        return '₡' + parseFloat(n).toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                    };
                    $('#sum_registros').text(data.total_registros);
                    $('#sum_venta').text(fmt(data.total_venta));
                    $('#sum_clinica').text(fmt(data.total_clinica));
                    $('#sum_especialista').text(fmt(data.total_especialista));
                });
            }

            // ── Aplicar filtros ───────────────────────────────────────────────
            function aplicarFiltros() {
                tableVentas.ajax.reload();
                cargarResumen();
            }

            $('#btn_aplicar_filtros').on('click', aplicarFiltros);

            // Aplicar también al presionar Enter en los inputs de fecha
            $('#filter_desde, #filter_hasta').on('change', aplicarFiltros);

            $('#btn_limpiar_filtros').on('click', function() {
                $('#filter_desde').val(primerDiaMes);
                $('#filter_hasta').val(hoy);
                $('#filter_especialista').val('');
                $('#filter_estado').val('activas');
                $('#searchfor').val('');
                aplicarFiltros();
            });

            // Carga inicial
            cargarResumen();
        });

        // ── Modales ───────────────────────────────────────────────────────────
        function abrirAnularModal(id) {
            const modal = new bootstrap.Modal(document.getElementById('anularModal'));
            document.getElementById('anularForm').action = '/anular/venta/' + id;
            document.getElementById('nota_anulacion_input').value = '';
            modal.show();
        }

        function abrirCambioArqueoModal(ventaId, createdAt) {
            const modal = new bootstrap.Modal(document.getElementById('changeArqueoModal'));
            const form  = document.getElementById('changeArqueoForm');
            form.action = '/cambiar/venta/' + ventaId;

            document.getElementById('justificacionArqueoInput').value = '';
            const select = document.getElementById('arqueoSelect');
            select.innerHTML = '<option>Cargando...</option>';

            fetch('/api/arqueos-validos?fecha=' + createdAt)
                .then(r => r.json())
                .then(data => {
                    select.innerHTML = '';
                    if (!data.length) {
                        select.innerHTML = '<option disabled selected>No hay arqueos disponibles</option>';
                        return;
                    }
                    data.forEach((arqueo, i) => {
                        const opt = document.createElement('option');
                        opt.value = arqueo.id;
                        opt.text  = 'Inicio: ' + arqueo.fecha_ini + ' — Cierre: ' + arqueo.fecha_fin;
                        if (i === 0) opt.selected = true;
                        select.appendChild(opt);
                    });
                });

            modal.show();
        }
    </script>
@endsection
