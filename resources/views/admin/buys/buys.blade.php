@extends('layouts.pos')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
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
.sale-wrap { max-width: 860px; margin: 0 auto; }

/* ── Step pill ──────────────────────────────────────────── */
.step-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.25rem;
}
.step-pill-num {
    min-width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--blue);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.step-pill-label {
    font-size: .875rem;
    font-weight: 600;
    color: var(--black);
    line-height: 1;
}
.step-pill-sub {
    font-size: .72rem;
    color: var(--gray3);
    margin-top: 1px;
}

/* ── Card ───────────────────────────────────────────────── */
.s-card {
    background: var(--white);
    border: 1px solid var(--gray1);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 10px;
    overflow: hidden;
}
.s-card-body { padding: 20px 24px; }

/* ── Search row ─────────────────────────────────────────── */
.search-row {
    display: flex;
    gap: 10px;
    align-items: center;
}
.search-field {
    position: relative;
    flex: 1;
}
.search-field i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
    color: var(--gray3);
    pointer-events: none;
}
.search-field input {
    width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1.5px solid var(--gray1);
    border-radius: 10px;
    font-size: .875rem;
    color: var(--black);
    background: var(--gray0);
    outline: none;
    transition: border-color .15s, background .15s;
}
.search-field input:focus {
    border-color: var(--blue);
    background: var(--white);
}
.btn-catalog {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    background: var(--blue);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    white-space: nowrap;
}
.btn-catalog:hover { opacity: .88; }
.btn-catalog:active { transform: scale(.97); }

/* ── Attribute selector area ────────────────────────────── */
.attr-area {
    margin-top: 14px;
    padding: 16px;
    background: var(--gray0);
    border-radius: 10px;
    border: 1px solid var(--gray1);
}
.attr-area label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--gray3);
    text-transform: uppercase;
    letter-spacing: .04em;
    display: block;
    margin-bottom: 4px;
}
.attr-area select, .attr-area input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border: 1.5px solid var(--gray1);
    border-radius: 8px;
    font-size: .875rem;
    color: var(--black);
    background: var(--white);
    outline: none;
}
.attr-area select:focus, .attr-area input[type="number"]:focus {
    border-color: var(--blue);
}
.btn-add-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: var(--black);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s;
    margin-top: 10px;
}
.btn-add-item:hover { opacity: .8; }

/* ── Cart table ─────────────────────────────────────────── */
.cart-table { width: 100%; border-collapse: collapse; }
.cart-table thead th {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--gray3);
    padding: 0 12px 10px;
    border-bottom: 1px solid var(--gray1);
    white-space: nowrap;
}
.cart-table tbody tr {
    transition: background .12s;
}
.cart-table tbody tr:hover { background: var(--gray0); }
.cart-table td {
    padding: 12px;
    border-bottom: 1px solid var(--gray1);
    vertical-align: middle;
}
.cart-table tbody tr:last-child td { border-bottom: none; }

.prod-img {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: var(--gray0);
}
.prod-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--black);
    margin: 0;
}
.prod-code {
    font-size: .72rem;
    color: var(--gray3);
    font-family: monospace;
    margin: 0;
}
.price-input {
    width: 100px;
    padding: 6px 10px;
    border: 1.5px solid var(--gray1);
    border-radius: 8px;
    font-size: .875rem;
    font-weight: 600;
    color: var(--black);
    text-align: right;
    outline: none;
    transition: border-color .15s;
}
.price-input:focus { border-color: var(--blue); }
.price-hint {
    font-size: .68rem;
    color: var(--gray3);
    margin-top: 2px;
    text-align: right;
    display: block;
}
.attr-pill {
    display: inline-block;
    padding: 2px 8px;
    background: var(--gray0);
    border: 1px solid var(--gray1);
    border-radius: 20px;
    font-size: .7rem;
    color: var(--gray4);
    margin: 1px;
}
.qty-input {
    width: 60px;
    padding: 6px 8px;
    border: 1.5px solid var(--gray1);
    border-radius: 8px;
    font-size: .875rem;
    text-align: center;
    color: var(--black);
    outline: none;
    transition: border-color .15s;
}
.qty-input:focus { border-color: var(--blue); }
.qty-input:disabled { background: var(--gray0); color: var(--gray3); cursor: not-allowed; }
.btn-del {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--gray1);
    background: var(--white);
    color: var(--gray3);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s;
    padding: 0;
}
.btn-del:hover { background: #fff0ee; border-color: var(--red); color: var(--red); }
.cart-empty {
    padding: 32px;
    text-align: center;
    color: var(--gray3);
    font-size: .875rem;
}

/* ── Totals strip ───────────────────────────────────────── */
.totals-strip {
    border-top: 1px solid var(--gray1);
    padding: 14px 20px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: var(--gray0);
}
.totals-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .82rem;
    color: var(--gray4);
}
.totals-row.total-final {
    font-size: 1rem;
    font-weight: 700;
    color: var(--black);
    padding-top: 8px;
    border-top: 1px solid var(--gray1);
    margin-top: 2px;
}

