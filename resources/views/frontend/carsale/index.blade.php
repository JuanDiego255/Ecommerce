@extends('layouts.frontrent')
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
    {{-- Main Banner --}}
    <div class="hero-wrap ftco-degree-bg"
        style="background-image: url('{{ isset($tenantcarousel[0]->image) ? route('file', $tenantcarousel[0]->image) : url('images/producto-sin-imagen.PNG') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">
                            {{ isset($tenantcarousel[0]->text1) ? $tenantcarousel[0]->text1 : '' }}</h1>
                        <p style="font-size: 18px;">
                            {{ isset($tenantcarousel[0]->text2) ? $tenantcarousel[0]->text2 : '' }}</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Contact form --}}
    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-12	featured-top">
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center">
                            
                            <form class="request-form ftco-animate bg-primary" action="{{ url('send-email/blog') }}" method="POST" enctype="multipart/form-data">
                                <h2>¿Tienes alguna duda?, ¡contáctanos!</h2>
                                @csrf
                                <div class="form-group">
                                    <label for="" class="label">Nombre</label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="Nombre completo">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">Teléfono</label>
                                    <input type="text" class="form-control" name="telephone" required
                                        placeholder="Número de teléfono">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">E-mail</label>
                                    <input type="text" class="form-control" name="email" required
                                        placeholder="Correo electrónico">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">Consulta</label>
                                    <input type="text" class="form-control" name="question" required
                                        placeholder="¿Cuál es tu duda?">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Enviar formulario" class="btn btn-secondary py-3 px-4">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="services-wrap rounded-right w-100">
                                <h3 class="heading-section mb-4">En autos Grecia contamos con los mejores precios</h3>
                                <div class="row d-flex mb-4">
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-route"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Ubicación estratégica</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-handshake"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Trámite rápido y sencillo</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-rent"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Te ayudamos a escoger el carro ideal</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p><a href="#" class="btn btn-primary py-3 px-4">Financiar vehículo</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    {{-- Trending --}}
    @if (isset($tenantinfo->show_trending) && $tenantinfo->show_trending == 1)
        <section class="ftco-section ftco-no-pt bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                        <span class="subheading">Navega en nuestro sitio web</span>
                        <h2 class="mb-2">Vehículos en tendencia</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="carousel-car owl-carousel">
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
                                    // Calcular el descuento
                                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                                    // Calcular el precio con el descuento aplicado
                                    $precioConDescuento = $precio - $descuento;
                                @endphp
                                <div class="item">
                                    <div class="car-wrap rounded ftco-animate">
                                        <div class="img rounded d-flex align-items-end"
                                            style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');">
                                        </div>
                                        <div class="text">
                                            <h2 class="mb-0"><a href="#">{{ $item->name }}</a></h2>
                                            <div class="d-flex mb-3">
                                                <span class="cat">Tendencia</span>
                                                <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p>
                                            </div>
                                            <p class="d-flex mb-0 d-block"><a
                                                    href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                                    class="btn btn-secondary py-2 ml-1">Detallar</a></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- About Us --}}
    @if (isset($tenantinfo->about) && $tenantinfo->about != '')
        <section class="ftco-section ftco-about" id="about_us">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-6 p-md-5 img img-2 d-flex justify-content-center align-items-center"
                        style="background-image: url(car-styles/images/about.jpg);">
                    </div>
                    <div class="col-md-6 wrap-about ftco-animate">
                        <div class="heading-section heading-section-white pl-md-5">
                            <span class="subheading">Nosotros</span>
                            <h2 class="mb-4">Bienvenido a {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h2>

                            <p class="text-justify">{{ $tenantinfo->about }}</p>
                            <p><a href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                                    class="btn btn-primary py-3 px-4">Agendar cita</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- Services --}}
   {{--  <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Services</span>
                    <h2 class="mb-3">Our Latest Services</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-wedding-car"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Wedding Ceremony</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-transportation"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">City Transfer</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-car"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Airport Transfer</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="services services-2 w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span
                                class="flaticon-transportation"></span></div>
                        <div class="text w-100">
                            <h3 class="heading mb-2">Whole City Tour</h3>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    {{--     <section class="ftco-section ftco-intro" style="background-image: url(images/bg_3.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-6 heading-section heading-section-white ftco-animate">
                    <h2 class="mb-3">Do You Want To Earn With Us? So Don't Be Late.</h2>
                    <a href="#" class="btn btn-primary btn-lg">Become A Driver</a>
                </div>
            </div>
        </div>
    </section> --}}
    {{-- Comments --}}
    @if (count($comments) != 0)
        <hr class="dark horizontal text-danger my-0">
        <section class="ftco-section testimony-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section ftco-animate">
                        <span class="subheading">Testimonios de nuestros clientes</span>
                        <h2 class="mb-3">Clientes satisfechos</h2>
                    </div>
                </div>
                <div class="row ftco-animate">
                    <div class="col-md-12">
                        <div class="carousel-testimony owl-carousel ftco-owl">
                            @foreach ($comments as $item)
                                <div class="item">
                                    <div class="testimony-wrap rounded text-center py-4 pb-5">
                                        <div class="user-img mb-2"
                                            style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}')">
                                        </div>
                                        <div class="text pt-4">
                                            <p class="name">{{ $item->name }}</p>
                                            <div class="rated text-center">
                                                @for ($i = 1; $i <= $item->stars; $i++)
                                                    {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                    <label class="star-rating-complete"
                                                        title="text">{{ $i }}
                                                        stars</label>
                                                @endfor
                                            </div>
                                            <p class="card-text card-comment">“{{ $item->description }}”</p>
                                            <span class="show-more">Ver más</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- blogs --}}
    @if (count($blogs) != 0)
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <span class="subheading">Blog de
                            {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}, explora nuestras secciones, y aclara
                            las
                            dudas acerca de nuestros servicios.</span>
                    </div>
                </div>
                <div class="row d-flex">
                    @foreach ($blogs as $item)
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="blog-entry justify-content-end">
                                <a href="blog-single.html" class="block-20"
                                    style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');">
                                </a>
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
    {{-- Counter --}}
    <section class="ftco-counter ftco-section img bg-light" id="section-counter">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="40">0</strong>
                            <span>Años de <br>Experiencia</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="{{$car_count}}">0</strong>
                            <span>Vehículos <br>Disponibles</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="{{$comment_count}}">0</strong>
                            <span>Clientes <br>Satisfechos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.inc.carsale.footer')
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
