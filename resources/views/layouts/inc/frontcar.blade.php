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
        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'marylu')
            @if (count($clothings_offer) != 0)
                @foreach ($clothings_offer as $item)
                    <nav class="navbar-cintillo navbar-expand-lg bg-dark d-none d-lg-block" id="templatemo_nav_top">
                        <div class="container text-cintillo text-center">
                            Hasta {{ $descuento_mas_alto }}% de descuento en <a
                                class="text-cintillo text-decoration-underline"
                                href="{{ url('clothes-category/' . $item->category_id) }}">productos</a> seleccionados!
                        </div>
                    </nav>
                @break
            @endforeach
        @else
            <nav class="navbar-cintillo navbar-expand-lg bg-cintillo d-none d-lg-block" id="templatemo_nav_top">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <div class="container text-cintillo text-center">
                        Explora nuestras <a class="text-cintillo text-decoration-underline"
                            href="{{ url('category') }}">categorías </a> y encuentra lo que más te gusta!
                    </div>
                @else
                    <div class="container text-cintillpo text-center">
                        Explora nuestros <a class="text-cintillo text-decoration-underline"
                            href="{{ url('departments/index') }}">departamentos </a> y encuentra lo que más te
                        gusta!
                    </div>
                @endif

            </nav>
        @endif
    @endif

    <div class="flexMain">
        <div class="flex2">
            <button
                class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"
                id="btnMenu" style="color: var(--navbar_text); border-right:1px solid #eaeaea"
                onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
        </div>
        <div class="flex3 text-center" id="siteBrand">
            <a class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'text-title-mandi' : 'text-title' }} text-uppercase"
                href="{{ url('/') }}"><img class="logo" src="{{ route('file', $tenantinfo->logo) }}" alt=""></a>
        </div>

        <div class="flex2 text-end d-block d-md-none">
            @guest
                <a href="{{ route('login') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"><i
                            style="color: var(--navbar_text);" class="fa fa-sign-in cartIcon"></i></button></a>
            @else
                <a href="{{ url('/category') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"><i
                            style="color: var(--navbar_text);" class="fas fa-car cartIcon"></i></button></a>
            @endguest

            <a href="{{ url('#best-car') }}"><button
                    class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"><i
                        style="color: var(--navbar_text);" class="fas fa-car-side cartIcon">
                        </i></button></a>
        </div>

        <div class="flex2 text-end d-none d-md-block">
            @guest
                <a href="{{ route('login') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"
                        style="border-right:1px solid #eaeaea"><i class="fa fa-sign-in"></i> INGRESAR</button>
                </a>
            @else
                <a href="{{ url('/category') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"
                        style="border-right:1px solid #eaeaea"><i class="fas fa-car"></i> CATEGORIAS</button>
                </a>
            @endguest

            <a href="{{ url('#best-car') }}"><button
                    class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'blackLink-mandi' : 'blackLink' }} siteLink-car"><i
                        class="fas fa-car-side"></i>
                    OFERTA DEL MES
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
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <div class="nav-menu-item">
                    <i class="fas fa-car me-3"></i><a class="color-menu" href="javascript:void(0);"
                        id="toggleCategories">CATEGORIAS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                    <div class="subcategories" id="categoriesDropdown">
                        <ul>
                            <li class="item-submenu"><a href="{{ url('category/') }}" class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>TODAS LAS CATEGORIAS</a>
                            </li>
                            @foreach ($categories as $item)
                                <li class="item-submenu"><a
                                        href="{{ url('clothes-category/' . $item->id . '/' . $item->department_id) }}"
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
            @else
                <div class="nav-menu-item">
                    <i class="fas fa-car me-3"></i><a class="color-menu" href="javascript:void(0);"
                        id="toggleCategories">DEPARTAMENTOS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                    <div class="subcategories" id="categoriesDropdown">
                        <ul>
                            <li class="item-submenu"><a href="{{ url('departments/index') }}" class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>TODOS LOS DEPARTAMENTOS</a>
                            </li>
                            @foreach ($departments as $department)
                                <li class="item-submenu">
                                    <a href="{{ url('category/' . $department->id) }}" class="nav-submenu-item">
                                        <span class="alert-icon align-middle">
                                            <span class="material-icons text-md">label</span>
                                        </span>{{ $department->department }}
                                    </a>
                                    <ul>
                                        @foreach ($department->categories as $categoria)
                                            <li class="item-submenu">
                                                <a href="{{ url('clothes-category/' . $categoria->id . '/' . $department->id) }}"
                                                    class="nav-submenu-item">
                                                    <span class="alert-icon align-middle">
                                                        <span class="material-icons text-md">label</span>
                                                    </span>{{ $categoria->name }}
                                                </a>

                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Agrega más subcategorías si es necesario -->
                    </div>
                </div>
            @endif
            
            <a href="{{ route('register') }}" class="nav-menu-item"><i
                    class="fa fa-user-plus me-3"></i>REGISTRARSE</a>
            <a href="{{ route('login') }}" class="nav-menu-item"><i class="fa fa-sign-in me-3"></i>INGRESAR</a>
        @else
            <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                    class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>
            <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <div class="nav-menu-item">
                    <i class="fas fa-car me-3"></i><a class="color-menu" href="javascript:void(0);"
                        id="toggleCategories">CATEGORIAS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                    <div class="subcategories" id="categoriesDropdown">
                        <ul>
                            <li class="item-submenu"><a href="{{ url('category/') }}" class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>TODAS LAS CATEGORIAS</a>
                            </li>
                            @foreach ($categories as $item)
                                <li class="item-submenu"><a
                                        href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
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
            @else
                <div class="nav-menu-item">
                    <i class="fas fa-car me-3"></i><a class="color-menu" href="javascript:void(0);"
                        id="toggleCategories">DEPARTAMENTOS <i class="fa fa-arrow-circle-down ml-3"></i></a>
                    <div class="subcategories" id="categoriesDropdown">
                        <ul>
                            <li class="item-submenu"><a href="{{ url('departments/index') }}"
                                    class="nav-submenu-item">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">label</span>
                                    </span>TODOS LOS DEPARTAMENTOS</a>
                            </li>
                            @foreach ($departments as $department)
                                <li class="item-submenu">
                                    <a href="{{ url('category/' . $department->id) }}" class="nav-submenu-item">
                                        <span class="alert-icon align-middle">
                                            <span class="material-icons text-md">label</span>
                                        </span>{{ $department->department }}
                                    </a>
                                    <ul>
                                        @foreach ($department->categories as $categoria)
                                            <li class="item-submenu">
                                                <a href="{{ url('clothes-category/' . $categoria->id . '/' . $department->id) }}"
                                                    class="nav-submenu-item">
                                                    <span class="alert-icon align-middle">
                                                        <span class="material-icons text-md">label</span>
                                                    </span>{{ $categoria->name }}
                                                </a>

                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Agrega más subcategorías si es necesario -->
                    </div>
                </div>
            @endif
           
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