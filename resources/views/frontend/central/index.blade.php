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

        /* ── Cómo Funciona ── */
        .sw-step-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--btn_cart, #333);
            color: #fff;
            font-size: 1.6rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            z-index: 1;
            box-shadow: 0 6px 20px rgba(0,0,0,.3);
        }
        .sw-step-connector {
            position: absolute;
            top: 36px;
            left: calc(50% + 36px);
            width: calc(100% - 72px);
            height: 3px;
            background: rgba(255,255,255,.2);
            z-index: 0;
        }
        @media (max-width: 767px) {
            .sw-step-connector { display: none !important; }
        }

        /* ── Stack Tech Badges ── */
        .sw-tech-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: .82rem;
            font-weight: 600;
            margin: 5px;
            color: #fff;
            letter-spacing: .3px;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        /* ── FAQ Accordion ── */
        .sw-faq-btn {
            background: #fff;
            border: none;
            width: 100%;
            text-align: left;
            padding: 18px 24px;
            font-weight: 600;
            font-size: .95rem;
            color: #222;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background .2s;
        }
        .sw-faq-btn:hover { background: #f9f9f9; }
        .sw-faq-btn[aria-expanded="true"] { color: var(--btn_cart, #333); }
        .sw-faq-body {
            padding: 16px 24px;
            font-size: .9rem;
            color: #555;
            background: #fff;
            border-bottom: 1px solid #eee;
            line-height: 1.7;
        }
        .sw-faq-wrap {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 16px rgba(0,0,0,.07);
        }

        /* ── Calculadora ── */
        .sw-calc-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 30px rgba(0,0,0,.08);
            padding: 32px;
        }
        .sw-calc-option {
            display: flex;
            align-items: center;
            padding: 13px 16px;
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            margin-bottom: 10px;
        }
        .sw-calc-option:hover { border-color: var(--btn_cart, #333); background: #f9f9f9; }
        .sw-calc-option input[type="radio"],
        .sw-calc-option input[type="checkbox"] {
            margin-right: 12px;
            cursor: pointer;
            accent-color: var(--btn_cart, #333);
            min-width: 16px;
        }
        .sw-calc-price-display {
            background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);
            border-radius: 12px;
            padding: 32px 24px;
            text-align: center;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .sw-calc-total { font-size: 2.4rem; font-weight: 800; color: #fff; line-height: 1.1; }
        .sw-calc-label { font-size: .78rem; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
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

    {{-- ═══════════════════════════════════════════════════════════════
         SECCIÓN: CÓMO FUNCIONA
    ════════════════════════════════════════════════════════════════ --}}
    <section class="p-t-80 p-b-80" style="background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 100%);">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl0" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    ¿Cómo funciona?
                </h2>
                <p class="stext-102 cl7 p-t-15" style="max-width:560px; margin:0 auto;">
                    Desde la idea hasta el lanzamiento, te acompañamos en cada etapa del proceso.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#e8b84b); margin:18px auto 0;"></div>
            </div>

            <div class="row justify-content-center">
                {{-- Paso 1: Cotizar --}}
                <div class="col-md-4 p-b-40 text-center position-relative">
                    <div class="sw-step-circle">1</div>
                    <div class="sw-step-connector d-none d-md-block"></div>
                    <h5 class="mtext-112 cl0 p-b-12" style="font-weight:700; font-size:1.15rem;">
                        <i class="fa fa-comments m-r-8" style="color:var(--btn_cart,#e8b84b);"></i> Cotizar
                    </h5>
                    <p class="stext-107 cl7" style="max-width:260px; margin:0 auto;">
                        Cuéntanos tu idea. Analizamos tu proyecto, tus objetivos y preparamos una propuesta personalizada sin costo.
                    </p>
                </div>

                {{-- Paso 2: Desarrollar --}}
                <div class="col-md-4 p-b-40 text-center position-relative">
                    <div class="sw-step-circle">2</div>
                    <div class="sw-step-connector d-none d-md-block"></div>
                    <h5 class="mtext-112 cl0 p-b-12" style="font-weight:700; font-size:1.15rem;">
                        <i class="fa fa-code m-r-8" style="color:var(--btn_cart,#e8b84b);"></i> Desarrollar
                    </h5>
                    <p class="stext-107 cl7" style="max-width:260px; margin:0 auto;">
                        Construimos tu solución de forma iterativa. Revisiones continuas para que el resultado sea exactamente lo que necesitas.
                    </p>
                </div>

                {{-- Paso 3: Lanzar --}}
                <div class="col-md-4 p-b-40 text-center position-relative">
                    <div class="sw-step-circle">3</div>
                    <h5 class="mtext-112 cl0 p-b-12" style="font-weight:700; font-size:1.15rem;">
                        <i class="fa fa-rocket m-r-8" style="color:var(--btn_cart,#e8b84b);"></i> Lanzar
                    </h5>
                    <p class="stext-107 cl7" style="max-width:260px; margin:0 auto;">
                        Publicamos tu proyecto, migramos datos si aplica y te capacitamos. Soporte post-lanzamiento incluido.
                    </p>
                </div>
            </div>
        </div>
    </section>

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
         SECCIÓN: STACK TECNOLÓGICO
    ════════════════════════════════════════════════════════════════ --}}
    <section class="bg0 p-t-70 p-b-70">
        <div class="container">
            <div class="text-center p-b-40">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Stack Tecnológico
                </h2>
                <p class="stext-102 cl6 p-t-15" style="max-width:540px; margin:0 auto;">
                    Construimos con tecnologías robustas y probadas que garantizan rendimiento, seguridad y escalabilidad.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="text-center">
                <span class="sw-tech-badge" style="background:#f05340;"><i class="fa fa-leaf"></i> Laravel</span>
                <span class="sw-tech-badge" style="background:#8892be;"><i class="fa fa-code"></i> PHP 8</span>
                <span class="sw-tech-badge" style="background:#00758f;"><i class="fa fa-database"></i> MySQL</span>
                <span class="sw-tech-badge" style="background:#7952b3;"><i class="fa fa-columns"></i> Bootstrap 5</span>
                <span class="sw-tech-badge" style="background:#c9a800; color:#222;"><i class="fa fa-terminal"></i> JavaScript</span>
                <span class="sw-tech-badge" style="background:#e1306c;"><i class="fa fa-instagram"></i> Instagram Graph API</span>
                <span class="sw-tech-badge" style="background:#e34f26;"><i class="fa fa-html5"></i> HTML5</span>
                <span class="sw-tech-badge" style="background:#264de4;"><i class="fa fa-css3"></i> CSS3</span>
                <span class="sw-tech-badge" style="background:#0769ad;"><i class="fa fa-code"></i> jQuery</span>
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
         SECCIÓN: PREGUNTAS FRECUENTES (FAQ)
    ════════════════════════════════════════════════════════════════ --}}
    <section class="p-t-80 p-b-80" style="background:#f4f4f4;">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Preguntas Frecuentes
                </h2>
                <p class="stext-102 cl6 p-t-15" style="max-width:540px; margin:0 auto;">
                    Resolvemos las dudas más comunes antes de que empieces tu proyecto.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="sw-faq-wrap" id="sw-faq-accordion">

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq1" aria-expanded="false" aria-controls="faq1">
                                ¿Cuánto cuesta desarrollar un proyecto?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq1" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    El costo varía según el tipo de proyecto y los módulos requeridos. Una <strong>Landing Page</strong> parte desde $200 USD, un <strong>E-commerce Básico</strong> desde $500 USD y los <strong>Sistemas a la Medida</strong> desde $1,000 USD. Usa nuestra <a href="#calculadora" style="color:var(--btn_cart,#333);">calculadora de cotización</a> para obtener un estimado inicial sin compromiso.
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                ¿Qué incluye el precio del proyecto?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq2" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    El precio incluye diseño responsivo, desarrollo back-end y front-end, panel de administración, configuración inicial del servidor, capacitación básica de uso y <strong>30 días de soporte post-lanzamiento</strong>. El dominio y el hosting no están incluidos en el precio base.
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                ¿Puedo personalizar los colores, logo y diseño?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq3" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    Absolutamente. Cada proyecto es <strong>100% personalizado</strong> a la identidad visual de tu marca. Trabajamos con tu paleta de colores, tipografías y activos de diseño. Si no tienes identidad visual, también ofrecemos servicios de branding básico como módulo adicional.
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                ¿Cuánto tiempo tarda el desarrollo?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq4" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    Los tiempos estimados son: <strong>Landing Page</strong> 1–2 semanas, <strong>E-commerce Básico</strong> 3–5 semanas, <strong>E-commerce Multi Sucursal</strong> 6–10 semanas, <strong>Sistemas a la Medida</strong> 8–16 semanas según complejidad. Trabajamos con entregas iterativas para que puedas ver el avance en todo momento.
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq6" aria-expanded="false" aria-controls="faq6">
                                ¿Qué soporte ofrecen después del lanzamiento?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq6" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    Incluimos <strong>30 días de soporte correctivo</strong> post-lanzamiento sin costo adicional. Después de ese período ofrecemos planes de mantenimiento mensual que incluyen actualizaciones, corrección de errores, respaldo de datos y mejoras menores. Puedes contactarnos en cualquier momento vía WhatsApp.
                                </div>
                            </div>
                        </div>

                        <div>
                            <button class="sw-faq-btn collapsed" type="button" data-toggle="collapse" data-target="#faq7" aria-expanded="false" aria-controls="faq7">
                                ¿El sitio funciona bien en dispositivos móviles?
                                <i class="fa fa-plus sw-faq-icon"></i>
                            </button>
                            <div id="faq7" class="collapse" data-parent="#sw-faq-accordion">
                                <div class="sw-faq-body">
                                    Sí, todos nuestros proyectos son <strong>100% responsivos</strong>. Diseñamos con la metodología Mobile First, garantizando que la experiencia sea óptima en smartphones, tablets y escritorios. Además optimizamos el rendimiento con imágenes comprimidas y código limpio para cargas rápidas incluso en conexiones móviles.
                                </div>
                            </div>
                        </div>

                    </div>
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
                            "{{ $comment->description }}"
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
         SECCIÓN: CALCULADORA DE COTIZACIÓN
    ════════════════════════════════════════════════════════════════ --}}
    <section id="calculadora" class="bg0 p-t-80 p-b-80">
        <div class="container">
            <div class="text-center p-b-50">
                <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                    Calculadora de Cotización
                </h2>
                <p class="stext-102 cl6 p-t-15" style="max-width:560px; margin:0 auto;">
                    Obtén un estimado de inversión al instante. Selecciona el tipo de proyecto y los módulos adicionales.
                </p>
                <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
            </div>

            <div class="row">
                {{-- Columna 1: Tipo de proyecto --}}
                <div class="col-lg-5 p-b-30">
                    <div class="sw-calc-card" style="height:100%;">
                        <h5 class="mtext-112 cl2 p-b-20" style="font-weight:700;">
                            <i class="fa fa-list-alt m-r-10" style="color:var(--btn_cart,#333);"></i> Tipo de Proyecto
                        </h5>

                        <label class="sw-calc-option">
                            <input type="radio" name="calc_type" value="200,500" data-label="Landing Page" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Landing Page</strong>
                                <span style="font-size:.8rem; color:#888;">Desde $200 – $500 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="radio" name="calc_type" value="500,800" data-label="E-commerce potente" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">E-commerce potente</strong>
                                <span style="font-size:.8rem; color:#888;">Desde $500 – $800 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="radio" name="calc_type" value="1500,5000" data-label="Sistema a la Medida" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Sistema a la Medida</strong>
                                <span style="font-size:.8rem; color:#888;">Desde $1,500 – $5,000+ USD</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Columna 2: Módulos adicionales --}}
                <div class="col-lg-4 p-b-30">
                    <div class="sw-calc-card" style="height:100%;">
                        <h5 class="mtext-112 cl2 p-b-20" style="font-weight:700;">
                            <i class="fa fa-puzzle-piece m-r-10" style="color:var(--btn_cart,#333);"></i> Módulos Adicionales
                        </h5>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="300" data-label="Blog Integrado" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Blog Integrado</strong>
                                <span style="font-size:.8rem; color:#888;">+$300 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="400" data-label="Sistema de Reservas (Booking)" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Sistema de Reservas</strong>
                                <span style="font-size:.8rem; color:#888;">+$400 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="350" data-label="Integración Instagram API" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Integración Instagram API</strong>
                                <span style="font-size:.8rem; color:#888;">+$350 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="500" data-label="Virtual Tour 360°" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Virtual Tour 360°</strong>
                                <span style="font-size:.8rem; color:#888;">+$500 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="200" data-label="Pasarela de Pagos (PayPal/SINPE)" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Pasarela de Pagos</strong>
                                <span style="font-size:.8rem; color:#888;">+$200 USD</span>
                            </div>
                        </label>

                        <label class="sw-calc-option">
                            <input type="checkbox" name="calc_addon" value="500" data-label="Panel Admin Avanzado" onchange="swCalcUpdate()">
                            <div>
                                <strong style="display:block; font-size:.92rem;">Panel Admin Avanzado</strong>
                                <span style="font-size:.8rem; color:#888;">+$500 USD</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Columna 3: Resultado --}}
                <div class="col-lg-3 p-b-30">
                    <div class="sw-calc-price-display" style="height:100%; min-height:380px;">
                        <div class="sw-calc-label">Estimado de Inversión</div>
                        <div class="sw-calc-total" id="sw-calc-result">$0</div>
                        <div class="stext-107 p-t-8" id="sw-calc-range"
                             style="font-size:.82rem; color:rgba(255,255,255,.6); min-height:24px;">
                            Selecciona un tipo de proyecto
                        </div>
                        <div class="p-t-16 p-b-10" id="sw-calc-selection"
                             style="font-size:.8rem; text-align:left; color:rgba(255,255,255,.75); min-height:80px; line-height:1.8;">
                        </div>
                        @if (isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                        <a id="sw-calc-wa"
                           href="https://wa.me/506{{ $tenantinfo->whatsapp }}?text=Hola%20Safewor%2C%20estoy%20interesado%20en%20un%20proyecto."
                           target="_blank"
                           class="flex-c-m stext-101 cl0 size-116 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-t-10"
                           style="display:inline-flex; gap:8px; text-decoration:none; width:100%; justify-content:center;">
                            <i class="fa fa-whatsapp"></i> Solicitar Cotización
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                    style="display:inline-flex; gap:8px; border:2px solid #fff; color:#000 !important;">
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

            // ── FAQ icon toggle ──
            document.querySelectorAll('.sw-faq-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var icon = btn.querySelector('.sw-faq-icon');
                    var targetId = btn.getAttribute('data-target');
                    var target = document.querySelector(targetId);
                    // Wait for Bootstrap collapse animation to start
                    setTimeout(function() {
                        if (target && target.classList.contains('show')) {
                            if (icon) icon.className = 'fa fa-minus sw-faq-icon';
                            btn.setAttribute('aria-expanded', 'true');
                        } else {
                            if (icon) icon.className = 'fa fa-plus sw-faq-icon';
                            btn.setAttribute('aria-expanded', 'false');
                        }
                    }, 50);
                });
            });
        });

        // ── Calculadora de Cotización ──
        function swCalcUpdate() {
            var typeInput = document.querySelector('input[name="calc_type"]:checked');
            var addonInputs = document.querySelectorAll('input[name="calc_addon"]:checked');
            var baseMin = 0, baseMax = 0, addonsTotal = 0;
            var selectionParts = [];

            if (typeInput) {
                var vals = typeInput.value.split(',');
                baseMin = parseInt(vals[0]);
                baseMax = parseInt(vals[1]);
                selectionParts.push('<span style="color:#7ef7b1;">✔</span> ' + typeInput.getAttribute('data-label'));
            }

            addonInputs.forEach(function(inp) {
                addonsTotal += parseInt(inp.value);
                selectionParts.push('<span style="color:#7ef7b1;">✔</span> ' + inp.getAttribute('data-label'));
            });

            var totalMin = baseMin + addonsTotal;
            var totalMax = baseMax + addonsTotal;

            var resultEl = document.getElementById('sw-calc-result');
            var rangeEl  = document.getElementById('sw-calc-range');
            var selEl    = document.getElementById('sw-calc-selection');
            var waBtn    = document.getElementById('sw-calc-wa');

            if (totalMin === 0) {
                resultEl.textContent = '$0';
                rangeEl.textContent  = 'Selecciona un tipo de proyecto';
            } else {
                resultEl.textContent = '$' + totalMin.toLocaleString();
                rangeEl.textContent  = 'Hasta $' + totalMax.toLocaleString() + ' USD (estimado)';
            }

            selEl.innerHTML = selectionParts.map(function(s) { return '<div>' + s + '</div>'; }).join('');

            if (waBtn && typeInput) {
                var addonLabels = Array.from(addonInputs).map(function(i) { return i.getAttribute('data-label'); });
                var msgParts = [
                    'Hola Safewor, estoy interesado en un proyecto.',
                    'Tipo: ' + typeInput.getAttribute('data-label'),
                ];
                if (addonLabels.length > 0) {
                    msgParts.push('Módulos: ' + addonLabels.join(', '));
                }
                msgParts.push('Estimado: $' + totalMin.toLocaleString() + ' – $' + totalMax.toLocaleString() + ' USD');
                waBtn.href = 'https://wa.me/506{{ $tenantinfo->whatsapp ?? "" }}?text=' + encodeURIComponent(msgParts.join('\n'));
            }
        }
    </script>
@endsection
