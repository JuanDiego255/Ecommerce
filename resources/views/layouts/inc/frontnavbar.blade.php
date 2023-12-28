{{-- <nav id="menu" class="navbar navbar-expand-md  navbar-light shadow-sm sticky-top pb-5 pt-5">
    <div class="container-fluid nav-bar d-flex justify-content-between text-center">
        <div class="navbar-header">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarPrincipal" aria-controls="navbarPrincipal" aria-expanded="true"
                aria-label="Toggle navigation">
                <span class="material-icons">
                    menu
                </span>
            </button>

        </div>

        <div class="collapse navbar-collapse" id="navbarPrincipal">

            <ul class="navbar-nav me-auto">
                @guest
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_index')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('/') }}"><i class="fa fa-home"></i> {{ __('INICIO') }}</a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('/') }}"><i class="fa fa-home"></i> {{ __('INICIO') }}</a>
                        @endif
                    </li>
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_category')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('category') }}"><i class="fas fa-tshirt"></i> {{ __('CATEGORIAS') }}
                            </a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('category') }}"><i class="fas fa-tshirt"></i> {{ __('CATEGORIAS') }}                               
                            </a>
                        @endif
                    </li>
                    <li class="nav-item text-center">
                        <a id="inicio" class=" nav-link font-weight-bold"
                            href="{{ route('login') }}"><i class="fa fa-sign-in"></i> {{ __('INGRESAR') }}</a>
                    </li>
                    <li class="nav-item text-center">
                        <a id="inicio" class=" nav-link font-weight-bold"
                            href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __('REGISTRARSE') }}</a>
                    </li>
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_view-cart')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('view-cart') }}"><i class="fa fa-shopping-cart"></i> {{ __('CARRITO') }}
                                <span
                                class="badge text-secondary badge-primary border border-secondary badge-circle badge-sm  border-2">{{ $cartNumber }}</span>
                            </a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('view-cart') }}"><i class="fa fa-shopping-cart"></i> {{ __('CARRITO') }}
                                <span
                                class="badge text-secondary badge-primary border border-secondary badge-circle badge-sm  border-2">{{ $cartNumber }}</span>
                            </a>
                        @endif

                    </li>
                @else
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_index')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('/') }}"><i class="fa fa-heart"></i> {{ __('INICIO') }}</a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('/') }}"><i class="fa fa-heart"></i> {{ __('INICIO') }}</a>
                        @endif
                    </li>
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_category' || $view_name == 'frontend_clothes-category' || $view_name == 'frontend_detail-clothing')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('category') }}"><i class="fas fa-tshirt"></i> {{ __('CATEGORIAS') }}
                            </a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('category') }}"><i class="fas fa-tshirt"></i> {{ __('CATEGORIAS') }}
                            </a>
                        @endif

                    </li>
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_buys' || $view_name == 'frontend_detail-buy')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('buys') }}"><i class="fa fa-credit-card"></i> {{ __('MIS COMPRAS') }}</a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('buys') }}"><i class="fa fa-credit-card"></i> {{ __('MIS COMPRAS') }}</a>
                        @endif

                    </li>
                    <li class="nav-item text-center">
                        @if ($view_name == 'frontend_view-cart')
                            <a id="inicio" style="border-bottom:5px solid;"
                                class="nav-link font-weight-bold seleccionado"
                                href="{{ url('view-cart') }}"><i class="fa fa-shopping-cart"></i> {{ __('CARRITO') }}
                                <span
                                class="badge text-secondary badge-primary border border-secondary badge-circle badge-sm  border-2">{{ $cartNumber }}</span>
                            </a>
                        @else
                            <a id="inicio" class=" nav-link font-weight-bold"
                                href="{{ url('view-cart') }}"><i class="fa fa-shopping-cart"></i> {{ __('CARRITO') }}
                                <span
                                class="badge text-secondary badge-primary border border-secondary badge-circle badge-sm  border-2">{{ $cartNumber }}</span>
                            </a>
                        @endif

                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link text-uppercase font-weight-bold dropdown-toggle"
                            href="javascript:;" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item border-radius-md" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                <div class="d-flex align-items-center py-1">
                                    <div class="my-auto">
                                        <span class="material-icons">
                                            logout
                                        </span>
                                    </div>
                                    <div class="ms-2">
                                        <h6 class="text-sm font-weight-normal mb-0">
                                            Cerrar Sesi贸n
                                        </h6>
                                    </div>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </a>
                        </div>
                    </li>

                @endguest

            </ul>
            <div>
                <h4 class="velvet-title text-center">Velvet Boutique</h4>
            </div>
        </div>
       
    </div>
    
</nav> --}}

<div id="menuHolder" class="bg-menu-velvet sticky-top">
    <div role="navigation" class="border-bottom bg-menu-velvet" id="mainNavigation">
        <div class="flexMain">
            <div class="flex2">
                <button class="whiteLink siteLink" id="btnMenu" style="border-right:1px solid #eaeaea"
                    onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>                
            </div>
            <div class="flex3 text-center" id="siteBrand">
                <a class="velvet-title" href="{{ url('/') }}">VELVET BOUTIQUE</a>
            </div>

            <div class="flex2 text-end d-block d-md-none">
                <button id="btnIngresar" class="whiteLink siteLink"><i class="fa fa-sign-in"></i></button>
                <button class="whiteLink siteLink"><i class="fa fa-shopping-cart"> {{ $cartNumber }}</i></button>
            </div>

            <div class="flex2 text-end d-none d-md-block">
                <a href="{{ route('login') }}">
                    <button id="btnIngresarLogo" class="whiteLink siteLink" style="border-right:1px solid #eaeaea"><i
                            class="fa fa-sign-in me-2"></i> Ingresar</button>
                </a>
                <a href="{{ url('view-cart') }}"><button class="blackLink siteLink"><i class="fa fa-shopping-cart"></i>
                        Carrito <span class="badge badge-sm badge-info border text-xxs">{{ $cartNumber }}</span>
                    </button>
                </a>


            </div>
        </div>
    </div>

    <div id="menuDrawer" class="bg-menu-d">

        <div>
            @guest
                <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
                <a href="{{ url('category') }}" class="nav-menu-item"><i class="fas fa-tshirt me-3"></i>CATEGORIAS</a>
                <a href="{{ url('view-cart') }}" class="nav-menu-item"><i class="fa fa-shopping-cart me-3"></i>CARRITO</a>
                <a href="{{ route('register') }}" class="nav-menu-item"><i class="fa fa-user-plus me-3"></i>REGISTRARSE</a>
            @else
                <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
                <a href="{{ url('category') }}" class="nav-menu-item"><i class="fas fa-tshirt me-3"></i>CATEGORIAS</a>
                <a href="{{ url('buys') }}" class="nav-menu-item"><i class="fa fa-credit-card me-3"></i>MIS COMPRAS</a>
                <a href="{{ url('view-cart') }}" class="nav-menu-item"><i class="fa fa-shopping-cart me-3"></i>CARRITO</a>
                <a id="navbarDropdown" class="nav-menu-item" href="javascript:;" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre><i
                        class="fas fa-user-minus me-3"></i>{{ Auth::user()->name }} {{ Auth::user()->last_name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item border-radius-md" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                        <div class="d-flex align-items-center py-1">
                            <div class="my-auto">
                                <span class="material-icons">
                                    logout
                                </span>
                            </div>
                            <div class="ms-2">
                                <h6 class="text-sm font-weight-normal mb-0">
                                    Cerrar Sesión
                                </h6>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </a>
                </div>
            @endguest

        </div>
    </div>
</div>
