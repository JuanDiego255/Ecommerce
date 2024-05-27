@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item_offer) {
            return $item_offer['discount'];
        }, $clothings_offer_array);

        // Luego, encontramos el descuento más alto usando la función max()
        $descuento_mas_alto = max($descuentos);
    }
    $font_icon = '';
    switch ($tenantinfo->kind_business) {
        case 1:
            $font_icon = 'fas fa-car';
            break;
        case 2:
            $font_icon = 'fas fa-spa';
            break;
        case 3:
            $font_icon = 'fas fa-heart';
            break;
        default:
            $font_icon = 'fas fa-tshirt';
            break;
    }
@endphp
@if ($view_name != 'frontend_view-cart' && $view_name != 'frontend_checkout')
    <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva }}">
    <input type="hidden" name="view_name" value="{{ $view_name }}" id="view_name">
    <div class="modal px-modal-right fade" data-image-base-url="{{ route('file', '') }}" id="modalMiniCart" tabindex="-1"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog px-modal-vertical">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5>
                        Carrito de compras
                    </h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </div>
                <div class="modal-body">
                    <ul class="list-unstyled m-0 p-0 productsList">
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
                                // Calcular el descuento
                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                // Calcular el precio con el descuento aplicado
                                $precioConDescuento = $precio - $descuento;
                                $attributesValues = explode(', ', $item->attributes_values);
                            @endphp

                            <li class="py-3 border-bottom">
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
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <a href="{{ route('file', $item->image) }}">
                                            <img class="img-fluid border" src="{{ route('file', $item->image) }}"
                                                alt="...">
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <p class="mb-2">
                                            <a class="text-muted fw-500" href="#">{{ $item->name }}</a><br>
                                            <span class="m-0 text-muted w-100 d-block">
                                                Atributos
                                            </span>
                                            @foreach ($attributesValues as $attributeValue)
                                                @php
                                                    // Separa el atributo del valor por ": "
                                                    [$attribute, $value] = explode(': ', $attributeValue);
                                                @endphp

                                                {{ $attribute }}: {{ $value }}<br>
                                            @endforeach
                                            <span class="m-0 text-muted w-100 d-block">
                                                ₡{{ $item->discount > 0 ? $precioConDescuento : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                            </span>
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <div class="input-group text-center input-group-static w-100">
                                                <input min="1" max="{{ $item->stock }}"
                                                    value="{{ $item->quantity }}" type="number" name="quantityCart"
                                                    data-cart-id="{{ $item->cart_id }}"
                                                    class="form-control btnQuantity text-center w-100 quantity">
                                            </div>
                                            <form name="delete-item-cart" id="delete-item-cart" class="delete-form">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button data-item-id="{{ $item->cart_id }}"
                                                    class="btn btn-icon btn-3 btn-danger btnDelete">
                                                    <span class="btn-inner--icon"><i
                                                            class="material-icons">delete</i></span>
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>
                <div class="mt-auto p-3 pt-0">

                    <div class="row g-0 py-2 subtotal">
                        <div class="col-8">
                            <span class="text-dark">Productos</span>
                        </div>
                        <div class="col-4 text-end">
                            <span id="totalPriceElement"
                                class="ml-auto subtotalValue">₡{{ number_format($cloth_price) }}</span>
                        </div>
                        @if ($iva > 0)
                            <div class="col-8">
                                <span class="text-dark">I.V.A</span>
                            </div>
                            <div class="col-4 text-end">
                                <span id="totalIvaElement"
                                    class="ml-auto subtotalValue">₡{{ number_format($iva) }}</span>
                            </div>
                        @endif
                    </div>


                    @if ($you_save > 0)
                        <div class="row g-0 py-2 descuento">
                            <div class="col-8">
                                <span class="text-dark">Descuento:</span>
                            </div>
                            <div class="col-4 text-end">
                                <span id="totalDiscountElement"
                                    class="ml-auto descuentoValue">₡{{ number_format($you_save) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="row g-0 pt-2 mt-2 border-top fw-bold text-dark total">
                        <div class="col-8">
                            <span class="text-dark">Total:</span>
                        </div>
                        <div class="col-4 text-end">
                            <span id="totalCloth" class="ml-auto totalValue">₡{{ number_format($total_price) }}</span>
                        </div>
                    </div>


                    <div class="pt-4">
                        <a class="btn btn-block btn-velvet w-100" href="{{ url('view-cart') }}">
                            Ver carrito de compras
                        </a>
                        <a class="btn btn-block btn-add_to_cart w-100" href="{{ url('checkout') }}">
                            Finalizar pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="menuHolder" class="bg-menu-velvet sticky-top">
    <div role="navigation" class="border-bottom bg-menu-velvet" id="mainNavigation">
        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'marylu')
            @if (count($clothings_offer) != 0)
                @foreach ($clothings_offer as $item)
                    <nav class="navbar-cintillo navbar-expand-lg bg-dark d-none d-lg-block" id="templatemo_nav_top">
                        <div class="container text-cintillo text-center">
                            Hasta {{ $descuento_mas_alto }}% de descuento en <a
                                class="text-cintillo text-decoration-underline"
                                href="{{ url('clothes-category/' . $item->category_id) }}">productos</a>
                            seleccionados!
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
                class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                id="btnMenu" style="color: var(--navbar_text); border-right:1px solid #eaeaea"
                onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
        </div>
        <div class="flex3 text-center" id="siteBrand">
            @if (isset($tenantinfo->show_logo) && $tenantinfo->show_logo != 0)
                <a class="text-uppercase" href="{{ url('/') }}"><img class="logo"
                        src="{{ route('file', $tenantinfo->logo) }}" alt=""></a>
            @else
                <a class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'text-title-mandi' : 'text-title' }} text-uppercase"
                    href="{{ url('/') }}">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
            @endif
        </div>

        <div class="flex2 text-end d-block d-md-none">
            @guest
                <a href="{{ route('login') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                            style="color: var(--navbar_text);" class="fa fa-sign-in btnIcon"></i></button></a>
            @else
                <a href="{{ url('buys') }}"><button id="btnIngresar"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                            style="color: var(--navbar_text);" class="fa fa-credit-card btnIcon"></i></button></a>
            @endguest

            <button type="button" data-bs-toggle="modal" data-bs-target="#modalMiniCart"
                class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"><i
                    style="color: var(--navbar_text);" class="fa fa-shopping-cart cartIcon">
                    {{ $cartNumber }}</i></button>
        </div>

        <div class="flex2 text-end d-none d-md-block">
            @guest
                <a href="{{ route('login') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                        style="border-right:1px solid #eaeaea"><i class="fa fa-sign-in"></i> INGRESAR</button>
                </a>
            @else
                <a href="{{ url('buys') }}">
                    <button id="btnIngresarLogo"
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'whiteLink-mandi' : 'whiteLink' }} siteLink"
                        style="border-right:1px solid #eaeaea"><i class="fa fa-credit-card"></i> MIS COMPRAS</button>
                </a>
            @endguest
            @if ($view_name != 'frontend_view-cart' && $view_name != 'frontend_checkout')
                <button type="button" data-bs-toggle="modal" data-bs-target="#modalMiniCart"
                    class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'blackLink-mandi' : 'blackLink' }} siteLink"><i
                        class="fa fa-shopping-cart"></i>
                    {{ 'CARRITO' }} <span
                        class="badge badge-sm badge-info text-pill border-pill text-xxs">{{ $cartNumber }}</span>
                </button>
            @else
                <a href="{{ url('checkout') }}">
                    <button
                        class="{{ isset($tenantinfo->tenant) && ($tenantinfo->tenant === 'mandicr' || $tenantinfo->tenant === 'marylu') ? 'blackLink-mandi' : 'blackLink' }} siteLink"><i
                            class="fa fa-dollar"></i>
                        {{ $view_name == 'frontend_checkout' ? 'FINALIZAR PAGO' : 'IR A PAGAR' }}
                    </button>
                </a>
            @endif
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
                    <i class="{{ $font_icon }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
                    <i class="{{ $font_icon }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
            <a href="{{ url('view-cart') }}" class="nav-menu-item"><i class="fa fa-shopping-cart me-3"></i>CARRITO
                <span
                    class="badge badge-sm text-pill-menu badge-info border-pill-menu border-2 text-xxs">{{ $cartNumber }}</span></a>
            <a href="{{ route('register') }}" class="nav-menu-item"><i
                    class="fa fa-user-plus me-3"></i>REGISTRARSE</a>
            <a href="{{ route('login') }}" class="nav-menu-item"><i class="fa fa-sign-in me-3"></i>INGRESAR</a>
        @else
            <a class="nav-menu-item" href="javascript:void(0);" onclick="menuToggle()"><i
                    class="fa fa-arrow-circle-left me-3"></i>CERRAR MENU</a>
            <a href="{{ url('/') }}" class="nav-menu-item"><i class="fas fa-home me-3"></i>INICIO</a>
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <div class="nav-menu-item">
                    <i class="{{ $font_icon }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
                    <i class="{{ $font_icon }} me-3"></i><a class="color-menu" href="javascript:void(0);"
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
