@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item) {
            return $item['discount'];
        }, $clothings_offer_array);

        // Luego, encontramos el descuento más alto usando la función max()
        $descuento_mas_alto = max($descuentos);
    }

@endphp
<div id="menuHolder" class="bg-menu-velvet sticky-top">

    <div role="navigation" class="border-bottom bg-menu-velvet" id="mainNavigation">
        @if (count($clothings_offer) != 0)
            @foreach ($clothings_offer as $item)
                <nav class="navbar-cintillo navbar-expand-lg bg-dark d-none d-lg-block" id="templatemo_nav_top">
                    <div class="container text-light text-center">
                        Hasta {{ $descuento_mas_alto }}% de descuento en <a class="text-light text-decoration-underline"
                            href="{{ url('clothes-category/' . $item->category_id) }}">productos</a> seleccionados!
                    </div>
                </nav>
            @break
        @endforeach
    @else
        <nav class="navbar-cintillo navbar-expand-lg bg-dark d-none d-lg-block" id="templatemo_nav_top">
            <div class="container text-light text-center">
                Explora nuestras <a class="text-light text-decoration-underline"
                href="{{ url('category') }}">categorías </a> y encuentra lo que más te gusta!
            </div>
        </nav>
    @endif
    <div class="flexMain">
        <div class="flex2">
            <button
                class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                id="btnMenu" style="color: var(--navbar_text); border-right:1px solid #eaeaea"
                onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
        </div>
        <div class="flex3 text-center" id="siteBrand">
            <a class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'text-title-mandi' : 'text-title' }} text-uppercase"
                href="{{ url('/') }}">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
        </div>

        <div class="flex2 text-end d-block d-md-none">
            @guest
                <a href="{{ route('login') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                            style="color: var(--navbar_text);" class="fa fa-sign-in"></i></button></a>
            @else
                <a href="{{ url('buys') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                            style="color: var(--navbar_text);" class="fa fa-credit-card"></i></button></a>
            @endguest

            <a href="{{ url('view-cart') }}"><button
                    class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                        style="color: var(--navbar_text);" class="fa fa-shopping-cart cartIcon">
                        {{ $cartNumber }}</i></button></a>
        </div>

        <div class="flex2 text-end d-none d-md-block">
            @guest
                <a href="{{ route('login') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                        style="border-right:1px solid #eaeaea"><i class="fa fa-sign-in"></i> INGRESAR</button>
                </a>
            @else
                <a href="{{ url('buys') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                        style="border-right:1px solid #eaeaea"><i class="fa fa-credit-card"></i> MIS COMPRAS</button>
                </a>
            @endguest

            <a href="{{ url('view-cart') }}"><button
                    class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr' ? 'blackLink-mandi' : 'blackLink' }} siteLink"><i
                        class="fa fa-shopping-cart"></i>
                    CARRITO <span
                        class="badge badge-sm badge-info text-pill border-pill text-xxs">{{ $cartNumber }}</span>
                </button>
            </a>


        </div>
    </div>
</div>

<div id="menuDrawer" class="bg-menu-d">

    <div>
        @guest
            <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                    class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>

            <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
            <div class="nav-menu-item">
                <i class="fas fa-tshirt me-3"></i><a class="color-menu" href="javascript:void(0);"
                    id="toggleCategories">CATEGORIAS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                <div class="subcategories" id="categoriesDropdown">
                    <ul>
                        <li class="item-submenu"><a href="{{ url('category/') }}" class="nav-submenu-item">
                                <span class="alert-icon align-middle">
                                    <span class="material-icons text-md">label</span>
                                </span>TODAS LAS CATEGORIAS</a>
                        </li>
                        @foreach ($categories as $item)
                            <li class="item-submenu"><a href="{{ url('clothes-category/' . $item->id) }}"
                                    class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>{{ $item->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Agrega más subcategorías si es necesario -->
                </div>
            </div>
            <a href="{{ url('view-cart') }}" class="nav-menu-item"><i class="fa fa-shopping-cart me-3"></i>CARRITO
                <span
                    class="badge badge-sm text-pill-menu badge-info border-pill-menu border-2 text-xxs">{{ $cartNumber }}</span></a>
            <a href="{{ route('register') }}" class="nav-menu-item"><i class="fa fa-user-plus me-3"></i>REGISTRARSE</a>
            <a href="{{ route('login') }}" class="nav-menu-item"><i class="fa fa-sign-in me-3"></i>INGRESAR</a>
        @else
            <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                    class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>
            <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
            <div class="nav-menu-item">
                <i class="fas fa-tshirt me-3"></i><a class="color-menu" href="javascript:void(0);"
                    id="toggleCategories">CATEGORIAS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                <div class="subcategories" id="categoriesDropdown">
                    <ul>
                        <li class="item-submenu"><a href="{{ url('category/') }}" class="nav-submenu-item">
                                <span class="alert-icon align-middle">
                                    <span class="material-icons text-md">label</span>
                                </span>TODAS LAS CATEGORIAS</a>
                        </li>
                        @foreach ($categories as $item)
                            <li class="item-submenu"><a href="{{ url('clothes-category/' . $item->id) }}"
                                    class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>{{ $item->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Agrega más subcategorías si es necesario -->
                </div>
            </div>
            <a href="{{ url('view-cart') }}" class="nav-menu-item"><i class="fa fa-shopping-cart me-3"></i>CARRITO
                <span
                    class="badge badge-sm text-pill-menu badge-info border-pill-menu border-2 text-xxs">{{ $cartNumber }}</span></a>
            <a href="{{ url('buys') }}" class="nav-menu-item"><i class="fa fa-credit-card me-3"></i>MIS
                COMPRAS</a>
            <a href="{{ url('/address') }}" class="nav-menu-item"><i
                    class="fas fa-map-marker me-3"></i>DIRECCIONES</a>

            <div class="nav-menu-item">
                <a class="color-menu" href="javascript:void(0);" id="toggleLogout"><i
                        class="fas fa-user-minus me-3"></i>{{ Auth::user()->name }} {{ Auth::user()->last_name }} <i
                        class="fa fa-arrow-circle-down me-3"></i></a>
                <div class="subLogout" id="logoutDropdown">
                    <ul>
                        <li class="item-submenu">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                class="nav-submenu-item">
                                <span class="alert-icon align-middle">
                                    <span class="material-icons text-md">logout</span>
                                </span>Salir
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </li>

                    </ul>
                    <!-- Agrega más subcategorías si es necesario -->
                </div>
            </div>
        @endguest

    </div>
</div>
</div>
