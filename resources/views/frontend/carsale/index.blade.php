@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <style>
        .cascading-right {
            margin-right: -50px;
        }

        @media (max-width: 991.98px) {
            .cascading-right {
                margin-right: 0;
            }
        }
    </style>
    @if (count($tenantcarousel) != 0)
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner mb-4 foto">
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="page-header min-vh-75 m-3"
                            style="background-image: url('{{ tenant_asset('/') . '/' . $carousel->image }}');">
                            <span class="mask bg-gradient-dark"></span>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-6 my-auto">
                                        <h4 class="text-white mb-0 fadeIn1 fadeInBottom">{{ $carousel->text1 }}</h4>
                                        <h1 class="text-white fadeIn2 fadeInBottom">{{ $carousel->text2 }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="min-vh-75 position-absolute w-100 top-0">
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon position-absolute bottom-50" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon position-absolute bottom-50" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>
        </div>
    @endif
    {{-- Trending --}}
    @if (isset($tenantinfo->show_trending) && $tenantinfo->show_trending == 1)
        <div class="mt-3 mb-5">
            <div class="container-fluid">
                @if (count($clothings) != 0)
                    <div class="text-center">
                        <h3 class="text-center text-muted mt-5 mb-3">
                            {{ isset($tenantinfo->title_trend) ? $tenantinfo->title_trend : '' }}</h3>
                    </div>
                @endif
                <div class="row">
                    <div class="owl-carousel featured-carousel owl-theme">
                        @foreach ($clothings as $item)
                            @if ($item->total_stock != 0)
                                <div class="item">
                                    <div class="product-grid product_data">
                                        <div class="product-image">
                                            <img src="{{ route('file', $item->image) }}">
                                            @if ($item->discount)
                                                <span class="product-discount-label">-{{ $item->discount }}%</span>
                                            @endif
                                            <ul class="product-links">
                                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                            class="fas fa-eye"></i></a></li>
                                            </ul>
                                            <a href="{{ url('detail-car/' . $item->id . '/' . $item->category_id) }}"
                                                class="add-to-cart">Detallar</a>
                                        </div>
                                        <div class="product-content">
                                            <h3
                                                class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                                {{ $item->casa }}
                                            </h3>
                                            <h3
                                                class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'title' : 'title-frags' }}">
                                                <a
                                                    href="{{ url('detail-car/' . $item->id . '/' . $item->category_id) }}">{{ $item->name }}</a>
                                            </h3>
                                            @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                                <h4 class="title">Stock: {{ $item->total_stock }}</h4>
                                            @endif

                                            @php

                                                $precio = $item->price;
                                                if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
                                                    $precio = $item->first_price;
                                                }
                                                if (
                                                    Auth::check() &&
                                                    Auth::user()->mayor == '1' &&
                                                    $item->mayor_price > 0
                                                ) {
                                                    $precio = $item->mayor_price;
                                                }
                                                $descuentoPorcentaje = $item->discount;
                                                // Calcular el descuento
                                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                // Calcular el precio con el descuento aplicado
                                                $precioConDescuento = $precio - $descuento;
                                            @endphp
                                            <div class="price">₡{{ number_format($precioConDescuento) }}
                                                @if ($item->discount)
                                                    <s class="text-danger"><span
                                                            class="text-danger">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                                        </span></s>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Insta --}}
    <hr class="text-dark">
    @if (isset($tenantinfo->show_insta) && $tenantinfo->show_insta == 1)
        <div class="text-center">
            <span class="text-muted text-center"><a href="{{ isset($instagram) ? $instagram : '' }}">Instagram</a> |
                {{ isset($tenantinfo->title_instagram) ? $tenantinfo->title_instagram : '' }}</span>
        </div>
        <div class="row mb-5 container-fluid">
            @foreach ($social as $item)
                <div class="col-md-6 mt-4">
                    <div class="card text-center">
                        <div class="overflow-hidden position-relative bg-cover p-3"
                            style="background-image: url('{{ route('file', $item->image) }}'); height:700px;  background-position: center;">
                            <span class="mask bg-gradient-dark opacity-6"></span>
                            <div class="card-body position-relative z-index-1 d-flex flex-column mt-5">
                                <h3 class="text-white">{{ $item->description }}.</h3>
                                <a target="blank" class="text-white text-sm mb-0 icon-move-right mt-4"
                                    href="{{ $item->url }}">
                                    <h3 class="text-white"> Ver fotografía
                                        <i class="material-icons text-sm ms-1 position-relative"
                                            aria-hidden="true">arrow_forward</i>
                                    </h3>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif

    @if (isset($tenantinfo->show_mision) && $tenantinfo->show_mision == 1)
        <div class="bg-footer p-3 mb-3 text-center">
            <h3
                class="text-center {{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'text-title-mandi' : 'text-title' }} mt-3">
                {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h3>
            <span class="text-center text-muted">{{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}</span>


        </div>
    @endif

    {{-- Categories --}}
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($categories) != 0)
            <div class="text-center">
                <span class="text-muted text-center">Explora nuestras secciones! Navega y encuentra todo lo que desees. <a
                        href="{{ url('category') }}">(Más secciones)</a></span>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group container-fluid mt-2 mb-5">
                @foreach ($category as $item)
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img src="{{ route('file', $item->image) }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                class="fas fa-eye"></i></a></li>
                                </ul>
                                <a href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                    class="add-to-cart">Descubrir
                                    estilos</a>
                            </div>
                            <div class="product-content">
                                <h3 class="title"><a
                                        href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}">{{ $item->name }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        @if (count($departments) != 0)
            <div class="text-center">
                <span class="text-muted text-center">Explora nuestros departamentos! Navega y encuentra todo lo que
                    desees.</span>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group container-fluid mt-2 mb-5">
                @foreach ($departments as $item)
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img src="{{ route('file', $item->image) }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                class="fas fa-eye"></i></a></li>
                                </ul>
                                <a href="{{ url('category/' . $item->id) }}" class="add-to-cart">Categorías</a>
                            </div>
                            <div class="product-content">
                                <h3 class="title"><a
                                        href="{{ url('category/' . $item->id) }}">{{ $item->department }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    @if (isset($tenantinfo->show_trending) && $tenantinfo->show_trending == 1)
        <div class="mt-3 mb-5" id="best-car">
            <div class="container-fluid">
                @if (count($clothings) != 0)
                    <div class="text-center">
                        <h3 class="text-center text-muted mt-5 mb-3">
                            {{ isset($tenantinfo->title_trend) ? $tenantinfo->title_trend : '' }}</h3>
                    </div>
                @endif

                @foreach ($clothings as $item)
                    <!-- Jumbotron -->
                    <div class="container py-4">
                        <div class="row g-0 align-items-center">
                            <div class="col-lg-6 mb-5 mb-lg-0">
                                <div class="card cascading-right card-login">
                                    <div class="card-body p-5 shadow-5">
                                        <h4 class="fw-bold mb-1">Detalles del vehículo</h4>
                                        <div class="card-body">
                                            <h4
                                                class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                                {{ $item->casa }}
                                            </h4>
                                            <h4 class="title text-dark">
                                                {{ $item->name }}
                                            </h4>

                                            <div class="mb-1">

                                                @php
                                                    $precio = $item->price;
                                                    if (
                                                        isset($tenantinfo->custom_size) &&
                                                        $tenantinfo->custom_size == 1
                                                    ) {
                                                        $precio = $item->first_price;
                                                    }
                                                    if (
                                                        Auth::check() &&
                                                        Auth::user()->mayor == '1' &&
                                                        $item->mayor_price > 0
                                                    ) {
                                                        $precio = $item->mayor_price;
                                                    }
                                                    $descuentoPorcentaje = $item->discount;
                                                    // Calcular el descuento
                                                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                    // Calcular el precio con el descuento aplicado
                                                    $precioConDescuento = $precio - $descuento;
                                                @endphp

                                            </div>

                                            <p class="text text-justify">
                                                {!! $item->description !!}
                                            </p>

                                            <a href="{{ url('detail-car/' . $item->id . '/' . $item->category_id) }}"
                                                class="btn btn-add_to_cart shadow-0 btnAddToCart"> <i
                                                    class="me-1 fas fa-eye"></i>Detallar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-5 mb-lg-0">
                                <img src="{{ route('file', isset($item->image) ? $item->image : '') }}"
                                    class="w-100 rounded-4 shadow-4" alt="" />
                            </div>
                        </div>
                    </div>
                    <!-- Jumbotron -->
                @break;
            @endforeach
        </div>
    </div>
@endif

<hr class="dark horizontal text-danger my-0">


@include('layouts.inc.indexfooter')
@endsection
@section('scripts')
<script src="{{ asset('js/image-error-handler.js') }}"></script>
<script>
    $('.featured-carousel').owlCarousel({
        loop: true,
        margin: 10,

        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
</script>
@endsection
