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
    <input type="hidden" id="especialista_id" value="{{ $especialista->id }}">

    {{-- Encabezado con info del especialista y selector para cambiar --}}
    <div class="page-header d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">Servicios asignados</h4>
            <p class="mb-0 mt-1" style="font-size:.8rem;color:#718096;">
                <i class="fas fa-user-md me-1"></i>
                <strong>{{ $especialista->nombre }}</strong>
                @if($especialista->salario_base > 0)
                    &nbsp;·&nbsp;
                    <span class="esp-badge badge-green">Salario base ₡{{ number_format($especialista->salario_base) }}</span>
                @endif
                @if($especialista->monto_por_servicio > 0)
                    &nbsp;·&nbsp;
                    <span class="esp-badge badge-blue">Por servicio ₡{{ number_format($especialista->monto_por_servicio) }}</span>
                @endif
            </p>
        </div>
        <a href="{{ url('/especialistas/') }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Selector para cambiar de especialista sin volver atrás --}}
    <div class="surface p-3 mb-3" style="border-left:4px solid var(--primary,#5e72e4);">
        <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.5rem;">
            <i class="fas fa-exchange-alt me-1"></i> Cambiar de especialista
        </p>
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="filter-label">Seleccionar otro especialista</label>
                <select id="selector_especialista" class="filter-input">
                    @foreach($todosEspecialistas as $esp)
                        <option value="{{ $esp->id }}" {{ $esp->id == $especialista->id ? 'selected' : '' }}>
                            {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button type="button" id="btn_cambiar_esp" class="s-btn-primary">
                    <i class="fas fa-arrow-right me-1"></i> Ir a ese especialista
                </button>
            </div>
        </div>
    </div>

    {{-- Agregar servicio --}}
    <div class="surface p-3 mb-3">
        <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.75rem;">
            <i class="fas fa-plus-circle me-1"></i> Agregar nuevo servicio
        </p>
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="filter-label">
                    Porcentaje (%)
                    <span class="ms-1" title="Porcentaje que la clínica recibe de este servicio. El especialista recibe el resto."
                        style="cursor:help;color:#a0aec0;font-size:.8rem;">&#9432;</span>
                </label>
                <input type="number" class="filter-input" id="porcentaje" placeholder="Ej: 30" min="0" max="100">
                <p style="font-size:.7rem;color:#a0aec0;margin-top:3px;margin-bottom:0;">
                    Clínica recibe el <strong id="prc_clinica_preview">0</strong>% · Especialista recibe el <strong id="prc_esp_preview">100</strong>%
                </p>
            </div>
            <div class="col-md-6">
                <label class="filter-label">Buscar y seleccionar servicio</label>
                <select id="search-select" class="filter-input select2" name="search">
                    <option value="">Seleccionar servicio...</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="surface p-2" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:.75rem;color:#166534;">
                    <i class="fas fa-lightbulb me-1 text-warning"></i>
                    Escribe el nombre del servicio en el campo de búsqueda y luego selecciónalo.
                    El porcentaje debe ingresarse <strong>antes</strong> de seleccionar el servicio.
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de servicios asignados --}}
    <div class="surface">
        <div class="d-flex align-items-center justify-content-between p-3 pb-0 mb-2 flex-wrap gap-2">
            <p class="mb-0" style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;">
                Servicios asignados
            </p>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <div>
                    <label class="filter-label visually-hidden">Filtrar</label>
                    <input type="text" class="filter-input" id="searchfor" placeholder="Filtrar servicios...">
                </div>
                <div>
                    <label class="filter-label visually-hidden">Mostrar</label>
                    <select id="recordsPerPage" class="filter-input">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option selected value="15">15</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table-services">
                <thead class="thead-lite">
                    <tr>
                        <th style="width:100px;">Acciones</th>
                        <th>Servicio</th>
                        <th style="width:140px;">% Clínica</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <style>
    .esp-badge {
        font-size: .68rem; font-weight: 700; padding: 2px 8px;
        border-radius: 20px; letter-spacing: .03em;
    }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-blue  { background: #dbeafe; color: #1d4ed8; }
    </style>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            var especialista_id = document.getElementById('especialista_id').value;

            // --- DataTable de servicios asignados ---
            var tableServices = $('#table-services').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: 15,
                serverSide: true,
                ajax: {
                    url: "/especialistas/service/list/" + especialista_id,
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
                        title: 'Servicios - {{ $especialista->nombre }}'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-table',
                        title: 'Servicios - {{ $especialista->nombre }}'
                    }
                ],
                language: {
                    sProcessing: "Procesando...",
                    sZeroRecords: "Este especialista no tiene servicios asignados",
                    sEmptyTable: "Ningún servicio asignado",
                    sInfo: "Mostrando _START_ a _END_ de _TOTAL_ servicios",
                    sInfoEmpty: "Mostrando 0 a 0 de 0 servicios",
                    sInfoFiltered: "(filtrado de _MAX_ total)",
                    sSearch: "Buscar:",
                    oPaginate: { sFirst: "<<", sLast: "Último", sNext: ">", sPrevious: "<" }
                }
            });

            $('#recordsPerPage').on('change', function() {
                tableServices.page.len(parseInt($(this).val())).draw();
            });
            $('#searchfor').on('input', function() {
                tableServices.search($(this).val()).draw();
            });

            // --- Preview dinámico del porcentaje ---
            $('#porcentaje').on('input', function() {
                var prc = parseFloat($(this).val()) || 0;
                prc = Math.min(100, Math.max(0, prc));
                $('#prc_clinica_preview').text(prc);
                $('#prc_esp_preview').text(100 - prc);
            });

            // --- Select2 para buscar servicios ---
            $('#search-select').select2({
                placeholder: "Escribe para buscar servicios...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/get/products/select/' + especialista_id,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) { return { search: params.term }; },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return { id: item.service_id, text: item.name };
                            })
                        };
                    }
                }
            });

            $('#search-select').on('change', function() {
                var selectedId = $(this).val();
                if (!selectedId) return;
                var porcentaje = document.getElementById('porcentaje').value;
                if (porcentaje === '' || isNaN(porcentaje)) {
                    Swal.fire({ title: "Ingresa el porcentaje antes de seleccionar el servicio.", icon: "warning" });
                    $(this).val(null).trigger('change');
                    return;
                }
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/especialistas/service/store-new",
                    data: { clothing_id: selectedId, especialista_id: especialista_id, porcentaje: porcentaje },
                    success: function() {
                        tableServices.ajax.reload();
                        $('#porcentaje').val('');
                        $('#prc_clinica_preview').text('0');
                        $('#prc_esp_preview').text('100');
                        $('#search-select').val(null).trigger('change');
                    },
                    error: function() {
                        Swal.fire({ title: "No se pudo agregar el servicio.", icon: "error" });
                    }
                });
            });

            // --- Cambiar de especialista ---
            $('#btn_cambiar_esp').on('click', function() {
                var newId = $('#selector_especialista').val();
                if (newId && newId != especialista_id) {
                    window.location.href = '/services/specialists/' + newId;
                }
            });
        });
    </script>
@endsection
