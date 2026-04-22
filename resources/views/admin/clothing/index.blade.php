@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    @if(isset($tenantinfo) && $tenantinfo->manage_department == 1)
        <li class="breadcrumb-item"><a href="{{ url('departments') }}">Departamentos</a></li>
        @if(isset($department_name) && $department_name)
        <li class="breadcrumb-item"><a href="{{ url('categories/' . $department_id) }}">{{ $department_name }}</a></li>
        @endif
    @else
        <li class="breadcrumb-item"><a href="{{ url('categories') }}">Categorías</a></li>
    @endif
    <li class="breadcrumb-item active">{{ $category_name }}</li>
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
{{-- ── Category quick-nav ──────────────────────────────────── --}}
@if(isset($categories) && $categories->count() > 1)
<div class="cat-nav-bar">
    <a href="{{ url('categories/' . $department_id) }}" class="cn-back"
        title="Volver a {{ (isset($tenantinfo) && $tenantinfo->manage_department == 1) ? ($department_name ?? 'Categorías') : 'Categorías' }}">
        <span class="material-icons">arrow_back</span>
        <span>{{ (isset($tenantinfo) && $tenantinfo->manage_department == 1) ? ($department_name ?? 'Categorías') : 'Categorías' }}</span>
    </a>
    <div class="cat-nav-sep"></div>
    @foreach($categories as $cat)
    <a href="{{ url('/add-item/' . $cat->id) }}"
        class="cat-chip {{ $cat->id == $category_id ? 'active' : '' }}">
        {{ $cat->name }}
    </a>
    @endforeach
</div>
@endif
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">filter_list</span></div>
        <span class="card-h-title">Filtros</span>
        <div class="card-h-actions">
            <a href="{{ url('new-item/' . $category_id) }}" class="act-btn ab-add" title="Agregar producto">
                <span class="material-icons">add</span>
            </a>
            <a href="{{ url('bulk-upload/' . $category_id) }}" class="act-btn ab-neutral" title="Carga masiva CSV">
                <span class="material-icons">upload_file</span>
            </a>
        </div>
    </div>
    <div class="s-card-body" style="display:grid;grid-template-columns:1fr 180px 150px 170px;gap:12px;">
        <div>
            <label class="filter-label">Filtrar</label>
            <input value="" placeholder="Escribe para filtrar...." type="text"
                class="filter-input" name="searchfor" id="searchfor">
        </div>
        <div>
            <label class="filter-label">Mostrar</label>
            <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                <option value="5">5 Registros</option>
                <option value="10">10 Registros</option>
                <option selected value="15">15 Registros</option>
                <option value="50">50 Registros</option>
            </select>
        </div>
        <div>
            <label class="filter-label">Estado</label>
            <select id="status" name="status" class="filter-input">
                <option value="2">Todos</option>
                <option value="1" selected>Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
        <div>
            <label class="filter-label">Stock</label>
            <select id="stock-filter" name="stock" class="filter-input">
                <option value="">Todos</option>
                <option value="low">Bajo (≤5)</option>
                <option value="out">Sin stock</option>
            </select>
        </div>
    </div>
</div>
{{-- Bulk action toolbar --}}
<div class="bulk-toolbar" id="bulk-toolbar">
    <span class="bulk-count" id="bulk-count">0 seleccionados</span>
    <button class="act-btn ab-neutral" id="bulk-activate" title="Activar seleccionados">
        <span class="material-icons">visibility</span>
    </button>
    <button class="act-btn ab-neutral" id="bulk-deactivate" title="Desactivar seleccionados">
        <span class="material-icons">visibility_off</span>
    </button>
    <button class="act-btn ab-del" id="bulk-delete" title="Eliminar seleccionados">
        <span class="material-icons">delete</span>
    </button>
    <button class="act-btn ab-neutral" id="bulk-price-adj" title="Ajustar precios">
        <span class="material-icons">percent</span>
    </button>
    <button class="act-btn ab-neutral" id="bulk-cancel" title="Cancelar selección">
        <span class="material-icons">close</span>
    </button>
