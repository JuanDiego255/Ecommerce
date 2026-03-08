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

/* Cards de presentación */
.lp-feature-card {
    border: none;
    border-radius: 16px;
    padding: 2rem;
    height: 100%;
    transition: box-shadow .25s, transform .25s;
    background: #fff;
}
.lp-feature-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,.1);
    transform: translateY(-4px);
}
.lp-feature-icon {
    width: 56px; height: 56px;
    border-radius: 12px;
    background: var(--lp-primary);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.2rem;
    color: #fff; font-size: 1.4rem;
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

        <div class="row g-4 justify-content-center">
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
                <div class="col-lg-4 col-md-6">
                    <a href="{{ $href }}" class="text-decoration-none">
                        <div class="lp-feature-card">
                            <div class="lp-feature-icon">
                                <i class="fa {{ $icon }}"></i>
                            </div>
                            <h5 style="font-weight:700;color:var(--lp-primary);margin-bottom:.5rem;">
                                {{ $sec->titulo }}
                            </h5>
                            @if($sec->subtitulo)
                                <p style="color:#6c757d;font-size:.9rem;margin-bottom:0;">
                                    {{ $sec->subtitulo }}
                                </p>
                            @endif
                        </div>
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
