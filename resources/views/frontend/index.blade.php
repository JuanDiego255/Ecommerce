@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
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
                                        <img src="{{ route('file',$item->image) }}">
                                        @if ($item->discount)
                                            <span class="product-discount-label">-{{ $item->discount }}%</span>
                                        @endif
                                        <ul class="product-links">
                                            <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                        class="fas fa-eye"></i></a></li>
                                        </ul>
                                        <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                            class="add-to-cart">Detallar</a>
                                    </div>
                                    <div class="product-content">

                                        <h3 class="title"><a
                                                href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">{{ $item->name }}</a>
                                        </h3>
                                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'mandicr')
                                            <h4 class="title">Stock: {{ $item->total_stock }}</h4>
                                        @endif

                                        @php
                                            $precio = $item->price;
                                            $descuentoPorcentaje = $item->discount;
                                            // Calcular el descuento
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            // Calcular el precio con el descuento aplicado
                                            $precioConDescuento = $precio - $descuento;
                                        @endphp
                                        <div class="price">₡{{ number_format($precioConDescuento) }}
                                            @if ($item->discount)
                                                <s class="text-danger"><span
                                                        class="text-danger">₡{{ number_format($item->price) }}
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

    <hr class="dark horizontal text-danger mb-3">
    {{-- Offer --}}
    @if (count($clothings_offer) != 0)
        <div class="container-fluid mb-5 offer">
            <div class="text-center">
                <h3 class="text-center text-muted mt-5 mb-4">
                    {{ isset($tenantinfo->title_discount) ? $tenantinfo->title_discount : '' }}</h3>
            </div>

            <div class="row">
                @foreach ($clothings_offer as $item)
                    @php
                        $cant_img = 0;
                    @endphp
                    <div class="col-md-3 col-sm-6">
                        <div class="product-grid-offer">
                            <div class="product-image-offer">
                                <a href="#" class="image-offer">
                                    @if (!empty($item->images))
                                        @foreach ($item->images as $index => $image)
                                            @php
                                                $cant_img++;
                                            @endphp
                                            <img class="pic-{{ $index + 1 }}"
                                                src="{{ route('file',$image) }}">
                                        @endforeach
                                        @if ($cant_img == 1)
                                            <img class="pic-2" src="{{ route('file',$image) }}">
                                        @endif
                                    @endif
                                </a>
                                <span class="product-hot-label">-{{ $item->discount }}%</span>
                                <ul class="product-links-offer">
                                    <li><a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                            data-tip="Detallar"><i class="fa fa-eye"></i></a></li>

                                </ul>
                            </div>
                            <div class="product-content-offer">
                                <a class="add-to-cart" href="{{ url('clothes-category/' . $item->category_id) }}">
                                    <i class="fas fa-plus"></i>Más ofertas
                                </a>
                                <h3 class="title"><a
                                        href="{{ url('clothes-category/' . $item->category_id) }}">{{ $item->name }}</a>
                                </h3>
                                @php
                                    $precio = $item->price;
                                    $descuentoPorcentaje = $item->discount;
                                    // Calcular el descuento
                                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                                    // Calcular el precio con el descuento aplicado
                                    $precioConDescuento = $precio - $descuento;
                                @endphp
                                <div class="price">₡{{ number_format($precioConDescuento) }}
                                    @if ($item->discount)
                                        <s class="text-danger"><span class="text-danger">₡{{ number_format($item->price) }}
                                            </span></s>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <hr class="dark horizontal text-danger mb-3">
    @endif
    {{-- Insta --}}
    <hr class="text-dark">
    @if (count($social) != 0)
        <div class="text-center">
            <span class="text-muted text-center"><a href="{{ isset($instagram) ? $instagram : '' }}">Instagram</a> |
                {{ isset($tenantinfo->title_instagram) ? $tenantinfo->title_instagram : '' }}</span>
        </div>
    @endif
    <div class="row mb-5 container-fluid">
        @foreach ($social as $item)
            <div class="col-md-6 mt-4">
                <div class="card text-center">
                    <div class="overflow-hidden position-relative bg-cover p-3"
                        style="background-image: url('{{ route('file',$item->image) }}'); height:700px;  background-position: center;">
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

    <div class="bg-footer p-3 mb-3 text-center">
        <h3 class="text-center {{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'text-title-mandi' : 'text-title' }} mt-3">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h3>
        <span class="text-center text-muted">{{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}</span>


    </div>
    {{-- <div class="col-md-12 mt-5 mb-5">
        <div class="card text-center">
            <div class="overflow-hidden position-relative bg-cover p-3"
                style="background-image: url('images/playa.PNG'); height:500px;">
                <span class="mask bg-gradient-dark opacity-6"></span>
               
            </div>
        </div>
    </div> --}}



    <hr class="dark horizontal text-danger my-0">


    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
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
