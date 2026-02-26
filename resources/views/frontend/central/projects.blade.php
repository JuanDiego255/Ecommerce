@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    <title>Proyectos Realizados — Safewor Solutions</title>
    <meta name="description" content="Conoce los proyectos que hemos desarrollado: E-commerce, sistemas a la medida, Virtual Tour 360, Booking para barberías y más.">
@endsection
@section('content')
<style>
    .sw-project-card {
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        transition: transform .25s, box-shadow .25s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .sw-project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(0,0,0,.14);
    }
    .sw-project-icon {
        font-size: 2.8rem;
        color: var(--btn_cart, #333);
        margin-bottom: 18px;
    }
    .sw-badge {
        display: inline-block;
        background: rgba(0,0,0,.06);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: .78rem;
        color: #444;
        margin-top: 4px;
        margin-right: 4px;
    }
    .sw-modal-icon {
        font-size: 3.5rem;
        color: var(--btn_cart, #333);
    }
    .sw-feature-list li {
        padding: 6px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .sw-feature-list li:last-child { border-bottom: none; }
    .sw-hero-projects {
        padding: 80px 0 50px;
        background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);
    }
</style>

{{-- Hero --}}
<section class="sw-hero-projects text-center">
    <div class="container">
        <h1 class="ltext-103 cl0" style="font-size:2.4rem; font-weight:800; letter-spacing:1px;">
            Proyectos Realizados
        </h1>
        <p class="stext-102 cl7 p-t-15 p-b-10" style="max-width:620px; margin:0 auto; opacity:.85;">
            Cada proyecto es una solución real para un negocio real. Aquí algunos de los sistemas que hemos construido y que hoy transforman la forma en que nuestros clientes operan.
        </p>
        <a href="{{ url('/') }}" class="stext-107 cl7" style="opacity:.65; text-decoration:underline;">
            <i class="fa fa-arrow-left m-r-5"></i> Volver al inicio
        </a>
    </div>
</section>

{{-- Grid de proyectos --}}
<section class="bg0 p-t-70 p-b-80">
    <div class="container">
        <div class="row">

            {{-- ─── Proyecto 1: E-commerce Multi Sucursal ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-shopping-bag"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">E-commerce Multi Sucursal</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Plataforma de ventas en línea multi-tenant con carrito, pasarela de pagos, catálogo por sucursal y panel administrativo independiente por cliente.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">E-commerce</span>
                        <span class="sw-badge">Multi-tenant</span>
                        <span class="sw-badge">Laravel</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto1" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

            {{-- ─── Proyecto 2: Sistema Contable Concesionario ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-car"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">Sistema Contable — Concesionario</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Sistema a la medida para la gestión contable de un concesionario de autos: inventario, ventas, reportes y control financiero en un solo lugar.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">Sistema a la Medida</span>
                        <span class="sw-badge">Contabilidad</span>
                        <span class="sw-badge">Automotriz</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto2" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

            {{-- ─── Proyecto 3: Integración Instagram + E-commerce ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-instagram"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">Integración Instagram × E-commerce</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Módulo que sincroniza automáticamente el catálogo de productos con publicaciones de Instagram, ahorrando horas de gestión de contenido en redes sociales.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">Integración API</span>
                        <span class="sw-badge">Redes Sociales</span>
                        <span class="sw-badge">Automatización</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto3" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

            {{-- ─── Proyecto 4: Virtual Tour 360° ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-camera"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">Virtual Tour 360°</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Recorridos virtuales inmersivos en 360° para el sector automotriz e inmobiliario. El cliente recorre el espacio desde cualquier dispositivo antes de visitarlo.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">Inmobiliario</span>
                        <span class="sw-badge">Automotriz</span>
                        <span class="sw-badge">360°</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto4" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

            {{-- ─── Proyecto 5: Sistema Booking Barberías ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-scissors"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">Sistema Booking — Barberías</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Plataforma de reservas online para barberías: agenda inteligente, gestión de barberos, servicios, historial de citas y notificaciones automáticas.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">Booking</span>
                        <span class="sw-badge">Barbería</span>
                        <span class="sw-badge">Reservas Online</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto5" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

            {{-- ─── Proyecto 6: Sistema Gestión Clínica ─── --}}
            <div class="col-md-6 col-lg-4 p-b-35 d-flex">
                <div class="sw-project-card p-lr-30 p-tb-35 w-full">
                    <div class="sw-project-icon"><i class="fa fa-stethoscope"></i></div>
                    <h5 class="mtext-112 cl2 p-b-12" style="font-weight:700;">Sistema de Ventas — Clínica</h5>
                    <p class="stext-107 cl6 p-b-20" style="flex:1;">
                        Sistema integral para clínicas: gestión de ventas de servicios, segmentación de salarios y nóminas para especialistas, y reportes de ingresos.
                    </p>
                    <div class="p-b-20">
                        <span class="sw-badge">Clínica</span>
                        <span class="sw-badge">Nóminas</span>
                        <span class="sw-badge">Gestión</span>
                    </div>
                    <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-15 trans-04 w-full"
                        data-toggle="modal" data-target="#modalProyecto6" style="cursor:pointer; width:100%;">
                        Ver detalles <i class="fa fa-arrow-right m-l-8"></i>
                    </button>
                </div>
            </div>

        </div>{{-- /row --}}

        {{-- CTA al final --}}
        <div class="text-center p-t-40">
            <p class="stext-102 cl6 p-b-20">¿Tienes un proyecto en mente?</p>
            @if (isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
            <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                class="flex-c-m stext-101 cl0 size-116 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-lr-auto"
                style="display:inline-flex; gap:8px; max-width:280px;">
                <i class="fa fa-whatsapp"></i> Contactar por WhatsApp
            </a>
            @endif
        </div>
    </div>
</section>

{{-- ════════════════ MODALES ════════════════ --}}

{{-- Modal 1: E-commerce Multi Sucursal --}}
<div class="modal fade" id="modalProyecto1" tabindex="-1" role="dialog" aria-labelledby="modal1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-shopping-bag"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">E-commerce Multi Sucursal</h4>
                <p class="stext-102 cl6 p-b-20">
                    Una plataforma robusta de comercio electrónico diseñada para negocios con múltiples sucursales o que desean ofrecer su propio sistema de ventas bajo una sola infraestructura. Cada cliente (tenant) tiene su propio espacio personalizado, colores, catálogo y configuración.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Arquitectura multi-tenant: cada cliente con su propio dominio y base de datos</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Carrito de compras con descuentos, IVA y precios al por mayor</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Pasarela de pagos integrada (PayPal, SINPE)</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Panel administrativo completo por sucursal</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> SEO optimizado con meta tags dinámicos y Open Graph</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Gestión de inventario, atributos de productos y tallas</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Blog integrado para marketing de contenidos</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Sistema de lista de favoritos y testimonios</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Laravel</span>
                    <span class="sw-badge">MySQL</span>
                    <span class="sw-badge">Bootstrap 5</span>
                    <span class="sw-badge">Stancl Tenancy</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2: Sistema Contable Concesionario --}}
<div class="modal fade" id="modalProyecto2" tabindex="-1" role="dialog" aria-labelledby="modal2Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-car"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">Sistema Contable — Concesionario de Autos</h4>
                <p class="stext-102 cl6 p-b-20">
                    Sistema personalizado desarrollado para un concesionario de vehículos que necesitaba centralizar su operación contable y de ventas en una sola herramienta digital. Elimina las hojas de cálculo manuales y garantiza trazabilidad completa de cada transacción.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Gestión de inventario de vehículos (nuevos y usados)</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Registro de ventas con desglose de comisiones</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Control de gastos operativos y financieros</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Reportes contables y estados financieros</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Gestión de clientes y seguimiento de prospectos</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Roles y permisos: administrador, vendedor, contador</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Laravel</span>
                    <span class="sw-badge">MySQL</span>
                    <span class="sw-badge">Sector Automotriz</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 3: Integración Instagram --}}
<div class="modal fade" id="modalProyecto3" tabindex="-1" role="dialog" aria-labelledby="modal3Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-instagram"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">Integración Instagram × E-commerce</h4>
                <p class="stext-102 cl6 p-b-20">
                    Módulo potente que conecta el catálogo de productos del E-commerce con la cuenta de Instagram del negocio. Elimina la duplicidad de trabajo: gestiona tu inventario una sola vez y el sistema publica automáticamente en Instagram, manteniendo ambas plataformas sincronizadas.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Publicación automática de productos en Instagram</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Generación dinámica de imagen con precio y nombre del producto</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Sincronización de cambios de precio en tiempo real</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Integración con Meta Graph API</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Panel de control de publicaciones programadas</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Ahorra hasta 5 horas semanales de gestión de contenido</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Meta Graph API</span>
                    <span class="sw-badge">Laravel</span>
                    <span class="sw-badge">Automatización</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 4: Virtual Tour 360° --}}
<div class="modal fade" id="modalProyecto4" tabindex="-1" role="dialog" aria-labelledby="modal4Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-camera"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">Virtual Tour 360° — Automotriz & Inmobiliario</h4>
                <p class="stext-102 cl6 p-b-20">
                    Solución de recorrido virtual inmersivo que permite a los clientes explorar vehículos o propiedades desde la comodidad de su hogar. Reduce la barrera de entrada, aumenta la confianza del comprador y acelera la toma de decisiones.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Navegación 360° interactiva en el navegador</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Compatible con dispositivos móviles y desktop</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Puntos de información (hotspots) dentro del recorrido</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Múltiples escenas enlazadas (salas, habitaciones, áreas del vehículo)</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Carga rápida con imágenes optimizadas</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Integrable en cualquier sitio web o landing page</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Inmobiliario</span>
                    <span class="sw-badge">Automotriz</span>
                    <span class="sw-badge">WebGL / Pannellum</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 5: Booking Barberías --}}
<div class="modal fade" id="modalProyecto5" tabindex="-1" role="dialog" aria-labelledby="modal5Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-scissors"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">Sistema Booking — Barberías</h4>
                <p class="stext-102 cl6 p-b-20">
                    Sistema de reservas online diseñado específicamente para barberías y salones de belleza. Permite a los clientes agendar sus citas en cualquier momento, eliminando la dependencia de llamadas telefónicas y reduciendo las citas perdidas.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Reservas online 24/7 desde cualquier dispositivo</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Gestión de múltiples barberos y sus horarios</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Catálogo de servicios con precios y duración</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Historial de citas por cliente y por barbero</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Panel administrativo con vista de agenda diaria/semanal</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Integrado dentro del mismo E-commerce multi-sucursal</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Notificaciones y recordatorios de citas</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Booking</span>
                    <span class="sw-badge">Laravel</span>
                    <span class="sw-badge">Barbería</span>
                    <span class="sw-badge">Multi-tenant</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 6: Sistema Gestión Clínica --}}
<div class="modal fade" id="modalProyecto6" tabindex="-1" role="dialog" aria-labelledby="modal6Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--navbar,#1a1a2e) 0%, #16213e 100%); border:none; padding:28px 32px;">
                <div class="sw-modal-icon cl0"><i class="fa fa-stethoscope"></i></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1; font-size:1.8rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-lr-40 p-tb-35">
                <h4 class="mtext-112 cl2 p-b-15" style="font-weight:700;">Sistema de Ventas & Nóminas — Clínica</h4>
                <p class="stext-102 cl6 p-b-20">
                    Sistema integral para clínicas de salud y estética que centraliza la gestión de ventas de servicios y automatiza el cálculo de nóminas para especialistas según el tipo de procedimiento y porcentaje pactado con cada profesional.
                </p>
                <h6 class="stext-301 cl2 p-b-12" style="font-weight:700;">Características principales:</h6>
                <ul class="sw-feature-list stext-107 cl6" style="list-style:none; padding:0;">
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Registro de ventas de servicios clínicos</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Segmentación de ingresos por especialista</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Cálculo automático de nóminas según porcentajes configurables</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Reportes de desempeño por especialista y período</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Gestión de citas y agenda médica</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Control de inventario de insumos</li>
                    <li><i class="fa fa-check-circle m-r-10" style="color:var(--btn_cart,#333);"></i> Roles diferenciados: administrador, especialista, recepcionista</li>
                </ul>
                <div class="p-t-20">
                    <span class="sw-badge">Clínica</span>
                    <span class="sw-badge">Nóminas</span>
                    <span class="sw-badge">Laravel</span>
                    <span class="sw-badge">Multi-tenant</span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
<script>
    // Asegurar que los modales de Bootstrap funcionen
    $(document).ready(function () {
        $('[data-toggle="modal"]').on('click', function () {
            var target = $(this).data('target');
            $(target).modal('show');
        });
    });
</script>
@endsection
