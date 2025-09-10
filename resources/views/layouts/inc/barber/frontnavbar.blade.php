@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item) {
            return $item['discount'];
        }, $clothings_offer_array);
        $descuento_mas_alto = max($descuentos);
    }
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
{{-- <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light shadow-sm" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @if (isset($tenantinfo->show_logo) && $tenantinfo->show_logo != 0)
                <img class="logo-car" src="{{ route('file', $tenantinfo->logo) }}" alt="">
            @else
                {{ isset($tenantinfo->title) ? $tenantinfo->title : 'Car<span>Book</span>' }}
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>




        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ $view_name == 'frontend_carsale_index' ? 'active' : '' }}"><a
                        href="{{ url('/') }}" class="nav-link">Inicio</a></li>
                <li class="nav-item"><a href="{{ url('#about_us') }}" class="nav-link">Acerca de</a></li>
                <li class="nav-item {{ $view_name == 'frontend_carsale_category' || $view_name == 'frontend_carsale_detail-car' ? 'active' : '' }}"><a href="{{ url('category/') }}" class="nav-link">Categorías</a></li>
                <li
                    class="nav-item {{ $view_name == 'frontend_blog_carsale_index' || $view_name == 'frontend_blog_carsale_show-articles' ? 'active' : '' }}">
                    <a href="{{ url('blog/index') }}" class="nav-link">Blog</a>
                </li>

                <li class="nav-item {{ $view_name == 'frontend_carsale_compare' ? 'active' : '' }}"><a
                        href="{{ url('compare/vehicles') }}" class="nav-link">Comparar Vehículos</a>
                </li>
                @guest


                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ url('/category') }}">Todas las categorías</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Salir
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav> --}}
<header>
    <!--? Header Start -->
    <div class="header-area header-transparent pt-20">
        <div class="main-header header-sticky">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-xl-2 col-lg-2 col-md-1">
                        @if (isset($tenantinfo->show_logo) && $tenantinfo->show_logo != 0)
                            <div class="logo">
                                <a href="index.html"><img
                                        src="{{ route('file', isset($tenantinfo->logo) ? $tenantinfo->logo : '') }}"
                                        alt=""></a>
                            </div>
                        @else
                            <p class="text-white">
                                {{ isset($tenantinfo->title) ? $tenantinfo->title : 'Car<span>Book</span>' }}</p>
                        @endif
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="menu-main d-flex align-items-center justify-content-end">
                            <!-- Main-menu -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li class="active"><a href="{{ url('/') }}">Inicio</a></li>
                                        <li><a href="{{ url('/#about') }}">Acerca de</a></li>
                                        <li><a href="{{ url('/#services') }}">Servicios</a></li>
                                        <li><a href="{{ url('/#contact') }}">Contacto</a></li>
                                        <li><a href="{{ url('catalogo/barber') }}">Tienda Online</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="header-right-btn f-right d-none d-lg-block ml-30">
                                <a href="{{ url('/#reservation') }}" class="btn header-btn">Reservar una cita</a>
                            </div>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>
