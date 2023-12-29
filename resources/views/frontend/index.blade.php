@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner mb-4 foto">

            <div class="carousel-item">
                <div class="page-header min-vh-75 m-3" style="background-image: url('images/carousel2.PNG');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 my-auto">
                                <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Para todas las chicas.</h4>
                                <h1 class="text-white fadeIn2 fadeInBottom">Gran Variedad De Estilos</h1>
                                <p class="lead text-white opacity-8 fadeIn3 fadeInBottom">No dudes en contactarnos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item active">
                <div class="page-header min-vh-75 m-3" style="background-image: url('images/carousel1.PNG');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 my-auto">
                                <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Somos Un Nuevo Emprendimiento.</h4>
                                <h1 class="text-white fadeIn2 fadeInBottom">Velvet Boutique</h1>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <hr class="text-dark">
    <div class="text-center">
        <h3 class="text-center text-dark mt-5">Instragram <a class="text-title"
                href="https://www.instagram.com/velvetboutiquegrecia/">Velvet
                Boutique.</a></h3>

        <span class="text-muted text-center">Compartimos con ustedes lo que nos hace sentir mejor, un excelente
            outfit!</span>
    </div>


    <hr class="dark horizontal text-danger mb-3">

    <div class="row mb-5 container-fluid">
        @foreach ($social as $item)
            <div class="col-md-6 mt-4">
                <div class="card text-center">
                    <div class="overflow-hidden position-relative bg-cover p-3"
                        style="background-image: url('{{ asset('storage') . '/' . $item->image }}'); height:700px;">
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

    <div class="bg-velvet-mision p-3 mb-3 text-center">
        <h3 class="text-center text-title mt-3">Velvet Boutique</h3>
        <span class="text-center text-muted">Nuestra misión es envolverte con nuestros atuendos, y hacerlos llegar a la
            puerta de tu casa.<br>Realizamos envios a nivel nacional.</span>


    </div>
    {{-- <div class="col-md-12 mt-5 mb-5">
        <div class="card text-center">
            <div class="overflow-hidden position-relative bg-cover p-3"
                style="background-image: url('images/playa.PNG'); height:500px;">
                <span class="mask bg-gradient-dark opacity-6"></span>
               
            </div>
        </div>
    </div> --}}

    <hr class="dark horizontal text-danger mb-3">
    <div class="text-center">
        <h3 class="text-center text-dark mt-5">Outfit`s En Tendencia</h3>
        <span class="text-muted">Nuestras últimas colecciones, los artículos más solicitados!</span>
    </div>

    <hr class="dark horizontal text-danger my-0">

    <div class="mt-3 mb-5">
        <div class="container">
            <div class="row">
                <div class="owl-carousel featured-carousel owl-theme">
                    @foreach ($clothings as $clothing)
                        @if ($clothing->total_stock != 0)
                            <div class="item">
                                <div class="card" data-animation="true">

                                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                        <a target="blank" data-fancybox="gallery"
                                            href="{{ asset('storage') . '/' . $clothing->image }}"
                                            class="d-block blur-shadow-image">
                                            <img src="{{ asset('storage') . '/' . $clothing->image }}" alt="img-blur-shadow"
                                                class="img-fluid shadow border-radius-lg w-100" style="height:300px;">
                                        </a>
                                        <div class="colored-shadow"
                                            style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                                        </div>
                                    </div>
                                    <div class="card-body text-center">

                                        <h5 class="font-weight-normal mt-3">
                                            <a
                                                href="{{ url('detail-clothing/' . $clothing->id . '/' . $clothing->category_id) }}">{{ $clothing->name }}</a>
                                        </h5>
                                        <p class="mb-0">
                                            {{ $clothing->description }}
                                        </p>
                                    </div>
                                    <hr class="dark horizontal my-0">
                                    <div class="card-footer d-flex">
                                        <p class="font-weight-normal my-auto">Precio: ₡{{ number_format($clothing->price) }}
                                        </p>


                                        <i
                                            class="material-icons position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                                        <p class="text-sm my-auto"> Stock: {{ $clothing->total_stock }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

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
