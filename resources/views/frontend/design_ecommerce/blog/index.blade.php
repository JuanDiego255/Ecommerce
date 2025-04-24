@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-book me-1"></i>Blog</a></li>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($blogs as $item)
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img
                                src="{{ isset($item->image) ? route($ruta, $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                class="add-to-cart">{{ __('Ver Información') }}</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a
                                    href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>
            <div class="container mb-3">
                {{ $blogs ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div> --}}
    {{-- <div class="container m-t-70">
        <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg mt-5">
            <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Inicio
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Blog
            </span>
        </div>
    </div> --}}
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: {{$tenantinfo->tenant !== "aclimate" ? "url('/design_ecommerce/images/bg-02.jpg');" : "url('/design_ecommerce/images/blog-ac.png');"}}">
        <h2 class="ltext-105 cl0 txt-center">
            Blog
        </h2>
    </section>


    <!-- Content page -->
    <section class="bg0 p-t-62 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-9 p-b-80">
                    <div class="p-r-45 p-r-0-lg">
                        <!-- item blog -->
                        @foreach ($blogs as $item)
                            <div class="p-b-63">
                                <a href="blog-detail.html" class="hov-img0 how-pos5-parent">
                                    <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                        alt="IMG-BLOG">

                                    @php
                                        $fecha = \Carbon\Carbon::parse($item->fecha_post);
                                    @endphp

                                    <div class="flex-col-c-m size-123 bg9 how-pos5">
                                        <span class="ltext-107 cl2 txt-center">
                                            {{ $fecha->format('d') }}
                                        </span>

                                        <span class="stext-109 cl3 txt-center">
                                            {{ $fecha->translatedFormat('F Y') }}
                                        </span>
                                    </div>
                                </a>

                                <div class="p-t-32">
                                    <h4 class="p-b-15">
                                        <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="ltext-108 cl2 hov-cl1 trans-04">
                                            {{ $item->title }}
                                        </a>
                                    </h4>

                                    <p class="stext-117 cl6">
                                        {{ $item->title_optional }}
                                    </p>

                                    <div class="flex-w flex-sb-m p-t-18">
                                        <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                                            <span>
                                                <span class="cl4">Publicado por:</span> {{ $item->autor }}
                                                <span class="cl12 m-l-4 m-r-6">|</span>
                                            </span>

                                            <span>
                                                {{ $tenantinfo->title }}
                                                <span class="cl12 m-l-4 m-r-6"></span>
                                            </span>
                                        </span>

                                        <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}" class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                                            Leer más

                                            <i class="fa fa-long-arrow-right m-l-9"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- Pagination -->
                     {{--    <div class="flex-l-m flex-w w-full p-t-10 m-lr--7">
                            <a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7 active-pagination1">
                                1
                            </a>

                            <a href="#" class="flex-c-m how-pagination1 trans-04 m-all-7">
                                2
                            </a>
                        </div> --}}
                    </div>
                </div>

                <div class="col-md-4 col-lg-3 p-b-80">
                    <div class="side-menu">
                        {{--  <div class="bor17 of-hidden pos-relative">
                            <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search"
                                placeholder="Search">

                            <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                                <i class="zmdi zmdi-search"></i>
                            </button>
                        </div> --}}

                        {{--    <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Categories
                            </h4>

                            <ul>
                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Fashion
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Beauty
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Street Style
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Life Style
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        DIY & Crafts
                                    </a>
                                </li>
                            </ul>
                        </div> --}}

                        <div class="p-t-0">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Productos que pueden interesarte
                            </h4>

                            <ul>
                                @foreach ($clothings->take(4) as $item)
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

                                    <li class="flex-w flex-t p-b-30">
                                        <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}" class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                                            <img class="img-min" src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                alt="PRODUCT">
                                        </a>

                                        <div class="size-215 flex-col-t p-t-8">
                                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}" class="stext-116 cl8 hov-cl1 trans-04">
                                                {{ $item->name }}
                                            </a>

                                            <span class="stext-116 cl6 p-t-20">
                                                <div class="price">₡{{ number_format($precioConDescuento) }}
                                                    @if ($item->discount)
                                                        <s class="text-danger">
                                                            ₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                                        </s>
                                                    @endif
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-20">
                                Archive
                            </h4>

                            <ul>
                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            July 2018
                                        </span>

                                        <span>
                                            (9)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            June 2018
                                        </span>

                                        <span>
                                            (39)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            May 2018
                                        </span>

                                        <span>
                                            (29)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            April 2018
                                        </span>

                                        <span>
                                            (35)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            March 2018
                                        </span>

                                        <span>
                                            (22)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            February 2018
                                        </span>

                                        <span>
                                            (32)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            January 2018
                                        </span>

                                        <span>
                                            (21)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            December 2017
                                        </span>

                                        <span>
                                            (26)
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div> --}}

                        {{--  <div class="p-t-50">
                            <h4 class="mtext-112 cl2 p-b-27">
                                Palabras Destacadas
                            </h4>

                            <div class="flex-w m-r--5">
                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Fashion
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Lifestyle
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Denim
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Streetstyle
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Crafts
                                </a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
