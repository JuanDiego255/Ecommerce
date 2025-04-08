@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item) {
            return $item['discount'];
        }, $clothings_offer_array);
        $descuento_mas_alto = max($descuentos);
    }
    $ruta = $tenantinfo->tenant != 'aclimate' ? 'file' : 'aclifile';
    $logo =
        $view_name != 'frontend_av_blog_show-articles'
            ? route($ruta, $tenantinfo->logo)
            : asset('avstyles/img/logos/logo-av2.svg');
    $color_navs = $view_name != 'frontend_av_blog_show-articles' ? '' : 'text-dark';
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
    <div class="header-area {{ $tenantinfo->tenant == 'aclimate' ? 'position-acli' : '' }}">
        @if ($view_name == 'frontend_av_blog_show-articles')
            <div class="header-top_area d-none d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-md-5">
                            <div class="header_left">
                                <p>Encuentra increibles articulos en nuestro blog</p>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>
        @endif
        <div id="sticky-header"
            class="main-header-area {{ $view_name == 'frontend_av_clothes-category' ? 'details_nav shadow-sm' : '' }} {{ $tenantinfo->tenant == 'aclimate' ? 'bg-acli' : '' }}">
            <div class="container">
                <div class="header_bottom_border">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-2">
                            <div class="logo">
                                <a href="index.html">
                                    <img id="logo-img" src="{{ $logo }}"
                                        data-logo-scroll="{{ asset('avstyles/img/logos/logo-av2.svg') }}"
                                        data-logo-original="{{ $logo }}" alt="Logo" />
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-7">
                            <div class="main-menu d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li><a class="{{ $view_name == 'frontend_av_index' ? 'active' : '' }} {{ $tenantinfo->tenant == 'aclimate' ? 'text-color-acli' : $color_navs }}"
                                                href="{{ url('/') }}">Inicio</a></li>
                                        <li>
                                            <a class="{{ $view_name == 'frontend_av_about_us' ? 'active' : '' }} {{ $tenantinfo->tenant == 'aclimate' ? 'text-color-acli' : $color_navs }}"
                                                href="#">Nosotros <i class="ti-angle-down"></i></a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="{{ url('/about_us') }}">Acerca de</a>
                                                </li>
                                                {{-- <li><a href="about.html">about</a></li>
                                                <li><a href="elements.html">elements</a></li> --}}
                                            </ul>
                                        </li>
                                        <li><a class="{{ $view_name == 'frontend_av_clothes-category' ? 'active' : '' }} {{ $tenantinfo->tenant == 'aclimate' ? 'text-color-acli' : $color_navs }}"
                                                href="services.html">Servicios</a></li>
                                        <li>
                                            <a class="{{ $tenantinfo->tenant == 'aclimate' ? 'text-color-acli' : $color_navs }}"
                                                href="#">blog <i class="ti-angle-down"></i></a>
                                            <ul class="submenu">
                                                <li><a href="blog.html">blog</a></li>
                                                <li><a href="single-blog.html">single-blog</a></li>
                                            </ul>
                                        </li>
                                        <li><a class="{{ $tenantinfo->tenant == 'aclimate' ? 'text-color-acli' : $color_navs }}"
                                                href="{{ url('/contact') }}">Contacto</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                            <div class="Appointment">
                                <div
                                    class="d-none d-lg-block {{ $tenantinfo->tenant == 'aclimate' ? 'book_btn_acli' : 'book_btn' }}">
                                    <a type="button"
                                        href="{{ $tenantinfo->tenant == 'aclimate' ? url('/') : url('/aclimate') }}">{{ $tenantinfo->tenant == 'aclimate' ? '¡Visita AV!' : '¡Visita Aclimate!' }}
                                        <i class="fa fa-snowflake"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
