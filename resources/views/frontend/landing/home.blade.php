@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Inicio') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    .lp-hero {
        position: relative;
        min-height: 92vh;
        display: flex;
        align-items: center;
        background: var(--navbar, #1a1a2e);
        overflow: hidden;
    }
    .lp-hero-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: .35;
    }
    .lp-hero-content {
        position: relative;
        z-index: 2;
    }
    .lp-hero-title {
        font-size: clamp(2.2rem, 6vw, 4.5rem);
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 1.25rem;
        color: #fff;
    }
    .lp-hero-subtitle {
        font-size: clamp(1rem, 2.5vw, 1.25rem);
        opacity: .85;
        max-width: 560px;
        line-height: 1.7;
        margin-bottom: 2rem;
        color: #fff;
    }
    .lp-scroll-indicator {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        animation: lp-bounce 2s infinite;
    }
    @keyframes lp-bounce {
        0%,100% { transform: translateX(-50%) translateY(0); }
        50%      { transform: translateX(-50%) translateY(10px); }
    }
    .lp-section-card {
        border-radius: 8px;
        background: #f8f8f8;
        height: 100%;
        padding: 2rem 1.8rem;
        transition: box-shadow .2s;
        text-decoration: none;
        display: block;
    }
    .lp-section-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,.1);
        text-decoration: none;
    }
</style>

{{-- ── Hero ── --}}
<section class="lp-hero">
    @php $heroImage = $settings->landing_hero_image ?? null; @endphp
    @if($heroImage)
        <div class="lp-hero-bg"
             style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($heroImage) }}')"></div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-lg-7 lp-hero-content">
                <h1 class="lp-hero-title">
                    {!! $settings->landing_hero_titulo ?? ($tenantinfo->title ?? 'Bienvenidos') !!}
                </h1>
                <p class="lp-hero-subtitle">
                    {!! $settings->landing_hero_subtitulo ?? ($tenantinfo->mision ?? '') !!}
                </p>

                <div class="d-flex flex-wrap gap-2">
                    @if($settings->landing_hero_btn_texto ?? null)
                        <a href="{{ $settings->landing_hero_btn_url ?? route('landing.contacto') }}"
                           class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                           style="display:inline-flex;">
                            {{ $settings->landing_hero_btn_texto }}
                        </a>
                    @endif
                    @foreach($sections as $sec)
                        @if($sec->section_key === 'contacto')
                            <a href="{{ route('landing.contacto') }}"
                               class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                               style="display:inline-flex;">
                                Contáctanos
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="lp-scroll-indicator">
        <i class="fa fa-chevron-down" style="color:rgba(255,255,255,.6);font-size:1.4rem;"></i>
    </div>
</section>

{{-- ── Cards de secciones ── --}}
@php $cardSections = $sections->whereNotIn('section_key', ['inicio']); @endphp
@if($cardSections->count() > 0)
<div class="sec-banner bg0 p-t-80 p-b-30">
    <div class="container">

        <div class="text-center p-b-50">
            <h2 class="ltext-103 cl3" style="font-size:2rem; font-weight:700; letter-spacing:1px;">
                ¿Qué ofrecemos?
            </h2>
            <div class="dis-block" style="width:60px; height:4px; background:var(--btn_cart,#333); margin:18px auto 0;"></div>
        </div>

        <div class="row">
            @foreach($cardSections as $sec)
                @php
                    $icons = [
                        'nosotros'  => 'fa-users',
                        'servicios' => 'fa-star',
                        'faq'       => 'fa-question-circle',
                        'blog'      => 'fa-pencil',
                        'contacto'  => 'fa-envelope',
                    ];
                    $cardRoutes = [
                        'nosotros'  => route('landing.nosotros'),
                        'servicios' => route('landing.servicios'),
                        'faq'       => route('landing.faq'),
                        'blog'      => route('landing.blog'),
                        'contacto'  => route('landing.contacto'),
                    ];
                    $icon = $icons[$sec->section_key] ?? 'fa-circle';
                    $href = $cardRoutes[$sec->section_key] ?? '#';
                @endphp
                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                    <a href="{{ $href }}" class="lp-section-card">
                        <div class="p-b-12" style="font-size:2.2rem; color:var(--btn_cart,#333);">
                            <i class="fa {{ $icon }}"></i>
                        </div>
                        <h5 class="mtext-112 cl2 p-b-10" style="font-weight:700;">
                            {{ $sec->titulo }}
                        </h5>
                        @if($sec->subtitulo)
                            <p class="stext-102 cl6" style="font-size:.88rem; margin-bottom:0;">
                                {{ $sec->subtitulo }}
                            </p>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── CTA Final ── --}}
<section class="p-t-80 p-b-80" style="background:linear-gradient(135deg,var(--navbar,#1a1a2e) 0%,#16213e 100%);text-align:center;">
    <div class="container">
        <h2 class="ltext-103 cl0" style="font-size:2rem;font-weight:700;margin-bottom:1rem;">
            ¿Listo para comenzar?
        </h2>
        <p class="stext-102 cl7" style="max-width:500px;margin:0 auto 2rem;opacity:.8;">
            Contáctanos hoy y cuéntanos sobre tu proyecto.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            @foreach($sections as $sec)
                @if($sec->section_key === 'contacto')
                    <a href="{{ route('landing.contacto') }}"
                       class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                       style="display:inline-flex;">
                        Hablemos <i class="fa fa-arrow-right m-l-10"></i>
                    </a>
                @endif
            @endforeach
            @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                   class="flex-c-m stext-101 cl0 size-116 bor1 p-lr-15 trans-04"
                   style="display:inline-flex;background:rgba(255,255,255,.15);">
                    <i class="fa fa-whatsapp m-r-5"></i> WhatsApp
                </a>
            @endif
        </div>
    </div>
</section>

@endsection
