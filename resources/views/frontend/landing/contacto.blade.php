@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Contacto') . ' - ' . ($tenantinfo->title ?? ''))

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

.contact-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 8px 40px rgba(0,0,0,.09);
}
.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.75rem;
}
.contact-info-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: var(--lp-primary);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #fff; font-size: 1.1rem;
}
.contact-info-text strong { display: block; color: var(--lp-primary); font-weight: 600; }
.contact-info-text span { color: #6c757d; font-size: .92rem; }
.contact-info-text a { color: #6c757d; text-decoration: none; }
.contact-info-text a:hover { color: var(--lp-secondary); }

.form-control-lp {
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    padding: .875rem 1rem;
    font-size: .95rem;
    transition: border-color .2s, box-shadow .2s;
}
.form-control-lp:focus {
    border-color: var(--lp-secondary);
    box-shadow: 0 0 0 .2rem rgba(201,168,76,.18);
    outline: none;
}
.form-label-lp {
    font-weight: 600;
    color: var(--lp-primary);
    font-size: .9rem;
    margin-bottom: .4rem;
}
@endsection

@section('content')

{{-- ── Page Hero ── --}}
<section class="lp-page-hero">
    <div class="container">
        <h1>{{ $section->titulo ?? 'Contáctanos' }}</h1>
        @if($section->subtitulo)
            <p>{{ $section->subtitulo }}</p>
        @endif
    </div>
</section>

{{-- ── Contacto ── --}}
<section class="lp-section">
    <div class="container">
        <div class="row g-5 justify-content-center">

            {{-- ─ Info de contacto ─ --}}
            <div class="col-lg-4">
                <h2 class="lp-section-title" style="font-size:1.7rem;">Información de contacto</h2>
                <div class="lp-divider" style="margin-left:0;margin-bottom:2rem;"></div>

                @if(isset($tenantinfo->email) && $tenantinfo->email)
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-envelope-o"></i>
                        </div>
                        <div class="contact-info-text">
                            <strong>Correo electrónico</strong>
                            <a href="mailto:{{ $tenantinfo->email }}">{{ $tenantinfo->email }}</a>
                        </div>
                    </div>
                @endif

                @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                    <div class="contact-info-item">
                        <div class="contact-info-icon" style="background:var(--lp-secondary);">
                            <i class="fa fa-whatsapp"></i>
                        </div>
                        <div class="contact-info-text">
                            <strong>WhatsApp</strong>
                            <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank">
                                +506 {{ $tenantinfo->whatsapp }}
                            </a>
                        </div>
                    </div>
                @endif

                @if(isset($settings->landing_direccion) && $settings->landing_direccion)
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="contact-info-text">
                            <strong>Dirección</strong>
                            <span>{{ $settings->landing_direccion }}</span>
                        </div>
                    </div>
                @endif

                @if(isset($settings->landing_horario) && $settings->landing_horario)
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="contact-info-text">
                            <strong>Horario</strong>
                            <span>{{ $settings->landing_horario }}</span>
                        </div>
                    </div>
                @endif

                {{-- Redes sociales --}}
                @if($social->count() > 0 || (isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp))
                    <div class="mt-4">
                        <h6 style="color:var(--lp-primary);font-weight:700;margin-bottom:1rem;">Síguenos</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                                <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                                   style="width:40px;height:40px;border-radius:10px;background:var(--lp-primary);
                                          display:flex;align-items:center;justify-content:center;color:#fff;
                                          text-decoration:none;font-size:1.1rem;">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                            @endif
                            @foreach($social as $sn)
                                @php
                                    $icon = 'fa-globe';
                                    $name = $sn->social_network ?? $sn->name ?? '';
                                    if (stripos($name, 'facebook') !== false)  $icon = 'fa-facebook';
                                    elseif (stripos($name, 'instagram') !== false) $icon = 'fa-instagram';
                                    elseif (stripos($name, 'twitter') !== false)   $icon = 'fa-twitter';
                                    elseif (stripos($name, 'linkedin') !== false)  $icon = 'fa-linkedin';
                                @endphp
                                <a href="{{ $sn->url ?? '#' }}" target="_blank"
                                   style="width:40px;height:40px;border-radius:10px;background:var(--lp-primary);
                                          display:flex;align-items:center;justify-content:center;color:#fff;
                                          text-decoration:none;font-size:1.1rem;">
                                    <i class="fa {{ $icon }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─ Formulario ─ --}}
            <div class="col-lg-7">
                <div class="contact-card">
                    <h3 style="font-family:'Playfair Display',serif;color:var(--lp-primary);
                               font-size:1.6rem;margin-bottom:1.5rem;">
                        Envíanos un mensaje
                    </h3>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('landing.contacto.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-lp">Nombre *</label>
                                <input type="text" name="nombre"
                                       class="form-control form-control-lp @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}" required placeholder="Tu nombre completo">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-lp">Correo electrónico *</label>
                                <input type="email" name="email"
                                       class="form-control form-control-lp @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required placeholder="tu@correo.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label-lp">Teléfono (opcional)</label>
                                <input type="text" name="telefono"
                                       class="form-control form-control-lp"
                                       value="{{ old('telefono') }}" placeholder="Ej: 8888-8888">
                            </div>
                            <div class="col-12">
                                <label class="form-label-lp">Mensaje *</label>
                                <textarea name="mensaje" rows="5"
                                          class="form-control form-control-lp @error('mensaje') is-invalid @enderror"
                                          required placeholder="¿En qué podemos ayudarte?">{{ old('mensaje') }}</textarea>
                                @error('mensaje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 pt-1">
                                <button type="submit" class="btn btn-lp-primary w-100 py-3">
                                    <i class="fa fa-paper-plane me-2"></i>Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
