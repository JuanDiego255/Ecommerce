<div id="menuHolder" class="bg-menu-velvet sticky-top">
    <div role="navigation" class="border-bottom bg-menu-velvet" id="mainNavigation">
        <div class="flexMain">
            <div class="flex2">
                <button
                    class="whiteLink siteLink"
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
                            class="whiteLink siteLink"><i
                                style="color: var(--navbar_text);" class="fa fa-sign-in"></i></button></a>
                @else
                    <a href="{{ url('tenants') }}"><button id="btnIngresar"
                            class="whiteLink siteLink"><i
                                style="color: var(--navbar_text);" class="fa fa-credit-card"></i></button></a>
                @endguest
            </div>

            <div class="flex2 text-end d-none d-md-block">
                @guest
                    <a href="{{ route('login') }}">
                        <button id="btnIngresarLogo"
                            class="blackLink siteLink"
                            style="border-right:1px solid #eaeaea"><i class="fa fa-sign-in"></i> INGRESAR</button>
                    </a>
                @else
                    <a href="{{ url('tenants') }}">
                        <button id="btnIngresarLogo"
                            class="blackLink siteLink"
                            style="border-right:1px solid #eaeaea"><i class="fas fa-address-book"></i> INQUILINOS</button>
                    </a>
                @endguest   
            </div>
        </div>
    </div>

    <div id="menuDrawer" class="bg-menu-d">

        <div>
            @guest
                <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                        class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>

                <a href="{{ url('/tenants') }}" class="nav-menu-item"><i class="fas fa-address-book me-3"></i>INQUILINOS</a>
                <a href="{{ route('login') }}" class="nav-menu-item"><i class="fa fa-sign-in me-3"></i>INGRESAR</a>
            @else
                <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                        class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>
                <a href="{{ url('/tenants') }}" class="nav-menu-item"><i class="fas fa-address-book me-3"></i>INQUILINOS</a>
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
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
