@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
<style>
    .step-badge {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(195deg, #42424a, #191919);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,.3);
    }
    .step-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,.08);
    }
    .step-header h5 { margin: 0; font-size: 1rem; font-weight: 600; }
    .step-header small { color: #7b809a; font-size: .78rem; }
    .step-card { border-radius: 0.75rem !important; }
    .price-input-cell input[type="number"] {
        max-width: 110px;
        text-align: center;
    }
    .price-original-badge {
        font-size: 0.72rem;
        color: #7b809a;
        display: block;
    }
    .apartado-section {
        background: rgba(66,66,74,.04);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    .table-cart th { font-size: 0.78rem; text-transform: uppercase; letter-spacing: .04em; }
    .step-connector {
        width: 2px;
        height: 18px;
        background: rgba(0,0,0,.12);
        margin: 0 auto;
    }
</style>

    <h1 class="font-title text-center mb-1">{{ $id != 0 ? 'Editar Venta #'.$id : 'Nueva Venta' }}</h1>
    <p class="text-center text-muted mb-4" style="font-size:.85rem">Sigue los pasos para registrar la venta</p>

    <div class="container">
        @include('admin.buys.products')

        {{-- PASO 1: Productos --}}
        <div class="card step-card mt-3 mb-1">
            <div class="card-body">
                <div class="step-header">
                    <div class="step-badge">1</div>
                    <div>
                        <h5>Agregar Productos</h5>
                        <small>Busca por código o usa el botón "Productos" para seleccionar del catálogo</small>
                    </div>
                </div>

                @if ($id != 0)
                    <div class="alert alert-info alert-sm py-2 mb-3" role="alert">
                        <i class="material-icons me-1" style="vertical-align:middle;font-size:1rem">info</i>
                        Al editar un pedido existente, la cantidad en la tabla no puede modificarse para evitar inconsistencias en stock. Los productos nuevos que agregues sí permiten definir la cantidad.
                    </div>
                @endif

                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="input-group input-group-lg input-group-static my-2 w-100">
                            <label>Código del Producto</label>
                            <input value="" placeholder="Ej: PROD-001" type="text"
                                class="form-control form-control-lg" name="code" id="code">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#add-products-modal"
                            data-name="products" class="btn btn-accion icon-button mb-2">
                            <i class="material-icons me-1" style="vertical-align:middle;font-size:1.1rem">inventory_2</i>
                            Productos
                        </button>
                    </div>
                    <input type="hidden" value="{{ $tenantinfo->manage_size }}" id="manage_size">
                </div>

                <div id="container" class="d-none mt-2">
                    <div id="select-container" class="d-none"></div>
                </div>

                {{-- Tabla del carrito --}}
                <div class="mt-3">
                    <div class="table-responsive">
                        <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva_tenant }}">
                        <table class="table align-items-center mb-0 table-cart" id="cartTable">
                            <thead>
                                <tr>
                                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">Producto</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">Precio Unit.</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">Atributos</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">Cant.</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart_items as $item)
                                    @php
                                        $precio = $item->price != 0 ? $item->price : $item->price_cloth;
                                        $descuentoPorcentaje = $item->discount;
                                        $descuento = ($precio * $descuentoPorcentaje) / 100;
                                        $precioConDescuento = $precio - $descuento;
                                        $precioEfectivo = $item->custom_price > 0
                                            ? $item->custom_price
                                            : ($item->discount > 0 ? $precioConDescuento : $precio);
                                        $precioOriginal = $item->discount > 0 ? $precioConDescuento : $precio;
                                        $attributesValues = !empty($item->attributes_values)
                                            ? explode(', ', $item->attributes_values)
                                            : [];
                                    @endphp
                                    <tr>
                                        <input type="hidden" name="prod_id" value="{{ $item->id }}" class="prod_id">
                                        <input type="hidden" value="{{ $item->custom_price > 0 ? 0 : $descuento }}" class="discount" name="discount">
                                        <td class="w-40">
                                            <div class="d-flex px-2 py-1 align-items-center">
                                                <div>
                                                    <a target="blank" data-fancybox="gallery"
                                                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                        <img src="{{ isset($item->image) && $item->image ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                            class="avatar avatar-md me-3">
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 text-lg">{{ $item->name }}</h4>
                                                    <p class="text-xs text-secondary mb-0">Código: {{ $item->code }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm price-input-cell">
                                            <input type="number"
                                                class="form-control price text-center mx-auto"
                                                value="{{ $precioEfectivo }}"
                                                data-cart-id="{{ $item->cart_id }}"
                                                data-original="{{ $precioOriginal }}"
                                                min="0" step="1"
                                                title="Edita para cambiar el precio de esta línea">
                                            @if($item->custom_price > 0)
                                                <span class="price-original-badge">Original: ₡{{ number_format($precioOriginal) }}</span>
                                            @elseif($item->discount > 0)
                                                <span class="price-original-badge"><s>₡{{ number_format($precio) }}</s></span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @foreach ($attributesValues as $attributeValue)
                                                @php
                                                    $parts = explode(': ', $attributeValue, 2);
                                                    $attribute = $parts[0] ?? '';
                                                    $value = $parts[1] ?? '';
                                                @endphp
                                                @if ($attribute !== '')
                                                    <span class="badge badge-sm bg-gradient-secondary">{{ $attribute }}: {{ $value }}</span><br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <div class="input-group text-center input-group-static" style="max-width:80px;margin:auto">
                                                <input @if ($id != 0) disabled @endif
                                                    min="1"
                                                    max="{{ $item->stock > 0 ? $item->stock : '' }}"
                                                    data-cart-id="{{ $item->cart_id }}"
                                                    value="{{ $item->quantity }}"
                                                    type="number" name="quantity"
                                                    class="form-control btnQuantity text-center quantity">
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <form name="delete-item-cart" class="delete-form">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button data-item-id="{{ $item->cart_id }}"
                                                    class="btn btn-icon btn-3 btn-danger btnDeleteCart" type="button">
                                                    <span class="btn-inner--icon"><i class="material-icons">delete</i></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(count($cart_items) > 0)
                    <div class="d-flex justify-content-end mt-2 pe-2">
                        <span class="text-secondary text-sm me-2">Subtotal:</span>
                        <strong class="text-sm" id="subtotalBadge">₡{{ number_format($cloth_price) }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="step-connector"></div>

        {{-- PASO 2: Datos del Cliente --}}
        <div class="card step-card mb-1">
            <div class="card-body">
                <div class="step-header">
                    <div class="step-badge">2</div>
                    <div>
                        <h5>Datos del Cliente <span class="badge badge-sm bg-gradient-secondary ms-1">Opcional</span></h5>
                        <small>Información del cliente para identificar la venta</small>
                    </div>
                </div>
                <form action="{{ url('payment') }}" method="POST" enctype="multipart/form-data" id="saleForm">
                    @csrf
                    <input type="hidden" value="F" name="kind_of" id="kind_of">
                    <input type="hidden" name="updateId" value="{{ $id }}" id="updateId">
                    <input type="hidden" value="{{ $tenantinfo->tenant }}" name="tenant" id="tenant">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->name) && $buy->name ? 'is-filled' : '' }}">
                                <label class="form-label">Nombre</label>
                                <input value="{{ isset($buy->name) ? $buy->name : '' }}" type="text"
                                    class="form-control" name="name">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->email) && $buy->email ? 'is-filled' : '' }}">
                                <label class="form-label">E-mail</label>
                                <input value="{{ isset($buy->email) ? $buy->email : '' }}" type="text"
                                    class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->telephone) && $buy->telephone ? 'is-filled' : '' }}">
                                <label class="form-label">Teléfono</label>
                                <input value="{{ isset($buy->telephone) ? $buy->telephone : '' }}" type="text"
                                    class="form-control" name="telephone">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline is-filled">
                                <label class="form-label">País</label>
                                <input type="text" readonly value="Costa Rica"
                                    class="form-control" name="country">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->province) && $buy->province ? 'is-filled' : '' }}">
                                <label class="form-label">Provincia</label>
                                <input type="text" value="{{ isset($buy->province) ? $buy->province : '' }}"
                                    class="form-control" name="province">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->city) && $buy->city ? 'is-filled' : '' }}">
                                <label class="form-label">Ciudad</label>
                                <input type="text" value="{{ isset($buy->city) ? $buy->city : '' }}"
                                    class="form-control" name="city">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->address_two) && $buy->address_two ? 'is-filled' : '' }}">
                                <label class="form-label">Distrito</label>
                                <input type="text" value="{{ isset($buy->address_two) ? $buy->address_two : '' }}"
                                    class="form-control" name="address_two">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->address) && $buy->address ? 'is-filled' : '' }}">
                                <label class="form-label">Dirección Exacta</label>
                                <input type="text" value="{{ isset($buy->address) ? $buy->address : '' }}"
                                    class="form-control" name="address">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group input-group-lg input-group-outline {{ isset($buy->detail) && $buy->detail ? 'is-filled' : '' }}">
                                <label class="form-label">Nota / Detalle</label>
                                <input type="text" value="{{ isset($buy->detail) ? $buy->detail : '' }}"
                                    class="form-control" name="detail">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="step-connector"></div>

        {{-- PASO 3: Resumen y Confirmación --}}
        <div class="card step-card mb-4">
            <div class="card-body">
                <div class="step-header">
                    <div class="step-badge">3</div>
                    <div>
                        <h5>Resumen y Confirmación</h5>
                        <small>Revisa el total, configura el envío o apartado y confirma la venta</small>
                    </div>
                </div>

                <div class="row">
                    {{-- Columna izquierda: Envío + Apartado --}}
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($buy->total_delivery) && $buy->total_delivery ? 'is-filled' : '' }}">
                            <label class="form-label">Costo de Envío (Opcional)</label>
                            <input type="number" form="saleForm"
                                value="{{ isset($buy->total_delivery) ? $buy->total_delivery : '' }}"
                                class="form-control" name="delivery" id="deliveryInput">
                        </div>

                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="apartado-section mt-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" value="1"
                                        id="apartado" name="apartado" form="saleForm"
                                        {{ (isset($buy->apartado) && $buy->apartado == 1) || old('apartado') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="apartado">
                                        <i class="material-icons me-1" style="vertical-align:middle;font-size:1rem">savings</i>
                                        Apartado
                                    </label>
                                </div>
                                <span class="badge badge-sm bg-gradient-warning">Pago parcial</span>
                            </div>
                            <p class="text-xs text-muted mb-2">El cliente cancela un monto ahora y el resto después. La venta queda registrada con estado "Apartado".</p>
                            <div class="{{ (isset($buy->monto_apartado) && $buy->monto_apartado) ? '' : 'd-none' }}" id="monto_apartado">
                                <div class="input-group input-group-lg input-group-outline {{ isset($buy->monto_apartado) && $buy->monto_apartado ? 'is-filled' : '' }}">
                                    <label class="form-label">Monto del Apartado (₡)</label>
                                    <input value="{{ isset($buy->monto_apartado) ? $buy->monto_apartado : '' }}"
                                        type="number" class="form-control" name="monto_apartado" form="saleForm">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Columna derecha: Totales --}}
                    <div class="col-md-6">
                        <div class="card bg-gradient-dark mb-3">
                            <div class="card-body p-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-1"
                                        style="background:transparent;color:#fff">
                                        <span class="text-white-50 text-sm">Productos</span>
                                        <strong id="totalCloth">₡{{ number_format($cloth_price) }}</strong>
                                    </li>
                                    @if ($iva > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-1"
                                        style="background:transparent;color:#fff">
                                        <span class="text-white-50 text-sm">I.V.A</span>
                                        <span id="totalIvaElement">₡{{ number_format($iva) }}</span>
                                    </li>
                                    @endif
                                    @if ($you_save > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-1"
                                        style="background:transparent;color:#fff">
                                        <span class="text-white-50 text-sm">Descuento</span>
                                        <span class="text-warning" id="totalDiscountElement">-₡{{ number_format($you_save) }}</span>
                                    </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-2"
                                        style="background:transparent;color:#fff;border-top:1px solid rgba(255,255,255,.2) !important">
                                        <strong class="text-white">TOTAL</strong>
                                        <strong class="text-white fs-5" id="totalPriceElement">₡{{ number_format($total_price) }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <button @if ($total_price == 0) disabled @endif
                            id="btnSinpe" type="submit" form="saleForm"
                            class="btn btn-add_to_cart w-100 d-block h8">
                            <i class="material-icons me-1" style="vertical-align:middle;font-size:1.1rem">point_of_sale</i>
                            Realizar Venta &nbsp;₡<span id="btnPay">{{ number_format($total_price) }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var tenant = $('#tenant').val();
            var updateId = document.getElementById('updateId').value;

            // ── Apartado toggle ──────────────────────────────────────────────
            if (tenant !== "rutalimon") {
                const checkbox  = document.getElementById("apartado");
                const montoDiv  = document.getElementById("monto_apartado");
                if (checkbox) {
                    checkbox.addEventListener("click", function() {
                        montoDiv.classList.toggle("d-none", !this.checked);
                    });
                }
            }

            // ── DataTable (cart) ─────────────────────────────────────────────
            var dataTable = $('#cartTable').DataTable({
                searching: false,
                lengthChange: false,
                paging: false,
                info: false,
                ordering: false,
            });

            // ── Código: buscar por Enter ─────────────────────────────────────
            $('#code').keypress(function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    fetchProductAttributes($(this).val());
                }
            });

            // ── AJAX: obtener atributos del producto ─────────────────────────
            function fetchProductAttributes(code) {
                var $container = $('#container');
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/size-by-cloth",
                    data: { 'code': code },
                    success: function(response) {
                        $container.empty();
                        if (response.status !== "success") {
                            Swal.fire({ title: response.status, icon: response.icon });
                            $container.removeClass('d-block').addClass('d-none');
                            $('#select-container').removeClass('d-block').addClass('d-none');
                        } else {
                            renderAttributes(response.results, $container, updateId);
                            $container.removeClass('d-none').addClass('d-block');
                            $('#select-container').removeClass('d-none').addClass('d-block');
                        }
                    }
                });
            }

            // ── Renderizar selectores de atributos ───────────────────────────
            function renderAttributes(results, $container, updateId) {
                var $currentRow;
                if (results.length > 0) {
                    $.each(results, function(index, attribute) {
                        if (index % 2 === 0) {
                            $currentRow = $('<div>', { class: 'row align-items-end mb-3' });
                            $container.append($currentRow);
                        }

                        var values      = attribute.valores.split('/');
                        var ids         = attribute.ids.split('/');
                        var stockValues = attribute.stock.split('/');

                        var $label  = $('<label>', { text: attribute.columna_atributo });
                        var $select = $('<select>', {
                            required: true,
                            name: 'size_id',
                            class: 'size_id form-control form-control-lg'
                        });

                        $.each(values, function(key, value) {
                            if (ids[key] !== undefined && stockValues[key] != 0) {
                                var $option = $('<option>', {
                                    value: ids[key] + '-' + attribute.attr_id + '-' + attribute.clothing_id,
                                    id: 'size_' + ids[key],
                                    text: value + ' (stock: ' + stockValues[key] + ')'
                                });
                                if (key === 0) $option.attr('selected', 'selected');
                                $select.append($option);
                            }
                        });

                        var $colAttr = $('<div>', { class: 'col-md-4' })
                            .append($label).append($select);
                        $currentRow.append($colAttr);

                        if (typeof updateId !== 'undefined' && updateId != 0) {
                            var $qtyLabel = $('<label>', { text: 'Cantidad' });
                            var $qtyInput = $('<input>', {
                                type: 'number', id: 'quantityBox', name: 'quantityBox',
                                class: 'form-control', placeholder: 'Cant.', min: 1, value: 1
                            });
                            $currentRow.append(
                                $('<div>', { class: 'col-md-2' }).append($qtyLabel).append($qtyInput)
                            );
                        }

                        if (index % 2 === 1 || index === results.length - 1) {
                            var $btn = $('<button>', { class: 'btn btn-add_to_cart shadow-0 btnAdd' })
                                .append('<i class="me-1 fa fa-shopping-basket"></i> Agregar');
                            $currentRow.append(
                                $('<div>', { class: 'col-md-12 mt-2' }).append($btn)
                            );
                        }
                    });
                } else {
                    // Sin atributos: sólo botón Agregar
                    var $btn = $('<button>', { class: 'btn btn-add_to_cart shadow-0 btnAdd' })
                        .append('<i class="me-1 fa fa-shopping-basket"></i> Agregar');
                    $container.append(
                        $('<div>', { class: 'row' }).append(
                            $('<div>', { class: 'col-md-12 text-center' }).append($btn)
                        )
                    );
                }
            }

            // ── Agregar al carrito ───────────────────────────────────────────
            $('#container').on('click', '.btnAdd', function(e) {
                e.preventDefault();
                var code     = document.getElementById('code').value;
                var updateId = document.getElementById('updateId').value;
                var quantity = 1;
                if (updateId != 0) {
                    var qtyBox = document.getElementById('quantityBox');
                    if (qtyBox) quantity = qtyBox.value;
                }
                var selected_sizes = [];
                $('.size_id').each(function() {
                    var v = $(this).val();
                    if (v && v.trim() !== "") selected_sizes.push(v);
                });
                var attributes = JSON.stringify(selected_sizes);

                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/add-to-cart",
                    data: { 'code': code, 'updateId': updateId, 'attributes': attributes, 'quantity': quantity },
                    success: function(response) {
                        if (response.icon === "success") {
                            location.reload();
                        } else {
                            Swal.fire({ title: response.status, icon: response.icon });
                        }
                    }
                });
            });

            // ── Actualizar cantidad ──────────────────────────────────────────
            $('.btnQuantity').on('change', function() {
                var quantity = $(this).val();
                var itemId   = $(this).data('cart-id');
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/edit-quantity",
                    data: { 'cart_id': itemId, 'quantity': quantity },
                    success: function() { calcularTotal(); }
                });
            });

            // ── Actualizar precio personalizado ──────────────────────────────
            $(document).on('change', '.price', function() {
                var customPrice = $(this).val();
                var itemId      = $(this).data('cart-id');
                if (!itemId) return; // no es fila del cart
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    method: "POST",
                    url: "/edit-price",
                    data: { 'cart_id': itemId, 'custom_price': customPrice },
                    success: function(response) {
                        if (response.status === 'success') {
                            calcularTotal();
                        }
                    }
                });
            });

            // ── Eliminar item del carrito ────────────────────────────────────
            $('.btnDeleteCart').on('click', function(e) {
                e.preventDefault();
                var itemId = $(this).data('item-id');
                Swal.fire({
                    title: '¿Eliminar este producto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "POST",
                            url: "/delete-item-cart/" + itemId,
                            data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                            success: function(response) {
                                if (response.refresh == true) {
                                    window.location.href = "{{ url('/') }}";
                                } else {
                                    location.reload();
                                }
                            },
                            error: function(xhr) { console.error(xhr.responseText); }
                        });
                    }
                });
            });
        });

        // ── Seleccionar producto desde modal ─────────────────────────────────
        let currentButtonName = null;
        document.querySelectorAll('.icon-button').forEach(button => {
            button.addEventListener('click', function() {
                currentButtonName = this.getAttribute('data-name');
            });
        });

        function selectIcon(icon) {
            if (!currentButtonName) return;
            document.getElementById("code").value = icon;
            var modal = bootstrap.Modal.getInstance(document.getElementById('add-products-modal'));
            modal.hide();

            var $container = $('#container');
            var updateId   = document.getElementById('updateId').value;
            $container.empty();

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                method: "POST",
                url: "/size-by-cloth",
                data: { 'code': icon },
                success: function(response) {
                    if (response.status !== "success") {
                        Swal.fire({ title: response.status, icon: response.icon });
                        $container.removeClass('d-block').addClass('d-none');
                        $('#select-container').removeClass('d-block').addClass('d-none');
                    } else {
                        renderAttributes(response.results, $container, updateId);
                        $container.removeClass('d-none').addClass('d-block');
                        $('#select-container').removeClass('d-none').addClass('d-block');
                    }
                }
            });

            function renderAttributes(results, $container, updateId) {
                var $currentRow;
                if (results.length > 0) {
                    $.each(results, function(index, attribute) {
                        if (index % 2 === 0) {
                            $currentRow = $('<div>', { class: 'row align-items-end mb-3' });
                            $container.append($currentRow);
                        }
                        var values      = attribute.valores.split('/');
                        var ids         = attribute.ids.split('/');
                        var stockValues = attribute.stock.split('/');
                        var $label  = $('<label>', { text: attribute.columna_atributo });
                        var $select = $('<select>', { required: true, name: 'size_id', class: 'size_id form-control form-control-lg' });

                        $.each(values, function(key, value) {
                            if (ids[key] !== undefined && stockValues[key] != 0) {
                                var $option = $('<option>', {
                                    value: ids[key] + '-' + attribute.attr_id + '-' + attribute.clothing_id,
                                    id: 'size_' + ids[key],
                                    text: value + ' (stock: ' + stockValues[key] + ')'
                                });
                                if (key === 0) $option.attr('selected', 'selected');
                                $select.append($option);
                            }
                        });

                        var $colAttr = $('<div>', { class: 'col-md-4' }).append($label).append($select);
                        $currentRow.append($colAttr);

                        if (typeof updateId !== 'undefined' && updateId != 0) {
                            var $qtyLabel = $('<label>', { text: 'Cantidad' });
                            var $qtyInput = $('<input>', { type: 'number', id: 'quantityBox', name: 'quantityBox', class: 'form-control', placeholder: 'Cant.', min: 1, value: 1 });
                            $currentRow.append($('<div>', { class: 'col-md-2' }).append($qtyLabel).append($qtyInput));
                        }

                        if (index % 2 === 1 || index === results.length - 1) {
                            var $btn = $('<button>', { class: 'btn btn-add_to_cart shadow-0 btnAdd' })
                                .append('<i class="me-1 fa fa-shopping-basket"></i> Agregar');
                            $currentRow.append($('<div>', { class: 'col-md-12 mt-2' }).append($btn));
                        }
                    });
                } else {
                    var $btn = $('<button>', { class: 'btn btn-add_to_cart shadow-0 btnAdd' })
                        .append('<i class="me-1 fa fa-shopping-basket"></i> Agregar');
                    $container.append($('<div>', { class: 'row' }).append($('<div>', { class: 'col-md-12 text-center' }).append($btn)));
                }
            }
        }

        // ── Filtrar productos en el modal ────────────────────────────────────
        function filterIcons() {
            var input    = document.getElementById('icon-search');
            var filter   = input.value.toLowerCase();
            var iconList = document.getElementById('icon-list');
            var icons    = iconList.getElementsByClassName('icon-item');
            for (var i = 0; i < icons.length; i++) {
                var code = icons[i].getAttribute('data-code').toLowerCase();
                var name = icons[i].getAttribute('data-name').toLowerCase();
                icons[i].style.display = (code.indexOf(filter) > -1 || name.indexOf(filter) > -1) ? "" : "none";
            }
        }

        // ── Recalcular totales ───────────────────────────────────────────────
        function calcularTotal() {
            var total       = 0;
            var total_cloth = 0;
            var iva         = parseFloat(document.getElementById("iva_tenant").value);
            var total_iva   = 0;
            var you_save    = 0;

            document.querySelectorAll('#cartTable tbody tr').forEach(function(fila) {
                var priceInput = fila.querySelector('.price');
                var discInput  = fila.querySelector('.discount');
                var qtyInput   = fila.querySelector('.quantity');
                if (!priceInput || !qtyInput) return;
                var precio    = parseFloat(priceInput.value) || 0;
                var discount  = parseFloat(discInput ? discInput.value : 0) || 0;
                var cantidad  = parseInt(qtyInput.value) || 1;
                you_save += discount * cantidad;
                total    += precio * cantidad;
            });

            total_iva   = total * iva;
            total_cloth = total;

            var fmt = function(n) {
                return '₡' + n.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.');
            };

            var totalEl    = document.getElementById('totalPriceElement');
            var ivaEl      = document.getElementById('totalIvaElement');
            var discountEl = document.getElementById('totalDiscountElement');
            var clothEl    = document.getElementById('totalCloth');
            var btnPay     = document.getElementById('btnPay');
            var subtotal   = document.getElementById('subtotalBadge');
            var btnSinpe   = document.getElementById('btnSinpe');

            if (totalEl)    totalEl.textContent   = fmt(total + total_iva);
            if (ivaEl && total_iva > 0) ivaEl.textContent = fmt(total_iva);
            if (discountEl && you_save > 0) discountEl.textContent = '-' + fmt(you_save);
            if (clothEl)    clothEl.textContent   = fmt(total_cloth);
            if (btnPay)     btnPay.textContent     = (total + total_iva).toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.');
            if (subtotal)   subtotal.textContent   = fmt(total_cloth);
            if (btnSinpe)   btnSinpe.disabled      = (total === 0);
        }
    </script>
@endsection