</div>

{{-- Price adjustment modal --}}
<div class="modal fade" id="priceAdjModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajustar precios</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-xs text-secondary mb-3" id="pa-count-label"></p>
                <div style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Tipo de ajuste</label>
                        <select id="pa-type" class="filter-input">
                            <option value="increase">Incrementar (%)</option>
                            <option value="discount">Descontar (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label">Porcentaje</label>
                        <input type="number" id="pa-pct" class="filter-input" min="1" max="100" value="10" placeholder="Ej: 10">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button class="s-btn-sec w-auto" data-bs-dismiss="modal">Cancelar</button>
                <button class="s-btn-primary w-auto" id="pa-save">Aplicar</button>
            </div>
        </div>
    </div>
</div>

    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="clothing_table">
                        <thead>
                            <tr>
                                <th style="width:36px;">
                                    <input type="checkbox" id="bulk-select-all" title="Seleccionar todos">
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Activo') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Producto') }}
                                </th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Precio') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Atributos') }}</th>
                                <th class=" text-secondary font-weight-bolder opacity-7">
                                    {{ __('Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

{{-- Quick-edit modal --}}
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="qe-product-name">Edición rápida</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="qe-body">
                <div class="text-center py-3"><span class="material-icons" style="font-size:2rem;color:var(--gray2)">sync</span></div>
            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button class="s-btn-sec w-auto" data-bs-dismiss="modal">Cancelar</button>
                <button class="s-btn-primary w-auto" id="qe-save">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        var CATEGORY_ID = {{ $category_id }};
        var CSRF_TOKEN  = $('meta[name="csrf-token"]').attr('content');

        /* ── Persistent filter helpers (B1) ───────────────────────── */
        var LS_STATUS = 'dt_status_' + CATEGORY_ID;
        var LS_LEN    = 'dt_len_' + CATEGORY_ID;
        var LS_STOCK  = 'dt_stock_' + CATEGORY_ID;
        var savedStatus = localStorage.getItem(LS_STATUS) ?? '1';
        var savedLen    = parseInt(localStorage.getItem(LS_LEN) ?? '15');
        var savedStock  = localStorage.getItem(LS_STOCK) ?? '';
        // Pre-select saved filter values
        $('#status').val(savedStatus);
        $('#recordsPerPage').val(savedLen);
        $('#stock-filter').val(savedStock);

        function buildAjaxUrl() {
            var status = $('#status').val();
            var stock  = $('#stock-filter').val();
            return '/add-item/' + CATEGORY_ID + '?status=' + status + (stock ? '&stock=' + stock : '');
        }

        $(document).ready(function() {
            /* ── DataTable ─────────────────────────────────────────── */
            var tableClothings = $('#clothing_table').DataTable({
                searching: true,
                lengthChange: false,
                pageLength: savedLen,
                serverSide: true,
                ajax: { url: buildAjaxUrl(), type: 'GET' },
                columns: [
                    { data: 'bulk_check', orderable: false, searchable: false },
                    { data: 'status' },
                    { data: 'acciones', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'price' },
                    { data: 'atributos' },
                    { data: 'stock' }
                ],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', titleAttr: 'Exportar a Excel', className: 'btn btn-table', title: 'Reporte Excel',
                      exportOptions: { modifier: { search: 'applied', order: 'applied' } } },
                    { extend: 'pdfHtml5',   text: '<i class="fas fa-file-pdf"></i> PDF',   titleAttr: 'Exportar a PDF',   className: 'btn btn-table', title: 'Reporte PDF',
                      exportOptions: { modifier: { search: 'applied', order: 'applied' } } }
                ],
                language: {
                    sProcessing: 'Procesando...', sZeroRecords: 'No se encontraron resultados',
                    sEmptyTable: 'Ningún dato disponible en esta tabla',
                    sInfo: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    sInfoEmpty: 'Mostrando 0 a 0 de 0 registros',
                    sInfoFiltered: '(filtrado de _MAX_ registros)',
                    sSearch: 'Buscar:',
                    oPaginate: { sFirst: '<<', sLast: 'Último', sNext: '>>', sPrevious: '<<' }
                }
            });

            /* ── Filters ───────────────────────────────────────────── */
            $('#recordsPerPage').on('change', function() {
                localStorage.setItem(LS_LEN, $(this).val());
                tableClothings.page.len(parseInt($(this).val())).draw();
            });
            $('#searchfor').on('input', function() {
                tableClothings.search($(this).val()).draw();
            });
            $('#status').on('change', function() {
                localStorage.setItem(LS_STATUS, $(this).val());
                tableClothings.ajax.url(buildAjaxUrl()).load();
            });
            $('#stock-filter').on('change', function() {
                localStorage.setItem(LS_STOCK, $(this).val());
                tableClothings.ajax.url(buildAjaxUrl()).load();
            });

            /* ── Copy SKU (B3) ─────────────────────────────────────── */
            $(document).on('click', '.copy-sku', function(e) {
                e.stopPropagation();
                var sku  = $(this).data('sku');
                var icon = $(this).find('.material-icons');
                navigator.clipboard.writeText(sku).then(function() {
                    icon.text('check');
                    setTimeout(() => icon.text('content_copy'), 1500);
                });
            });

            /* ── Delete single ─────────────────────────────────────── */
            function getTotal(itemId, cb) {
                $.get('/get-total-categories/' + itemId, cb);
            }
            $(document).on('click', '.btnDeleteItem', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                getTotal(itemId, function(total) {
                    var msg = total > 1
                        ? 'Este producto está ligado a más de una categoría. ¿Deseas eliminarlo de todas?'
                        : '¿Deseas eliminar este artículo?';
                    Swal.fire({ title: 'Eliminar producto', text: msg, icon: 'warning',
                        showCancelButton: true, confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar', confirmButtonColor: '#ff3b30'
                    }).then(res => {
                        if (!res.isConfirmed) return;
                        $.ajax({ method: 'POST', url: '/delete-clothing/' + itemId,
                            data: { _token: CSRF_TOKEN, _method: 'DELETE' },
                            success: () => tableClothings.ajax.reload(null, false),
                            error: xhr => console.error(xhr.responseText)
                        });
                    });
                });
            });

            /* ── Toggle status ─────────────────────────────────────── */
            $(document).on('change', '.changeStatus', function() {
                var itemId = $(this).val(), status = $(this).prop('checked') ? 1 : 0;
                $.ajax({ url: '/status/' + itemId, method: 'POST',
                    data: { _token: CSRF_TOKEN, status },
                    success: res => Swal.fire({ title: 'Cambio de estado', text: res.message,
                        icon: 'success', timer: 1500, showConfirmButton: false }),
                    error: () => Swal.fire({ title: 'Error', text: 'No se pudo actualizar el estado', icon: 'error' })
                });
            });

            /* ── Bulk selection ────────────────────────────────────── */
            function updateBulkToolbar() {
                var count = $('.bulk-cb:checked').length;
                $('#bulk-count').text(count + ' seleccionado' + (count !== 1 ? 's' : ''));
                count > 0 ? $('#bulk-toolbar').addClass('visible') : $('#bulk-toolbar').removeClass('visible');
                $('#bulk-select-all').prop('indeterminate', count > 0 && count < $('.bulk-cb').length);
                $('#bulk-select-all').prop('checked', count > 0 && count === $('.bulk-cb').length);
            }
            $(document).on('change', '.bulk-cb', updateBulkToolbar);
            $('#bulk-select-all').on('change', function() {
                $('.bulk-cb').prop('checked', $(this).prop('checked'));
                updateBulkToolbar();
            });
            tableClothings.on('draw', function() {
                $('#bulk-select-all').prop('checked', false).prop('indeterminate', false);
                $('#bulk-toolbar').removeClass('visible');
            });
            $('#bulk-cancel').on('click', function() {
                $('.bulk-cb, #bulk-select-all').prop('checked', false).prop('indeterminate', false);
                $('#bulk-toolbar').removeClass('visible');
            });

            function bulkRequest(action, confirmMsg) {
                var ids = $('.bulk-cb:checked').map(function() { return $(this).val(); }).get();
                if (!ids.length) return;
                Swal.fire({ title: '¿Confirmar acción?', text: confirmMsg, icon: 'warning',
                    showCancelButton: true, confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: action === 'delete' ? '#ff3b30' : '#007aff'
                }).then(res => {
                    if (!res.isConfirmed) return;
                    $.ajax({ url: '/clothing/bulk-action', method: 'POST',
                        data: { _token: CSRF_TOKEN, action, ids, category_id: CATEGORY_ID },
                        success: () => {
                            tableClothings.ajax.reload(null, false);
                            $('#bulk-cancel').trigger('click');
                        },
                        error: xhr => Swal.fire('Error', xhr.responseJSON?.error ?? 'Error', 'error')
                    });
                });
            }
            $('#bulk-activate').on('click',   () => bulkRequest('activate',   'Se activarán los productos seleccionados.'));
            $('#bulk-deactivate').on('click', () => bulkRequest('deactivate', 'Se desactivarán los productos seleccionados.'));
            $('#bulk-delete').on('click',     () => bulkRequest('delete',     'Se eliminarán permanentemente los productos seleccionados.'));

            /* ── Bulk price adjust ─────────────────────────────────── */
            $('#bulk-price-adj').on('click', function() {
                var count = $('.bulk-cb:checked').length;
                if (!count) return;
                $('#pa-count-label').text(count + ' producto' + (count !== 1 ? 's' : '') + ' seleccionado' + (count !== 1 ? 's' : ''));
                new bootstrap.Modal(document.getElementById('priceAdjModal')).show();
            });
            $('#pa-save').on('click', function() {
                var ids  = $('.bulk-cb:checked').map(function() { return $(this).val(); }).get();
                var type = $('#pa-type').val();
                var pct  = parseFloat($('#pa-pct').val());
                if (!pct || pct <= 0 || pct > 100) {
                    Swal.fire('Atención', 'Ingresa un porcentaje válido (1-100)', 'warning');
                    return;
                }
                var label = type === 'increase' ? 'incrementarán' : 'descontarán';
                Swal.fire({
                    title: '¿Confirmar ajuste?',
                    text: 'Se ' + label + ' los precios un ' + pct + '% en ' + ids.length + ' producto(s). Los precios de variantes también serán ajustados.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonText: 'Aplicar', cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#007aff'
                }).then(res => {
                    if (!res.isConfirmed) return;
                    $.ajax({ url: '/clothing/bulk-price-adjust', method: 'POST',
                        data: { _token: CSRF_TOKEN, ids, type, pct, category_id: CATEGORY_ID },
                        success: function() {
                            bootstrap.Modal.getInstance(document.getElementById('priceAdjModal')).hide();
                            tableClothings.ajax.reload(null, false);
                            $('#bulk-cancel').trigger('click');
                            Swal.fire({ icon: 'success', title: 'Precios actualizados', timer: 1400, showConfirmButton: false });
                        },
                        error: xhr => Swal.fire('Error', xhr.responseJSON?.error ?? 'Error', 'error')
                    });
                });
            });

            /* ── Quick-edit ────────────────────────────────────────── */
            var qeItemId = null, qeHasAttr = false;

            $(document).on('click', '.btnQuickEdit', function() {
                qeItemId  = $(this).data('item-id');
                qeHasAttr = $(this).data('has-attr') == 1;
                var name  = $(this).data('item-name');
                $('#qe-product-name').text(name);
                $('#qe-body').html('<div class="text-center py-3"><span class="material-icons spin" style="font-size:2rem;color:var(--gray2)">sync</span></div>');
                var modal = new bootstrap.Modal(document.getElementById('quickEditModal'));
                modal.show();

                $.get('/clothing/' + qeItemId + '/variants', function(data) {
                    var html = '';
                    if (data.has_attr) {
                        html += '<p style="font-size:.75rem;color:var(--gray3);margin-bottom:.6rem;">Editá precio y stock por variante. Precio <strong>0</strong> = usa el precio base del producto. Stock <strong>−1</strong> = sin control.</p>';
                        html += '<div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse">';
                        html += '<thead><tr style="border-bottom:1px solid var(--gray1)">'
                            + '<th class="surface-title" style="padding:.35rem .5rem;font-size:.67rem;text-align:left">Variante</th>'
                            + '<th class="surface-title" style="padding:.35rem .5rem;font-size:.67rem;width:90px">Stock</th>'
                            + '<th class="surface-title" style="padding:.35rem .5rem;font-size:.67rem;width:110px">Precio (₡)</th>'
                            + '</tr></thead><tbody>';
                        data.variants.forEach(function(v) {
                            html += '<tr>'
                                + '<td style="padding:.3rem .4rem"><span class="vb-variant-chip" style="font-size:.72rem">' + v.label + '</span></td>'
                                + '<td style="padding:.25rem .4rem"><input type="number" class="filter-input qe-stock" style="width:100%" data-id="' + v.id + '" data-type="' + (v.type || 'stock') + '" value="' + v.stock + '" min="-1"></td>'
                                + '<td style="padding:.25rem .4rem"><input type="number" class="filter-input qe-price" style="width:100%" data-id="' + v.id + '" value="' + v.price + '" min="0" placeholder="0 = base"></td>'
                                + '</tr>';
                        });
                        html += '</tbody></table></div>';
                    } else {
                        html += '<div style="display:grid;gap:12px;">';
                        html += '<div><label class="filter-label">Precio (₡)</label>'
                            + '<input type="number" id="qe-base-price" class="filter-input" value="' + data.base_price + '" min="0"></div>';
                        if (data.manage_stock != 0) {
                            html += '<div><label class="filter-label">Stock</label>'
                                + '<input type="number" id="qe-base-stock" class="filter-input" value="' + data.base_stock + '" min="0"></div>';
                        }
                        html += '</div>';
                    }
                    $('#qe-body').html(html);
                }).fail(function() {
                    $('#qe-body').html('<p class="text-danger">Error al cargar datos.</p>');
                });
            });

            $('#qe-save').on('click', function() {
                var btn = $(this).prop('disabled', true).text('Guardando...');
                if (qeHasAttr) {
                    var variants = [];
                    $('.qe-stock').each(function() {
                        var id   = $(this).data('id');
                        var type = $(this).data('type') || 'stock';
                        variants.push({ id, type, stock: $(this).val(), price: $('.qe-price[data-id="' + id + '"]').val() });
                    });
                    $.ajax({ url: '/clothing/variants/update', method: 'POST',
                        data: { _token: CSRF_TOKEN, variants, category_id: CATEGORY_ID },
                        success: function() {
                            bootstrap.Modal.getInstance(document.getElementById('quickEditModal')).hide();
                            tableClothings.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Guardado', timer: 1200, showConfirmButton: false });
                        },
                        error: xhr => Swal.fire('Error', xhr.responseJSON?.error ?? 'Error', 'error'),
                        complete: () => btn.prop('disabled', false).text('Guardar')
                    });
                } else {
                    var payload = { _token: CSRF_TOKEN, category_id: CATEGORY_ID };
                    var price = $('#qe-base-price').val(), stock = $('#qe-base-stock').val();
                    if (price !== undefined) payload.price = price;
                    if (stock !== undefined) payload.stock = stock;
                    $.ajax({ url: '/clothing/' + qeItemId + '/quick-edit', method: 'POST',
                        data: payload,
                        success: function() {
                            bootstrap.Modal.getInstance(document.getElementById('quickEditModal')).hide();
                            tableClothings.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Guardado', timer: 1200, showConfirmButton: false });
                        },
                        error: xhr => Swal.fire('Error', xhr.responseJSON?.error ?? 'Error', 'error'),
                        complete: () => btn.prop('disabled', false).text('Guardar')
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
