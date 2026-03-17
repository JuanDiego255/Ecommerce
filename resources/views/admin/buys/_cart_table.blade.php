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
