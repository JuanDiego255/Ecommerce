@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <style>
        @media screen and (max-width: 768px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->mobile_image ?? $carousel->image) }}');
                }
            @endforeach
        }

        @media screen and (min-width: 769px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->image) }}');
                }
            @endforeach
        }

        .item-slick1 {
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }

        .video-container {
            width: 1930px;
            height: 920px;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
        }

        .video-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
    {{-- Carousel Start --}}
    @if (count($tenantcarousel) != 0)
        <div class="wrap-slick1">
            <div class="slick1">
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="item-slick1 bg-carousel-{{ $loop->index }}">

                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        {!! $carousel->text1 !!}
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        {{ $carousel->text2 }}
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . $carousel->url) }}"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        {{ $carousel->link_text }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($tenantinfo->tenant == 'main')
                    {{-- 🔥 Slide manual con video quemado --}}
                    <div class="item-slick1">
                        <div class="video-container">
                            <video autoplay muted loop playsinline class="video-bg">
                                <source src="{{ url('/design_ecommerce/videos/main.mp4') }}" type="video/mp4">
                                Tu navegador no soporta video HTML5.
                            </video>
                        </div>

                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        ¡Bienvenido a nuestro mundo!
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        Mira nuestro video promocional
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="/promo"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin slide manual --}}
                @endif
            </div>
        </div>
    @endif
    {{-- Carousel End --}}
    <!-- Banner Start-->
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($category) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($category->take($take) as $key => $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span
                                                    class="ltext-102 trans-04 p-b-8 {{ isset($tenantinfo->tenant) && ($tenantinfo->tenant != 'aclimate' && $tenantinfo->tenant != 'solociclismocrc') ? 'block1-name' : 'block1-name-ac' }}">
                                                    {{ $item->name }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">
                                                    {!! $item->description !!}
                                                </span>
                                            </div>

                                            <div class="block1-txt-child2 p-b-4 trans-05">
                                                <div class="block1-link stext-101 cl0 trans-09">
                                                    Detallar
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        @if (count($departments) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($departments as $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'category/' . $item->id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span class="block1-name ltext-102 trans-04 p-b-8">
                                                    {{ $item->department }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">

                                                </span>
                                            </div>

                                            <div class="block1-txt-child2 p-b-4 trans-05">
                                                <div class="block1-link stext-101 cl0 trans-09">
                                                    Detallar
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: SERVICIOS
    ════════════════════════════════════════════════════════════════ --}}
    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'main')
    <section id="servicios" class="bg0 p-t-80 p-b-80">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Nuestros Servicios
                </h2>
                <p class="stext-102 cl6 p-t-15" style="max-width:620px; margin:0 auto;">
                    Construimos soluciones digitales completas para llevar tu negocio al siguiente nivel.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="row p-b-20">
                {{-- Servicio 1: Desarrollo Web --}}
                <div class="col-md-4 p-b-40">
                    <div class="bor10 p-lr-40 p-tb-40 text-center" style="height:100%; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,.07); background:#fff;">
                        <div class="p-b-20" style="font-size:3rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h4 class="mtext-112 cl2 p-b-16" style="font-weight:700;">
                            Desarrollo Web
                        </h4>
                        <p class="stext-102 cl6">
                            Tu presencia en el mundo digital. Diseñamos y desarrollamos sitios web modernos, rápidos y optimizados para convertir visitantes en clientes.
                        </p>
                        <div class="p-t-20">
                            <span class="stext-107 cl3" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px;">
                                <i class="fa fa-check m-r-5"></i> Diseño Responsivo
                            </span>
                            <span class="stext-107 cl3 m-t-10" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px; margin-top:8px;">
                                <i class="fa fa-check m-r-5"></i> Alta Performance
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Servicio 2: Sistemas a la Medida --}}
                <div class="col-md-4 p-b-40">
                    <div class="bor10 p-lr-40 p-tb-40 text-center" style="height:100%; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,.07); background:#fff;">
                        <div class="p-b-20" style="font-size:3rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <h4 class="mtext-112 cl2 p-b-16" style="font-weight:700;">
                            Sistemas a la Medida
                        </h4>
                        <p class="stext-102 cl6">
                            Soluciones personalizadas para tu empresa. Desde sistemas contables, gestión de ventas, clínicas y más — construimos exactamente lo que tu operación necesita.
                        </p>
                        <div class="p-t-20">
                            <span class="stext-107 cl3" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px;">
                                <i class="fa fa-check m-r-5"></i> 100% Personalizado
                            </span>
                            <span class="stext-107 cl3 m-t-10" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px; margin-top:8px;">
                                <i class="fa fa-check m-r-5"></i> Escalable
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Servicio 3: SEO --}}
                <div class="col-md-4 p-b-40">
                    <div class="bor10 p-lr-40 p-tb-40 text-center" style="height:100%; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,.07); background:#fff;">
                        <div class="p-b-20" style="font-size:3rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-search"></i>
                        </div>
                        <h4 class="mtext-112 cl2 p-b-16" style="font-weight:700;">
                            SEO Integrado
                        </h4>
                        <p class="stext-102 cl6">
                            Herramientas SEO integradas en cada sistema que desarrollamos. Destacamos tu sitio en los motores de búsqueda y llevamos tráfico orgánico a tu negocio.
                        </p>
                        <div class="p-t-20">
                            <span class="stext-107 cl3" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px;">
                                <i class="fa fa-check m-r-5"></i> Google Ready
                            </span>
                            <span class="stext-107 cl3 m-t-10" style="display:inline-block; background:rgba(0,0,0,.05); border-radius:20px; padding:5px 16px; margin-top:8px;">
                                <i class="fa fa-check m-r-5"></i> Meta Tags & OG
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: STATS / NÚMEROS
    ════════════════════════════════════════════════════════════════ --}}
    <section class="p-t-60 p-b-60" style="background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 p-b-30">
                    <div class="ltext-103 cl0" style="font-size:2.8rem; font-weight:800; line-height:1;">6+</div>
                    <div class="stext-107 cl7 p-t-10">Proyectos Completados</div>
                </div>
                <div class="col-6 col-md-3 p-b-30">
                    <div class="ltext-103 cl0" style="font-size:2.8rem; font-weight:800; line-height:1;">10+</div>
                    <div class="stext-107 cl7 p-t-10">Clientes Activos</div>
                </div>
                <div class="col-6 col-md-3 p-b-30">
                    <div class="ltext-103 cl0" style="font-size:2.8rem; font-weight:800; line-height:1;">3</div>
                    <div class="stext-107 cl7 p-t-10">Sectores Atendidos</div>
                </div>
                <div class="col-6 col-md-3 p-b-30">
                    <div class="ltext-103 cl0" style="font-size:2.8rem; font-weight:800; line-height:1;">100%</div>
                    <div class="stext-107 cl7 p-t-10">Compromiso</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: PROYECTOS DESTACADOS (preview → /proyectos)
    ════════════════════════════════════════════════════════════════ --}}
    <section class="bg0 p-t-80 p-b-60">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Proyectos Realizados
                </h2>
                <p class="stext-102 cl6 p-t-15" style="max-width:620px; margin:0 auto;">
                    Soluciones reales para negocios reales. Cada proyecto es una historia de transformación digital.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="row">
                {{-- Proyecto 1: E-commerce Multi Sucursal --}}
                <div class="col-md-6 col-lg-4 p-b-30">
                    <div class="bor10 p-lr-30 p-tb-30" style="border-radius:8px; background:#f8f8f8; height:100%;">
                        <div class="p-b-12" style="font-size:2.2rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                        <h5 class="mtext-112 cl2 p-b-10" style="font-weight:700;">E-commerce Multi Sucursal</h5>
                        <p class="stext-102 cl6 p-b-15" style="font-size:.88rem;">
                            Plataforma de ventas en línea con soporte para múltiples sucursales, carrito de compras, pagos en línea y panel administrativo por tenant.
                        </p>
                        <span class="stext-107 cl3" style="background:rgba(0,0,0,.07); border-radius:12px; padding:3px 12px; font-size:.8rem;">
                            E-commerce
                        </span>
                    </div>
                </div>

                {{-- Proyecto 2: Sistema Contable Concesionario --}}
                <div class="col-md-6 col-lg-4 p-b-30">
                    <div class="bor10 p-lr-30 p-tb-30" style="border-radius:8px; background:#f8f8f8; height:100%;">
                        <div class="p-b-12" style="font-size:2.2rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-car"></i>
                        </div>
                        <h5 class="mtext-112 cl2 p-b-10" style="font-weight:700;">Sistema Contable para Concesionario</h5>
                        <p class="stext-102 cl6 p-b-15" style="font-size:.88rem;">
                            Sistema personalizado para la gestión contable de un concesionario de autos, con control de inventario, ventas y reportes financieros.
                        </p>
                        <span class="stext-107 cl3" style="background:rgba(0,0,0,.07); border-radius:12px; padding:3px 12px; font-size:.8rem;">
                            Sistema a la Medida
                        </span>
                    </div>
                </div>

                {{-- Proyecto 3: Virtual Tour 360 --}}
                <div class="col-md-6 col-lg-4 p-b-30">
                    <div class="bor10 p-lr-30 p-tb-30" style="border-radius:8px; background:#f8f8f8; height:100%;">
                        <div class="p-b-12" style="font-size:2.2rem; color:var(--btn_cart,#333);">
                            <i class="fa fa-camera"></i>
                        </div>
                        <h5 class="mtext-112 cl2 p-b-10" style="font-weight:700;">Virtual Tour 360°</h5>
                        <p class="stext-102 cl6 p-b-15" style="font-size:.88rem;">
                            Recorridos virtuales inmersivos para el sector automotriz e inmobiliario. Experiencia 360° que potencia la decisión de compra del cliente.
                        </p>
                        <span class="stext-107 cl3" style="background:rgba(0,0,0,.07); border-radius:12px; padding:3px 12px; font-size:.8rem;">
                            Experiencia Digital
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center p-t-30">
                <a href="{{ url('/proyectos') }}"
                    class="flex-c-m stext-101 cl0 size-116 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-lr-auto"
                    style="display:inline-flex; max-width:260px;">
                    Ver todos los proyectos <i class="fa fa-arrow-right m-l-10"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: ¿POR QUÉ ELEGIRNOS?
    ════════════════════════════════════════════════════════════════ --}}
    <section class="p-t-80 p-b-70" style="background:#f4f4f4;">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    ¿Por qué elegirnos?
                </h2>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="row">
                <div class="col-sm-6 col-lg-3 p-b-30 text-center">
                    <div style="font-size:2.5rem; color:var(--btn_cart,#333); margin-bottom:16px;">
                        <i class="fa fa-code"></i>
                    </div>
                    <h6 class="stext-301 cl2 p-b-10" style="font-weight:700;">Código de Calidad</h6>
                    <p class="stext-107 cl6">Desarrollamos con las mejores prácticas y tecnologías modernas para que tu sistema sea robusto y escalable.</p>
                </div>
                <div class="col-sm-6 col-lg-3 p-b-30 text-center">
                    <div style="font-size:2.5rem; color:var(--btn_cart,#333); margin-bottom:16px;">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <h6 class="stext-301 cl2 p-b-10" style="font-weight:700;">Entrega Rápida</h6>
                    <p class="stext-107 cl6">Metodología ágil que garantiza entregas iterativas. Tu negocio no puede esperar y lo sabemos.</p>
                </div>
                <div class="col-sm-6 col-lg-3 p-b-30 text-center">
                    <div style="font-size:2.5rem; color:var(--btn_cart,#333); margin-bottom:16px;">
                        <i class="fa fa-headset"></i>
                    </div>
                    <h6 class="stext-301 cl2 p-b-10" style="font-weight:700;">Soporte Continuo</h6>
                    <p class="stext-107 cl6">No desaparecemos después de entregar. Te acompañamos en cada paso del crecimiento de tu plataforma.</p>
                </div>
                <div class="col-sm-6 col-lg-3 p-b-30 text-center">
                    <div style="font-size:2.5rem; color:var(--btn_cart,#333); margin-bottom:16px;">
                        <i class="fa fa-lock"></i>
                    </div>
                    <h6 class="stext-301 cl2 p-b-10" style="font-weight:700;">Seguridad Primero</h6>
                    <p class="stext-107 cl6">Tus datos y los de tus clientes son prioridad. Implementamos las mejores prácticas de seguridad web.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: TESTIMONIOS
    ════════════════════════════════════════════════════════════════ --}}
    @if (isset($comments) && count($comments) > 0)
    <section class="bg0 p-t-80 p-b-70">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Lo que dicen nuestros clientes
                </h2>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>
            <div class="row">
                @foreach ($comments->take(3) as $comment)
                <div class="col-md-4 p-b-30">
                    <div class="p-lr-30 p-tb-30 bor10" style="border-radius:8px; height:100%; background:#fafafa; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                        <div class="p-b-16" style="color:#f4c430; font-size:1rem;">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </div>
                        <p class="stext-102 cl6 p-b-20" style="font-style:italic;">
                            "{{ $comment->comment }}"
                        </p>
                        <div class="flex-w flex-m">
                            <div>
                                <span class="stext-107 cl2" style="font-weight:700;">{{ $comment->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: CTA CONTACTO
    ════════════════════════════════════════════════════════════════ --}}
    <section id="contacto" class="p-t-80 p-b-80" style="background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);">
        <div class="container text-center">
            <h2 class="ltext-103 cl0 p-b-20" style="font-size:2rem; font-weight:700;">
                ¿Listo para transformar tu negocio?
            </h2>
            <p class="stext-102 cl7 p-b-35" style="max-width:560px; margin:0 auto;">
                Cuéntanos tu idea y construyamos juntos la solución que tu empresa necesita.
            </p>
            <div class="flex-c-m flex-w" style="gap:16px;">
                @if (isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}"
                    target="_blank"
                    class="flex-c-m stext-101 cl0 size-116 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                    style="display:inline-flex; gap:8px;">
                    <i class="fa fa-whatsapp"></i> Hablemos por WhatsApp
                </a>
                @endif
                @if (isset($tenantinfo->email) && $tenantinfo->email)
                <a href="mailto:{{ $tenantinfo->email }}"
                    class="flex-c-m stext-101 cl3 size-116 bor2 bg0 hov-btn3 p-lr-15 trans-04"
                    style="display:inline-flex; gap:8px; border:2px solid #fff; color:#fff !important;">
                    <i class="fa fa-envelope"></i> Enviar un correo
                </a>
                @endif
            </div>
        </div>
    </section>
    @endif {{-- end if tenant == main --}}

    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        $('.featured-carousel').owlCarousel({
            loop: true,
            margin: 10,

            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        })

        document.addEventListener('DOMContentLoaded', function() {
            var showMoreButtons = document.querySelectorAll('.show-more');

            showMoreButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var cardText = button.previousElementSibling;
                    if (cardText.classList.contains('expanded')) {
                        cardText.classList.remove('expanded');
                        button.textContent = 'Ver más';
                    } else {
                        cardText.classList.add('expanded');
                        button.textContent = 'Ver menos';
                    }
                });
            });
        });
    </script>
@endsection
