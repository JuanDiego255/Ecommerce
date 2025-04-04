@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <section class="bg0 p-t-100 p-b-140">
        <div class="container">
            <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('category/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Categorías
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $category_name }}
                    </span>
                @else
                    <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('departments/index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Departamentos
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('category/' . $department_id) }}" class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $department_name }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $category_name }}
                    </span>
                @endif
            </div>
            <div class="p-b-10">
                <h3 class="ltext-103 cl5 text-center">
                    {{ $category_name }}
                </h3>
            </div>

            <!-- Botones de filtros -->
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    @php
                        $categories_pluck = $clothings->pluck('category')->unique();
                    @endphp
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                        Todos
                    </button>
                    @foreach ($categories_pluck as $category)
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
                    <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="searchfor" id="searchfor"
                        placeholder="Buscar">
                </div>
            </div>
            <!-- Aquí podrías agregar un panel-filter similar si lo requieres -->

            <!-- Grid de productos -->
            <div class="row isotope-grid" id="product-container">
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
                    <link rel="preload" as="image"
                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                    <div
                        class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{ strtolower(str_replace(' ', '', $item->category)) }}">
                        <div class="block2 product_data">
                            <input type="hidden" class="code" name="code" value="{{ $item->code }}">
                            <input type="hidden" class="clothing-name" name="clothing-name" value="{{ $item->name }}">
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
                                    <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                        class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
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
        </div>
        <div class="flex-c-m flex-w w-full p-t-45">
            <button id="btnPrev" class="lex-c-m stext-101 cl5 m-r-5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"
                data-prev="{{ $clothings->previousPageUrl() }}" data-id="{{ $category_id }}">Anterior</button>
            <button id="circleNumber"
                class="lex-c-m stext-101 m-r-5 cl5 size-103-clothes bg2 bor1 hov-btn1 p-lr-15 w-5 trans-04">1</button>
            <button id="btnNext" class="lex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"
                data-next="{{ $clothings->nextPageUrl() }}" data-id="{{ $category_id }}">Siguiente</button>
        </div>
    </section>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        $(document).on('click', '#btnPrev, #btnNext', function() {
            let pageUrl = $(this).data('next') || $(this).data('prev'); // Detecta si es "next" o "prev"
            if (!pageUrl) return;

            let urlParams = new URLSearchParams(new URL(pageUrl).search);
            let page = urlParams.get('page'); // Extrae el número de página
            let id = $(this).data('id');

            $.ajax({
                method: "GET",
                url: "/paginate/" + Number(page) + "/" + id,
                success: function(response) {
                    var items = response.clothings.data;
                    var category_id = response.category_id;
                    var html = '';
                    // Construcción del HTML dinámicamente
                    items.forEach(function(item) {
                        let precio = item.price;
                        if (item.custom_size == 1) {
                            precio = item.first_price;
                        }
                        if (item.mayor_price > 0 && item.is_mayor) {
                            precio = item.mayor_price;
                        }
                        let descuento = (precio * item.discount) / 100;
                        let precioConDescuento = precio - descuento;

                        html += `
    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item ${item.category.toLowerCase().replace(/\s/g, '')}">
        <div class="block2 product_data">
            <input type="hidden" class="code" name="code" value="${item.code}">
            <input type="hidden" class="clothing-name" name="clothing-name" value="${item.name}">
            <div class="block2-pic hov-img0">
                <img src="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}" 
                    alt="IMG-PRODUCT">
                <a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                    data-id="${item.id}" 
                    data-name="${item.name}" 
                    data-discount="${item.discount}" 
                    data-description="${item.description}"
                    data-price="${precioConDescuento}"
                    data-original-price="${item.price}"
                    data-attributes='${JSON.stringify(item.atributos)}'
                    data-category="${item.category}"
                    data-images='${JSON.stringify(item.all_images.map(img => `/file/${img}`))}'
                    data-image="${item.image ? `/file/${item.image}` : '/images/producto-sin-imagen.PNG'}">
                    Detallar
                </a>
            </div>
            <div class="block2-txt flex-w flex-t p-t-14">
                <div class="block2-txt-child1 flex-col-l ">
                    <a href="/detail-clothing/${item.id}/${category_id}" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                        ${item.name}
                    </a>
                    <div class="price">
                        ₡${precioConDescuento}
                        ${item.discount ? `<s class="text-danger">₡${item.price}</s>` : ''}
                    </div>
                </div>
                <div class="block2-txt-child2 flex-r p-t-3">
                    ${item.is_fav ? 
                        `<a href="#" class="dis-block pos-relative add_favorite" data-clothing-id="${item.id}">
                            <i class="fa fa-heart text-danger"></i>
                        </a>` : ''
                    }
                </div>
            </div>
        </div>
    </div>`;

                    });

                    // Actualizar el contenido del contenedor
                    $('#product-container').empty().append(html);
                    $('#circleNumber').text(response.page);

                    // Actualizar paginación
                    response.next_page_url ? $('#btnNext').data('next', response.next_page_url) : $(
                        '#btnNext').removeData('next');
                    response.prev_page_url ? $('#btnPrev').data('prev', response.prev_page_url) : $(
                        '#btnPrev').removeData('prev');

                    // Reinicializar Isotope
                    var $grid = $('.isotope-grid').data('isotope');
                    if ($grid) {
                        $grid.destroy();
                    }
                    var $newGrid = $('#product-container').isotope({
                        itemSelector: '.isotope-item',
                        layoutMode: 'fitRows',
                        percentPosition: true,
                        animationEngine: 'best-available',
                        masonry: {
                            columnWidth: '.isotope-item'
                        }
                    });

                    setTimeout(function() {
                        $newGrid.isotope('layout');
                    }, 500);

                    // Desplazarse arriba
                    $('html, body').animate({
                        scrollTop: 0
                    }, 600);
                }
            });

        });


        $(document).on('click', '.js-show-modal1', function(e) {
            e.preventDefault();
            $('.js-modal1').addClass('show-modal1');
        });
    </script>
@endsection
