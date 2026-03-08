@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Servicios') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    .service-wrap {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.08);
        transition: box-shadow .25s, transform .25s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .service-wrap:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,.14);
        transform: translateY(-4px);
    }
    .service-img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        display: block;
    }
    .service-img-placeholder {
        width: 100%;
        height: 210px;
        background: linear-gradient(135deg, var(--navbar,#222) 0%, var(--btn_cart,#888) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.35);
        font-size: 3rem;
    }
    .service-body { padding: 1.4rem 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .service-badge {
        display: inline-block;
        background: var(--navbar, #222);
        color: #fff;
        font-size: .71rem;
        padding: .2rem .75rem;
        border-radius: 40px;
        margin-bottom: .7rem;
        letter-spacing: .05em;
        text-transform: uppercase;
    }
    .service-price { font-size: 1.2rem; font-weight: 700; color: var(--btn_cart, #333); }
    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #f0f0f0;
        padding-top: .9rem;
        margin-top: auto;
    }
</style>

{{-- ── Page Banner ── --}}
<section style="background:var(--navbar);padding:72px 0 60px;text-align:center;">
    <h1 class="ltext-105 cl0">{{ $section->titulo ?? 'Nuestros Servicios' }}</h1>
    @if($section->subtitulo)
        <p class="stext-102 cl7 p-t-15" style="opacity:.82;max-width:560px;margin:0 auto;">{{ $section->subtitulo }}</p>
    @endif
</section>

{{-- ── Listado de Servicios ── --}}
<div class="bg0 p-t-80 p-b-50">
    <div class="container">

        @if($services->isEmpty())
            <div class="txt-center p-t-50 p-b-50">
                <i class="fa fa-briefcase" style="font-size:3.5rem;color:#dee2e6;"></i>
                <p class="stext-102 cl6 p-t-20">Próximamente publicaremos nuestros servicios.</p>
            </div>
        @else
            @php $grouped = $services->groupBy(fn($s) => $s->category?->nombre ?? ''); @endphp

            @foreach($grouped as $categoryName => $group)

                @if($grouped->count() > 1 && $categoryName)
                    <div class="p-b-20 p-t-20">
                        <h3 class="ltext-103 cl3" style="font-size:1.5rem;font-weight:700;">
                            {{ $categoryName }}
                        </h3>
                        <div style="width:50px;height:3px;background:var(--btn_cart,#333);margin-top:.5rem;"></div>
                    </div>
                @endif

                <div class="row p-b-50">
                    @foreach($group as $service)
                        <div class="col-md-6 col-xl-4 p-b-30">
                            <div class="service-wrap">

                                @if($service->image)
                                    <img src="{{ route($ruta, $service->image) }}"
                                         alt="{{ $service->nombre }}" class="service-img">
                                @else
                                    <div class="service-img-placeholder">
                                        <i class="fa fa-scissors"></i>
                                    </div>
                                @endif

                                <div class="service-body">
                                    @if($service->category?->nombre)
                                        <span class="service-badge">{{ $service->category->nombre }}</span>
                                    @endif

                                    <h5 class="mtext-112 cl2 p-b-8" style="font-weight:700;">
                                        {{ $service->nombre }}
                                    </h5>

                                    @if($service->descripcion)
                                        <p class="stext-102 cl6" style="font-size:.87rem;line-height:1.65;flex:1;margin-bottom:.5rem;">
                                            {{ Str::limit($service->descripcion, 120) }}
                                        </p>
                                    @endif

                                    <div class="service-footer">
                                        <span class="service-price">
                                            @if($service->base_price_cents > 0)
                                                ₡{{ number_format($service->base_price_cents / 100, 0) }}
                                            @else
                                                Consultar
                                            @endif
                                        </span>
                                        @if($service->duration_minutes)
                                            <span class="stext-102 cl6" style="font-size:.82rem;">
                                                <i class="fa fa-clock-o" style="margin-right:4px;"></i>{{ $service->duration_minutes }} min
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
</div>

{{-- ── CTA ── --}}
@foreach($sections as $sec)
    @if($sec->section_key === 'contacto')
        <section style="background:#f8f8f8;padding:72px 0;text-align:center;">
            <div class="container">
                <h2 class="ltext-103 cl3" style="font-size:2rem;font-weight:700;margin-bottom:.5rem;">
                    ¿Te interesa algún servicio?
                </h2>
                <div style="width:60px;height:4px;background:var(--btn_cart,#333);margin:16px auto 1.5rem;"></div>
                <p class="stext-102 cl6 p-b-30">Contáctanos y con gusto te asesoramos</p>
                <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem;">
                    <a href="{{ route('landing.contacto') }}"
                       class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                       style="display:inline-flex;">
                        Solicitar Información
                    </a>
                    @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                        <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                           class="flex-c-m stext-101 cl0 size-101 bor1 p-lr-15 trans-04"
                           style="display:inline-flex;background:var(--navbar);">
                            <i class="fa fa-whatsapp" style="margin-right:6px;"></i> WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </section>
        @break
    @endif
@endforeach

@endsection
