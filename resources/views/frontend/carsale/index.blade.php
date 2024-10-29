@extends('layouts.frontrent')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')

    {{-- Main Banner --}}
    <!-- Incluye la versión más reciente de Bootstrap 4 o 5 si no está ya incluida -->


    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            @if (isset($tenantcarousel) && count($tenantcarousel) > 0)
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ route('file', $carousel->image) }}" class="w-100 h-100"
                        alt="carousel image">
                        <div class="carousel-caption">
                            <h1>{{ $carousel->text1 ?? '' }}</h1>
                            <p style="font-size: 18px;">{{ $carousel->text2 ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <div class="carousel-background"
                        style="background-image: url('{{ asset('images/producto-sin-imagen.PNG') }}'); background-size: cover; background-position: center; height: 600px;">
                    </div>
                    <div class="carousel-caption">
                        <h1>No hay contenido disponible</h1>
                        <p style="font-size: 18px;">Agregue contenido para el carrusel</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Controles de navegación -->
        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>


    <!-- Botón flotante de WhatsApp -->
    <div class="whatsapp-button" id="whatsappButton">
        <span class="whatsapp-label">¡Contáctanos!</span> <!-- Etiqueta -->
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="40"
            height="40">
    </div>

    {{-- Contact form --}}
    <section class="ftco-section ftco-no-pt bg-light mt-large-mobile">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-12	featured-top">
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center">
                            <form class="request-form ftco-animate bg-primary" action="{{ url('send-email/blog') }}"
                                method="POST" enctype="multipart/form-data">
                                <h2>¿Tienes alguna duda? ¡Contáctanos!</h2>
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
                                <h3 class="heading-section mb-4">En AUTOS GRECIA contamos con los mejores precios</h3>
                                <div class="row d-flex mb-4">
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-route"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Ubicación estratégica</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-handshake"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Trámite rápido y sencillo</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-car"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Te ayudamos a escoger el carro ideal</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <a href="{{ url('compare/vehicles') }}" class="btn btn-primary py-3 px-4 align-service-center">Comparar
                                        vehículos</a>
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
                <!-- Header -->
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center ftco-animate mb-1">
                        <h3 class="mb-2 title align-text-center">Descubre todos nuestros vehículos</h3>
                    </div>
                </div>

                <!-- Categorías como tabs -->
                <div class="d-flex justify-content-center">
                    <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                        @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $categoryId }}-tab"
                                    data-toggle="pill" href="#pills-{{ $categoryId }}" role="tab"
                                    aria-controls="pills-{{ $categoryId }}">
                                    {{ $vehicles->first()->category }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <hr>

                <div class="tab-content" id="pills-tabContent">
                    @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="pills-{{ $categoryId }}" role="tabpanel"
                            aria-labelledby="pills-{{ $categoryId }}-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="carousel-car owl-carousel">
                                        @foreach ($vehicles as $item)
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
            
                                            <div class="item">
            
                                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">
                                                    <div class="car-wrap rounded ftco-animate">
                                                        <div class="img rounded d-flex align-items-end"
                                                            style="background-image: url('{{ isset($item->main_image) ? route('file', $item->main_image) : url('images/producto-sin-imagen.PNG') }}');">
                                                        </div>
                                                        <div class="text">
                                                            @if ($item->created_at->diffInDays(now()) <= 7)
                                                                <span class="badge badge-pill ml-2 badge-date text-white animacion"
                                                                    id="comparison-count">
                                                                    Nuevo Ingreso
                                                                </span>
                                                            @endif
                                                            <h2 class="mb-0">
                                                                <a href="#">
                                                                    {{ $item->name . ' (' . $item->model . ')' }}
            
                                                                </a>
                                                            </h2>
            
                                                            <div class="d-flex mb-3">
            
                                                                <!-- <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p> -->
                                                            </div>
                                                            <span class="line"><span>Tendencia</span></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                                                    <label class="star-rating-complete"
                                                        title="text">{{ $i }} stars</label>
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
                        <span class="subheading">Blog de {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }},
                            explora nuestras secciones, y aclara las dudas acerca de nuestros servicios.</span>
                    </div>
                </div>
                {{-- Condición para centrado si hay dos o menos blogs --}}
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
                            <strong class="number" data-number="{{ $car_count }}">0</strong>
                            <span>Vehículos <br>Disponibles</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="{{ $comment_count }}">0</strong>
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
    <script></script>

    <script>
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
