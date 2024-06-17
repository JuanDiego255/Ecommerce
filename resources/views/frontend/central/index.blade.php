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
                            style="background-image: url('{{ route('file', $carousel->image) }}');">
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
    {{-- Categories --}}
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($categories) != 0)
            <div class="row g-4 align-content-center card-group container-fluid mt-2">
                @foreach ($category as $key => $item)
                    <div class="{{ $key + 1 > 3 ? 'col-md-3' : 'col-md-4' }} col-sm-6 mb-2">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img
                                    src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                class="fas fa-eye"></i></a></li>
                                </ul>
                                <a href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                    class="add-to-cart">{{ $item->name }}</a>
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
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img
                                    src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
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
    {{-- profesional info --}}
    @if (isset($profesional_info))
        <div class="mt-5 bg-white">
            <div class="container pt-3 pb-5">
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img src="{{ route('file', $profesional_info->image) }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ route('file', $profesional_info->image) }}"><i
                                                class="fas fa-eye"></i></a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    </div>
                    <div class="col-md-8 mb-2">
                        <h1 class="text-title">{{ $profesional_info->name }}</h1>
                        {!! $profesional_info->body !!}
                        <a class="btn btn-icon btn-3 mt-2 btn-add_to_cart"
                            href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}">
                            <span class="btn-inner--icon"><i class="material-icons">calendar_month</i></span>
                            <span class="btn-inner--text">{{ __('Contactar') }}</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endif
    {{-- Insta --}}
    @if (isset($tenantinfo->show_insta) && $tenantinfo->show_insta == 1)
        <hr class="text-dark">
        <div class="row mb-3 container-fluid">
            <div class="text-center">
                <span
                    class="text-muted text-center">{{ isset($tenantinfo->title_instagram) ? $tenantinfo->title_instagram : '' }}</span>
            </div>
            @foreach ($social as $item)
                <div class="col-md-6 mt-4">
                    <div class="card text-center">
                        <div class="overflow-hidden position-relative bg-cover p-3"
                            style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}'); height:700px;  background-position: center;">
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
    {{-- blogs --}}
    @if (count($blogs) != 0)
        <hr class="dark horizontal text-danger my-0">
        <div class="mt-3 mb-5">
            <div class="container-fluid">
                <div class="text-center">
                    <h3 class="text-center text-muted mt-5 mb-3">Blog de
                        {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}, explora nuestras secciones, y aclara las
                        dudas acerca de nuestros servicios.</h3>
                </div>

                <div class="row">
                    <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-2 mb-5">
                        @foreach ($blogs as $item)
                            <div class="item">
                                <div class="product-grid product_data">
                                    <div class="product-image">
                                        <img
                                            src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">

                                        <ul class="product-links">
                                            <li><a target="blank"
                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                                        class="fas fa-eye"></i></a></li>
                                        </ul>
                                        <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="add-to-cart">Ver
                                            información</a>
                                    </div>
                                    <div class="product-content">

                                        <h3
                                            class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'title-frags' }}">
                                            <a
                                                href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    @endif
    {{-- Mision --}}
    @if (isset($tenantinfo->show_mision) && $tenantinfo->show_mision == 1)
        <hr class="text-dark">
        <div class="bg-footer p-3 mb-3 text-justify">
            <h3
                class="text-center {{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'text-title-mandi' : 'text-title' }} mt-3">
                {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h3>
            <span class="text-center text-muted">{{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}</span>


        </div>
    @endif
    {{-- Comentarios --}}
    @if (count($comments) != 0)
        <hr class="dark horizontal text-danger my-0">
        <div class="mt-3 mb-5">
            <div class="container">
                <div class="text-center">
                    <h3 class="text-center text-muted mt-5 mb-3">Testimonios de nuestros clientes</h3>
                </div>

                <div class="row">
                    <div class="owl-carousel featured-carousel owl-theme">
                        @foreach ($comments as $item)
                            <div class="item">
                                <div>
                                    <div class="card text-center">
                                        <img class="card-img-top"
                                            @if ($item->image) src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                        
                                    @else
                                    src="{{ url('images/sin-foto.PNG') }}" @endif
                                            src="{{ url('images/sin-foto.PNG') }}" alt="">
                                        <div class="card-body">
                                            <h5>{{ $item->name }}
                                            </h5>
                                            <div class="rated text-center">
                                                @for ($i = 1; $i <= $item->stars; $i++)
                                                    {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                    <label class="star-rating-complete"
                                                        title="text">{{ $i }}
                                                        stars</label>
                                                @endfor
                                            </div>
                                            <p class="card-text card-comment">“{{ $item->description }}” </p>
                                            <span class="show-more">Ver más</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('layouts.inc.main.indexfooter')
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

        document.addEventListener('DOMContentLoaded', function() {
            var showMoreButtons = document.querySelectorAll('.show-more');

            showMoreButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var cardText = button.previousElementSibling;
                    if (cardText.classList.contains('expanded')) {
                        cardText.classList.remove('expanded');
                        button.textContent = 'Ver más';
                    } else {
                        cardText.classList.add('expanded');
                        button.textContent = 'Ver menos';
                    }
                });
            });
        });
    </script>
@endsection
