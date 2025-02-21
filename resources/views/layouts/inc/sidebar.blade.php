@php
    $name_category = $tenantinfo->tenant == 'rutalimon' ? 'Mant. Categorías' : 'Categorías';
    $name_atributos = $tenantinfo->tenant == 'rutalimon' ? 'Mant. Atributos' : 'Atributos';
@endphp
<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-velvet"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#" target="_blank">
            {{-- <img src="{{ url('images/carousel1.png') }}" class="navbar-brand-img h-100" alt="main_logo"> --}}
            <h4 class="ms-1 font-weight-bold text-white">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h4>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'main')
                <li class="nav-item">
                    <a @if (
                        $view_name == 'admin_categories_index' ||
                            $view_name == 'admin_departments_index' ||
                            $view_name == 'admin_departments_edit' ||
                            $view_name == 'admin_departments_add' ||
                            $view_name == 'admin_clothing_index' ||
                            $view_name == 'admin_clothing_edit' ||
                            $view_name == 'admin_clothing_add' ||
                            $view_name == 'admin_categories_add' ||
                            $view_name == 'admin_categories_edit') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="javascript:void(0);">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">inventory_2</i>
                        </div>
                        <span
                            class="nav-link-text ms-1">{{ $tenantinfo->tenant == 'rutalimon' ? __('Mant. Catálogo / Inventario') : __('Catálogo') }}</span>
                    </a>

                    <!-- Lista desplegable de "Mi Negocio" -->
                    <ul class="submenu">
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1 ? url('categories') : url('departments') }}">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                {{ isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1 ? $name_category : 'Departamentos' }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/attributes') }}" class="nav-link">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                {{ $name_atributos }}
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a @if ($view_name == 'admin_social_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('social-network') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">photo_library</i>
                        </div>
                        <span
                            class="nav-link-text ms-1">{{ $tenantinfo->tenant == 'rutalimon' ? __('Manejo de Redes Sociales') : __('Redes Sociales') }}</span>
                    </a>
                </li>
            @endif
            @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
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
                    <a @if ($view_name == 'admin_gifts_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('/gifts') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">redeem</i>
                        </div>
                        <span
                            class="nav-link-text ms-1">{{ $tenantinfo->tenant == 'rutalimon' ? __('Gestión de Tarjetas de Regalo') : __('Tarjetas de Regalo') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @if ($view_name == 'admin_buys_buys') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('new-buy') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">payments</i>
                        </div>
                        <span class="nav-link-text ms-1">Ventas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-sidebar" href="javascript:void(0);">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">description</i>
                        </div>
                        <span class="nav-link-text ms-1">Reportes</span>
                    </a>

                    <!-- Lista desplegable de "Mi Negocio" -->
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="{{ url('total-buys') }}" class="nav-link">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                Reporte Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/report/stock') }}" class="nav-link">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                Reporte Inventario
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/report/cat-prod/1') }}" class="nav-link">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                Rep. Categorías / Prod
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link text-sidebar" href="javascript:void(0);">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span
                        class="nav-link-text ms-1">{{ $tenantinfo->tenant == 'rutalimon' ? __('Gestión del Negocio') : __('Mi Negocio') }}</span>
                </a>

                <!-- Lista desplegable de "Mi Negocio" -->
                <ul class="submenu">
                    <li class="nav-item">
                        <a href="{{ url('tenant-info') }}" class="nav-link">
                            <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">arrow_right_alt</i>
                            </div>
                            Información Principal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('tenant-components') }}" class="nav-link">
                            <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">arrow_right_alt</i>
                            </div>
                            Componentes
                        </a>
                    </li>
                    @if (
                        (isset($tenantinfo->kind_business) && $tenantinfo->kind_business == 3) ||
                            $tenantinfo->kind_business == 5 ||
                            $tenantinfo->kind_business == 6)
                        <li class="nav-item">
                            <a href="{{ url('user-info') }}" class="nav-link">
                                <div
                                    class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10">arrow_right_alt</i>
                                </div>
                                Profesionales
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_blog_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('blog/indexadmin') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">menu_book</i>
                    </div>
                    <span class="nav-link-text ms-1">Blog</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_testimonial_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('comments') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">chat</i>
                    </div>
                    <span class="nav-link-text ms-1">Testimonios Clientes</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'rutalimon' ? 'd-none' : 'd-block' }}">
                <a @if ($view_name == 'admin_adverts_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('adverts') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">campaign</i>
                    </div>
                    <span class="nav-link-text ms-1">Anuncios</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'clinicare' ? 'd-block' : 'd-none' }}">
                <a @if ($view_name == 'admin_cajas_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/cajas') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">point_of_sale</i>
                    </div>
                    <span class="nav-link-text ms-1">Cajas</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'clinicare' ? 'd-block' : 'd-none' }}">
                <a @if ($view_name == 'admin_especialistas_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/especialistas') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">group</i>
                    </div>
                    <span class="nav-link-text ms-1">Especialistas</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'clinicare' ? 'd-block' : 'd-none' }}">
                <a @if ($view_name == 'admin_estudiantes_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/estudiantes') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">group</i>
                    </div>
                    <span class="nav-link-text ms-1">Estudiantes</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_users_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('users') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">group</i>
                    </div>
                    <span
                        class="nav-link-text ms-1">{{ $tenantinfo->tenant == 'rutalimon' ? __('Mantenimiento de usuarios') : __('Usuarios') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_roles_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/roles') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">admin_panel_settings</i>
                    </div>
                    <span class="nav-link-text ms-1">Mantenimiento de Roles</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'rutalimon' ? 'd-block' : 'd-none' }}">
                <a class="nav-link text-sidebar" href="javascript:void(0);">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">history</i>
                    </div>
                    <span class="nav-link-text ms-1">Bitácoras</span>
                </a>

                <!-- Lista desplegable de "Mi Negocio" -->
                <ul class="submenu">
                    <li class="nav-item">
                        <a href="{{ url('/report/logs/log') }}" class="nav-link">
                            <div
                                class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">arrow_right_alt</i>
                            </div>
                            Ingresos y Salidas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/report/logs/movement') }}" class="nav-link">
                            <div
                                class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">arrow_right_alt</i>
                            </div>
                            Movimientos usuarios
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_testimonial_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ $tenantinfo->tenant == 'rutalimon' ? url('/software/about_us') : url('/tenant-info#about_us') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">info</i>
                    </div>
                    <span class="nav-link-text ms-1">Acerca De</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->tenant == 'rutalimon' ? 'd-none' : 'd-block' }}">
                <a @if (
                    $view_name == 'admin_metatags_index' ||
                        $view_name == 'admin_metatags_agregar' ||
                        $view_name == 'admin_metatags_edit') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/meta-tags/indexadmin') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">sports_score</i>
                    </div>
                    <span class="nav-link-text ms-1">SEO Tools</span>
                </a>
            </li>
            <li class="nav-item">
                <a @if ($view_name == 'admin_testimonial_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('/help') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">help</i>
                    </div>
                    <span class="nav-link-text ms-1">Ayuda</span>
                </a>
            </li>
            <li class="nav-item {{ $tenantinfo->kind_business == 6 ? 'd-block' : 'd-none' }}">
                <a @if ($view_name == 'admin_logos_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                    href="{{ url('logos') }}">
                    <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">campaign</i>
                    </div>
                    <span class="nav-link-text ms-1">Logos</span>
                </a>
            </li>
            @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business == 1)
                <li class="nav-item">
                    <a @if ($view_name == 'admin_sellers_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('sellers') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">record_voice_over</i>
                        </div>
                        <span class="nav-link-text ms-1">Vendedores</span>
                    </a>
                </li>
            @endif
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'main')
                <li class="nav-item">
                    <a @if ($view_name == 'admin_users_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('/tenants') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">group</i>
                        </div>
                        <span class="nav-link-text ms-1">Administrar Inquilinos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a @if ($view_name == 'admin_users_index') class="nav-link active text-white bg-gradient-btnVelvet" @else class="nav-link text-sidebar" @endif
                        href="{{ url('/tenants/payments') }}">
                        <div class="text-sidebar text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">payments</i>
                        </div>
                        <span class="nav-link-text ms-1">Pagos y gastos</span>
                    </a>
                </li>
            @endif
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
