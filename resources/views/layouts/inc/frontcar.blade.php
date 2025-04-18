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
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light shadow-sm" id="ftco-navbar">
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
</nav>


{{-- <div id="menuHolder" class="bg-menu-velvet sticky-top">

    <div role="navigation" class="border-bottom bg-menu-velvet" id="mainNavigation">
        <nav class="navbar-cintillo navbar-expand-lg bg-cintillo d-lg-block" id="templatemo_nav_top">
            <div class="container text-cintillo text-center">
                {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
            </div>
        </nav>

        <div class="flexMain">
            <div class="flex2">
                <button
                    class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink-car"
                    id="btnMenu" style="color: var(--navbar_text); border-right:1px solid #eaeaea"
                    onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
            </div>
            <div class="flex3 text-center" id="siteBrand">
                @if (isset($tenantinfo->show_logo) && $tenantinfo->show_logo != 0)
                    <a class="text-uppercase" href="{{ url('/') }}"><img class="logo-car"
                            src="{{ route('file', $tenantinfo->logo) }}" alt=""></a>
                @else
                    <a class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'text-title-mandi' : 'text-title' }} text-uppercase"
                        href="{{ url('/') }}">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
                @endif
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
                        OFERTA MES
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

                <a href="{{ url('/') }}" class="nav-menu-item"><i
                        class="fas fa-{{ $icon->home }} me-3"></i>INICIO</a>
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <div class="nav-menu-item">
                        <i class="fas fa-{{ $icon->categories }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
                        <i class="fas fa-{{ $icon->categories }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
                <a href="{{ url('blog/index') }}" class="nav-menu-item"><i class="fa fa-book me-3"></i>BLOG</a>
                <a href="{{ route('register') }}" class="nav-menu-item"><i
                        class="fa fa-user-plus me-3"></i>REGISTRARSE</a>
                <a href="{{ route('login') }}" class="nav-menu-item"><i class="fa fa-sign-in me-3"></i>INGRESAR</a>
            @else
                <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                        class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>
                <a href="{{ url('/') }}" class="nav-menu-item"><i
                        class="fas fa-{{ $icon->home }} me-3"></i>INICIO</a>
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <div class="nav-menu-item">
                        <i class="fas fa-{{ $icon->categories }} me-3"></i><a class="color-menu"
                            href="javascript:void(0);" id="toggleCategories">CATEGORIAS <i
                                class="fa fa-arrow-circle-down ml-3"></i></a>
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
                        <i class="fas fa-{{ $icon->categories }} me-3"></i><a class="color-menu"
                            href="javascript:void(0);" id="toggleCategories">DEPARTAMENTOS <i
                                class="fa fa-arrow-circle-down ml-3"></i></a>
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
                <a href="{{ url('blog/index') }}" class="nav-menu-item"><i class="fa fa-book me-3"></i>BLOG</a>

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
</div> --}}
