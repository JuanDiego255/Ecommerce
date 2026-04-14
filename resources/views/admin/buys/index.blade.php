@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Pedidos</li>
@endsection
@section('content')
<style>
/* ── Variables ──────────────────────────────────────────── */
:root {
    --blue:   #007aff;
    --green:  #34c759;
    --red:    #ff3b30;
    --orange: #ff9500;
    --gray0:  #f5f5f7;
    --gray1:  #e5e5ea;
    --gray2:  #c7c7cc;
    --gray3:  #86868b;
    --gray4:  #3a3a3c;
    --black:  #1d1d1f;
    --white:  #ffffff;
    --radius: 14px;
    --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
}

/* ── Layout ─────────────────────────────────────────────── */
.orders-wrap { max-width: 1500px; margin: 0 auto; }

/* ── Card ───────────────────────────────────────────────── */
.s-card {
    background: var(--white);
    border: 1px solid var(--gray1);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 12px;
    overflow: hidden;
}
.s-card-header {
    background: var(--gray0);
    border-bottom: 1px solid var(--gray1);
    padding: 11px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.s-card-header .card-h-icon {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: rgba(0,122,255,.1);
    display: flex;
    align-items: center;
    justify-content: center;
}
.s-card-header .card-h-icon .material-icons { font-size: .9rem; color: var(--blue); }
.s-card-header .card-h-title {
    font-size: .82rem;
    font-weight: 600;
    color: var(--black);
    letter-spacing: -.01em;
}

/* ── Filter inputs ──────────────────────────────────────── */
.filter-label {
    display: block;
    font-size: .7rem;
    font-weight: 600;
    color: var(--gray3);
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 5px;
}
.filter-input {
    width: 100%;
    padding: 9px 14px;
    border: 1.5px solid var(--gray1);
    border-radius: 10px;
    font-size: .875rem;
    background: var(--white);
    color: var(--black);
    outline: none;
    transition: border-color .15s;
    -webkit-appearance: none;
}
.filter-input:focus { border-color: var(--blue); }

/* ── Table ──────────────────────────────────────────────── */
.orders-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.orders-table thead th {
    padding: 10px 12px;
    font-size: .67rem;
    font-weight: 600;
    color: var(--gray3);
    text-transform: uppercase;
    letter-spacing: .05em;
    border-bottom: 1px solid var(--gray1);
    background: var(--gray0);
    white-space: nowrap;
}
.orders-table tbody tr { border-bottom: 1px solid var(--gray1); transition: background .1s; }
.orders-table tbody tr:hover { background: #fafafa; }
.orders-table tbody tr:last-child { border-bottom: none; }
.orders-table td { padding: 10px 12px; vertical-align: middle; color: var(--black); }

/* ── Action buttons ─────────────────────────────────────── */
.act-group { display: flex; align-items: center; gap: 4px; flex-wrap: nowrap; }
.act-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1.5px solid transparent;
    cursor: pointer;
    padding: 0;
    background: transparent;
    transition: background .12s, border-color .12s;
    text-decoration: none;
}
.act-btn .material-icons { font-size: .9rem; }
.act-btn.ab-view    { color: var(--blue);   border-color: rgba(0,122,255,.25);  background: rgba(0,122,255,.08); }
.act-btn.ab-view:hover { background: rgba(0,122,255,.18); }
.act-btn.ab-ok      { color: var(--green);  border-color: rgba(52,199,89,.25);  background: rgba(52,199,89,.08); }
.act-btn.ab-ok:hover { background: rgba(52,199,89,.18); }
.act-btn.ab-warn    { color: var(--orange); border-color: rgba(255,149,0,.25);  background: rgba(255,149,0,.08); }
.act-btn.ab-warn:hover { background: rgba(255,149,0,.18); }
.act-btn.ab-del     { color: var(--red);    border-color: rgba(255,59,48,.25);  background: rgba(255,59,48,.08); }
.act-btn.ab-del:hover { background: rgba(255,59,48,.18); }
.act-btn.ab-neutral { color: var(--gray4);  border-color: var(--gray1); background: var(--gray0); }
.act-btn.ab-neutral:hover { background: var(--gray1); }
.act-divider { width: 1px; height: 18px; background: var(--gray1); margin: 0 2px; flex-shrink: 0; }

/* ── Status pills ───────────────────────────────────────── */
.s-pill {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: .7rem;
    font-weight: 600;
    border-radius: 20px;
    padding: 3px 9px;
    white-space: nowrap;
}
.pill-green  { background: rgba(52,199,89,.12);  color: #1a7f3c; }
.pill-blue   { background: rgba(0,122,255,.1);   color: #0051c7; }
.pill-orange { background: rgba(255,149,0,.12);  color: #7d4a00; }
.pill-red    { background: rgba(255,59,48,.12);  color: #c41230; }
.pill-gray   { background: var(--gray1);          color: var(--gray3); }

/* ── Cell text ──────────────────────────────────────────── */
.cell-main { font-size: .82rem; font-weight: 500; color: var(--black); margin: 0; }
.cell-sub  { font-size: .72rem; color: var(--gray3); margin: 0; }
.cell-mono { font-size: .82rem; font-family: ui-monospace, monospace; font-weight: 600; color: var(--black); margin: 0; }

/* ── Thumbnail ──────────────────────────────────────────── */
.thumb-img {
    width: 38px; height: 38px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--gray1);
}

/* ── DataTable overrides ─────────────────────────────────── */
div.dataTables_wrapper div.dataTables_info,
div.dataTables_wrapper div.dataTables_paginate { font-size: .78rem; color: var(--gray3); padding: 12px 16px; }
div.dataTables_wrapper div.dataTables_paginate .paginate_button { border-radius: 8px !important; font-size: .78rem !important; padding: 4px 10px !important; border: none !important; }
div.dataTables_wrapper div.dataTables_paginate .paginate_button.current { background: var(--blue) !important; color: #fff !important; border: none !important; }
div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover:not(.current) { background: var(--gray1) !important; color: var(--black) !important; border: none !important; }
div.dataTables_wrapper div.dataTables_filter,
div.dataTables_wrapper div.dataTables_length { display: none; }

/* ── Excel / PDF export buttons ─────────────────────────── */
div.dt-buttons { padding: 12px 20px 4px; display: flex; gap: 8px; }
.dt-button,
div.dt-buttons a.dt-button,
div.dt-buttons button.dt-button {
    background: var(--blue) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 999px !important;
    padding: 8px 22px !important;
    font-size: .78rem !important;
    font-weight: 600 !important;
    letter-spacing: .01em !important;
    box-shadow: 0 2px 6px rgba(0,122,255,.3) !important;
    transition: background .14s, box-shadow .14s !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    text-transform: none !important;
}
.dt-button:hover,
div.dt-buttons a.dt-button:hover,
div.dt-buttons button.dt-button:hover {
    background: #0066d6 !important;
    box-shadow: 0 4px 12px rgba(0,122,255,.35) !important;
    color: #fff !important;
}
.dt-button:focus { outline: none !important; }
</style>

<div class="orders-wrap py-3 px-2 px-md-3">

    {{-- Breadcrumb --}}
{{--     <div class="mb-4 d-flex align-items-center gap-2">
        <span style="font-size:.72rem;color:var(--gray3);font-weight:500;">Admin</span>
        <i class="material-icons" style="font-size:.8rem;color:var(--gray2);">chevron_right</i>
        <span style="font-size:.72rem;color:var(--black);font-weight:600;">Pedidos</span>
    </div> --}}

    {{-- Filters card --}}
    <div class="s-card">
        <div class="s-card-header">
            <div class="card-h-icon"><i class="material-icons">tune</i></div>
            <span class="card-h-title">Filtros</span>
        </div>
        <div style="padding:18px 22px;">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label">Buscar</label>
                    <input value="" placeholder="Nombre, teléfono, correo…" type="text"
                        class="filter-input" name="searchfor" id="searchfor">
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Mostrar</label>
                    <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                        <option value="5">5 registros</option>
                        <option value="10">10 registros</option>
                        <option selected value="15">15 registros</option>
                        <option value="50">50 registros</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Estado</label>
                    <select id="recordsPerStatus" name="recordsPerStatus" class="filter-input">
                        <option value="Pendiente" selected>Pendiente</option>
                        <option value="Entregado">Entregado</option>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                            <option value="Apartado">Apartado</option>
                        @endif
                        <option value="Venta Interna">Venta Interna</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="s-card">
        <div class="s-card-header">
            <div class="card-h-icon"><i class="material-icons">receipt_long</i></div>
            <span class="card-h-title">Pedidos</span>
        </div>
        <div class="table-responsive">
            <table id="table" class="orders-table">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Origen</th>
                        @if ($tenantinfo->tenant == 'sakura318')
                            <th>Recolección</th>
                        @endif
                        <th>Comprobante</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>E-mail</th>
                        <th>Total</th>
                        <th>Envío</th>
                        <th>Pendiente</th>
                        <th>Cupón</th>
                        <th>Entrega</th>
                        <th>Aprobación</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buys as $buy)
                        @php
                            $createdAt = $buy->created_at;
                            $isOlderThanMonth = $createdAt &&
                                \Carbon\Carbon::parse($createdAt)
                                    ->setTimezone('America/Costa_Rica')
                                    ->lt(\Carbon\Carbon::now('America/Costa_Rica')->subMonth());
                            $buyName  = $buy->name ?? $buy->name_b ?? $buy->last_name ?? $buy->last_name_b ?? $buy->detail ?? '—';
                            $buyTel   = $buy->telephone ?? $buy->telephone_b ?? '—';
                            $buyEmail = $buy->email ?? $buy->email_b ?? '—';
                        @endphp
                        <tr>
                            {{-- Acciones --}}
                            <td>
                                <div class="act-group">
                                    @if ($buy->cancel_buy == 0)
                                        <a href="{{ url('buy/details/admin/' . $buy->id) }}"
                                           class="act-btn ab-view"
                                           data-bs-toggle="tooltip" title="Ver detalle">
                                            <i class="material-icons">visibility</i>
                                        </a>

                                        <div class="act-divider"></div>

                                        {{-- Aprobar --}}
                                        <form style="display:inline" action="{{ url('approve/' . $buy->id . '/' . $buy->approved) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                class="act-btn {{ $buy->approved ? 'ab-warn' : 'ab-ok' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $buy->approved ? 'Desaprobar' : 'Aprobar' }}">
                                                <i class="material-icons">{{ $buy->approved ? 'cancel' : 'check_circle' }}</i>
                                            </button>
                                        </form>

                                        {{-- Listo para enviar --}}
                                        <form style="display:inline" action="{{ url('ready/' . $buy->id . '/' . $buy->ready_to_give) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                class="act-btn {{ $buy->ready_to_give ? 'ab-warn' : 'ab-neutral' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $buy->ready_to_give ? 'Procesando…' : 'Listo para enviar' }}">
                                                <i class="material-icons">{{ $buy->ready_to_give ? 'inventory' : 'inventory_2' }}</i>
                                            </button>
                                        </form>

                                        {{-- Entregado --}}
                                        <form style="display:inline" action="{{ url('delivery/' . $buy->id . '/' . $buy->delivered) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                class="act-btn {{ $buy->delivered ? 'ab-warn' : 'ab-neutral' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $buy->delivered ? 'Marcar no entregado' : 'Marcar entregado' }}">
                                                <i class="material-icons">{{ $buy->delivered ? 'undo' : 'local_shipping' }}</i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($buy->cancel_buy == 1)
                                        <form style="display:inline" action="{{ url('cancel/buy/' . $buy->id . '/' . $buy->cancel_buy) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="1">
                                            <button type="submit" class="act-btn ab-ok"
                                                data-bs-toggle="tooltip" title="Aprobar cancelación">
                                                <i class="material-icons">check</i>
                                            </button>
                                        </form>
                                        <form style="display:inline" action="{{ url('cancel/buy/' . $buy->id . '/' . $buy->cancel_buy) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="0">
                                            <button type="submit" class="act-btn ab-del"
                                                data-bs-toggle="tooltip" title="Rechazar cancelación">
                                                <i class="material-icons">cancel</i>
                                            </button>
                                        </form>
                                    @endif

                                    <div class="act-divider"></div>

                                    <form style="display:inline" action="{{ url('delete-buy/' . $buy->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este pedido?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn ab-del"
                                            data-bs-toggle="tooltip" title="Eliminar pedido">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>

                                    <div class="act-divider"></div>

                                    {{-- Ver info de envío --}}
                                    <button type="button"
                                        class="act-btn ab-neutral btn-shipping-info"
                                        data-buy-id="{{ $buy->id }}"
                                        data-bs-toggle="tooltip" title="Ver info de envío">
                                        <i class="material-icons">local_shipping</i>
                                    </button>

                                    {{-- Ver items --}}
                                    <button type="button"
                                        class="act-btn ab-neutral btn-view-items"
                                        data-buy-id="{{ $buy->id }}"
                                        data-bs-toggle="tooltip" title="Ver items del pedido">
                                        <i class="material-icons">shopping_bag</i>
                                    </button>
                                </div>
                            </td>

                            {{-- Origen --}}
                            <td>
                                @switch($buy->kind_of_buy)
                                    @case('V')
                                        <span class="s-pill pill-blue">
                                            <i class="material-icons" style="font-size:.7rem;">language</i> Web
                                        </span>
                                    @break
                                    @case('F')
                                        <span class="s-pill {{ $buy->apartado ? 'pill-orange' : 'pill-green' }}">
                                            <i class="material-icons" style="font-size:.7rem;">storefront</i>
                                            {{ $buy->apartado ? 'Apartado' : 'Interna' }}
                                        </span>
                                    @break
                                    @default
                                        <span class="cell-sub">—</span>
                                @endswitch
                            </td>

                            {{-- Recolección --}}
                            @if ($tenantinfo->tenant == 'sakura318')
                                <td><p class="cell-main">{{ $buy->sucursal == 'T' ? 'Tibás' : 'Guadalupe' }}</p></td>
                            @endif

                            {{-- Comprobante --}}
                            <td>
                                @if ($buy->image)
                                    <a target="_blank" data-fancybox="gallery" href="{{ route('file', $buy->image) }}">
                                        <img src="{{ route('file', $buy->image) }}" class="thumb-img">
                                    </a>
                                @else
                                    <span style="color:var(--gray2);">—</span>
                                @endif
                            </td>

                            {{-- Nombre --}}
                            <td><p class="cell-main">{{ $buyName }}</p></td>

                            {{-- Teléfono --}}
                            <td><p class="cell-mono">{{ $buyTel }}</p></td>

                            {{-- E-mail --}}
                            <td><p class="cell-sub" style="color:var(--black);font-size:.79rem;">{{ $buyEmail }}</p></td>

                            {{-- Total --}}
                            <td><p class="cell-mono">₡{{ number_format($buy->total_buy + $buy->total_delivery) }}</p></td>

                            {{-- Envío --}}
                            <td><p class="cell-mono">₡{{ number_format($buy->total_delivery) }}</p></td>

                            {{-- Pendiente (apartado) --}}
                            <td>
                                @if ($buy->apartado == 1)
                                    <p class="cell-mono" style="color:var(--orange);">
                                        ₡{{ number_format($buy->total_buy + $buy->total_delivery - $buy->monto_apartado) }}
                                    </p>
                                @else
                                    <span style="color:var(--gray2);">—</span>
                                @endif
                            </td>

                            {{-- Cupón --}}
                            <td>
                                @if ($buy->credit_used > 0)
                                    <p class="cell-mono" style="color:var(--green);">₡{{ number_format($buy->credit_used) }}</p>
                                @else
                                    <span style="color:var(--gray2);">—</span>
                                @endif
                            </td>

                            {{-- Entrega --}}
                            <td>
                                @if ($buy->delivered)
                                    <span class="s-pill pill-green">Entregado</span>
                                @else
                                    <span class="s-pill pill-gray">Pendiente</span>
                                @endif
                            </td>

                            {{-- Aprobación --}}
                            <td>
                                @if ($buy->approved)
                                    <span class="s-pill pill-blue">Aprobado</span>
                                @else
                                    <span class="s-pill pill-gray">Pendiente</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td>
                                @switch($buy->cancel_buy)
                                    @case(0)  <span class="s-pill pill-green">Vigente</span>        @break
                                    @case(1)  <span class="s-pill pill-orange">En cancelación</span> @break
                                    @default  <span class="s-pill pill-red">Cancelada</span>
                                @endswitch
                            </td>

                            {{-- Fecha --}}
                            <td>
                                <p class="cell-main" style="font-size:.75rem;">
                                    {{ \Carbon\Carbon::parse($buy->created_at)->setTimezone('America/Costa_Rica')->format('d/m/Y') }}
                                </p>
                                <p class="cell-sub">
                                    {{ \Carbon\Carbon::parse($buy->created_at)->setTimezone('America/Costa_Rica')->format('H:i') }}
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── Modal: Info de envío ──────────────────────────────────────────── --}}
<div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="border-radius:18px;border:none;overflow:hidden;">
            <div class="modal-header" style="background:var(--gray0);border-bottom:1px solid var(--gray1);padding:16px 22px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(0,122,255,.1);display:flex;align-items:center;justify-content:center;">
                        <i class="material-icons" style="font-size:1.1rem;color:var(--blue);">local_shipping</i>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0" id="shippingModalLabel" style="font-weight:700;font-size:.9rem;color:var(--black);">Información de envío</h6>
                        <p class="mb-0" style="font-size:.72rem;color:var(--gray3);" id="shippingModalOrderId"></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:22px;">
                <div id="shippingModalBody">
                    <div class="text-center py-4" id="shippingModalLoading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <p class="mt-2 mb-0" style="font-size:.78rem;color:var(--gray3);">Cargando…</p>
                    </div>
                    <div id="shippingModalContent" style="display:none;">
                        <div class="row g-3">
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Nombre</label>
                                <p class="mb-0" style="font-size:.85rem;font-weight:500;color:var(--black);" id="si-name">—</p>
                            </div>
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Teléfono</label>
                                <p class="mb-0" style="font-size:.85rem;font-weight:500;color:var(--black);" id="si-telephone">—</p>
                            </div>
                            <div class="col-12">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">E-mail</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-email">—</p>
                            </div>
                            <div class="col-12"><hr style="margin:4px 0;border-color:var(--gray1);"></div>
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">País</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-country">—</p>
                            </div>
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Provincia</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-province">—</p>
                            </div>
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Cantón</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-city">—</p>
                            </div>
                            <div class="col-6">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Distrito</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-district">—</p>
                            </div>
                            <div class="col-12">
                                <label style="font-size:.67rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">Dirección</label>
                                <p class="mb-0" style="font-size:.85rem;color:var(--black);" id="si-address">—</p>
                            </div>
                        </div>
                    </div>
                    <div id="shippingModalError" style="display:none;" class="alert alert-danger mb-0 py-2" style="font-size:.8rem;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal: Ver items ──────────────────────────────────────────────── --}}
<div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:18px;border:none;overflow:hidden;">
            <div class="modal-header" style="background:var(--gray0);border-bottom:1px solid var(--gray1);padding:16px 22px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(52,199,89,.1);display:flex;align-items:center;justify-content:center;">
                        <i class="material-icons" style="font-size:1.1rem;color:var(--green);">shopping_bag</i>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0" id="itemsModalLabel" style="font-weight:700;font-size:.9rem;color:var(--black);">Artículos del pedido</h6>
                        <p class="mb-0" style="font-size:.72rem;color:var(--gray3);" id="itemsModalOrderId"></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:18px 22px;">
                <div id="itemsModalLoading" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                    <p class="mt-2 mb-0" style="font-size:.78rem;color:var(--gray3);">Cargando artículos…</p>
                </div>
                <div id="itemsModalContent" style="display:none;"></div>
                <div id="itemsModalError" style="display:none;" class="alert alert-danger mb-0 py-2"></div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
    (function () {
        let lastFetched = {};   // cache per buy id to avoid repeated AJAX calls

        // Lazy modal getter — Bootstrap may not be available at script parse time
        // because bootstrap.min.js uses `defer`. We instantiate on first use.
        function getModal(id) {
            return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
        }

        async function fetchQuickInfo(buyId) {
            if (lastFetched[buyId]) return lastFetched[buyId];
            const resp = await fetch(`/buy/${buyId}/quick-info`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!resp.ok) throw new Error('Error al cargar los datos del pedido.');
            const data = await resp.json();
            lastFetched[buyId] = data;
            return data;
        }

        // ── Shipping modal ──────────────────────────────────────────────
        document.querySelectorAll('.btn-shipping-info').forEach(btn => {
            btn.addEventListener('click', async function () {
                const buyId = this.getAttribute('data-buy-id');
                document.getElementById('shippingModalOrderId').textContent = 'Pedido #' + buyId;
                document.getElementById('shippingModalLoading').style.display = 'block';
                document.getElementById('shippingModalContent').style.display = 'none';
                document.getElementById('shippingModalError').style.display   = 'none';
                getModal('shippingModal').show();

                try {
                    const data = await fetchQuickInfo(buyId);
                    const s = data.shipping;
                    document.getElementById('si-name').textContent      = s.name      || '—';
                    document.getElementById('si-telephone').textContent  = s.telephone || '—';
                    document.getElementById('si-email').textContent      = s.email     || '—';
                    document.getElementById('si-country').textContent    = s.country   || '—';
                    document.getElementById('si-province').textContent   = s.province  || '—';
                    document.getElementById('si-city').textContent       = s.city      || '—';
                    document.getElementById('si-district').textContent   = s.district  || '—';
                    document.getElementById('si-address').textContent    = s.address   || '—';
                    document.getElementById('shippingModalLoading').style.display = 'none';
                    document.getElementById('shippingModalContent').style.display = 'block';
                } catch (e) {
                    document.getElementById('shippingModalLoading').style.display = 'none';
                    const errEl = document.getElementById('shippingModalError');
                    errEl.textContent = e.message;
                    errEl.style.display = 'block';
                }
            });
        });

        // ── Items modal ─────────────────────────────────────────────────
        document.querySelectorAll('.btn-view-items').forEach(btn => {
            btn.addEventListener('click', async function () {
                const buyId = this.getAttribute('data-buy-id');
                document.getElementById('itemsModalOrderId').textContent  = 'Pedido #' + buyId;
                document.getElementById('itemsModalLoading').style.display  = 'block';
                document.getElementById('itemsModalContent').style.display  = 'none';
                document.getElementById('itemsModalError').style.display    = 'none';
                getModal('itemsModal').show();

                try {
                    const data = await fetchQuickInfo(buyId);
                    const container = document.getElementById('itemsModalContent');
                    if (!data.items || data.items.length === 0) {
                        container.innerHTML = '<p class="text-center py-3" style="color:var(--gray3);font-size:.82rem;">Sin artículos registrados.</p>';
                    } else {
                        container.innerHTML = data.items.map(item => {
                            const attrs = (item.attributes || []).map(a =>
                                `<span style="display:inline-flex;align-items:center;font-size:.68rem;font-weight:600;border-radius:20px;padding:3px 9px;background:var(--gray1);color:var(--gray3);white-space:nowrap;">${a}</span>`
                            ).join(' ');
                            const imgHtml = item.image_url
                                ? `<img src="${item.image_url}" style="width:56px;height:56px;object-fit:cover;border-radius:10px;border:1px solid var(--gray1);flex-shrink:0;">`
                                : `<div style="width:56px;height:56px;border-radius:10px;background:var(--gray1);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="material-icons" style="color:var(--gray3);font-size:1.4rem;">image_not_supported</i></div>`;
                            return `
                            <div style="display:flex;align-items:flex-start;gap:14px;padding:12px 0;border-bottom:1px solid var(--gray1);">
                                ${imgHtml}
                                <div style="flex:1;min-width:0;">
                                    <p style="margin:0 0 4px;font-size:.85rem;font-weight:600;color:var(--black);">${item.name}</p>
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:5px;">${attrs || '<span style="font-size:.75rem;color:var(--gray3);">Sin atributos</span>'}</div>
                                </div>
                                <div style="text-align:right;flex-shrink:0;">
                                    <p style="margin:0 0 2px;font-size:.72rem;font-weight:600;color:var(--gray3);text-transform:uppercase;letter-spacing:.03em;">Cantidad</p>
                                    <p style="margin:0;font-size:1.1rem;font-weight:700;color:var(--black);">${item.quantity}</p>
                                    <p style="margin:4px 0 0;font-size:.75rem;color:var(--gray3);">₡${Number(item.total).toLocaleString('es-CR')}</p>
                                </div>
                            </div>`;
                        }).join('');
                    }
                    document.getElementById('itemsModalLoading').style.display = 'none';
                    container.style.display = 'block';
                } catch (e) {
                    document.getElementById('itemsModalLoading').style.display = 'none';
                    const errEl = document.getElementById('itemsModalError');
                    errEl.textContent = e.message;
                    errEl.style.display = 'block';
                }
            });
        });
    })();
    </script>
@endsection
