@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    switch ($tenantinfo->tenant) {
        case 'solociclismocrc':
            $take = 6;
            break;

        default:
            $take = 4;
            break;
    }
@endphp
@section('content')
    <style>
        @media screen and (max-width: 768px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->mobile_image ?? $carousel->image) }}');
                }
            @endforeach
        }

        @media screen and (min-width: 769px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->image) }}');
                }
            @endforeach
        }

        .item-slick1 {
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
    </style>

    {{-- Carousel Start --}}
    @if (count($tenantcarousel) != 0)
        <div class="wrap-slick1">
            <div class="slick1">
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="item-slick1 bg-carousel-{{ $loop->index }}">

                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        {!! $carousel->text1 !!}
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        {{ $carousel->text2 }}
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . $carousel->url) }}"
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
        @if (count($category) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($category->take($take) as $key => $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span
                                                    class="ltext-102 trans-04 p-b-8 {{ isset($tenantinfo->tenant) && ($tenantinfo->tenant != 'aclimate' && $tenantinfo->tenant != 'solociclismocrc') ? 'block1-name' : 'block1-name-ac' }}">
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
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($departments as $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'category/' . $item->id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span class="block1-name ltext-102 trans-04 p-b-8">
                                                    {{ $item->department }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">

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
    <div class="container p-b-30">
        <div class="p-b-10">
            <h6 class="mtext-101 cl5 text-justify text-center p-b-10">
                Disfruta de la flexibilidad de nuestras tarjetas de regalo para canjear en cualquier
                producto.
            </h6>
        </div>
        <center>
            <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 text-center" type="button"
                data-toggle="modal" data-target="#gift-card-modal">Obtener
                Tarjeta
                de Regalo</button>
        </center>
    </div>
    <div class="container">
        <hr>
    </div>

    @include('frontend.modals.gift-card')
    <!-- Banner End-->
    <!-- Product -->
    <section class="bg0 p-t-15 p-b-140">
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
                <div class="dis-none panel-filter w-full p-t-10">
                    <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
                        @foreach ($attributes as $attribute)
                            @php
                                // Dividir los valores en chunks de 5
                                $chunks = $attribute->values->chunk(5);
                            @endphp

                            @foreach ($chunks as $chunk)
                                <div class="filter-col p-l-15 p-b-27">
                                    @if ($loop->first)
                                        <div class="mtext-102 cl2 p-b-15">
                                            {{ $attribute->name }}
                                        </div>
                                    @else
                                        <div class="mtext-102 cl2 p-b-15">
                                            &nbsp; {{-- espacio para mantener alineación visual --}}
                                        </div>
                                    @endif

                                    <ul>
                                        @foreach ($chunk as $value)
                                            <li class="p-b-6 p-r-50">
                                                <a href="#" class="filter-link stext-106 trans-04"
                                                    data-attr-id="{{ $attribute->id }}"
                                                    data-value-id="{{ $value->id }}">
                                                    {{ $value->value }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endforeach
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
                        <div class="block2 product_data" data-attributes-filter='@json(collect($item->atributos)->mapWithKeys(fn($a) => [$a->attr_id => explode('/', $a->ids)]))'>
                            <input type="hidden" class="code" name="code" value="{{ $item->code }}">
                            <input type="hidden" class="clothing-name" name="clothing-name" value="{{ $item->name }}">
                            <div class="block2-pic hov-img0">
                                <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="IMG-PRODUCT">

                                <a href="#"
                                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1"
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                    data-discount="{{ $item->discount }}" data-description="{!! $item->description !!}"
                                    data-price="{{ number_format($precioConDescuento, 2) }}"
                                    data-original-price="{{ number_format($item->price, 2) }}"
                                    data-attributes='@json($item->atributos)' data-category="{{ $item->category }}"
                                    data-images='@json(array_map(fn($img) => route($ruta, $img), $item->all_images))'
                                    data-image="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    Detallar
                                </a>
                            </div>
                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                        class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        ({{ $item->category }})
                                        {{ $item->name }}
                                    </a>
                                    @if ($tenantinfo->tenant == 'solociclismocrc' && $item->is_contra_pedido == 1)
                                        <p class="text-info font-weight-bold">Producto contrapedido</p>
                                    @endif
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
            {{-- <div class="flex-c-m flex-w w-full p-t-45">
                <a href="#" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Cargar Más
                </a>
            </div> --}}
        </div>
    </section>

    @include('layouts.inc.design_ecommerce.footer')
    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>
@endsection
@section('scripts')
    <script></script>
@endsection
