@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>Términos y Condiciones — Safewor Solutions</title>
    <meta name="description" content="Términos y condiciones de uso de la plataforma Safewor Solutions, incluyendo el uso de la Instagram Graph API y la plataforma de e-commerce multi-tenant.">
@endsection

@section('content')
<style>
    .terms-hero {
        padding: 70px 0 40px;
        background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);
    }
    .terms-body { max-width: 860px; margin: 0 auto; }
    .terms-body h2 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-top: 44px;
        margin-bottom: 10px;
        color: #1a1a2e;
        padding-bottom: 6px;
        border-bottom: 2px solid #f0f0f0;
    }
    .terms-body h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-top: 22px;
        margin-bottom: 8px;
        color: #333;
    }
    .terms-body p, .terms-body li {
        color: #555;
        line-height: 1.85;
        font-size: .955rem;
    }
    .terms-body ul, .terms-body ol { padding-left: 20px; }
    .terms-body a { color: var(--btn_cart, #1a1a2e); text-decoration: underline; }
    .terms-meta-box {
        background: #f4f8ff;
        border-left: 4px solid #1877F2;
        border-radius: 0 8px 8px 0;
        padding: 20px 24px;
        margin: 20px 0;
    }
    .terms-meta-box p { color: #333; margin: 0; }
    .terms-warning-box {
        background: #fff8e1;
        border-left: 4px solid #f9a825;
        border-radius: 0 8px 8px 0;
        padding: 18px 22px;
        margin: 16px 0;
    }
    .terms-warning-box p { color: #555; margin: 0; }
    .terms-important-box {
        background: #fef0f0;
        border-left: 4px solid #e53935;
        border-radius: 0 8px 8px 0;
        padding: 18px 22px;
        margin: 16px 0;
    }
    .terms-important-box p { color: #555; margin: 0; }
    .terms-toc { background: #f9f9f9; border-radius: 8px; padding: 22px 28px; margin-bottom: 30px; }
    .terms-toc ol { margin: 0; padding-left: 20px; }
    .terms-toc li { padding: 3px 0; }
    .terms-toc a { color: var(--btn_cart, #1a1a2e); text-decoration: none; }
    .terms-toc a:hover { text-decoration: underline; }
</style>

{{-- Hero --}}
<section class="terms-hero text-center">
    <div class="container">
        <h1 class="ltext-103 cl0" style="font-size:2rem; font-weight:700;">
            Términos y Condiciones
        </h1>
        <p class="stext-107 cl7 p-t-10" style="opacity:.8;">
            Safewor Solutions &nbsp;·&nbsp; Última actualización: {{ now()->translatedFormat('d \d\e F \d\e Y') }}
        </p>
        <p class="stext-107 cl7 p-t-5" style="opacity:.65;">
            Al usar nuestra plataforma, usted acepta los presentes términos en su totalidad.
        </p>
    </div>
</section>

{{-- Contenido --}}
<section class="bg0 p-t-60 p-b-80">
    <div class="container">
        <div class="terms-body">

            {{-- Tabla de contenidos --}}
            <div class="terms-toc">
                <strong style="display:block; margin-bottom:10px;">Contenido</strong>
                <ol>
                    <li><a href="#t1">Aceptación y ámbito de aplicación</a></li>
                    <li><a href="#t2">Descripción del servicio</a></li>
                    <li><a href="#t3">Registro y cuentas de usuario</a></li>
                    <li><a href="#t4">Condiciones para administradores (tenants)</a></li>
                    <li><a href="#t5">Uso de la Instagram Graph API</a></li>
                    <li><a href="#t6">Contenido y propiedad intelectual</a></li>
                    <li><a href="#t7">Pagos y facturación</a></li>
                    <li><a href="#t8">Disponibilidad del servicio</a></li>
                    <li><a href="#t9">Conducta prohibida</a></li>
                    <li><a href="#t10">Limitación de responsabilidad</a></li>
                    <li><a href="#t11">Modificaciones al servicio</a></li>
                    <li><a href="#t12">Terminación</a></li>
                    <li><a href="#t13">Legislación aplicable</a></li>
                    <li><a href="#t14">Contacto</a></li>
                </ol>
            </div>

            {{-- 1 --}}
            <h2 id="t1">1. Aceptación y ámbito de aplicación</h2>
            <p>
                Los presentes Términos y Condiciones (en adelante, "los Términos") regulan el acceso y uso de la plataforma digital <strong>Safewor Solutions</strong> (en adelante, "la Plataforma"), operada por Safewor Solutions con sede en Costa Rica.
            </p>
            <p>
                Al acceder, registrarse o utilizar cualquier parte de la Plataforma — ya sea como usuario final (comprador), como administrador de un tenant, o como visitante — usted manifiesta haber leído, entendido y aceptado estos Términos en su totalidad. Si no está de acuerdo con alguno de ellos, le rogamos que se abstenga de usar la Plataforma.
            </p>
            <p>
                Estos Términos aplican a:
            </p>
            <ul>
                <li><strong>Usuarios finales:</strong> personas que navegan, se registran o realizan compras en las tiendas individuales de cada tenant bajo el dominio <code>{tenant}.safeworsolutions.com</code>.</li>
                <li><strong>Administradores (tenants):</strong> empresas o personas que contratan los servicios de Safewor Solutions para operar su propia instancia de la plataforma.</li>
                <li><strong>Visitantes:</strong> personas que acceden al sitio principal <code>main.safeworsolutions.com</code> sin registrarse.</li>
            </ul>

            {{-- 2 --}}
            <h2 id="t2">2. Descripción del servicio</h2>
            <p>
                Safewor Solutions ofrece una plataforma SaaS (<em>Software as a Service</em>) de comercio electrónico multi-tenant que incluye, entre otras funcionalidades:
            </p>
            <ul>
                <li>Tiendas en línea personalizables con gestión de productos, categorías, inventario y precios.</li>
                <li>Carrito de compras, procesamiento de pedidos e integración con pasarelas de pago (PayPal, SINPE Móvil).</li>
                <li>Panel administrativo completo por cliente (tenant).</li>
                <li>Sistema de reservas y citas para negocios de servicios (barberías, clínicas, etc.).</li>
                <li>Módulo de publicación y gestión de contenido en Instagram Business mediante la Instagram Graph API.</li>
                <li>Blog integrado, gestión de redes sociales, SEO optimizado y herramientas de marketing.</li>
                <li>Gestión de usuarios, roles y permisos.</li>
            </ul>
            <p>
                Safewor Solutions se reserva el derecho de añadir, modificar o discontinuar funcionalidades en cualquier momento, con o sin aviso previo, según lo indicado en la sección 11.
            </p>

            {{-- 3 --}}
            <h2 id="t3">3. Registro y cuentas de usuario</h2>
            <p>
                Para acceder a ciertas funcionalidades, deberá crear una cuenta de usuario. Al hacerlo, usted se compromete a:
            </p>
            <ul>
                <li>Proporcionar información verídica, precisa y actualizada.</li>
                <li>Mantener la confidencialidad de sus credenciales de acceso (correo y contraseña).</li>
                <li>Notificarnos de inmediato ante cualquier uso no autorizado de su cuenta.</li>
                <li>No crear cuentas falsas, duplicadas o en nombre de terceros sin autorización.</li>
            </ul>
            <p>
                Usted es el único responsable de todas las actividades que ocurran bajo su cuenta. Safewor Solutions no será responsable por pérdidas o daños derivados del incumplimiento de estas obligaciones.
            </p>
            <p>
                También puede registrarse mediante el inicio de sesión con Facebook (OAuth). Al hacerlo, acepta adicionalmente los <a href="https://www.facebook.com/terms/" target="_blank" rel="noopener noreferrer">Términos de Servicio de Meta</a>.
            </p>

            {{-- 4 --}}
            <h2 id="t4">4. Condiciones para administradores (tenants)</h2>

            <h3>4.1 Contratación y licencia</h3>
            <p>
                Los administradores (tenants) acceden a la Plataforma bajo una licencia de uso no exclusiva, intransferible y revocable, sujeta al pago de las tarifas acordadas con Safewor Solutions. El alta de un nuevo tenant implica la creación de una base de datos dedicada y un subdominio bajo <code>.safeworsolutions.com</code>.
            </p>

            <h3>4.2 Responsabilidades del administrador</h3>
            <p>El administrador (tenant) es responsable de:</p>
            <ul>
                <li>El contenido que publica en su tienda (productos, imágenes, descripciones, precios).</li>
                <li>El cumplimiento de las leyes aplicables en su jurisdicción, incluyendo las leyes de protección al consumidor, fiscales y de protección de datos.</li>
                <li>La veracidad de la información de su negocio.</li>
                <li>La gestión de pedidos, devoluciones y atención al cliente de su tienda.</li>
                <li>El correcto uso del módulo de Instagram (ver sección 5).</li>
                <li>Mantener un comportamiento ético y legal en el uso de la plataforma.</li>
            </ul>

            <h3>4.3 Licencia activa</h3>
            <p>
                La disponibilidad de la plataforma del tenant está condicionada al estado activo de su licencia. Una licencia inactiva resulta en la suspensión temporal del acceso público a la tienda. Los datos se conservan por un período mínimo de 30 días después de la suspensión para facilitar la reactivación.
            </p>

            {{-- 5 --}}
            <h2 id="t5">5. Uso de la Instagram Graph API</h2>

            <div class="terms-meta-box">
                <p style="font-weight:600; color:#1877F2; margin-bottom:8px !important;">
                    <i class="fa fa-instagram m-r-6"></i> Esta sección regula el uso del módulo de publicación en Instagram disponible para administradores (tenants).
                </p>
                <p>
                    Su uso implica la aceptación de las <a href="https://developers.facebook.com/policy/" target="_blank" rel="noopener noreferrer">Políticas de Plataforma de Meta</a>, las <a href="https://developers.facebook.com/terms/" target="_blank" rel="noopener noreferrer">Condiciones de Servicio de Meta para Desarrolladores</a> y las <a href="https://help.instagram.com/581066165581870" target="_blank" rel="noopener noreferrer">Políticas de Uso de Instagram</a>.
                </p>
            </div>

            <h3>5.1 Requisitos de elegibilidad</h3>
            <p>Para usar el módulo de Instagram, el administrador debe:</p>
            <ul>
                <li>Poseer una <strong>Cuenta Profesional de Instagram</strong> de tipo Business (no Creator ni Personal) vinculada a una Página de Facebook.</li>
                <li>Ser el titular o administrador legítimo de dicha cuenta de Instagram Business.</li>
                <li>Autorizar a nuestra aplicación de Meta los permisos necesarios (<code>pages_show_list</code>, <code>instagram_basic</code>, <code>instagram_content_publish</code>) a través del flujo oficial de OAuth 2.0.</li>
                <li>Cumplir con las políticas de contenido de Instagram y Meta en todo momento.</li>
            </ul>

            <h3>5.2 Uso permitido</h3>
            <p>El módulo de Instagram puede utilizarse para:</p>
            <ul>
                <li>Publicar imágenes de los productos de su tienda en su cuenta de Instagram Business.</li>
                <li>Programar publicaciones futuras con pies de foto predefinidos.</li>
                <li>Crear carruseles de imágenes (hasta 10 imágenes por publicación).</li>
                <li>Gestionar plantillas de pies de foto y colecciones de imágenes.</li>
            </ul>

            <h3>5.3 Uso prohibido</h3>
            <div class="terms-important-box">
                <p style="margin-bottom:8px !important; font-weight:600; color:#c62828;">Está estrictamente prohibido usar el módulo de Instagram para:</p>
            </div>
            <ul>
                <li>Publicar contenido que infrinja derechos de autor, marcas registradas u otros derechos de propiedad intelectual de terceros.</li>
                <li>Publicar contenido que promueva el odio, la violencia, el acoso, la discriminación o actividades ilegales.</li>
                <li>Publicar imágenes de personas sin su consentimiento explícito.</li>
                <li>Usar el módulo para publicar spam, contenido engañoso o publicidad encubierta no declarada como tal.</li>
                <li>Intentar acceder a datos de Instagram más allá de los permisos otorgados.</li>
                <li>Automatizar interacciones que violen las restricciones de tasa (<em>rate limits</em>) de la Graph API de Meta.</li>
                <li>Usar las credenciales obtenidas a través de nuestra plataforma en aplicaciones o servicios de terceros.</li>
            </ul>

            <h3>5.4 Responsabilidad sobre el contenido publicado</h3>
            <p>
                El administrador (tenant) es el único responsable del contenido que publica en Instagram a través de nuestra plataforma. Safewor Solutions actúa únicamente como intermediario técnico y no revisa ni aprueba el contenido antes de su publicación. El incumplimiento de las políticas de Meta puede resultar en la suspensión de la cuenta de Instagram por parte de Meta, sin que Safewor Solutions incurra en responsabilidad alguna por ello.
            </p>

            <h3>5.5 Revocación de acceso</h3>
            <p>
                El administrador puede desconectar su cuenta de Instagram en cualquier momento desde el panel administrativo o desde la <a href="https://www.facebook.com/settings?tab=applications" target="_blank" rel="noopener noreferrer">Configuración de aplicaciones de Meta</a>. Safewor Solutions también puede revocar el acceso si detecta un uso contrario a estos Términos o a las políticas de Meta.
            </p>

            <h3>5.6 Cambios en la API de Meta</h3>
            <p>
                La disponibilidad del módulo de Instagram depende de las políticas y la infraestructura de Meta Platforms, Inc. Safewor Solutions no garantiza la disponibilidad permanente de esta funcionalidad y no se responsabiliza por interrupciones derivadas de cambios en la Graph API, revisiones de permisos por parte de Meta o modificaciones en las políticas de la plataforma de Meta.
            </p>

            {{-- 6 --}}
            <h2 id="t6">6. Contenido y propiedad intelectual</h2>

            <h3>6.1 Propiedad de Safewor Solutions</h3>
            <p>
                La Plataforma, su código fuente, diseño, logotipos, nombre comercial "Safewor Solutions" y toda la documentación asociada son propiedad exclusiva de Safewor Solutions y están protegidos por las leyes de propiedad intelectual aplicables. Queda prohibida su reproducción, distribución, modificación o ingeniería inversa sin autorización escrita.
            </p>

            <h3>6.2 Contenido del tenant</h3>
            <p>
                Los administradores conservan la propiedad de todo el contenido que suben a la plataforma (imágenes, textos, logos, productos). Al subirlo, otorgan a Safewor Solutions una licencia no exclusiva para almacenar, procesar y mostrar dicho contenido exclusivamente en el marco de la prestación del servicio.
            </p>

            <h3>6.3 Contenido del usuario final</h3>
            <p>
                Los comentarios, testimonios y reseñas enviados por usuarios finales pueden ser moderados, aprobados o rechazados por el administrador del tenant. Safewor Solutions no se responsabiliza por el contenido generado por usuarios.
            </p>

            {{-- 7 --}}
            <h2 id="t7">7. Pagos y facturación</h2>
            <p>
                Los pagos realizados en las tiendas de cada tenant se procesan a través de las pasarelas de pago habilitadas (PayPal y/o SINPE Móvil). Las condiciones específicas de cada transacción están sujetas a los términos del tenant correspondiente y a los términos del procesador de pago utilizado.
            </p>
            <p>
                Safewor Solutions no almacena datos completos de tarjetas de crédito ni débito. Toda la información de pago sensible es procesada directamente por las pasarelas certificadas.
            </p>
            <p>
                Las tarifas por el uso de la plataforma para administradores (tenants) son acordadas de forma individual con Safewor Solutions y pueden variar según el plan contratado. El incumplimiento en el pago de la tarifa puede resultar en la suspensión de la licencia conforme a la sección 4.3.
            </p>

            {{-- 8 --}}
            <h2 id="t8">8. Disponibilidad del servicio</h2>
            <p>
                Safewor Solutions procura mantener la Plataforma disponible las 24 horas, los 7 días de la semana. Sin embargo, no garantizamos disponibilidad ininterrumpida y nos reservamos el derecho de realizar mantenimientos programados o de emergencia que puedan causar interrupciones temporales.
            </p>
            <p>
                Las funcionalidades que dependen de APIs de terceros (Meta Graph API, PayPal, etc.) están sujetas a la disponibilidad y políticas de dichos terceros, frente a los cuales Safewor Solutions no asume responsabilidad por interrupciones.
            </p>

            {{-- 9 --}}
            <h2 id="t9">9. Conducta prohibida</h2>
            <p>Queda expresamente prohibido el uso de la Plataforma para:</p>
            <ul>
                <li>Cualquier actividad ilegal o contraria a la moral y el orden público.</li>
                <li>Intentar vulnerar la seguridad de la Plataforma, acceder sin autorización a datos de otros usuarios o realizar ataques de cualquier tipo (DoS, inyección SQL, XSS, etc.).</li>
                <li>Comercializar productos falsificados, ilegales o que infrinjan derechos de terceros.</li>
                <li>Recopilar datos de otros usuarios sin su consentimiento.</li>
                <li>Suplantar la identidad de otra persona o empresa.</li>
                <li>Intentar hacer ingeniería inversa del software de la Plataforma.</li>
                <li>Usar robots, scrapers o herramientas automatizadas para acceder a la Plataforma de forma masiva sin autorización escrita.</li>
            </ul>
            <p>
                La violación de estas prohibiciones puede resultar en la suspensión inmediata de la cuenta y, en los casos que corresponda, en acciones legales.
            </p>

            {{-- 10 --}}
            <h2 id="t10">10. Limitación de responsabilidad</h2>
            <p>
                En la máxima medida permitida por la ley aplicable, Safewor Solutions no será responsable por:
            </p>
            <ul>
                <li>Pérdidas indirectas, incidentales, especiales o consecuentes derivadas del uso o la imposibilidad de usar la Plataforma.</li>
                <li>Pérdida de datos, ingresos, beneficios o oportunidades de negocio.</li>
                <li>Daños causados por el contenido publicado por administradores o usuarios en la Plataforma.</li>
                <li>Interrupciones del servicio debidas a fuerza mayor, fallos de terceros proveedores o ataques externos.</li>
                <li>Cambios en las políticas o la disponibilidad de la API de Meta que afecten las funcionalidades de Instagram.</li>
                <li>Decisiones de Meta de rechazar, suspender o limitar el acceso de nuestra aplicación a la Graph API.</li>
            </ul>
            <p>
                En ningún caso la responsabilidad total acumulada de Safewor Solutions superará el monto pagado por el tenant en los últimos tres (3) meses de servicio.
            </p>

            {{-- 11 --}}
            <h2 id="t11">11. Modificaciones al servicio y a estos Términos</h2>
            <p>
                Safewor Solutions puede modificar estos Términos en cualquier momento. Los cambios entrarán en vigor en el momento de su publicación en esta página, salvo que se indique una fecha de vigencia diferente. El uso continuado de la Plataforma tras la publicación de los cambios implica su aceptación.
            </p>
            <p>
                Para cambios materiales que afecten significativamente los derechos u obligaciones de los administradores (tenants), procuraremos notificar con al menos 15 días de anticipación.
            </p>

            {{-- 12 --}}
            <h2 id="t12">12. Terminación</h2>
            <p>
                Safewor Solutions se reserva el derecho de suspender o terminar el acceso a la Plataforma, con o sin causa y con o sin previo aviso, en los siguientes casos (no limitativos):
            </p>
            <ul>
                <li>Violación de estos Términos o de las políticas de Meta.</li>
                <li>Falta de pago de la tarifa acordada.</li>
                <li>Actividad fraudulenta, ilegal o dañina.</li>
                <li>Solicitud de cierre de cuenta por parte del propio usuario o administrador.</li>
            </ul>
            <p>
                Ante la terminación, Safewor Solutions conservará los datos del tenant por un período de 30 días para facilitar la recuperación de información antes de proceder a su eliminación definitiva.
            </p>

            {{-- 13 --}}
            <h2 id="t13">13. Legislación aplicable y resolución de disputas</h2>
            <p>
                Estos Términos se rigen por las leyes de la República de Costa Rica. Cualquier disputa derivada de su interpretación o aplicación será sometida, en primera instancia, a un proceso de mediación de buena fe entre las partes. De no alcanzarse un acuerdo, las partes se someten a la jurisdicción de los Tribunales de Justicia de Costa Rica.
            </p>

            {{-- 14 --}}
            <h2 id="t14">14. Contacto</h2>
            <p>Si tiene preguntas sobre estos Términos o necesita reportar una violación, contáctenos:</p>
            <ul>
                <li><strong>Empresa:</strong> Safewor Solutions</li>
                <li><strong>Sitio web:</strong> <a href="https://main.safeworsolutions.com">main.safeworsolutions.com</a></li>
                @if (!empty($tenantEmail))
                    <li><strong>Correo:</strong> <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a></li>
                @else
                    <li><strong>Correo:</strong> <a href="mailto:info@safeworsolutions.com">info@safeworsolutions.com</a></li>
                @endif
                @if (!empty($tenantWhatsapp))
                    <li><strong>WhatsApp:</strong>
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenantWhatsapp) }}" target="_blank" rel="noopener noreferrer">{{ $tenantWhatsapp }}</a>
                    </li>
                @endif
            </ul>

            <div class="p-t-35" style="border-top: 1px solid #eee; margin-top:30px; display:flex; gap:16px; flex-wrap:wrap;">
                <a href="{{ url('/') }}" class="stext-107 cl3" style="text-decoration:underline;">
                    <i class="fa fa-arrow-left m-r-5"></i> Volver al inicio
                </a>
                <a href="{{ url('/privacy-policy') }}" class="stext-107 cl3" style="text-decoration:underline;">
                    Política de Privacidad
                </a>
            </div>

        </div>
    </div>
</section>

@include('layouts.inc.design_ecommerce.footer')
@endsection
