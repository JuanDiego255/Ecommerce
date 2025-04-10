@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item) {
            return $item['discount'];
        }, $clothings_offer_array);
        $descuento_mas_alto = max($descuentos);
    }
    $ruta = $tenantinfo->tenant != 'aclimate' ? 'file' : 'aclifile';
@endphp
{{-- <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light shadow-sm" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @if (isset($tenantinfo->show_logo) && $tenantinfo->show_logo != 0)
                <img class="logo-car" src="{{ route($ruta, $tenantinfo->logo) }}" alt="">
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
<input type="hidden" name="view_name" value="{{ $view_name }}" id="view_name">
<input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva }}">
<header class="{{ $view_name == 'frontend_design_ecommerce_blog_index' ? 'header-v4' : '' }}">
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar bg-cintillo">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar text-cintillo">
                    {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="{{ url('/about_us') }}" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                        Acerca De
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                            Ingresar <i class="m-l-2 fa fa-sign-in"></i>
                        </a>
                    @else
                        <a href="{{ url('buys') }}" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                            Mis Compras
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <div class="wrap-menu-desktop">
            <nav class="limiter-menu-desktop container">

                <!-- Logo desktop -->
                <a href="#" class="logo">
                    <img id="{{$tenantinfo->tenant == 'aclimate' ? 'logo-img' : 'logo'}}" data-logo-scroll="{{ asset('avstyles/img/logos/logo-acli.svg') }}"
                        data-logo-original="{{ route($ruta, $tenantinfo->logo) }}"
                        src="{{ route($ruta, $tenantinfo->logo) }}" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="{{ $view_name == 'frontend_design_ecommerce_index' ? 'active-menu' : '' }}">
                            <a href="{{ url('/') }}">Inicio</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" id="toggleMenu">Explorar</a>
                        </li>
                        <!-- Menú expandido -->
                        <div id="fullScreenMenu" class="fullscreen-menu">
                            <div class="menu-content">
                                <button id="closeMenu" class="close-menu">&times;</button>
                                <h2 class="text-center">
                                    {{ isset($tenantinfo->manage_department) && $tenantinfo->manage_department == 1 ? 'Departamentos' : 'Categorías' }}
                                </h2>
                                <div class="departments-container">
                                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department == 1)
                                        @foreach ($departments as $department)
                                            <div class="department-section">
                                                <h3>{{ $department->department }}</h3>
                                                <ul>
                                                    @foreach ($department->categories as $categoria)
                                                        <li>
                                                            <a
                                                                href="{{ url('clothes-category/' . $categoria->id . '/' . $department->id) }}">
                                                                {{ $categoria->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else
                                        <ul>
                                            <li><a href="{{ url('category/') }}" class="nav-submenu-item">Todas las
                                                    Categorías</a></li>
                                            @foreach ($categories as $item)
                                                <li>
                                                    <a
                                                        href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- <li class="label1" data-label1="hot">
                            <a href="shoping-cart.html">Features</a>
                        </li> --}}
                        <li>
                            <a href="{{ url('/blog/index') }}">Blog</a>
                        </li>
                        <li>
                            <a href="about.html">Favoritos</a>
                        </li>
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m">
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search icon-text-color-desk"></i>
                    </div>
                    <button
                        class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart icon-text-color-desk"
                        data-notify="{{ $cartNumber }}">
                        <i class="zmdi zmdi-shopping-cart icon-text-color-desk"></i>
                    </button>

                    <a href="#"
                        class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti icon-fav icon-text-color-desk"
                        data-notify="{{ $favNumber }}">
                        <i class="zmdi zmdi-favorite-outline icon-text-color-desk"></i>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
        <!-- Logo moblie -->
        <div class="logo-mobile">
            <a href="index.html">
                <img id="logo-img" data-logo-scroll="{{ asset('avstyles/img/logos/logo-av2.svg') }}"
                    data-logo-original="{{ route($ruta, $tenantinfo->logo) }}"
                    src="{{ route($ruta, $tenantinfo->logo) }}" alt="IMG-LOGO">
            </a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search icon-text-color-desk">
                <i class="zmdi zmdi-search"></i>
            </div>

            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart icon-text-color-desk"
                data-notify="2">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>

            <a href="#"
                class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti icon-text-color-desk"
                data-notify="0">
                <i class="zmdi zmdi-favorite-outline"></i>
            </a>
        </div>

        <!-- Button show menu -->
        <div class="btn-show-menu-mobile hamburger hamburger--squeeze icon-text-color-desk">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </div>
    </div>


    <!-- Menu Mobile -->
    <div class="menu-mobile">
        <ul class="topbar-mobile">
            <li>
                <div class="left-top-bar">
                    {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
                </div>
            </li>

            <li>
                <div class="right-top-bar flex-w h-full">
                    <a href="{{ url('/about_us') }}" class="flex-c-m trans-04 p-lr-10">
                        Acerca De
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="flex-c-m trans-04 p-lr-10">
                            Ingresar <i style="color: var(--navbar_text);" class="fas fa-sign-in"></i>
                        </a>
                    @else
                        <a href="{{ url('buys') }}" class="flex-c-m trans-04 p-lr-10">
                            Mis Compras
                        </a>
                    @endguest
                </div>
            </li>
        </ul>

        <ul class="main-menu-m">
            <li>
                <a href="{{ url('/') }}">Inicio</a>
            </li>

            <li>
                <a href="javascript:void(0);" id="toggleMenuMobile">Explorar</a>
            </li>

            <div id="fullScreenMenuMobile" class="fullscreen-menu-mobile">
                <button id="closeMenuMobile" class="close-menu-mobile">&times;</button>
                <div class="menu-content">
                    <h2>{{ isset($tenantinfo->manage_department) && $tenantinfo->manage_department == 1 ? 'Departamentos' : 'Categorías' }}
                    </h2>
                    <div class="departments-container">
                        @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department == 1)
                            @foreach ($departments as $department)
                                <div class="department-section">
                                    <h3>{{ $department->department }}</h3>
                                    <ul>
                                        @foreach ($department->categories as $categoria)
                                            <li>
                                                <a
                                                    href="{{ url('clothes-category/' . $categoria->id . '/' . $department->id) }}">
                                                    {{ $categoria->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @else
                            <ul>
                                <li><a href="{{ url('category/') }}" class="nav-submenu-item">Todas las
                                        Categorías</a></li>
                                @foreach ($categories as $item)
                                    <li>
                                        <a
                                            href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}">
                                            {{ $item->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>


            <li>
                <a href="shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>
            </li>

            <li>
                <a href="blog.html">Blog</a>
            </li>

            <li>
                <a href="about.html">About</a>
            </li>

            <li>
                <a href="contact.html">Contact</a>
            </li>
        </ul>
    </div>

    <!-- Modal Search -->
    <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
        <div class="container-search-header">
            <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                <img src="/design_ecommerce/images/icons/icon-close2.png" alt="CLOSE">
            </button>

            <form class="wrap-search-header flex-w p-l-15">
                <button class="flex-c-m trans-04">
                    <i class="zmdi zmdi-search"></i>
                </button>
                <input class="plh3" type="text" name="search" placeholder="Search...">
            </form>
        </div>
    </div>
</header>
@if ($view_name != 'frontend_design_ecommerce_view-cart')
    <div class="wrap-header-cart js-panel-cart" data-image-base-url="{{ route($ruta, '') }}" id="modalMiniCart">
        <div class="s-full js-hide-cart"></div>

        <div class="header-cart flex-col-l p-l-65 p-r-25">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span class="mtext-103 cl2 title-text">
                    Tu Carrito
                </span>

                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            <div class="header-cart-content flex-w js-pscroll">
                <ul class="header-cart-wrapitem w-full productsList">
                    @foreach ($cart_items as $item)
                        @php
                            $precio = $item->price;
                            if (
                                isset($tenantinfo->custom_size) &&
                                $tenantinfo->custom_size == 1 &&
                                $item->stock_price > 0
                            ) {
                                $precio = $item->stock_price;
                            }
                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                $precio = $item->mayor_price;
                            }
                            $descuentoPorcentaje = $item->discount;
                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                            $precioConDescuento = $precio - $descuento;
                            $attributesValues = !empty($item->attributes_values)
                                ? explode(', ', $item->attributes_values)
                                : [];
                        @endphp

                        <li class="header-cart-item flex-w flex-t m-b-12">
                            <input type="hidden" name="prod_id" value="{{ $item->id }}" class="prod_id">
                            <input type="hidden" class="price"
                                value="{{ $item->discount > 0
                                    ? $precioConDescuento
                                    : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0
                                        ? $item->mayor_price
                                        : ($tenantinfo->custom_size == 1
                                            ? $item->stock_price
                                            : $item->price)) }}
                        ">
                            <input type="hidden" value="{{ $descuento }}" class="discount" name="discount">
                            <div class="header-cart-item-img">
                                <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="IMG">
                            </div>

                            <div class="header-cart-item-txt p-t-8">
                                <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                    {{ $item->name }}
                                </a>

                                @if (!empty($attributesValues) && count($attributesValues) > 0)
                                    <span class="m-0 text-muted w-100 d-block">
                                        Atributos
                                    </span>
                                    @foreach ($attributesValues as $attributeValue)
                                        @php
                                            if (strpos($attributeValue, ': ') !== false) {
                                                [$attribute, $value] = explode(': ', $attributeValue, 2);
                                            } else {
                                                $attribute = $attributeValue;
                                                $value = '';
                                            }
                                        @endphp

                                        <span>{{ $attribute == 'Stock' ? 'Predeterminado' : $attribute . ':' }}
                                            {{ $attribute == 'Stock' ? '' : $value }}</span><br>
                                    @endforeach
                                @endif

                                <span class="header-cart-item-info">
                                    ₡{{ $item->discount > 0 ? $precioConDescuento : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                </span>
                                <div class="d-flex align-items-center">
                                    <!-- Input para actualizar la cantidad -->
                                    <div class="input-group text-center input-group-static w-100">
                                        <input min="1" max="{{ $item->stock > 0 ? $item->stock : '' }}"
                                            value="{{ $item->quantity }}" type="number" name="quantityCart"
                                            data-cart-id="{{ $item->cart_id }}"
                                            class="form-control btnQuantity text-center w-100 quantity">
                                    </div>

                                    <!-- Formulario para eliminar el producto -->
                                    <form name="delete-item-cart" id="delete-item-cart" class="delete-form">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button data-item-id="{{ $item->cart_id }}"
                                            class="btn btn-icon btn-3 btn-danger btnDelete">
                                            <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </li>
                    @endforeach
                </ul>

                <div class="w-full">
                    <div class="header-cart-total w-full pb-2">
                        Productos: <span id="totalPriceElementDE"
                            class="ml-auto subtotalValue">₡{{ number_format($cloth_price) }}</span>
                    </div>
                    @if ($iva > 0)
                        <div class="header-cart-total w-full pb-2">
                            I.V.A: <span id="totalIvaElementDE"
                                class="ml-auto subtotalValue">₡{{ number_format($iva) }}</span>
                        </div>
                    @endif
                    <div class="header-cart-total w-full pb-2">
                        Descuento: <span id="totalDiscountElementDE"
                            class="ml-auto descuentoValue">₡{{ number_format($you_save) }}</span>
                    </div>
                    <div class="header-cart-total w-full pb-5">
                        Total: <span id="totalClothDE"
                            class="ml-auto totalValue">₡{{ number_format($total_price) }}</span>
                    </div>


                    <div class="header-cart-buttons flex-w w-full">
                        <a href="{{ url('view-cart') }}"
                            class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                            Ver Carrito
                        </a>

                        <a href="{{ url('checkout') }}"
                            class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
                            Finalizar Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- Modal1 -->
<div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
    <div class="overlay-modal1 js-hide-modal1"></div>

    <div class="container">
        <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
            <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                <i class="zmdi zmdi-close text-white"></i>
            </button>

            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>
                            <div class="slick3 gallery-lb">

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                        </h4>

                        <span class="mtext-106 cl2">
                        </span>
                        <span class="mtext-106 cl2 price-discount">
                        </span>

                        <p class="stext-102 cl3 p-t-23 text-desc">
                        </p>
                        <!--  -->
                        <div class="p-t-33">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
