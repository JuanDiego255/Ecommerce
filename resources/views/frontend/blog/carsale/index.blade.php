@extends('layouts.frontrent')
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
                                src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
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
    {{-- blogs --}}
    {{-- Main Banner --}}
    <div class="hero-wrap ftco-degree-bg" style="background-image: url('{{ url('images/main-banner.jpg') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <!-- Titulo Banner Blog Autos Grecia -->
                        <h1 class="mb-4"></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Skeleton loader HTML para un blog -->
    <div class="skeleton-loader">
        <div class="skeleton-img"></div>
        <div class="skeleton-text">
            <div class="skeleton-line short"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-line"></div>
            <div class="skeleton-btn"></div>
        </div>
    </div>

    @if (count($blogs) != 0)
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <span class="subheading">Explora nuestros artículos, conoce más acerca de tu auto</span>
                    </div>
                </div>
                <div class="row d-flex ">
                    @foreach ($blogs as $item)
                        <div class="col-md-4 d-flex ftco-animate card shadow-sm mr-3 mb-5 ml-3 card-blog">
                            <div class="blog-entry justify-content-end">
                                <a href="blog-single.html" class="block-20"
                                    style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');"></a>
                                <div class="text pt-4">
                                    <div class="meta mb-3">
                                        <div><a href="#">{{ $item->fecha_post }}</a></div>
                                        <div><a href="#">{{ $item->autor }}</a></div>
                                    </div>
                                    <h3 class="heading mt-2"><a
                                            href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                                    </h3>
                                    <p><a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="btn btn-primary">Leer más</a></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @include('layouts.inc.carsale.footer')
@endsection
