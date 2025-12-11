@php
    $clothings_offer_array = $clothings_offer->toArray();
    if (count($clothings_offer_array) != 0) {
        $descuentos = array_map(function ($item) {
            return $item['discount'];
        }, $clothings_offer_array);
        $descuento_mas_alto = max($descuentos);
    }
    $ruta = $tenantinfo->tenant != 'aclimate' ? 'file' : 'aclifile';
    $logo_principal = null;
    switch ($view_name) {
        case 'frontend_design_ecommerce_clothes-category':
        case 'frontend_design_ecommerce_detail-clothing':
        case 'frontend_design_ecommerce_view-cart':
        case 'frontend_design_ecommerce_checkout':
        case 'frontend_design_ecommerce_category':
        case 'frontend_design_ecommerce_departments':
        case 'frontend_design_ecommerce_buys':
        case 'frontend_design_ecommerce_blog_index':
        case 'frontend_design_ecommerce_blog_show-articles':
            if ($tenantinfo->tenant == 'aclimate') {
                $logo_principal = asset('avstyles/img/logos/logo-acli.svg');
            } else {
                $logo_principal = route($ruta, $tenantinfo->logo);
            }
            break;
        default:
            $logo_principal = route($ruta, $tenantinfo->logo);
            break;
    }
@endphp
<input type="hidden" name="view_name" value="{{ $view_name }}" id="view_name">
<input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva }}">
<header class="{{ $view_name == 'frontend_design_ecommerce_blog_index' ? 'header-v4' : '' }}">
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar bg-cintillo">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar text-cintillo">
                    <h4>Sitio deshabilitado, comunícate con el admin del servicio para reactivarlo</h4>
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="#" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                        Locked
                    </a>
                    @guest
                        <a href="#" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                            Locked
                        </a>
                    @else
                        <a href="#" class="flex-c-m trans-04 p-lr-25 text-cintillo">
                            Locked
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</header>
