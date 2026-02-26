@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>Política de Privacidad — {{ $tenantTitle ?? config('app.name') }}</title>
    <meta name="description" content="Política de privacidad de {{ $tenantTitle ?? config('app.name') }}. Conoce cómo recopilamos, usamos y protegemos tu información.">
@endsection

@section('content')
    <style>
        .privacy-hero {
            padding: 70px 0 40px;
            background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);
        }
        .privacy-body {
            max-width: 820px;
            margin: 0 auto;
        }
        .privacy-body h2 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 40px;
            margin-bottom: 12px;
            color: #222;
        }
        .privacy-body p,
        .privacy-body li {
            color: #555;
            line-height: 1.8;
            font-size: .96rem;
        }
        .privacy-body ul {
            padding-left: 20px;
        }
        .privacy-body a {
            color: var(--btn_cart, #333);
        }
    </style>

    {{-- Hero --}}
    <section class="privacy-hero text-center">
        <div class="container">
            <h1 class="ltext-103 cl0" style="font-size:2rem; font-weight:700;">
                Política de Privacidad
            </h1>
            <p class="stext-107 cl7 p-t-10" style="opacity:.75;">
                Última actualización: {{ now()->translatedFormat('d \d\e F \d\e Y') }}
            </p>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="bg0 p-t-70 p-b-80">
        <div class="container">
            <div class="privacy-body">

                <p>
                    Esta Política de Privacidad describe cómo <strong>{{ $tenantTitle ?? config('app.name') }}</strong>
                    (@if (!empty($tenantEmail))
                        <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a>
                    @else
                        contacto a través de nuestro sitio web
                    @endif)
                    recopila, usa y protege la información que usted nos proporciona al utilizar nuestros servicios,
                    incluyendo aquellos accesibles a través de plataformas de Meta (Facebook e Instagram).
                </p>

                {{-- ── 1. Datos que recopilamos ── --}}
                <h2>1. Información que recopilamos</h2>
                <p>Podemos recopilar los siguientes tipos de información:</p>
                <ul>
                    <li><strong>Datos de contacto:</strong> nombre, correo electrónico, número de teléfono.</li>
                    <li><strong>Datos de perfil de Meta:</strong> nombre público e identificador de usuario de Facebook o Instagram,
                        cuando usted inicia sesión o interactúa con nosotros a través de esas plataformas.</li>
                    <li><strong>Datos de citas y reservas:</strong> fecha, hora y servicio seleccionado.</li>
                    <li><strong>Datos de uso:</strong> páginas visitadas, acciones realizadas en el sitio y datos de sesión.</li>
                </ul>

                {{-- ── 2. Cómo usamos la información ── --}}
                <h2>2. Cómo usamos su información</h2>
                <ul>
                    <li>Confirmar y gestionar reservas o citas.</li>
                    <li>Enviarle recordatorios y comunicaciones relacionadas con su cita.</li>
                    <li>Responder consultas y brindarle atención al cliente.</li>
                    <li>Mejorar nuestros servicios y la experiencia del usuario.</li>
                    <li>Cumplir con obligaciones legales aplicables.</li>
                </ul>
                <p>
                    <strong>No vendemos</strong> su información personal a terceros ni la utilizamos con fines
                    publicitarios sin su consentimiento explícito.
                </p>

                {{-- ── 3. Datos de Meta / Facebook ── --}}
                <h2>3. Integración con Meta (Facebook e Instagram)</h2>
                <p>
                    Si accede a nuestros servicios a través de Facebook o Instagram, podemos recibir información
                    básica de su perfil público según los permisos que usted otorgue. Esta información se utiliza
                    únicamente para personalizar su experiencia y facilitar la comunicación con usted.
                </p>
                <p>
                    Para más información sobre cómo Meta maneja sus datos, consulte la
                    <a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener noreferrer">
                        Política de Privacidad de Meta</a>.
                </p>

                {{-- ── 4. Eliminación de datos ── --}}
                <h2>4. Eliminación de sus datos</h2>
                <p>
                    Usted tiene derecho a solicitar la eliminación de su información personal en cualquier momento.
                    Para hacerlo, contáctenos a través de:
                </p>
                <ul>
                    @if (!empty($tenantEmail))
                        <li>Correo electrónico: <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a></li>
                    @endif
                    @if (!empty($tenantWhatsapp))
                        <li>WhatsApp: <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenantWhatsapp) }}" target="_blank"
                                rel="noopener noreferrer">{{ $tenantWhatsapp }}</a></li>
                    @endif
                    @if (empty($tenantEmail) && empty($tenantWhatsapp))
                        <li>A través del formulario de contacto en nuestro sitio web.</li>
                    @endif
                </ul>
                <p>
                    Procesaremos su solicitud en un plazo máximo de <strong>30 días hábiles</strong>.
                </p>

                {{-- ── 5. Seguridad ── --}}
                <h2>5. Seguridad de la información</h2>
                <p>
                    Implementamos medidas técnicas y organizativas razonables para proteger su información
                    contra accesos no autorizados, pérdida o alteración. Sin embargo, ningún sistema de
                    transmisión de datos por internet es completamente seguro.
                </p>

                {{-- ── 6. Cookies ── --}}
                <h2>6. Cookies</h2>
                <p>
                    Utilizamos cookies de sesión esenciales para el funcionamiento del sitio. No utilizamos
                    cookies de rastreo publicitario de terceros sin su consentimiento.
                </p>

                {{-- ── 7. Cambios a esta política ── --}}
                <h2>7. Cambios a esta política</h2>
                <p>
                    Podemos actualizar esta política ocasionalmente. Le notificaremos sobre cambios significativos
                    publicando la nueva versión en esta misma página con la fecha de actualización.
                </p>

                {{-- ── 8. Contacto ── --}}
                <h2>8. Contacto</h2>
                <p>Si tiene preguntas sobre esta política, comuníquese con nosotros:</p>
                <ul>
                    <li><strong>Empresa:</strong> {{ $tenantTitle ?? config('app.name') }}</li>
                    @if (!empty($tenantEmail))
                        <li><strong>Correo:</strong> <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a></li>
                    @endif
                    @if (!empty($tenantWhatsapp))
                        <li><strong>WhatsApp:</strong> {{ $tenantWhatsapp }}</li>
                    @endif
                </ul>

                <div class="p-t-30">
                    <a href="{{ url('/') }}" class="stext-107 cl3" style="text-decoration:underline;">
                        <i class="fa fa-arrow-left m-r-5"></i> Volver al inicio
                    </a>
                </div>

            </div>
        </div>
    </section>

    @include('layouts.inc.design_ecommerce.footer')
@endsection
