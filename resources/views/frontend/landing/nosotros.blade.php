@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Nosotros') . ' - ' . ($tenantinfo->title ?? ''))

@section('styles')
.lp-page-hero {
    background: var(--lp-primary);
    color: #fff;
    padding: 80px 0 60px;
    text-align: center;
}
.lp-page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 700;
    margin-bottom: .75rem;
}
.lp-page-hero p { opacity: .8; font-size: 1.05rem; }

.about-image-wrap {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.12);
}
.about-image-wrap img { width: 100%; height: 420px; object-fit: cover; }

.stat-box {
    text-align: center;
    padding: 1.5rem;
}
.stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--lp-primary);
    line-height: 1;
}
.stat-label { color: #6c757d; font-size: .9rem; margin-top: .3rem; }
@endsection

@section('content')

{{-- ── Page Hero ── --}}
<section class="lp-page-hero">
    <div class="container">
        <h1>{{ $section->titulo ?? 'Nosotros' }}</h1>
        @if($section->subtitulo)
            <p>{{ $section->subtitulo }}</p>
        @endif
    </div>
</section>

{{-- ── Historia / About ── --}}
<section class="lp-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <div class="about-image-wrap">
                    <img src="{{ route('file', $tenantinfo->login_image ?? $tenantinfo->logo ?? '') }}"
                         alt="{{ $tenantinfo->title ?? '' }}">
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="lp-section-title">Nuestra Historia</h2>
                <div class="lp-divider" style="margin-left:0;"></div>

                <div class="lp-section-subtitle" style="margin-bottom:2rem;">
                    {!! $tenantinfo->about_us ?? $tenantinfo->mision ?? '<p>Información sobre nosotros próximamente.</p>' !!}
                </div>

                @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                    <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                       class="btn btn-lp-secondary">
                        <i class="fa fa-whatsapp me-2"></i>Contáctanos
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── Misión ── --}}
@if(isset($tenantinfo->mision) && $tenantinfo->mision && $tenantinfo->mision !== $tenantinfo->about_us)
<section class="lp-section lp-section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="lp-section-title">Nuestra Misión</h2>
            <div class="lp-divider"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <p style="font-size:1.15rem;line-height:1.9;color:#4a4a4a;">
                    {{ $tenantinfo->mision }}
                </p>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── Contacto rápido ── --}}
<section class="lp-section" style="background:var(--lp-primary);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7" style="color:#fff;">
                <h2 style="font-family:'Playfair Display',serif;font-size:2rem;margin-bottom:1rem;">
                    ¿Quieres saber más?
                </h2>
                <p style="opacity:.8;margin-bottom:2rem;">
                    Estamos disponibles para responder todas tus preguntas.
                </p>
                @foreach($sections as $sec)
                    @if($sec->section_key === 'contacto')
                        <a href="{{ route('landing.contacto') }}" class="btn btn-lp-secondary btn-lg">
                            Ir a Contacto
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
