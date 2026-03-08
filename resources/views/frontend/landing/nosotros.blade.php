@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Nosotros') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    .about-img-wrap {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 16px 60px rgba(0,0,0,.18);
    }
    .about-img-wrap img,
    .about-img-wrap .about-img-placeholder {
        width: 100%;
        height: 460px;
        object-fit: cover;
        display: block;
    }
    .about-img-placeholder {
        background: linear-gradient(135deg, var(--navbar,#222) 0%, var(--btn_cart,#888) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .about-badge {
        position: absolute;
        bottom: 24px;
        left: 24px;
        background: var(--btn_cart, #333);
        color: #fff;
        padding: .55rem 1.4rem;
        border-radius: 40px;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .feature-box {
        border-radius: 12px;
        background: #f8f8f8;
        padding: 1.3rem 1.5rem;
        margin-bottom: .9rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--navbar, #222);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
</style>

{{-- ── Page Banner ── --}}
<section style="background:var(--navbar);padding:72px 0 60px;text-align:center;">
    <h1 class="ltext-105 cl0">{{ $section->titulo ?? 'Nosotros' }}</h1>
    @if($section->subtitulo)
        <p class="stext-102 cl7 p-t-15" style="opacity:.82;max-width:560px;margin:0 auto;">{{ $section->subtitulo }}</p>
    @endif
</section>

{{-- ── Historia / About ── --}}
<div class="bg0 p-t-80 p-b-80">
    <div class="container">
        <div class="row align-items-center" style="gap:2.5rem 0;">

            <div class="col-lg-5">
                <div class="about-img-wrap">
                    @php $aboutImg = $tenantinfo->login_image ?? $tenantinfo->logo ?? null; @endphp
                    @if($aboutImg)
                        <img src="{{ route($ruta, $aboutImg) }}" alt="{{ $tenantinfo->title ?? '' }}">
                    @else
                        <div class="about-img-placeholder">
                            <i class="fa fa-building-o" style="font-size:5rem;color:rgba(255,255,255,.25);"></i>
                        </div>
                    @endif
                    <div class="about-badge">
                        <i class="fa fa-check-circle" style="margin-right:6px;"></i>{{ $tenantinfo->title ?? '' }}
                    </div>
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1">
                <h2 class="ltext-103 cl3" style="font-size:2rem;font-weight:700;margin-bottom:.5rem;">
                    Nuestra Historia
                </h2>
                <div style="width:60px;height:4px;background:var(--btn_cart,#333);margin-bottom:1.8rem;"></div>

                <div class="stext-102 cl6" style="line-height:1.9;font-size:.96rem;margin-bottom:2rem;">
                    {!! $tenantinfo->about_us ?? $tenantinfo->mision ?? '<p>Información sobre nosotros próximamente.</p>' !!}
                </div>

                <div class="feature-box">
                    <div class="feature-icon"><i class="fa fa-users"></i></div>
                    <div>
                        <strong class="cl2" style="display:block;font-size:.93rem;margin-bottom:.2rem;">Equipo comprometido</strong>
                        <span class="stext-102 cl6" style="font-size:.87rem;">Profesionales dedicados a brindar el mejor servicio.</span>
                    </div>
                </div>

                <div class="feature-box">
                    <div class="feature-icon"><i class="fa fa-star"></i></div>
                    <div>
                        <strong class="cl2" style="display:block;font-size:.93rem;margin-bottom:.2rem;">Calidad garantizada</strong>
                        <span class="stext-102 cl6" style="font-size:.87rem;">Nos aseguramos de superar tus expectativas en cada proyecto.</span>
                    </div>
                </div>

                @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                    <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                       class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                       style="display:inline-flex;max-width:220px;margin-top:1.5rem;">
                        <i class="fa fa-whatsapp" style="margin-right:8px;"></i> Contáctanos
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Misión ── --}}
@if(isset($tenantinfo->mision) && $tenantinfo->mision && $tenantinfo->mision !== $tenantinfo->about_us)
<div style="background:#f8f8f8;padding:72px 0;">
    <div class="container">
        <div class="txt-center p-b-50">
            <h2 class="ltext-103 cl3" style="font-size:2rem;font-weight:700;">Nuestra Misión</h2>
            <div style="width:60px;height:4px;background:var(--btn_cart,#333);margin:18px auto 0;"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-7 txt-center">
                <div class="bor10 p-lr-38 p-tb-34"
                     style="border-radius:16px;background:#fff;box-shadow:0 4px 24px rgba(0,0,0,.07);">
                    <i class="fa fa-bullseye"
                       style="font-size:2.5rem;color:var(--btn_cart,#333);margin-bottom:1.2rem;display:block;"></i>
                    <p class="stext-102 cl6" style="font-size:1.05rem;line-height:1.9;margin:0;">
                        {{ $tenantinfo->mision }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── CTA ── --}}
<section style="background:var(--navbar);padding:72px 0;text-align:center;">
    <div class="container">
        <h2 class="ltext-103 cl0" style="font-size:2rem;font-weight:700;margin-bottom:1rem;">
            ¿Quieres saber más?
        </h2>
        <p class="stext-102 cl7" style="opacity:.8;max-width:500px;margin:0 auto 2rem;">
            Estamos disponibles para responder todas tus preguntas.
        </p>
        @foreach($sections as $sec)
            @if($sec->section_key === 'contacto')
                <a href="{{ route('landing.contacto') }}"
                   class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                   style="display:inline-flex;max-width:210px;margin:0 auto;">
                    Ir a Contacto <i class="fa fa-arrow-right" style="margin-left:8px;"></i>
                </a>
            @endif
        @endforeach
    </div>
</section>

@endsection