/* ── Form fields ────────────────────────────────────────── */
.field-group {
    margin-bottom: 14px;
}
.field-group label {
    display: block;
    font-size: .72rem;
    font-weight: 600;
    color: var(--gray3);
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 5px;
}
.field-group input, .field-group select {
    width: 100%;
    padding: 9px 13px;
    border: 1.5px solid var(--gray1);
    border-radius: 9px;
    font-size: .875rem;
    color: var(--black);
    background: var(--white);
    outline: none;
    transition: border-color .15s;
}
.field-group input:focus, .field-group select:focus {
    border-color: var(--blue);
}
.field-group input[readonly] {
    background: var(--gray0);
    color: var(--gray3);
    cursor: default;
}

/* ── Apartado box ───────────────────────────────────────── */
.apartado-box {
    border: 1.5px dashed var(--orange);
    border-radius: 12px;
    padding: 16px;
    background: #fffbf0;
    margin-top: 12px;
}
.apartado-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    user-select: none;
}
.apartado-toggle input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--orange);
    cursor: pointer;
    flex-shrink: 0;
}
.apartado-label-text {
    font-size: .875rem;
    font-weight: 600;
    color: var(--black);
}
.apartado-desc {
    font-size: .75rem;
    color: var(--gray3);
    margin: 6px 0 0 28px;
}

