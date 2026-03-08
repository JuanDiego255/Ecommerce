@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Servicios') . ' - ' . ($tenantinfo->title ?? ''))

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

.service-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    transition: box-shadow .25s, transform .25s;
    height: 100%;
}
.service-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,.14);
    transform: translateY(-4px);
}
.service-card-img {
    width: 100%; height: 200px;
    object-fit: cover;
    background: var(--lp-bg-section);
}
.service-card-img-placeholder {
    width: 100%; height: 200px;
    background: linear-gradient(135deg, var(--lp-primary) 0%, var(--lp-secondary) 100%);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 2.5rem;
}
.service-card-body { padding: 1.5rem; }
.service-price {
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--lp-secondary);
}
.service-duration {
    font-size: .85rem;
    color: #6c757d;
}
.service-badge {
    display: inline-block;
    background: var(--lp-primary);
    color: #fff;
    font-size: .75rem;
    padding: .25rem .75rem;
    border-radius: 50px;
    margin-bottom: .75rem;
}
@endsection

@section('content')

{{-- ── Page Hero ── --}}
<section class="lp-page-hero">
    <div class="container">
        <h1>{{ $section->titulo ?? 'Nuestros Servicios' }}</h1>
        @if($section->subtitulo)
            <p>{{ $section->subtitulo }}</p>
        @endif
    </div>
</section>

{{-- ── Listado de Servicios ── --}}
<section class="lp-section">
    <div class="container">

        @if($services->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-briefcase" style="font-size:3rem;color:#dee2e6;"></i>
                <p class="mt-3" style="color:#6c757d;">Próximamente publicaremos nuestros servicios.</p>
            </div>
        @else
            @php
                // Agrupar por categoría si existe
                $grouped = $services->groupBy(fn($s) => $s->category?->nombre ?? 'Servicios');
            @endphp

            @foreach($grouped as $categoryName => $group)
                @if($grouped->count() > 1)
                    <div class="mb-4">
                        <h3 style="font-family:'Playfair Display',serif;color:var(--lp-primary);font-size:1.6rem;">
                            {{ $categoryName }}
                        </h3>
                        <hr style="border-color:var(--lp-secondary);border-width:2px;width:60px;margin-top:.5rem;">
                    </div>
                @endif

                <div class="row g-4 mb-5">
                    @foreach($group as $service)
                        <div class="col-lg-4 col-md-6">
                            <div class="service-card">
                                {{-- Imagen del servicio --}}
                                @if($service->image)
                                    <img src="{{ route('file', $service->image) }}"
                                         alt="{{ $service->nombre }}" class="service-card-img">
                                @else
                                    <div class="service-card-img-placeholder">
                                        <i class="fa fa-scissors"></i>
                                    </div>
                                @endif

                                <div class="service-card-body">
                                    @if($service->category?->nombre)
                                        <span class="service-badge">{{ $service->category->nombre }}</span>
                                    @endif
                                    <h5 style="font-weight:700;color:var(--lp-primary);margin-bottom:.4rem;">
                                        {{ $service->nombre }}
                                    </h5>
                                    @if($service->descripcion)
                                        <p style="color:#6c757d;font-size:.9rem;line-height:1.6;margin-bottom:1rem;">
                                            {{ Str::limit($service->descripcion, 120) }}
                                        </p>
                                    @endif
                                    <div class="d-flex align-items-center justify-content-between">
                                        @if($service->base_price_cents > 0)
                                            <span class="service-price">
                                                ₡{{ number_format($service->base_price_cents / 100, 0) }}
                                            </span>
                                        @else
                                            <span class="service-price">Consultar</span>
                                        @endif
                                        @if($service->duration_minutes)
                                            <span class="service-duration">
                                                <i class="fa fa-clock-o me-1"></i>{{ $service->duration_minutes }} min
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif

    </div>
</section>

{{-- ── CTA ── --}}
@foreach($sections as $sec)
    @if($sec->section_key === 'contacto')
        <section class="lp-section" style="background:var(--lp-bg-section);text-align:center;">
            <div class="container">
                <h2 class="lp-section-title">¿Te interesa algún servicio?</h2>
                <div class="lp-divider"></div>
                <p class="lp-section-subtitle">Contáctanos y con gusto te asesoramos</p>
                <a href="{{ route('landing.contacto') }}" class="btn btn-lp-primary btn-lg">
                    Solicitar Información
                </a>
                @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                    <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                       class="btn btn-lp-secondary btn-lg ms-3">
                        <i class="fa fa-whatsapp me-1"></i> WhatsApp
                    </a>
                @endif
            </div>
        </section>
        @break
    @endif
@endforeach

@endsection
