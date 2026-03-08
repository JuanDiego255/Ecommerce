@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Inicio') . ' - ' . ($tenantinfo->title ?? ''))

@section('styles')
.lp-hero {
    position: relative;
    min-height: 92vh;
    display: flex;
    align-items: center;
    background: var(--lp-primary);
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
    color: var(--lp-text-hero);
}
.lp-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.2rem, 6vw, 4.5rem);
    font-weight: 700;
    line-height: 1.15;
    margin-bottom: 1.25rem;
}
.lp-hero-subtitle {
    font-size: clamp(1rem, 2.5vw, 1.3rem);
    opacity: .85;
    max-width: 560px;
    line-height: 1.7;
    margin-bottom: 2rem;
}
.lp-scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    animation: bounce 2s infinite;
}
@keyframes bounce {
    0%,100%{transform:translateX(-50%) translateY(0)}
    50%{transform:translateX(-50%) translateY(10px)}
}

/* Items de secciones */
.lp-section-item {
    display: block;
    text-align: center;
    padding: 2rem 1rem;
    text-decoration: none;
    border-top: 2px solid transparent;
    transition: border-color .2s;
}
.lp-section-item:hover {
    border-top-color: var(--lp-primary);
}
.lp-section-item-icon {
    font-size: 2rem;
    color: var(--lp-primary);
    margin-bottom: 1rem;
    display: block;
}
.lp-section-item-title {
    font-weight: 700;
    color: var(--lp-text, #1a1a1a);
    margin-bottom: .4rem;
    font-size: 1.05rem;
}
.lp-section-item-sub {
    color: #6c757d;
    font-size: .88rem;
    margin-bottom: 0;
    line-height: 1.6;
}
@endsection

@section('content')

{{-- ── Hero ── --}}
<section class="lp-hero">
    @php
        $heroImage = $settings->landing_hero_image ?? null;
    @endphp
    @if($heroImage)
        <div class="lp-hero-bg"
             style="background-image:url('{{ Storage::url($heroImage) }}')"></div>
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
                @if($settings->landing_hero_btn_texto ?? null)
                    <a href="{{ $settings->landing_hero_btn_url ?? route('landing.contacto') }}"
                       class="btn btn-lp-secondary btn-lg me-3">
                        {{ $settings->landing_hero_btn_texto }}
                    </a>
                @endif
                @foreach($sections as $sec)
                    @if($sec->section_key === 'contacto')
                        <a href="{{ route('landing.contacto') }}" class="btn btn-lp-primary btn-lg">
                            Contáctanos
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="lp-scroll-indicator">
        <i class="fa fa-chevron-down" style="color:rgba(255,255,255,.6);font-size:1.4rem;"></i>
    </div>
</section>

{{-- ── Cards rápidas de secciones activas ── --}}
@php $cardSections = $sections->whereNotIn('section_key', ['inicio']); @endphp
@if($cardSections->count() > 0)
<section class="lp-section lp-section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="lp-section-title">¿Qué ofrecemos?</h2>
            <div class="lp-divider"></div>
            <p class="lp-section-subtitle">Conoce todo lo que tenemos para ti</p>
        </div>

        <div class="row g-0 justify-content-center" style="border-bottom: 1px solid #eee;">
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
                <div class="col-lg-3 col-md-6" style="border-top: 1px solid #eee; border-left: 1px solid #eee;">
                    <a href="{{ $href }}" class="lp-section-item">
                        <i class="fa {{ $icon }} lp-section-item-icon"></i>
                        <div class="lp-section-item-title">{{ $sec->titulo }}</div>
                        @if($sec->subtitulo)
                            <p class="lp-section-item-sub">{{ $sec->subtitulo }}</p>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA Final ── --}}
<section class="lp-section" style="background:var(--lp-primary);color:#fff;text-align:center;">
    <div class="container">
        <h2 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:1rem;">
            ¿Listo para comenzar?
        </h2>
        <p style="opacity:.8;max-width:500px;margin:0 auto 2rem;">
            Contáctanos hoy y cuéntanos sobre tu proyecto.
        </p>
        @foreach($sections as $sec)
            @if($sec->section_key === 'contacto')
                <a href="{{ route('landing.contacto') }}" class="btn btn-lp-secondary btn-lg">
                    Hablemos <i class="fa fa-arrow-right ms-2"></i>
                </a>
            @endif
        @endforeach
        @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
            <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
               class="btn btn-lg ms-3"
               style="background:rgba(255,255,255,.15);color:#fff;border-radius:50px;">
                <i class="fa fa-whatsapp me-1"></i> WhatsApp
            </a>
        @endif
    </div>
</section>

@endsection
