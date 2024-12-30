@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <input type="hidden" value="{{ $showModal }}" name="showModalComment" id="showModalComment">
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if ($category_black_friday)
            <a
                href="{{ url('clothes-category/' . $category_black_friday->category_id . '/' . $category_black_friday->department_id) }}">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner mb-1 foto">
                        <div class="carousel-item active">
                            <div class="page-header min-vh-75 m-3"
                                style="background-image: url('{{ tenant_asset('/') . '/' . $category_black_friday->image }}');">
                                <span class="bg-gradient-dark"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
    @else
        @if ($department_black_friday && $category_black_friday)
            <a
                href="{{ url('clothes-category/' . $category_black_friday->category_id . '/' . $department_black_friday->id) }}">
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner mb-1 foto">
                        <div class="carousel-item active">
                            <div class="page-header min-vh-75 m-3"
                                style="background-image: url('{{ tenant_asset('/') . '/' . $department_black_friday->image }}');">
                                <span class="bg-gradient-dark"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endif
    @endif

    @if (count($tenantcarousel) != 0)
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner mb-1 foto">
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
    {{-- Categories --}}
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($categories) != 0)
            <div class="row g-4 align-content-center card-group container-fluid mt-2 mb-5 foto">
                @foreach ($category as $key => $item)
                    @if ($item->black_friday != 1)
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
                    @endif
                @endforeach
            </div>
        @endif
    @else
        @if (count($departments) != 0)
            <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group container-fluid mt-2 mb-5">
                @foreach ($departments as $item)
                    @if ($item->black_friday != 1)
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="product-grid product_data">
                                <div class="product-image">
                                    <img
                                        src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    <ul class="product-links">
                                        <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                    class="fas fa-eye"></i></a></li>
                                    </ul>
                                    <a href="{{ url('category/' . $item->id) }}" class="add-to-cart">Categorías -
                                        {{ $item->department }}</a>
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @endif
    {{-- Gift Cards --}}
    <div class="gift-card">
        <h1>Disfruta de la flexibilidad de nuestras tarjetas de regalo para canjear en cualquier producto.</h1>
        <button class="gift-button" type="button" data-bs-toggle="modal" data-bs-target="#gift-card-modal">Obtener Tarjeta
            de Regalo</button>
    </div>
    @include('frontend.modals.gift-card')
    {{-- Trending --}}
    @if (isset($tenantinfo->show_trending) && $tenantinfo->show_trending == 1)
        <div class="mt-3 mb-5 animado">
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
                            <div class="item">
                                <div class="product-grid product_data">
                                    <div class="product-image">
                                        <img
                                            src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                        @if ($item->discount)
                                            <span class="product-discount-label">-{{ $item->discount }}%</span>
                                        @endif
                                        <ul class="product-links">
                                            <li><a target="blank"
                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                                        class="fas fa-eye"></i></a></li>
                                        </ul>
                                        <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                            class="add-to-cart">Detallar</a>
                                    </div>
                                    <div class="product-content">
                                        <h3
                                            class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                            {{ $item->casa }}
                                        </h3>
                                        <h3 class="title clothing-name"><a href="#">({{ $item->category }})</a>
                                        </h3>
                                        <h3
                                            class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'title-frags' }}">
                                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">{{ $item->name }}
                                                @if ($item->total_stock == 0)
                                                    <s class="text-danger"> Agotado</s>
                                                @endif
                                            </a>
                                        </h3>

                                        @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                            <h4 class="title">
                                                Stock:
                                                @if ($item->total_stock > 0)
                                                    {{ $item->total_stock }}
                                                @elseif ($item->total_stock == 0)
                                                    <s class="text-danger">0</s>
                                                @else
                                                    <span class="text-info">Sin manejo de stock</span>
                                                @endif
                                            </h4>
                                        @endif


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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Offer --}}
    @if (count($clothings_offer) != 0)
        <hr class="dark horizontal text-danger mb-3">
        <div class="container-fluid mb-5 offer animado">
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
                                            <img class="pic-{{ $index + 1 }}" src="{{ route('file', $image) }}">
                                        @endforeach
                                        @if ($cant_img == 1)
                                            <img class="pic-2" src="{{ route('file', $image) }}">
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
                                <h3
                                    class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                    {{ $item->casa }}
                                </h3>
                                <h3 class="title clothing-name">
                                    <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">{{ $item->name }}
                                        @if ($item->total_stock == 0)
                                            <s class="text-danger"> Agotado</s>
                                        @endif
                                    </a>
                                </h3>

                                @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                    <h4 class="title">
                                        Stock:
                                        @if ($item->total_stock > 0)
                                            {{ $item->total_stock }}
                                        @elseif ($item->total_stock == 0)
                                            <s class="text-danger">0</s>
                                        @else
                                            <span class="text-info">Sin manejo de stock</span>
                                        @endif
                                    </h4>
                                @endif
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
                @endforeach

            </div>
        </div>
        <hr class="dark horizontal text-danger mb-3">
    @endif
    {{-- Insta --}}
    @if (isset($tenantinfo->show_insta) && $tenantinfo->show_insta == 1)
        <hr class="text-dark">
        <div class="text-center animado">
            <span class="text-muted text-center">
                @if ($instagram)
                    <a href="{{ isset($instagram) ? $instagram : '' }}">Siguenos en nuestras redes sociales <i class="fab fa-instagram"></i></a>
                    @if ($facebook)
                        | <a href="{{ isset($facebook) ? $facebook : '' }}"><i class="fab fa-facebook"></i></a>
                    @endif
                @endif

                {{ isset($tenantinfo->title_instagram) ? $tenantinfo->title_instagram : '' }}
            </span>
        </div>
        <div class="row mb-5 container-fluid animado">
            @foreach ($social as $item)
                @php
                    $social_logo = null;
                    if (stripos($item->url, 'Facebook') !== false) {
                        $social_logo = 'fab fa-facebook';
                    } elseif (stripos($item->url, 'Instagram') !== false) {
                        $social_logo = 'fab fa-instagram';
                    }
                    if (stripos($item->url, 'Twitter') !== false) {
                        $social_logo = 'fab fa-twitter';
                    }
                    if (stripos($item->url, 'You tube') !== false) {
                        $social_logo = 'fab fa-youtube';
                    }
                    if (stripos($item->url, 'Wordpress') !== false) {
                        $social_logo = 'fab fa-wordpress';
                    }
                    if (stripos($item->url, 'Tik tok') !== false) {
                        $social_logo = 'fab fa-tiktok';
                    }
                @endphp
                <div class="col-md-4 mt-4">
                    <a target="blank" class="text-white text-sm mb-0 icon-move-right mt-4" href="{{ $item->url }}">
                        <div class="card text-center">
                            <div class="overflow-hidden position-relative bg-cover p-3"
                                style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}'); height:700px; background-position: center;">
                                <span class="post-insta">
                                    <h3>
                                        <i class="{{ $social_logo }}"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    @endif
    {{-- Mision --}}
    @if (isset($tenantinfo->show_mision) && $tenantinfo->show_mision == 1)
        <div class="bg-footer p-3 mb-3 text-center">
            <h3
                class="text-center {{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'text-title-mandi' : 'text-title' }} mt-3">
                {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h3>
            <span class="text-center text-muted">
                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'muebleriasarchi')
                    {{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}
                @else
                    Mueblería Sarchí tiene sus raíces en una tradición familiar que comenzó hace más de 50 años, en los años
                    70, cuando Don Armando Salazar Chaverri, un pionero de la zona, fundó el taller que daría origen a una
                    de las empresas más reconocidas de la zona. Con una visión clara de ofrecer muebles de alta calidad
                    elaborados con maderas finas y acabados excepcionales, Don Armando se destacó por ser uno de los
                    primeros en la zona en especializarse en la fabricación de muebles de madera, dando forma a la identidad
                    del lugar como un referente en la carpintería y el diseño.
                    Desde sus inicios, el taller estuvo situado junto a la casa de Don Armando, un espacio donde sus hijos
                    crecieron aprendiendo las técnicas de fabricación que definieron el estilo clásico de la mueblería. A lo
                    largo de los años, la pasión y el conocimiento sobre la madera fueron transmitidos de generación en
                    generación, asegurando la continuidad de la tradición y la calidad que caracteriza a Mueblería Sarchi.
                    Hoy en día, los nietos de Don Armando siguen preservando el legado familiar, mientras incorporan un
                    enfoque moderno en los diseños. A lo largo de los años, han logrado un balance perfecto entre la
                    tradición artesanal y la innovación, manteniendo el nivel de calidad que representa a Sarchí, pero al
                    mismo tiempo, adaptándose a las nuevas tendencias del mercado para satisfacer las necesidades de los
                    clientes más exigentes.
                    Mueblería Sarchí sigue evolucionando, buscando siempre mejorar sus procesos y diseños para ofrecer lo
                    mejor a sus clientes, manteniendo la esencia de la empresa: una fusión de tradición, calidad y
                    modernidad.
                @endif

            </span>


        </div>
    @endif
    {{-- blogs --}}
    @if (count($blogs) != 0)
        <hr class="dark horizontal text-danger my-0">
        <div class="mt-3 mb-5 animado">
            <div class="container-fluid">
                <div class="text-center">
                    <h3 class="text-center text-muted mt-5 mb-3">Blog de
                        {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}, explora nuestras secciones, y aclara las
                        dudas acerca de nuestros servicios.</h3>
                </div>

                <div class="row">
                    <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-2 mb-5">
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
    {{-- Comentarios --}}
    @if (count($comments) != 0)
        <hr class="dark horizontal text-danger my-0">
        <div class="mt-3 mb-5 animado">
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

    <hr class="dark horizontal text-danger my-0">


    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    @if (isset($advert))
        <script>
            // Verifica si la alerta ya ha sido mostrada en esta sesión
            if (!sessionStorage.getItem('alertShown')) {
                // Muestra la alerta
                var advertContent = @json($advert->content);

                // Muestra la alerta
                Swal.fire({
                    title: 'Anuncio importante!',
                    html: advertContent,
                    icon: "info",
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: `
                <i class="fa fa-thumbs-up"></i> Entendido!
            `,
                    confirmButtonAriaLabel: "Thumbs up, great!"
                });
                // Marca que la alerta ha sido mostrada
                sessionStorage.setItem('alertShown', 'true');
            }
        </script>
    @endif

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

            var showModal = document.getElementById('showModalComment');
            var showModalValue = showModal.value;
            if (showModalValue == "add-comment") {
                var myModal = new bootstrap.Modal(document.getElementById('add-comment-modal'));
                myModal.show();
            }
        });
    </script>
@endsection
