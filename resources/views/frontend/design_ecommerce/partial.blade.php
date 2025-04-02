@foreach ($clothings as $item)
    @php
        $precio = $item->price;
        if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
            $precio = $item->first_price;
        }
        if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
            $precio = $item->mayor_price;
        }
        $descuentoPorcentaje = $item->discount;
        $descuento = ($precio * $descuentoPorcentaje) / 100;
        $precioConDescuento = $precio - $descuento;
    @endphp
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ strtolower(str_replace(' ', '', $item->category)) }}">
        <div class="block2 product_data">
            <input type="hidden" class="code" name="code" value="{{ $item->code }}">
            <input type="hidden" class="clothing-name" name="clothing-name" value="{{ $item->name }}">
            <div class="block2-pic hov-img0">
                <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                    alt="IMG-PRODUCT">

                <a href="#"
                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                    data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-discount="{{ $item->discount }}"
                    data-description="{!! $item->description !!}" data-price="{{ number_format($precioConDescuento, 2) }}"
                    data-original-price="{{ number_format($item->price, 2) }}"
                    data-attributes='@json($item->atributos)' data-category="{{ $item->category }}"
                    data-images='@json(array_map(fn($img) => route('file', $img), $item->all_images))'
                    data-image="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                    Detallar
                </a>
            </div>
            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                        class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                        ({{ $item->category }})
                        {{ $item->name }}
                    </a>
                    <div class="price">₡{{ number_format($precioConDescuento) }}
                        @if ($item->discount)
                            <s class="text-danger">
                                ₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                            </s>
                        @endif
                    </div>
                </div>
                <div class="block2-txt-child2 flex-r p-t-3">
                    <!-- Puedes mantener el icono del corazón o agregar otra funcionalidad -->
                    @if (Auth::check())
                        <a href="#" class="dis-block pos-relative add_favorite"
                            data-clothing-id="{{ $item->id }}">
                            <i
                                class="fa fa-heart {{ $clothing_favs->contains('clothing_id', $item->id) ? 'text-danger' : '' }}"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
