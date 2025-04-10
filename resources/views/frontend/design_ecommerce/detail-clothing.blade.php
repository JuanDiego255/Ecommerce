@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <!-- breadcrumb -->
    @foreach ($clothes as $key => $item)
        @php
            $sizes = explode(',', $item->available_sizes);
            $stockPerSize = explode(',', $item->stock_per_size);
            $pricePerSize = explode(',', $item->price_per_size);
            $precio = $item->price;
            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
                $precio = $item->first_price;
            }
            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                $precio = $item->mayor_price;
            }
            $descuentoPorcentaje = $item->discount;
            // Calcular el descuento
            $descuento = ($precio * $descuentoPorcentaje) / 100;
            // Calcular el precio con el descuento aplicado
            $precioConDescuento = $precio - $descuento;
        @endphp
        <input type="hidden" name="porcDescuento" value="{{ $item->discount }}" id="porcDescuento">
        <div class="container m-t-80">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') . '/') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'category/') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Categorías
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $category_id . '/' . $item->department_id) }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $item->category }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>

                    <span class="stext-109 cl4">
                        Detalles
                    </span>
                @else
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') . '/') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>

                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'departments/index') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Departamentos
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'category/' . $item->department_id) }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $item->department_name }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $category_id . '/' . $item->department_id) }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $item->category }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>

                    <span class="stext-109 cl4">
                        Detalles
                    </span>
                @endif
            </div>
        </div>
        <section class="sec-product-detail bg0 p-t-65 p-b-60">
            <div class="container product_data">
                <input type="hidden" class="cloth_item" value="{{ $item->id }}">
                <input type="hidden" class="prefix" id="prefix" value="{{ $prefix }}">
                <div class="row">
                    <div class="col-md-6 col-lg-7 p-b-30">
                        <div class="p-l-25 p-r-30 p-lr-0-lg">
                            <div class="wrap-slick3 flex-sb flex-w">
                                <div class="wrap-slick3-dots"></div>
                                <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
                                <div class="slick3 gallery-lb">
                                    @foreach ($clothes as $clothing)
                                        @php
                                            $images = explode(',', $clothing->images); // Convertir la lista de imágenes en un array
                                            $uniqueImages = array_unique($images); // Obtener imágenes únicas
                                        @endphp
                                        @foreach ($uniqueImages as $image)
                                            <div class="item-slick3"
                                                data-thumb="{{ isset($image) && $image != '' ? route('file', $image) : url('images/producto-sin-imagen.PNG') }}">
                                                <div class="wrap-pic-w pos-relative">
                                                    <img src="{{ isset($image) && $image != '' ? route('file', $image) : url('images/producto-sin-imagen.PNG') }}"
                                                        alt="IMG-PRODUCT">

                                                    <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                        href="{{ isset($image) && $image != '' ? route('file', $image) : url('images/producto-sin-imagen.PNG') }}">
                                                        <i class="fa fa-expand"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-5 p-b-30">
                        <div class="p-r-50 p-t-5 p-lr-0-lg">
                            <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                                {{ $item->name }}
                            </h4>
                            <span class="mtext-106 cl2">
                                <div class="price {{ $item->can_buy != 1 && $precioConDescuento <= 0 ? 'd-none' : '' }}">
                                    <strong id="text_price">₡{{ number_format($precioConDescuento) }}</strong>
                                    @if ($item->discount)
                                        <s class="text-danger"><span class="text-danger"><strong
                                                    id="text_price_discount">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}</strong>
                                            </span></s>
                                        / por unidad
                                    @endif
                                </div>
                            </span>

                            <p class="stext-102 cl3 p-t-23">
                                {!! $item->description !!}
                            </p>

                            <!--  -->
                            <div class="p-t-33">
                                @foreach ($result as $attribute)
                                    @if ($attribute->stock != 0)
                                        @php
                                            $values = explode('/', $attribute->valores);
                                            $ids = explode('/', $attribute->ids);
                                            $stock_values = explode('/', $attribute->stock);
                                        @endphp

                                        <div class="flex-w flex-r-m p-b-10">
                                            <div class="size-203 flex-c-m respon6">
                                                {{ $attribute->columna_atributo == 'Stock' ? 'Predeterminado' : $attribute->columna_atributo }}
                                            </div>

                                            <div class="size-204 respon6-next">
                                                <div class="rs1-select2 bor8 bg0">
                                                    <select class="js-select2"
                                                        name="{{ strtolower($attribute->columna_atributo) }}_id">
                                                        @foreach ($values as $key => $value)
                                                            @if (isset($ids[$key]) && $stock_values[$key] != 0)
                                                                <option
                                                                    value="{{ $ids[$key] . '-' . $attribute->attr_id . '-' . $item->id }}"
                                                                    {{ $key === 0 ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-204 flex-w flex-m respon6-next">
                                        <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>
                                            <input class="mtext-104 cl3 txt-center num-product qty quantity"
                                                @if ($item->total_stock == 0) disabled @endif min="1"
                                                @if ($item->total_stock > 0) max="{{ $item->total_stock }}" @endif
                                                id="quantityInput" value="1" type="number" name="quantity">

                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>

                                        <button
                                            class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 btnAddToCart">
                                            Añadir
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                                @guest
                                @else
                                    <a class="add_favorite btn btn-add_to_cart shadow-0"
                                        data-category-id="{{ $item->category_id }}" data-clothing-id="{{ $item->id }}"
                                        href="#">
                                        <i
                                            class="fa fa-heart {{ $clothing_favs->contains('clothing_id', $item->id) ? 'text-danger' : '' }}"></i>
                                    </a>
                                @endguest

                                {{-- <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                    data-tooltip="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>

                                <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                    data-tooltip="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>

                                <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                    data-tooltip="Google Plus">
                                    <i class="fa fa-google-plus"></i>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{--  <div class="bor10 m-t-50 p-t-43 p-b-40">
                    <!-- Tab01 -->
                    <div class="tab01">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item p-b-10">
                                <a class="nav-link active" data-toggle="tab" href="#description"
                                    role="tab">Description</a>
                            </li>

                            <li class="nav-item p-b-10">
                                <a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional
                                    information</a>
                            </li>

                            <li class="nav-item p-b-10">
                                <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-t-43">
                            <!-- - -->
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <div class="how-pos2 p-lr-15-md">
                                    <p class="stext-102 cl6">
                                        Aenean sit amet gravida nisi. Nam fermentum est felis, quis feugiat nunc fringilla
                                        sit
                                        amet. Ut in blandit ipsum. Quisque luctus dui at ante aliquet, in hendrerit lectus
                                        interdum. Morbi elementum sapien rhoncus pretium maximus. Nulla lectus enim, cursus
                                        et
                                        elementum sed, sodales vitae eros. Ut ex quam, porta consequat interdum in, faucibus
                                        eu
                                        velit. Quisque rhoncus ex ac libero varius molestie. Aenean tempor sit amet orci nec
                                        iaculis. Cras sit amet nulla libero. Curabitur dignissim, nunc nec laoreet
                                        consequat,
                                        purus nunc porta lacus, vel efficitur tellus augue in ipsum. Cras in arcu sed metus
                                        rutrum iaculis. Nulla non tempor erat. Duis in egestas nunc.
                                    </p>
                                </div>
                            </div>

                            <!-- - -->
                            <div class="tab-pane fade" id="information" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                        <ul class="p-lr-28 p-lr-15-sm">
                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Weight
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    0.79 kg
                                                </span>
                                            </li>

                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Dimensions
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    110 x 33 x 100 cm
                                                </span>
                                            </li>

                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Materials
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    60% cotton
                                                </span>
                                            </li>

                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Color
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    Black, Blue, Grey, Green, Red, White
                                                </span>
                                            </li>

                                            <li class="flex-w flex-t p-b-7">
                                                <span class="stext-102 cl3 size-205">
                                                    Size
                                                </span>

                                                <span class="stext-102 cl6 size-206">
                                                    XL, L, M, S
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- - -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="row">
                                    <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                        <div class="p-b-30 m-lr-15-sm">
                                            <!-- Review -->
                                            <div class="flex-w flex-t p-b-68">
                                                <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                    <img src="/design_ecommerce/images/avatar-01.jpg" alt="AVATAR">
                                                </div>

                                                <div class="size-207">
                                                    <div class="flex-w flex-sb-m p-b-17">
                                                        <span class="mtext-107 cl2 p-r-20">
                                                            Ariana Grande
                                                        </span>

                                                        <span class="fs-18 cl11">
                                                            <i class="zmdi zmdi-star"></i>
                                                            <i class="zmdi zmdi-star"></i>
                                                            <i class="zmdi zmdi-star"></i>
                                                            <i class="zmdi zmdi-star"></i>
                                                            <i class="zmdi zmdi-star-half"></i>
                                                        </span>
                                                    </div>

                                                    <p class="stext-102 cl6">
                                                        Quod autem in homine praestantissimum atque optimum est, id
                                                        deseruit.
                                                        Apud ceteros autem philosophos
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Add review -->
                                            <form class="w-full">
                                                <h5 class="mtext-108 cl2 p-b-7">
                                                    Add a review
                                                </h5>

                                                <p class="stext-102 cl6">
                                                    Your email address will not be published. Required fields are marked *
                                                </p>

                                                <div class="flex-w flex-m p-t-50 p-b-23">
                                                    <span class="stext-102 cl3 m-r-16">
                                                        Your Rating
                                                    </span>

                                                    <span class="wrap-rating fs-18 cl11 pointer">
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                        <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                        <input class="dis-none" type="number" name="rating">
                                                    </span>
                                                </div>

                                                <div class="row p-b-25">
                                                    <div class="col-12 p-b-5">
                                                        <label class="stext-102 cl3" for="review">Your review</label>
                                                        <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
                                                    </div>

                                                    <div class="col-sm-6 p-b-5">
                                                        <label class="stext-102 cl3" for="name">Name</label>
                                                        <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name"
                                                            type="text" name="name">
                                                    </div>

                                                    <div class="col-sm-6 p-b-5">
                                                        <label class="stext-102 cl3" for="email">Email</label>
                                                        <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email"
                                                            type="text" name="email">
                                                    </div>
                                                </div>

                                                <button
                                                    class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
                                                    Submit
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
                <span class="stext-107 cl6 p-lr-25">
                    SKU: {{ $item->code }}
                </span>

                <span class="stext-107 cl6 p-lr-25">
                    Categoría: {{ $item->category }}
                </span>
            </div>
        </section>
        <!-- Related Products -->
    @break
@endforeach
<section class="sec-relate-product bg0 p-t-45 p-b-105">
    <div class="container">
        <div class="p-b-45">
            <h3 class="ltext-106 cl5 txt-center">
                Productos Relacionados
            </h3>
        </div>

        <!-- Slide2 -->
        <div class="wrap-slick2">
            <div class="slick2">
                @foreach ($clothings_trending as $item)
                    @php
                        $precio = $item->price;
                        if (
                            isset($tenantinfo->custom_size) &&
                            $tenantinfo->custom_size == 1 &&
                            $item->first_price > 0
                        ) {
                            $precio = $item->first_price;
                        }
                        if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                            $precio = $item->mayor_price;
                        }
                        $descuentoPorcentaje = $item->discount;
                        // Calcular el descuento
                        $descuento = ($precio * $descuentoPorcentaje) / 100;
                        // Calcular el precio con el descuento aplicado
                        $precioConDescuento = $precio - $descuento;
                    @endphp
                    <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="IMG-PRODUCT">

                                <a href="#"
                                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                    data-discount="{{ $item->discount }}" data-description="{!! $item->description !!}"
                                    data-price="{{ number_format($precioConDescuento, 2) }}"
                                    data-original-price="{{ number_format($item->price, 2) }}"
                                    data-attributes='@json($item->atributos)'
                                    data-category="{{ $item->category }}" data-images='@json(array_map(fn($img) => route('file', $img), $item->all_images))'
                                    data-image="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    Detallar
                                </a>
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="product-detail.html"
                                        class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        {{ $item->name }}
                                    </a>

                                    <span class="stext-105 cl3">
                                        <div
                                            class="sakura-font sakura-color {{ $item->can_buy != 1 && $precioConDescuento <= 0 ? 'd-none' : '' }}">
                                            ₡{{ number_format($precioConDescuento) }}
                                            @if ($item->discount)
                                                <s class="text-danger"><span
                                                        class="text-danger">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                                    </span></s>
                                            @endif
                                        </div>
                                    </span>
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
            </div>
        </div>
    </div>
</section>
@include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
<script>
    $(document).ready(function() {

        var attributeButtons = document.querySelectorAll('.attribute-btn');
        attributeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var attributeType = this.getAttribute('data-attribute');
                var selectedButtons = document.querySelectorAll(
                    `.attribute-btn[data-attribute="${attributeType}"]`);

                // Deseleccionar todos los botones del mismo atributo
                selectedButtons.forEach(function(btn) {
                    btn.classList.remove('selected');
                });

                // Seleccionar el botón actual
                this.classList.add('selected');

                // Actualizar el valor del input oculto correspondiente
                updateHiddenInput(this);

                var partes = this.getAttribute('data-value').split("-");
                if (partes.length === 3) {
                    getStock(partes[2], partes[1], partes[0]);
                }
            });
        });

        function updateHiddenInput(button) {
            var attributeType = button.getAttribute('data-attribute').toLowerCase();
            var input = document.querySelector(`input[name="${attributeType}_id"]`);
            if (input) {
                input.value = button.getAttribute('data-value');
            }
        }

        function getQuantity() {
            $('input[type="hidden"][name$="_id"]').each(function() {
                var selected_value = $(this).val();
                if (selected_value) {
                    var partes = selected_value.split("-");
                    if (partes.length === 3) {
                        var value_attr = partes[0];
                        var attr_id = partes[1];
                        var cloth_id = partes[2];
                        if (value_attr !== "") {
                            getStock(cloth_id, attr_id, value_attr);
                        }
                    }
                }
            });
        }
        getQuantity();
    });
    $('.btnAddToCart').click(function(e) {
        e.preventDefault();
        var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
            .value : '';
        var cloth_id = $(this).closest('.product_data').find('.cloth_item').val();
        var quantity = $(this).closest('.product_data').find('.quantity').val();
        var selected_attributes = [];
        var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/add-to-cart";

        // Recorrer todos los inputs ocultos con los valores seleccionados
        $(".js-select2").each(function() {
            var selected_value = $(this).val();
            var regex = /^\d+-\d+-\d+$/;
            if (selected_value && regex.test(selected_value)) {
                selected_attributes.push(selected_value);
            }
        });

        // Convertir el array a una cadena JSON
        var attributes = JSON.stringify(selected_attributes);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: url,
            data: {
                'clothing_id': cloth_id,
                'quantity': quantity,
                'attributes': attributes,
            },
            success: function(response) {
                console.log(response);
                swal(response.status,
                    "Producto agregado al carrito", response
                    .icon);
                if (response.icon === "success") {
                    var newCartNumber = response.cartNumber;
                    const button = document.querySelector(
                        '.js-show-cart');
                    button.dataset.notify = newCartNumber;
                    getCart();
                }
            }
        });

        /* const quantityInput = document.getElementById('quantityInput');

        quantityInput.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        }); */
    });
    $(".js-select2").on("change", function() {
        var selected_value = $(this).val();
        var partes = selected_value.split("-");
        if (partes.length === 3) {
            getStock(partes[2], partes[1], partes[0]);
        }
    });

    function getStock(cloth_id, attr_id, value_attr) {
        var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
            .value : '';
        var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/get-stock/" + cloth_id + '/' + attr_id + '/' + value_attr;
        $.ajax({
            method: "GET",
            url: url,
            success: function(stock) {
                var maxStock = stock.stock > 0 ? stock.stock : '';
                var porcDescuento = document.getElementById("porcDescuento").value;
                var perPrice = stock.price;

                if (perPrice > 0) {
                    $('input[name="quantity"]').attr('max', maxStock);
                    $('input[name="quantity"]').val(1);

                    var price = document.getElementById('text_price');
                    var price_discount = document.getElementById('text_price_discount');
                    if (porcDescuento > 0) {
                        var descuento = (perPrice * porcDescuento) / 100;
                        var precioConDescuento = perPrice - descuento;
                        price.textContent =
                            `₡${precioConDescuento.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                        price_discount.textContent =
                            `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                    } else {
                        price.textContent =
                            `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                    }
                }
            }
        });
    }
</script>
@endsection
