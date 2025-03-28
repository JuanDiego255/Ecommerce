@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- Carousel Start --}}
    @if (count($tenantcarousel) != 0)
        <div class="wrap-slick1">
            <div class="slick1">
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="item-slick1"
                        style="background-image:url('{{ tenant_asset('/') . '/' . $carousel->image }}');">
                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        {{ $carousel->text1 }}
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        {{ $carousel->text2 }}
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="{{ url($carousel->url) }}"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        {{ $carousel->link_text }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    {{-- Carousel End --}}
    <!-- Banner Start-->
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($categories) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-50">
                <div class="container">
                    <div class="row">
                        @foreach ($category as $key => $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span class="block1-name ltext-102 trans-04 p-b-8">
                                                    {{ $item->name }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">
                                                    {!! $item->description !!}
                                                </span>
                                            </div>

                                            <div class="block1-txt-child2 p-b-4 trans-05">
                                                <div class="block1-link stext-101 cl0 trans-09">
                                                    Detallar
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        @if (count($departments) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-50">
                <div class="container">
                    <div class="row">
                        @foreach ($departments as $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url('category/' . $item->id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span class="block1-name ltext-102 trans-04 p-b-8">
                                                    {{ $item->department }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">
                                                    *Departamento*
                                                </span>
                                            </div>

                                            <div class="block1-txt-child2 p-b-4 trans-05">
                                                <div class="block1-link stext-101 cl0 trans-09">
                                                    Detallar
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
    <!-- Banner End-->
    <!-- Product -->
    <section class="bg0 p-t-23 p-b-140">
        <div class="container">
            <div class="p-b-10">
                <h3 class="ltext-103 cl5">
                    Productos Destacados
                </h3>
            </div>

            <!-- Botones de filtros -->
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    @php
                        $categories = $clothings->pluck('category')->unique();
                    @endphp
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                        Todos
                    </button>
                    @foreach ($categories as $category)
                        <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5"
                            data-filter=".{{ strtolower(str_replace(' ', '', $category)) }}">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Opciones de filtro adicionales (search, etc.) -->
                <div class="flex-w flex-c-m m-tb-10">
                    <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                        <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                        <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Filtro
                    </div>

                    <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Buscar
                    </div>
                </div>
            </div>

            <!-- Paneles de búsqueda y filtro (si los requieres) -->
            <div class="dis-none panel-search w-full p-t-10 p-b-15">
                <div class="bor8 dis-flex p-l-15">
                    <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                    <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product"
                        placeholder="Buscar">
                </div>
            </div>
            <!-- Aquí podrías agregar un panel-filter similar si lo requieres -->

            <!-- Grid de productos -->
            <div class="row isotope-grid">
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

                    <div
                        class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ strtolower(str_replace(' ', '', $item->category)) }}">
                        <div class="block2 product_data">
                            <div class="block2-pic hov-img0">
                                <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="IMG-PRODUCT">

                                <a href="#"
                                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                    data-discount="{{ $item->discount }}" data-description="{!! $item->description !!}"
                                    data-price="{{ number_format($precioConDescuento, 2) }}"
                                    data-original-price="{{ number_format($item->price, 2) }}"
                                    data-attributes='@json($item->atributos)' data-category="{{ $item->category }}"
                                    data-images='@json(array_map(fn($img) => route('file', $img), $item->all_images))'
                                    data-image="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    Detallar
                                </a>
                            </div>
                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
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
            </div>

            <!-- Botón "Load More" si lo requieres -->
            <div class="flex-c-m flex-w w-full p-t-45">
                <a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Cargar Más
                </a>
            </div>
        </div>
    </section>

    @include('layouts.inc.design_ecommerce.footer')
    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>
    <!-- Modal1 -->
    <div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
        <div class="overlay-modal1 js-hide-modal1"></div>

        <div class="container">
            <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
                <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                    <img src="/design_ecommerce/image/icons/icon-close.png" alt="CLOSE">
                </button>

                <div class="row">
                    <div class="col-md-6 col-lg-7 p-b-30">
                        <div class="p-l-25 p-r-30 p-lr-0-lg">
                            <div class="wrap-slick3 flex-sb flex-w">
                                <div class="wrap-slick3-dots"></div>
                                <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
                                <div class="slick3 gallery-lb">

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 col-lg-5 p-b-30">
                        <div class="p-r-50 p-t-5 p-lr-0-lg">
                            <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                            </h4>

                            <span class="mtext-106 cl2">
                            </span>
                            <span class="mtext-106 cl2 price-discount">
                            </span>

                            <p class="stext-102 cl3 p-t-23 text-desc">
                            </p>
                            <!--  -->
                            <div class="p-t-33">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".js-show-modal1").forEach(button => {
                button.addEventListener("click", function() {
                    let productName = this.getAttribute("data-name");
                    let porcDiscount = this.getAttribute("data-discount");
                    let productPrice = this.getAttribute("data-price");
                    let productPriceOrig = this.getAttribute("data-original-price");
                    let productDesc = this.getAttribute("data-description");
                    let productId = this.getAttribute("data-id");
                    let productImages = JSON.parse(this.getAttribute(
                        "data-images")); // Convertimos a array
                    let productAttributes = JSON.parse(this.getAttribute(
                        "data-attributes")); // Atributos en JSON

                    // Actualizar los elementos de texto en el modal
                    document.querySelector(".js-name-detail").innerText = productName;
                    document.querySelector(".mtext-106").innerText = `₡${productPrice}`;
                    document.querySelector(".text-desc").innerHTML = productDesc;

                    // Seleccionar el contenedor del slider
                    let slickContainer = document.querySelector(".slick3");

                    // Si Slick ya está inicializado, eliminarlo correctamente
                    if ($(slickContainer).hasClass("slick-initialized")) {
                        $(slickContainer).slick("unslick");
                    }

                    // Limpiar completamente el contenedor antes de agregar nuevas imágenes
                    slickContainer.innerHTML = "";

                    // Generar dinámicamente las imágenes
                    productImages.forEach(image => {
                        let slide = `
                    <div class="item-slick3" data-thumb="${image}">
                        <div class="wrap-pic-w pos-relative">
                            <img src="${image}" alt="IMG-PRODUCT">
                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="${image}">
                                <i class="fa fa-expand"></i>
                            </a>
                        </div>
                    </div>`;
                        slickContainer.insertAdjacentHTML("beforeend", slide);
                    });

                    // Generar dinámicamente los select para atributos
                    let attributesContainer = document.querySelector(".p-t-33");

                    // Limpiar cualquier contenido anterior
                    attributesContainer.innerHTML = '';

                    productAttributes.forEach(attribute => {
                        let attributeName = attribute.columna_atributo;
                        let attributeValues = attribute.valores.split(
                            "/"); // Valores de los atributos
                        let attributeIds = attribute.ids.split("/"); // IDs de los valores
                        let attributeStock = attribute.stock.split(
                            "/"); // Stock de los valores

                        let selectHTML = `
            <div class="flex-w flex-r-m p-b-10">
                <div class="size-203 flex-c-m respon6">${attributeName}</div>
                <div class="size-204 respon6-next">
                    <div class="rs1-select2 bor8 bg0">
                        <select class="js-select2" name="${attributeName.toLowerCase()}"
                                data-attribute="${attributeName}" data-value="${attribute.attr_id}-${attributeName}">
                            <option>Choose an option</option>`;

                        // Crear opciones dinámicas para el select
                        attributeValues.forEach((value, index) => {
                            let optionId = attributeIds[index]; // ID del valor
                            let selected = index === 0 ? 'selected' :
                                ''; // Seleccionar la primera opción por defecto
                            let stock = attributeStock[index]; // Stock del valor

                            // Agregar la opción al select con los atributos adecuados
                            selectHTML += `
                    <option value="${optionId}" 
                            data-stock="${stock}" 
                            ${selected} 
                            id="${attributeName}_${attribute.attr_id}">
                        ${value}
                    </option>`;
                        });

                        selectHTML += `
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
            </div>`;

                        // Insertar el select generado al contenedor
                        attributesContainer.insertAdjacentHTML("beforeend", selectHTML);
                    });
                    let quantityHTML = `
            <div class="flex-w flex-r-m p-b-10">
                <div class="size-204 flex-w flex-m respon6-next">
                    <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                        <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                            <i class="fs-16 zmdi zmdi-minus"></i>
                        </div>

                        <input class="mtext-104 cl3 txt-center num-product" type="number" min="1" max="1" name="quantity" value="1">

                        <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                            <i class="fs-16 zmdi zmdi-plus"></i>
                        </div>
                    </div>

                    <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                        Agregar
                    </button>
                </div>
            </div>`;

                    // Insertar el bloque de cantidad al final del contenedor de atributos
                    attributesContainer.insertAdjacentHTML("beforeend", quantityHTML);

                    // Inicializar select2 después de insertar los select
                    $(".js-select2").each(function() {
                        $(this).select2({
                            minimumResultsForSearch: 20,
                            dropdownParent: $(this).next('.dropDownSelect2')
                        });
                        $(this).on("change", function() {
                            let selectedValue = $(this)
                                .val(); // Obtener el valor seleccionado
                            let selectedOption = $(this).find(
                                "option:selected"); // Opción seleccionada
                            let stock = selectedOption.data(
                                "stock"
                            ); // Obtener el stock de la opción seleccionada
                            let partes = selectedOption.attr('id').split("_");
                            if (partes.length === 2) {
                                // Obtener id del atributo y valor y ejecutar la función getStock
                                getStock(productId, partes[1],
                                    selectedValue, porcDiscount
                                ); // Llamar a getStock con el producto, atributo, y valor seleccionado
                            }
                        });
                    });

                    // Esperar un poco antes de volver a inicializar Slick
                    setTimeout(() => {
                        $(slickContainer).slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            fade: true,
                            infinite: true,
                            autoplay: false,
                            autoplaySpeed: 6000,

                            arrows: true,
                            appendArrows: $(".wrap-slick3-arrows"),
                            prevArrow: '<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                            nextArrow: '<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',

                            dots: true,
                            appendDots: $(".wrap-slick3-dots"),
                            dotsClass: 'slick3-dots',
                            customPaging: function(slick, index) {
                                var portrait = $(slick.$slides[index]).data(
                                    'thumb');
                                return '<img src="' + portrait +
                                    '"/><div class="slick3-dot-overlay"></div>';
                            },
                        });
                    }, 200);
                    $(document).on('click', '.btn-num-product-down', function() {
                        var numProduct = Number($(this).next().val());
                        let maxStock = Number($(this).prev().attr("max"));
                        if (numProduct > 1) {
                            $(this).next().val(numProduct - 1); // Incrementar la cantidad
                        }
                    });

                    $(document).on('click', '.btn-num-product-up', function() {
                        var numProduct = Number($(this).prev().val());
                        let maxStock = Number($(this).prev().attr("max"));
                        if (numProduct < maxStock) {
                            $(this).prev().val(numProduct + 1); // Incrementar la cantidad
                        }
                    });
                    // Ejecutar la lógica de getStock() para el primer valor seleccionado por defecto
                    let firstSelect = document.querySelector(".js-select2");
                    let firstSelectedValue = firstSelect ? firstSelect.value : null;

                    if (firstSelectedValue) {
                        let selectedOption = firstSelect.selectedOptions[
                        0]; // Obtener la opción seleccionada
                        let partes = selectedOption ? selectedOption.id.split("_") : [];
                        if (partes.length === 2) {
                            // Ejecutar getStock con el primer valor seleccionado
                            getStock(productId, partes[1],
                            firstSelectedValue, porcDiscount); // Llamar a getStock con el producto, atributo, y valor seleccionado
                        }
                    }

                });
            });

            function getStock(cloth_id, attr_id, value_attr, porcDescuento) {
                $.ajax({
                    method: "GET",
                    url: "/get-stock/" + cloth_id + '/' + attr_id + '/' + value_attr,
                    success: function(stock) {
                        var maxStock = stock.stock > 0 ? stock.stock : '';
                        var perPrice = stock.price;
                        if (perPrice > 0) {
                            $('input[name="quantity"]').attr('max', maxStock);
                            $('input[name="quantity"]').val(1);
                            if (porcDescuento > 0) {
                                var descuento = (perPrice * porcDescuento) / 100;
                                var precioConDescuento = perPrice - descuento;
                                document.querySelector(".mtext-106").innerText =
                                    `₡${precioConDescuento.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                                /* price_discount.textContent =
                                    `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`; */
                            } else {
                                document.querySelector(".mtext-106").innerText =
                                    `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                            }
                        }
                    }
                });
            }
            // Cerrar modal
            document.querySelectorAll(".js-hide-modal1").forEach(button => {
                button.addEventListener("click", function() {
                    document.querySelector(".js-modal1").classList.remove("show-modal1");
                });
            });
        });
    </script>
@endsection
