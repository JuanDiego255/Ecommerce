@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Contacto') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    .contact-panel {
        background: linear-gradient(160deg, var(--navbar,#1a1a2e) 0%, #16213e 100%);
        padding: 72px 0 80px;
    }
    .contact-info-card {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 14px;
        padding: 1.35rem 1.4rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        margin-bottom: .9rem;
        transition: background .2s;
    }
    .contact-info-card:hover { background: rgba(255,255,255,.12); }
    .contact-info-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--btn_cart, #333);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.05rem;
        flex-shrink: 0;
    }
    .contact-info-label {
        font-size: .72rem;
        color: rgba(255,255,255,.55);
        text-transform: uppercase;
        letter-spacing: .09em;
        margin-bottom: .2rem;
    }
    .contact-info-value {
        color: #fff;
        font-weight: 600;
        font-size: .93rem;
        text-decoration: none;
        display: block;
    }
    .contact-info-value:hover { color: var(--btn_cart, #ccc); }
    .sn-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        font-size: 1.05rem;
        transition: background .2s;
    }
    .sn-btn:hover { background: var(--btn_cart, #555); color: #fff; }
    .contact-form-card {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem 2.5rem 2rem;
        box-shadow: 0 12px 50px rgba(0,0,0,.18);
    }
    .cf-label {
        font-weight: 600;
        color: var(--navbar, #222);
        font-size: .87rem;
        margin-bottom: .4rem;
        display: block;
    }
    .cf-control {
        display: block;
        width: 100%;
        border: 1.5px solid #e8e8e8;
        border-radius: 10px;
        padding: .8rem 1rem;
        font-size: .91rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        background: #fafafa;
        color: #333;
    }
    .cf-control:focus {
        border-color: var(--btn_cart, #333);
        box-shadow: 0 0 0 3px rgba(0,0,0,.06);
        background: #fff;
    }
    .cf-error { color: #dc3545; font-size: .81rem; margin-top: .25rem; display: block; }
    .cf-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        color: #155724;
        font-size: .9rem;
    }
</style>

{{-- ── Page Banner ── --}}
<section style="background:var(--navbar);padding:72px 0 60px;text-align:center;">
    <h1 class="ltext-105 cl0">{{ $section->titulo ?? 'Contáctanos' }}</h1>
    @if($section->subtitulo)
        <p class="stext-102 cl7 p-t-15" style="opacity:.82;max-width:560px;margin:0 auto;">{{ $section->subtitulo }}</p>
    @endif
</section>

{{-- ── Contacto ── --}}
<div class="contact-panel">
    <div class="container">
        <div class="row" style="gap:2.5rem 0;">

            {{-- ─ Info de contacto ─ --}}
            <div class="col-lg-4">
                <h3 class="ltext-103 cl0 p-b-25" style="font-size:1.45rem;font-weight:700;">
                    Información de contacto
                </h3>

                @if(isset($tenantinfo->email) && $tenantinfo->email)
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="fa fa-envelope-o"></i></div>
                        <div>
                            <div class="contact-info-label">Correo electrónico</div>
                            <a href="mailto:{{ $tenantinfo->email }}" class="contact-info-value">
                                {{ $tenantinfo->email }}
                            </a>
                        </div>
                    </div>
                @endif

                @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="fa fa-whatsapp"></i></div>
                        <div>
                            <div class="contact-info-label">WhatsApp</div>
                            <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                               class="contact-info-value">
                                +506 {{ $tenantinfo->whatsapp }}
                            </a>
                        </div>
                    </div>
                @endif

                @if(isset($settings->landing_direccion) && $settings->landing_direccion)
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="fa fa-map-marker"></i></div>
                        <div>
                            <div class="contact-info-label">Dirección</div>
                            <span class="contact-info-value">{{ $settings->landing_direccion }}</span>
                        </div>
                    </div>
                @endif

                @if(isset($settings->landing_horario) && $settings->landing_horario)
                    <div class="contact-info-card">
                        <div class="contact-info-icon"><i class="fa fa-clock-o"></i></div>
                        <div>
                            <div class="contact-info-label">Horario</div>
                            <span class="contact-info-value">{{ $settings->landing_horario }}</span>
                        </div>
                    </div>
                @endif

                {{-- Redes Sociales --}}
                @if($social->count() > 0 || (isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp))
                    <div style="margin-top:1.5rem;">
                        <h6 style="color:rgba(255,255,255,.55);font-size:.72rem;text-transform:uppercase;
                                   letter-spacing:.1em;margin-bottom:1rem;">
                            Síguenos
                        </h6>
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                                <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                                   class="sn-btn" title="WhatsApp">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                            @endif
                            @foreach($social as $sn)
                                @php
                                    $snIcon = 'fa-globe';
                                    $snName = $sn->social_network ?? $sn->name ?? '';
                                    if (stripos($snName, 'facebook')  !== false) $snIcon = 'fa-facebook';
                                    elseif (stripos($snName, 'instagram') !== false) $snIcon = 'fa-instagram';
                                    elseif (stripos($snName, 'twitter')   !== false) $snIcon = 'fa-twitter';
                                    elseif (stripos($snName, 'linkedin')  !== false) $snIcon = 'fa-linkedin';
                                    elseif (stripos($snName, 'youtube')   !== false) $snIcon = 'fa-youtube';
                                @endphp
                                <a href="{{ $sn->url ?? '#' }}" target="_blank"
                                   class="sn-btn" title="{{ $snName }}">
                                    <i class="fa {{ $snIcon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─ Formulario ─ --}}
            <div class="col-lg-7 offset-lg-1">
                <div class="contact-form-card">
                    <h3 class="mtext-112 cl2 p-b-25" style="font-weight:700;font-size:1.35rem;">
                        <i class="fa fa-paper-plane" style="color:var(--btn_cart,#333);margin-right:10px;"></i>
                        Envíanos un mensaje
                    </h3>

                    @if(session('success'))
                        <div class="cf-success">
                            <i class="fa fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('landing.contacto.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6" style="margin-bottom:1.2rem;">
                                <label class="cf-label">Nombre *</label>
                                <input type="text" name="nombre"
                                       class="cf-control"
                                       value="{{ old('nombre') }}" required
                                       placeholder="Tu nombre completo">
                                @error('nombre')
                                    <span class="cf-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6" style="margin-bottom:1.2rem;">
                                <label class="cf-label">Correo electrónico *</label>
                                <input type="email" name="email"
                                       class="cf-control"
                                       value="{{ old('email') }}" required
                                       placeholder="tu@correo.com">
                                @error('email')
                                    <span class="cf-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12" style="margin-bottom:1.2rem;">
                                <label class="cf-label">Teléfono (opcional)</label>
                                <input type="text" name="telefono"
                                       class="cf-control"
                                       value="{{ old('telefono') }}"
                                       placeholder="Ej: 8888-8888">
                            </div>
                            <div class="col-12" style="margin-bottom:1.5rem;">
                                <label class="cf-label">Mensaje *</label>
                                <textarea name="mensaje" rows="5"
                                          class="cf-control"
                                          required
                                          placeholder="¿En qué podemos ayudarte?">{{ old('mensaje') }}</textarea>
                                @error('mensaje')
                                    <span class="cf-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit"
                                        class="flex-c-m stext-101 cl0 bg1 bor1 hov-btn1 trans-04"
                                        style="display:flex;width:100%;height:52px;border-radius:10px;
                                               font-size:.95rem;font-weight:700;cursor:pointer;">
                                    <i class="fa fa-paper-plane" style="margin-right:8px;"></i> Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