/* ── Summary card ───────────────────────────────────────── */
.summary-card {
    border: 1px solid var(--gray1);
    border-radius: var(--radius);
    overflow: hidden;
}
.summary-card-header {
    background: var(--black);
    padding: 14px 18px;
}
.summary-card-header .total-lbl { font-size: .72rem; color: rgba(255,255,255,.55); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
.summary-card-header .total-val { font-size: 1.75rem; font-weight: 700; color: #fff; margin-top: 2px; letter-spacing: -.02em; }
.summary-card-body { padding: 14px 18px; background: var(--white); }
.summary-line {
    display: flex;
    justify-content: space-between;
    font-size: .82rem;
    color: var(--gray4);
    padding: 4px 0;
}
.summary-line .val { font-weight: 600; color: var(--black); }
.summary-line .discount-val { color: var(--green); }

/* ── CTA button ─────────────────────────────────────────── */
.btn-confirm {
    width: 100%;
    padding: 14px;
    background: var(--blue);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: -.01em;
    cursor: pointer;
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: opacity .15s, transform .1s;
}
.btn-confirm:hover { opacity: .88; }
.btn-confirm:active { transform: scale(.98); }
.btn-confirm:disabled { background: var(--gray2); cursor: not-allowed; opacity: 1; }

/* ── Step connector ─────────────────────────────────────── */
.step-connector {
    width: 1px;
    height: 16px;
    background: var(--gray1);
    margin: 0 auto 10px;
}

/* ── Alert ──────────────────────────────────────────────── */
.info-banner {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: #f0f6ff;
    border: 1px solid #c8dcff;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 14px;
}
.info-banner i { font-size: 1rem; color: var(--blue); margin-top: 1px; flex-shrink: 0; }
.info-banner p { font-size: .78rem; color: #1a3a6e; margin: 0; line-height: 1.4; }

/* ── Badge optional ─────────────────────────────────────── */
.badge-opt {
    font-size: .65rem;
    font-weight: 600;
    color: var(--gray3);
    background: var(--gray0);
    border: 1px solid var(--gray1);
    border-radius: 20px;
    padding: 1px 7px;
    vertical-align: middle;
    letter-spacing: .02em;
    text-transform: uppercase;
}

/* ── Collapsed section ──────────────────────────────────── */
.collapsible-header {
    cursor: pointer;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid transparent;
    transition: border-color .15s;
}
.collapsible-header.open { border-bottom-color: var(--gray1); }
.collapsible-body { padding: 20px 24px; }
.chevron { transition: transform .2s; color: var(--gray3); font-size: 1.1rem; }
.chevron.open { transform: rotate(180deg); }
</style>

    @include('admin.buys.products')

    <form action="{{ url('payment') }}" method="POST" id="saleForm">
        @csrf
        <input type="hidden" value="F" name="kind_of">
        <input type="hidden" name="updateId" value="{{ $id }}" id="updateId">
        <input type="hidden" value="{{ $tenantinfo->tenant }}" name="tenant" id="tenant">
        <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva_tenant }}">
    </form>

    <div class="sale-wrap py-3 px-2 px-md-0">

        {{-- Page header --}}
        <div class="mb-4 d-flex align-items-center gap-2" style="flex-wrap:wrap;">
            <span style="font-size:.72rem;color:var(--gray3);font-weight:500;letter-spacing:.01em;">Ventas</span>
            <i class="material-icons" style="font-size:.8rem;color:var(--gray2);">chevron_right</i>
            <span style="font-size:.72rem;color:var(--black);font-weight:600;letter-spacing:.01em;">{{ $id != 0 ? 'Editar venta #'.$id : 'Nueva venta' }}</span>
        </div>

        {{-- ╔═══════════════════════════════╗ --}}
        {{-- ║  PASO 1 · PRODUCTOS           ║ --}}
        {{-- ╚═══════════════════════════════╝ --}}
        <div class="s-card">
            <div class="s-card-body">
                <div class="step-pill">
                    <div class="step-pill-num">1</div>
                    <div>
                        <div class="step-pill-label">Productos</div>
                        <div class="step-pill-sub">Busca por código o abre el catálogo</div>
                    </div>
                </div>

                @if ($id != 0)
                <div class="info-banner mb-3">
                    <i class="material-icons">info</i>
                    <p>Edición de pedido: la cantidad existente no se puede cambiar para evitar inconsistencias en stock. Los nuevos productos agregados sí permiten definir cantidad.</p>
                </div>
                @endif

                {{-- Search row --}}
                <div class="search-row">
                    <div class="search-field">
                        <i class="material-icons">qr_code</i>
                        <input type="text" id="code" name="code" placeholder="Código del producto…" autocomplete="off">
                    </div>
                    <button type="button" class="btn-catalog icon-button"
                            data-bs-toggle="modal" data-bs-target="#add-products-modal"
                            data-name="products">
                        <i class="material-icons" style="font-size:1rem;">grid_view</i>
                        Catálogo
                    </button>
                </div>

                {{-- Attribute / add area (hidden until product selected) --}}
                <div id="container" class="d-none">
                    <div class="attr-area" id="select-container"></div>
                </div>
            </div>

            {{-- Cart table --}}
            @if(count($cart_items) > 0)
            <div style="border-top:1px solid var(--gray1);">
                <table class="cart-table" id="cartTable">
                    <thead>
                        <tr>
                            <th style="width:40%">Producto</th>
                            <th style="width:16%;text-align:right">Precio unit.</th>
                            <th style="width:20%;text-align:center">Atributos</th>
                            <th style="width:12%;text-align:center">Cant.</th>
                            <th style="width:6%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart_items as $item)
                            @php
                                $precio = $item->price != 0 ? $item->price : $item->price_cloth;
                                $descuento = ($precio * $item->discount) / 100;
                                $precioConDescuento = $precio - $descuento;
                                $precioEfectivo = $item->custom_price > 0
                                    ? $item->custom_price
                                    : ($item->discount > 0 ? $precioConDescuento : $precio);
                                $precioOriginal = $item->discount > 0 ? $precioConDescuento : $precio;
                                $attributesValues = !empty($item->attributes_values)
                                    ? explode(', ', $item->attributes_values) : [];
                            @endphp
                            <tr>
                                <input type="hidden" class="discount" value="{{ $item->custom_price > 0 ? 0 : $descuento }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ isset($item->image) && $item->image ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                           data-fancybox="gallery" target="_blank">
                                            <img class="prod-img"
                                                 src="{{ isset($item->image) && $item->image ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                 alt="{{ $item->name }}">
                                        </a>
                                        <div>
                                            <p class="prod-name">{{ $item->name }}</p>
                                            <p class="prod-code">{{ $item->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:right">
                                    <input type="number"
                                           class="price-input price"
                                           value="{{ $precioEfectivo }}"
                                           data-cart-id="{{ $item->cart_id }}"
                                           data-original="{{ $precioOriginal }}"
                                           min="0" step="1">
                                    @if($item->custom_price > 0)
                                        <span class="price-hint">orig. ₡{{ number_format($precioOriginal) }}</span>
                                    @elseif($item->discount > 0)
                                        <span class="price-hint"><s>₡{{ number_format($precio) }}</s></span>
                                    @endif
                                </td>
                                <td style="text-align:center">
                                    @foreach ($attributesValues as $av)
                                        @php $parts = explode(': ', $av, 2); @endphp
                                        @if(!empty($parts[0]))
                                            <span class="attr-pill">{{ $parts[0] }}: {{ $parts[1] ?? '' }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:center">
                                    <input type="number"
                                           class="qty-input btnQuantity quantity"
                                           value="{{ $item->quantity }}"
                                           data-cart-id="{{ $item->cart_id }}"
                                           min="1"
                                           max="{{ $item->stock > 0 ? $item->stock : '' }}"
                                           @if($id != 0) disabled @endif>
                                </td>
                                <td style="text-align:center">
                                    <button type="button" class="btn-del btnDeleteCart" data-item-id="{{ $item->cart_id }}">
                                        <i class="material-icons" style="font-size:.9rem;">delete</i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totals strip --}}
                <div class="totals-strip">
                    <div class="totals-row">
                        <span>Subtotal</span>
                        <span id="totalCloth">₡{{ number_format($cloth_price) }}</span>
                    </div>
                    @if($iva > 0)
                    <div class="totals-row">
                        <span>I.V.A.</span>
                        <span id="totalIvaElement">₡{{ number_format($iva) }}</span>
                    </div>
                    @endif
                    @if($you_save > 0)
                    <div class="totals-row">
                        <span>Descuento</span>
                        <span style="color:var(--green)" id="totalDiscountElement">−₡{{ number_format($you_save) }}</span>
                    </div>
                    @endif
                    <div class="totals-row total-final">
                        <span>Total</span>
                        <span id="totalPriceElement">₡{{ number_format($total_price) }}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="cart-empty">
                <i class="material-icons" style="font-size:2rem;display:block;margin-bottom:6px;color:var(--gray2)">shopping_cart</i>
                Aún no has agregado productos
            </div>
            @endif
        </div>

        <div class="step-connector"></div>

        {{-- ╔═══════════════════════════════╗ --}}
        {{-- ║  PASO 2 · CLIENTE             ║ --}}
        {{-- ╚═══════════════════════════════╝ --}}
        <div class="s-card">
            {{-- Collapsible header --}}
            <div class="collapsible-header open" id="step2-toggle" onclick="toggleStep2()">
                <div class="step-pill mb-0">
                    <div class="step-pill-num">2</div>
                    <div>
                        <div class="step-pill-label">
                            Datos del cliente <span class="badge-opt ms-1">Opcional</span>
                        </div>
                        <div class="step-pill-sub">Nombre, contacto y dirección</div>
                    </div>
                </div>
                <i class="material-icons chevron open" id="step2-chevron">expand_more</i>
            </div>
            <div id="step2-body" class="collapsible-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Nombre</label>
                            <input type="text" form="saleForm" name="name"
                                   value="{{ isset($buy->name) ? $buy->name : '' }}"
                                   placeholder="Cliente...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Correo</label>
                            <input type="email" form="saleForm" name="email"
                                   value="{{ isset($buy->email) ? $buy->email : '' }}"
                                   placeholder="correo@ejemplo.com">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Teléfono</label>
                            <input type="text" form="saleForm" name="telephone"
                                   value="{{ isset($buy->telephone) ? $buy->telephone : '' }}"
                                   placeholder="8888-0000">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>País</label>
                            <input type="text" form="saleForm" name="country" value="Costa Rica" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Provincia</label>
                            <input type="text" form="saleForm" name="province"
                                   value="{{ isset($buy->province) ? $buy->province : '' }}"
                                   placeholder="San José...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Ciudad</label>
                            <input type="text" form="saleForm" name="city"
                                   value="{{ isset($buy->city) ? $buy->city : '' }}"
                                   placeholder="Ciudad...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Distrito</label>
                            <input type="text" form="saleForm" name="address_two"
                                   value="{{ isset($buy->address_two) ? $buy->address_two : '' }}"
                                   placeholder="Distrito...">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="field-group">
                            <label>Dirección exacta</label>
                            <input type="text" form="saleForm" name="address"
                                   value="{{ isset($buy->address) ? $buy->address : '' }}"
                                   placeholder="Señas exactas...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="field-group">
                            <label>Nota / detalle</label>
                            <input type="text" form="saleForm" name="detail"
                                   value="{{ isset($buy->detail) ? $buy->detail : '' }}"
                                   placeholder="Ej: pago en efectivo">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="step-connector"></div>

        {{-- ╔═══════════════════════════════╗ --}}
        {{-- ║  PASO 3 · CONFIRMAR           ║ --}}
        {{-- ╚═══════════════════════════════╝ --}}
        <div class="s-card">
            <div class="s-card-body">
                <div class="step-pill">
                    <div class="step-pill-num">3</div>
                    <div>
                        <div class="step-pill-label">Confirmar venta</div>
                        <div class="step-pill-sub">Envío, apartado y total final</div>
                    </div>
                </div>

                <div class="row align-items-start">
                    {{-- Left: envío + apartado --}}
                    <div class="col-md-5">
                        <div class="field-group">
                            <label>
                                <i class="material-icons" style="font-size:.8rem;vertical-align:middle;">local_shipping</i>
                                Costo de envío
                            </label>
                            <input type="number" form="saleForm" name="delivery" id="deliveryInput"
                                   value="{{ isset($buy->total_delivery) ? $buy->total_delivery : '' }}"
                                   placeholder="0" min="0">
                        </div>

                        @if(isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="apartado-box">
                            <label class="apartado-toggle">
                                <input type="checkbox" id="apartado" name="apartado" form="saleForm" value="1"
                                       {{ (isset($buy->apartado) && $buy->apartado == 1) || old('apartado') ? 'checked' : '' }}>
                                <span>
                                    <span class="apartado-label-text">Apartado</span>
                                    <span style="display:inline-block;margin-left:6px;font-size:.65rem;font-weight:700;color:var(--orange);background:#fff3dc;border:1px solid #ffe0a0;border-radius:4px;padding:1px 6px;text-transform:uppercase;letter-spacing:.04em;">Pago parcial</span>
                                </span>
                            </label>
                            <p class="apartado-desc">El cliente cancela un monto inicial y el resto después. La venta queda registrada como apartado.</p>
                            <div id="monto_apartado" class="{{ (isset($buy->monto_apartado) && $buy->monto_apartado) ? '' : 'd-none' }}" style="margin-top:10px;">
                                <div class="field-group mb-0">
                                    <label>Monto del apartado (₡)</label>
                                    <input type="number" form="saleForm" name="monto_apartado"
                                           value="{{ isset($buy->monto_apartado) ? $buy->monto_apartado : '' }}"
                                           placeholder="Ej: 5000">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Right: summary card + CTA --}}
                    <div class="col-md-7 mt-3 mt-md-0">
                        <div class="summary-card">
                            <div class="summary-card-header">
                                <div class="total-lbl">Total a cobrar</div>
                                <div class="total-val">₡<span id="totalPriceElement2">{{ number_format($total_price) }}</span></div>
                            </div>
                            <div class="summary-card-body">
                                <div class="summary-line">
                                    <span>Productos</span>
                                    <span class="val" id="sc-cloth">₡{{ number_format($cloth_price) }}</span>
                                </div>
                                @if($iva > 0)
                                <div class="summary-line">
                                    <span>I.V.A.</span>
                                    <span class="val" id="sc-iva">₡{{ number_format($iva) }}</span>
                                </div>
                                @endif
                                @if($you_save > 0)
                                <div class="summary-line">
                                    <span>Descuento</span>
                                    <span class="discount-val" id="sc-discount">−₡{{ number_format($you_save) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <button type="submit" form="saleForm" id="btnSinpe"
                                class="btn-confirm"
                                @if($total_price == 0) disabled @endif>
                            <i class="material-icons" style="font-size:1.1rem;">point_of_sale</i>
                            Confirmar venta · ₡<span id="btnPay">{{ number_format($total_price) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.sale-wrap -->

@endsection
@section('script')
<script>
$(function() {
    var tenant   = $('#tenant').val();
    var updateId = document.getElementById('updateId').value;

    // ── Apartado toggle ────────────────────────────────────────
    if (tenant !== 'rutalimon') {
        var cbApartado = document.getElementById('apartado');
        var montoDiv   = document.getElementById('monto_apartado');
        if (cbApartado) {
            cbApartado.addEventListener('change', function() {
                montoDiv.classList.toggle('d-none', !this.checked);
            });
        }
    }

    // ── Código: Enter ─────────────────────────────────────────
    $('#code').on('keypress', function(e) {
        if (e.keyCode === 13) { e.preventDefault(); fetchAttrs($(this).val()); }
    });

    // ── Fetch atributos ───────────────────────────────────────
    function fetchAttrs(code) {
        var $cont = $('#container');
        $.ajax({
            method: 'POST',
            url: '/size-by-cloth',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { code: code },
            success: function(r) {
                $cont.find('.attr-area').empty();
                if (r.status !== 'success') {
                    Swal.fire({ title: r.status, icon: r.icon });
                    $cont.addClass('d-none');
                } else {
                    buildAttrs(r.results, $cont.find('.attr-area'), updateId);
                    $cont.removeClass('d-none');
                }
            }
        });
    }

    // ── Construir selectores ──────────────────────────────────
    function buildAttrs(results, $area, uid) {
        $area.empty();
        if (results.length === 0) {
            // sin atributos
            $area.append(buildAddBtn());
            return;
        }
        var $row = $('<div class="row g-2 align-items-end">');
        $.each(results, function(i, attr) {
            var vals   = attr.valores.split('/');
            var ids    = attr.ids.split('/');
            var stocks = attr.stock.split('/');

            var $sel = $('<select>', { name: 'size_id', class: 'size_id' });
            $.each(vals, function(k, v) {
                if (ids[k] !== undefined && stocks[k] != 0) {
                    var $opt = $('<option>', {
                        value: ids[k] + '-' + attr.attr_id + '-' + attr.clothing_id,
                        text: v + ' · stock ' + stocks[k]
                    });
                    if (k === 0) $opt.attr('selected', true);
                    $sel.append($opt);
                }
            });

            var $col = $('<div class="col-auto" style="min-width:140px">');
            $col.append($('<label>', { text: attr.columna_atributo })).append($sel);
            $row.append($col);
        });

        if (uid != 0) {
            var $qCol = $('<div class="col-auto" style="min-width:80px">');
            $qCol.append($('<label>', { text: 'Cantidad' }))
                 .append($('<input>', { type:'number', id:'quantityBox', name:'quantityBox', min:1, value:1 }));
            $row.append($qCol);
        }

        $row.append($('<div class="col-auto align-self-end">').append(buildAddBtn()));
        $area.append($row);
    }

    function buildAddBtn() {
        return $('<button>', { type:'button', class:'btn-add-item btnAdd' })
            .append('<i class="material-icons" style="font-size:1rem">add_shopping_cart</i> Agregar');
    }

    // ── Agregar al carrito ────────────────────────────────────
    $('#container').on('click', '.btnAdd', function() {
        var code = $('#code').val();
        var uid  = document.getElementById('updateId').value;
        var qty  = 1;
        if (uid != 0) { var qb = document.getElementById('quantityBox'); if (qb) qty = qb.value; }

        var sizes = [];
        $('.size_id').each(function() { var v=$(this).val(); if(v && v.trim()) sizes.push(v); });

        $.ajax({
            method: 'POST',
            url: '/add-to-cart',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { code: code, updateId: uid, attributes: JSON.stringify(sizes), quantity: qty },
            success: function(r) {
                if (r.icon === 'success') location.reload();
                else Swal.fire({ title: r.status, icon: r.icon });
            }
        });
    });

    // ── Actualizar cantidad ───────────────────────────────────
    $(document).on('change', '.btnQuantity', function() {
        $.ajax({
            method: 'POST',
            url: '/edit-quantity',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { cart_id: $(this).data('cart-id'), quantity: $(this).val() },
            success: function() { calcTotal(); }
        });
    });

    // ── Actualizar precio ─────────────────────────────────────
    $(document).on('change', '.price-input', function() {
        var id = $(this).data('cart-id');
        if (!id) return;
        $.ajax({
            method: 'POST',
            url: '/edit-price',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { cart_id: id, custom_price: $(this).val() },
            success: function(r) { if (r.status === 'success') calcTotal(); }
        });
    });

    // ── Eliminar producto ─────────────────────────────────────
    $(document).on('click', '.btnDeleteCart', function() {
        var id = $(this).data('item-id');
        Swal.fire({
            title: 'Eliminar producto',
            text: '¿Deseas quitarlo del pedido?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ff3b30',
            cancelButtonColor: '#e5e5ea',
        }).then(function(res) {
            if (!res.isConfirmed) return;
            $.ajax({
                method: 'POST',
                url: '/delete-item-cart/' + id,
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function(r) {
                    if (r.refresh) window.location.href = '{{ url("/") }}';
                    else location.reload();
                }
            });
        });
    });
});

// ── Modal: seleccionar producto ────────────────────────────
let _modalTrigger = null;
document.querySelectorAll('.icon-button').forEach(b => {
    b.addEventListener('click', () => _modalTrigger = b.dataset.name);
});

function selectIcon(code) {
    if (!_modalTrigger) return;
    document.getElementById('code').value = code;
    bootstrap.Modal.getInstance(document.getElementById('add-products-modal')).hide();

    var $cont    = $('#container');
    var $area    = $cont.find('.attr-area');
    var updateId = document.getElementById('updateId').value;
    $area.empty();

    $.ajax({
        method: 'POST',
        url: '/size-by-cloth',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { code: code },
        success: function(r) {
            if (r.status !== 'success') {
                Swal.fire({ title: r.status, icon: r.icon });
                $cont.addClass('d-none');
            } else {
                buildAttrsModal(r.results, $area, updateId);
                $cont.removeClass('d-none');
            }
        }
    });

    function buildAttrsModal(results, $area, uid) {
        $area.empty();
        function addBtn() {
            return $('<button>', { type:'button', class:'btn-add-item btnAdd' })
                .append('<i class="material-icons" style="font-size:1rem">add_shopping_cart</i> Agregar');
        }
        if (results.length === 0) { $area.append(addBtn()); return; }

        var $row = $('<div class="row g-2 align-items-end">');
        $.each(results, function(i, attr) {
            var vals=attr.valores.split('/'), ids=attr.ids.split('/'), stocks=attr.stock.split('/');
            var $sel = $('<select>', { name:'size_id', class:'size_id' });
            $.each(vals, function(k,v) {
                if (ids[k] !== undefined && stocks[k] != 0) {
                    var $o = $('<option>', { value:ids[k]+'-'+attr.attr_id+'-'+attr.clothing_id, text:v+' · stock '+stocks[k] });
                    if (k===0) $o.attr('selected',true);
                    $sel.append($o);
                }
            });
            $row.append($('<div class="col-auto" style="min-width:140px">').append($('<label>',{text:attr.columna_atributo})).append($sel));
        });
        if (uid != 0) {
            $row.append($('<div class="col-auto" style="min-width:80px">').append($('<label>',{text:'Cantidad'})).append($('<input>',{type:'number',id:'quantityBox',name:'quantityBox',min:1,value:1})));
        }
        $row.append($('<div class="col-auto align-self-end">').append(addBtn()));
        $area.append($row);
    }
}

// ── Filtrar modal ─────────────────────────────────────────
function filterIcons() {
    var q     = document.getElementById('icon-search').value.toLowerCase();
    var items = document.getElementById('icon-list').getElementsByClassName('icon-item');
    var count = 0;
    for (var i = 0; i < items.length; i++) {
        var show = items[i].dataset.code.toLowerCase().includes(q)
                || items[i].dataset.name.toLowerCase().includes(q);
        items[i].style.display = show ? '' : 'none';
        if (show) count++;
    }
    document.getElementById('product-count').textContent = count;
    document.getElementById('empty-search').classList.toggle('d-none', count > 0);
}

// ── Recalcular totales ────────────────────────────────────
function calcTotal() {
    var iva      = parseFloat(document.getElementById('iva_tenant').value) || 0;
    var subtotal = 0, youSave = 0;

    document.querySelectorAll('#cartTable tbody tr').forEach(function(row) {
        var pi = row.querySelector('.price-input, .price');
        var di = row.querySelector('.discount');
        var qi = row.querySelector('.qty-input, .quantity');
        if (!pi || !qi) return;
        var p = parseFloat(pi.value) || 0;
        var d = parseFloat(di ? di.value : 0) || 0;
        var q = parseInt(qi.value) || 1;
        youSave  += d * q;
        subtotal += p * q;
    });

    var ivaAmt = subtotal * iva;
    var total  = subtotal + ivaAmt;
    var fmt    = n => '₡' + n.toLocaleString('es-CR', {minimumFractionDigits:0,maximumFractionDigits:0}).replace(',','.');

    // Strip totals
    var el = id => document.getElementById(id);
    if (el('totalCloth'))       el('totalCloth').textContent       = fmt(subtotal);
    if (el('totalIvaElement'))  el('totalIvaElement').textContent  = fmt(ivaAmt);
    if (el('totalPriceElement')) el('totalPriceElement').textContent = fmt(total);
    if (el('totalDiscountElement')) el('totalDiscountElement').textContent = '−' + fmt(youSave);

    // Summary card
    if (el('sc-cloth'))    el('sc-cloth').textContent    = fmt(subtotal);
    if (el('sc-iva'))      el('sc-iva').textContent      = fmt(ivaAmt);
    if (el('sc-discount')) el('sc-discount').textContent = '−' + fmt(youSave);
    if (el('totalPriceElement2')) el('totalPriceElement2').textContent = total.toLocaleString('es-CR',{minimumFractionDigits:0,maximumFractionDigits:0}).replace(',','.');

    // Button
    if (el('btnPay'))   el('btnPay').textContent  = total.toLocaleString('es-CR',{minimumFractionDigits:0,maximumFractionDigits:0}).replace(',','.');
    if (el('btnSinpe')) el('btnSinpe').disabled   = (subtotal === 0);
}

// ── Step 2 collapse ───────────────────────────────────────
function toggleStep2() {
    var body    = document.getElementById('step2-body');
    var chevron = document.getElementById('step2-chevron');
    var header  = document.getElementById('step2-toggle');
    var open    = !body.classList.contains('d-none');
    body.classList.toggle('d-none', open);
    chevron.classList.toggle('open', !open);
    header.classList.toggle('open', !open);
}
</script>
@endsection
