<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-velvet"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#" target="_blank">
            {{-- <img src="{{ url('images/carousel1.png') }}" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <h4 class="ms-1 font-weight-bold text-white">{{isset($tenantinfo->title) ? $tenantinfo->title : ''}}</h4>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a @if (
                    $view_name == 'admin_categories_index' ||
                        $view_name == 'admin_clothing_index' ||
                        $view_name == 'admin_clothing_edit' ||
                        $view_name == 'admin_clothing_add'||
                        $view_name == 'admin_categories_add'||
                        $view_name == 'admin_categories_edit') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('categories') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Categorías</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_sizes_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('sizes') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">fullscreen</i>
                    </div>
                    <span class="nav-link-text ms-1">Tallas</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_buys_index' || $view_name == 'admin_buys_indexDetail') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('buys-admin') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">local_mall</i>
                    </div>
                    <span class="nav-link-text ms-1">Pedidos</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_buys_index-total' || $view_name == 'admin_buys_indexDetail') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('total-buys') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">payments</i>
                    </div>
                    <span class="nav-link-text ms-1">Ventas</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_social_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('social-network') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">photo_library</i>
                    </div>
                    <span class="nav-link-text ms-1">Redes Sociales</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_tenant-info_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('tenant-info') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Mi Negocio</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_metatags_index' || $view_name == 'admin_metatags_agregar' || $view_name == 'admin_metatags_edit') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/meta-tags/indexadmin') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">sports_score</i>
                    </div>
                    <span class="nav-link-text ms-1">Metatags</span>
                </a>
            </li>

        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">
            <a class="btn bg-gradient-btnVelvet mt-4 w-100" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Cerrar Sesion') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</aside>
