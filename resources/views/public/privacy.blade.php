@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>Política de Privacidad — Safewor Solutions</title>
    <meta name="description" content="Política de privacidad de Safewor Solutions. Conoce cómo recopilamos, usamos y protegemos tu información, incluidos los datos de Meta (Facebook e Instagram Graph API).">
@endsection

@section('content')
<style>
    .privacy-hero {
        padding: 70px 0 40px;
        background: linear-gradient(135deg, var(--navbar, #1a1a2e) 0%, #16213e 100%);
    }
    .privacy-body { max-width: 860px; margin: 0 auto; }
    .privacy-body h2 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-top: 44px;
        margin-bottom: 10px;
        color: #1a1a2e;
        padding-bottom: 6px;
        border-bottom: 2px solid #f0f0f0;
    }
    .privacy-body h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-top: 22px;
        margin-bottom: 8px;
        color: #333;
    }
    .privacy-body p, .privacy-body li {
        color: #555;
        line-height: 1.85;
        font-size: .955rem;
    }
    .privacy-body ul { padding-left: 20px; }
    .privacy-body a { color: var(--btn_cart, #1a1a2e); text-decoration: underline; }
    .privacy-meta-box {
        background: #f4f8ff;
        border-left: 4px solid #1877F2;
        border-radius: 0 8px 8px 0;
        padding: 20px 24px;
        margin: 20px 0;
    }
    .privacy-meta-box p, .privacy-meta-box li { color: #333; }
    .privacy-warning-box {
        background: #fff8e1;
        border-left: 4px solid #f9a825;
        border-radius: 0 8px 8px 0;
        padding: 18px 22px;
        margin: 16px 0;
    }
    .privacy-toc { background: #f9f9f9; border-radius: 8px; padding: 22px 28px; margin-bottom: 30px; }
    .privacy-toc ol { margin: 0; padding-left: 20px; }
    .privacy-toc li { padding: 3px 0; }
    .privacy-toc a { color: var(--btn_cart, #1a1a2e); text-decoration: none; }
    .privacy-toc a:hover { text-decoration: underline; }
    .scope-badge {
        display: inline-block;
        background: #e8f0fe;
        color: #1a73e8;
        border-radius: 4px;
        padding: 2px 10px;
        font-size: .8rem;
        font-weight: 600;
        margin: 2px 2px;
        font-family: monospace;
    }
</style>

{{-- Hero --}}
<section class="privacy-hero text-center">
    <div class="container">
        <h1 class="ltext-103 cl0" style="font-size:2rem; font-weight:700;">
            Política de Privacidad
        </h1>
        <p class="stext-107 cl7 p-t-10" style="opacity:.8;">
            Safewor Solutions &nbsp;·&nbsp; Última actualización: {{ now()->translatedFormat('d \d\e F \d\e Y') }}
        </p>
    </div>
</section>

{{-- Contenido --}}
<section class="bg0 p-t-60 p-b-80">
    <div class="container">
        <div class="privacy-body">

            {{-- Tabla de contenidos --}}
            <div class="privacy-toc">
                <strong style="display:block; margin-bottom:10px;">Contenido</strong>
                <ol>
                    <li><a href="#s1">Quiénes somos</a></li>
                    <li><a href="#s2">Información que recopilamos</a></li>
                    <li><a href="#s3">Cómo usamos su información</a></li>
                    <li><a href="#s4">Integración con Meta — Facebook e Instagram</a></li>
                    <li><a href="#s5">Instagram Graph API — Permisos y datos</a></li>
                    <li><a href="#s6">Callbacks de eliminación y desautorización de Meta</a></li>
                    <li><a href="#s7">Compartir información con terceros</a></li>
                    <li><a href="#s8">Eliminación de sus datos</a></li>
                    <li><a href="#s9">Seguridad de la información</a></li>
                    <li><a href="#s10">Cookies y sesiones</a></li>
                    <li><a href="#s11">Retención de datos</a></li>
                    <li><a href="#s12">Cambios a esta política</a></li>
                    <li><a href="#s13">Contacto</a></li>
                </ol>
            </div>

            {{-- 1 --}}
            <h2 id="s1">1. Quiénes somos</h2>
            <p>
                <strong>Safewor Solutions</strong> es una empresa de desarrollo de software y soluciones digitales con sede en Costa Rica. Diseñamos, construimos y operamos plataformas de comercio electrónico multi-tenant, sistemas a la medida, integraciones con redes sociales (incluyendo Instagram Graph API) y herramientas de gestión empresarial.
            </p>
            <p>
                El presente sitio web <strong>main.safeworsolutions.com</strong> es la plataforma central desde la cual operamos y desde la que nuestros clientes (inquilinos / <em>tenants</em>) acceden a sus propios entornos digitales bajo el dominio <code>{tenant}.safeworsolutions.com</code>.
            </p>
            <p>
                Para cualquier consulta sobre esta política, puede contactarnos en:
                @if (!empty($tenantEmail))
                    <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a>
                @else
                    <a href="mailto:info@safeworsolutions.com">info@safeworsolutions.com</a>
                @endif
            </p>

            {{-- 2 --}}
            <h2 id="s2">2. Información que recopilamos</h2>

            <h3>2.1 Datos que usted nos proporciona directamente</h3>
            <ul>
                <li><strong>Datos de registro:</strong> nombre, apellido, correo electrónico y contraseña al crear una cuenta.</li>
                <li><strong>Datos de compra:</strong> dirección de entrega, número de teléfono, método de pago al realizar pedidos.</li>
                <li><strong>Datos de contacto:</strong> información enviada a través de formularios, chat de WhatsApp o correo electrónico.</li>
                <li><strong>Datos de citas / reservas:</strong> fecha, hora, servicio y barbero/especialista seleccionado (en las plataformas de booking).</li>
            </ul>

            <h3>2.2 Datos recopilados automáticamente</h3>
            <ul>
                <li><strong>Datos de sesión:</strong> identificador de sesión para mantener el carrito de compras de usuarios no registrados.</li>
                <li><strong>Datos de uso:</strong> páginas visitadas, acciones realizadas, hora y duración de la visita.</li>
                <li><strong>Datos del dispositivo:</strong> tipo de navegador, sistema operativo, dirección IP (usada exclusivamente para análisis de seguridad).</li>
            </ul>

            <h3>2.3 Datos provenientes de Meta (Facebook / Instagram)</h3>
            <p>Cuando un usuario o administrador conecta su cuenta de Facebook / Instagram a nuestra plataforma, recopilamos:</p>
            <ul>
                <li>Identificador único de usuario de Facebook (<em>Facebook User ID</em>).</li>
                <li>Nombre público del perfil de Facebook.</li>
                <li>Correo electrónico asociado a la cuenta de Facebook (solo si el usuario otorga el permiso).</li>
                <li>Identificador de la Página de Facebook seleccionada.</li>
                <li>Token de acceso de la Página (almacenado de forma segura para publicaciones).</li>
                <li>Identificador de la cuenta de Instagram Business (<em>instagram_business_account_id</em>).</li>
                <li>Nombre de usuario de Instagram (<em>@username</em>).</li>
            </ul>
            <p>Este bloque se detalla en las secciones 4 y 5.</p>

            {{-- 3 --}}
            <h2 id="s3">3. Cómo usamos su información</h2>
            <ul>
                <li>Crear y gestionar su cuenta de usuario en la plataforma.</li>
                <li>Procesar pedidos, cobros y gestionar el historial de compras.</li>
                <li>Gestionar reservas de citas y enviar recordatorios.</li>
                <li>Publicar contenido en Instagram a través del servicio de gestión de posts (únicamente en las cuentas que el administrador-tenant haya conectado y autorizado explícitamente).</li>
                <li>Mejorar la experiencia del usuario y el rendimiento de la plataforma.</li>
                <li>Cumplir con obligaciones legales y responder a solicitudes de autoridades competentes.</li>
                <li>Enviar comunicaciones relacionadas con el servicio (no spam; solo notificaciones transaccionales).</li>
            </ul>
            <p>
                <strong>No vendemos, alquilamos ni cedemos</strong> su información personal a terceros con fines publicitarios.
            </p>

            {{-- 4 --}}
            <h2 id="s4">4. Integración con Meta — Facebook e Instagram</h2>

            <div class="privacy-meta-box">
                <p style="margin:0 0 8px; font-weight:600; color:#1877F2;">
                    <i class="fa fa-info-circle m-r-6"></i> Esta sección es relevante para los administradores que conectan su Cuenta Empresarial de Instagram a nuestra plataforma.
                </p>
                <p style="margin:0;">
                    Los usuarios finales (compradores / clientes) de las tiendas individuales no se ven afectados por esta integración a menos que hayan iniciado sesión usando Facebook OAuth.
                </p>
            </div>

            <h3>4.1 Inicio de sesión con Facebook (OAuth)</h3>
            <p>
                Ofrecemos la opción de iniciar sesión mediante Facebook OAuth en las plataformas tenant. Si usted elige esta opción, recibiremos de Meta los siguientes datos de su perfil público:
            </p>
            <ul>
                <li>Identificador de usuario de Facebook.</li>
                <li>Nombre y apellido (si están disponibles en su perfil).</li>
                <li>Correo electrónico (si el usuario otorga acceso).</li>
            </ul>
            <p>
                Estos datos se usan exclusivamente para crear o asociar su cuenta en nuestra plataforma. No accedemos a su lista de amigos, publicaciones, mensajes privados ni ningún otro dato de su perfil de Facebook.
            </p>

            <h3>4.2 Conexión de Instagram Business para publicación de contenido</h3>
            <p>
                Los administradores de cada tenant pueden conectar voluntariamente su Cuenta Empresarial de Instagram para gestionar publicaciones desde el panel administrativo. Este proceso utiliza el flujo oficial de OAuth 2.0 de Meta y la <strong>Facebook Graph API v19.0</strong>.
            </p>
            <p>
                El alcance de los permisos que solicitamos está estrictamente limitado a lo necesario para la publicación de contenido. Ver sección 5 para el detalle completo.
            </p>

            {{-- 5 --}}
            <h2 id="s5">5. Instagram Graph API — Permisos, datos y uso</h2>

            <div class="privacy-meta-box">
                <p style="margin:0; font-weight:600; color:#1877F2;">
                    <i class="fa fa-shield m-r-6"></i> Esta sección está diseñada para cumplir con los requisitos de revisión de la App de Meta y con las Políticas de Plataforma de Meta.
                </p>
            </div>

            <h3>5.1 Permisos solicitados</h3>
            <p>Durante el flujo de autorización OAuth, nuestra aplicación solicita los siguientes permisos (<em>scopes</em>):</p>

            <table style="width:100%; border-collapse:collapse; font-size:.9rem; margin:12px 0 20px;">
                <thead>
                    <tr style="background:#f0f4ff; color:#1a1a2e;">
                        <th style="padding:10px 14px; text-align:left; border:1px solid #dde;">Permiso</th>
                        <th style="padding:10px 14px; text-align:left; border:1px solid #dde;">Propósito</th>
                        <th style="padding:10px 14px; text-align:left; border:1px solid #dde;">Datos accedidos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:10px 14px; border:1px solid #dde;"><span class="scope-badge">pages_show_list</span></td>
                        <td style="padding:10px 14px; border:1px solid #dde;">Obtener la lista de Páginas de Facebook del usuario administrador para identificar la Página vinculada a la cuenta de Instagram Business.</td>
                        <td style="padding:10px 14px; border:1px solid #dde;">ID y nombre de las Páginas; token de acceso de la Página seleccionada.</td>
                    </tr>
                    <tr style="background:#fafafa;">
                        <td style="padding:10px 14px; border:1px solid #dde;"><span class="scope-badge">instagram_basic</span></td>
                        <td style="padding:10px 14px; border:1px solid #dde;">Leer el ID de la Cuenta Empresarial de Instagram vinculada a la Página y obtener el nombre de usuario de Instagram.</td>
                        <td style="padding:10px 14px; border:1px solid #dde;">Instagram Business Account ID y @username. Sin acceso a mensajes, seguidores, insights ni contenido privado.</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 14px; border:1px solid #dde;"><span class="scope-badge">instagram_content_publish</span></td>
                        <td style="padding:10px 14px; border:1px solid #dde;">Crear contenedores de medios y publicar imágenes y carruseles en la cuenta de Instagram Business del administrador.</td>
                        <td style="padding:10px 14px; border:1px solid #dde;">Únicamente las imágenes y pies de foto configurados por el propio administrador en el panel. No accedemos a contenido existente ni a métricas.</td>
                    </tr>
                </tbody>
            </table>

            <div class="privacy-warning-box">
                <strong>Lo que NO hacemos con la Instagram Graph API:</strong>
                <ul style="margin:8px 0 0; padding-left:20px;">
                    <li>No leemos mensajes directos (DMs) ni comentarios.</li>
                    <li>No accedemos a métricas de audiencia, insights o estadísticas.</li>
                    <li>No rastreamos seguidores ni datos de terceros que interactúan con la cuenta.</li>
                    <li>No accedemos a ninguna cuenta de Instagram que no sea la Cuenta Empresarial (<em>Business Account</em>) expresamente vinculada por el administrador.</li>
                    <li>No almacenamos el contenido publicado en Instagram más allá del estado de la publicación (ID de contenedor, ID de media, estado, fecha y pie de foto configurado).</li>
                    <li>No modificamos ni eliminamos publicaciones existentes en Instagram desde nuestra plataforma.</li>
                </ul>
            </div>

            <h3>5.2 Flujo técnico de publicación</h3>
            <p>
                Cuando el administrador programa o publica inmediatamente una publicación desde nuestro panel:
            </p>
            <ol>
                <li>Nuestro servidor crea un <em>contenedor de medios</em> en Meta llamando al endpoint <code>POST /{ig_user_id}/media</code> de la Graph API, con la imagen (servida públicamente desde nuestros servidores) y el pie de foto.</li>
                <li>Meta descarga la imagen desde nuestra URL para procesarla.</li>
                <li>Llamamos a <code>POST /{ig_user_id}/media_publish</code> para hacer efectiva la publicación en Instagram.</li>
                <li>Almacenamos localmente el <code>meta_container_id</code> y el <code>meta_media_id</code> retornados por Meta, únicamente como referencia de auditoría interna.</li>
            </ol>
            <p>
                Las imágenes publicadas en Instagram provienen exclusivamente del catálogo de productos del propio negocio. Nunca subimos imágenes de terceros sin autorización.
            </p>

            <h3>5.3 Token de acceso y seguridad</h3>
            <p>
                El token de acceso de larga duración (<em>long-lived token</em>, ~60 días de validez) de la Página de Facebook se almacena de forma cifrada en nuestra base de datos. Este token no se comparte con ningún tercero y se usa exclusivamente para realizar llamadas a la Graph API en nombre del administrador que lo otorgó. El administrador puede revocar el acceso en cualquier momento desde la <a href="https://www.facebook.com/settings?tab=applications" target="_blank" rel="noopener noreferrer">Configuración de aplicaciones de Meta</a> o desde el panel de Instagram de nuestra plataforma.
            </p>

            <h3>5.4 Uso legítimo y cumplimiento de políticas de Meta</h3>
            <p>
                Nuestra aplicación cumple con las <a href="https://developers.facebook.com/policy/" target="_blank" rel="noopener noreferrer">Políticas de Plataforma de Meta</a> y las <a href="https://developers.facebook.com/terms/" target="_blank" rel="noopener noreferrer">Condiciones del Servicio de Meta para Desarrolladores</a>. En particular:
            </p>
            <ul>
                <li>Solo solicitamos los permisos mínimos necesarios para la función de publicación de contenido.</li>
                <li>Los permisos se solicitan solo después de que el administrador inicia el flujo de conexión de forma voluntaria.</li>
                <li>Los datos obtenidos de la API de Meta se usan exclusivamente para la funcionalidad declarada (publicación de contenido en Instagram Business).</li>
                <li>Implementamos los callbacks obligatorios de eliminación de datos y desautorización de Meta (ver sección 6).</li>
                <li>No usamos los datos de la API de Meta para perfilado, publicidad dirigida ni ningún propósito no declarado.</li>
            </ul>

            {{-- 6 --}}
            <h2 id="s6">6. Callbacks de eliminación y desautorización de Meta</h2>
            <p>
                Conforme a los requisitos de la Plataforma de Meta para aplicaciones que usan la Graph API, hemos implementado los siguientes endpoints de callback:
            </p>

            <h3>6.1 Callback de eliminación de datos (<em>Data Deletion Callback</em>)</h3>
            <p>
                <strong>Endpoint:</strong> <code>POST https://main.safeworsolutions.com/facebook/data-deletion</code>
            </p>
            <p>
                Cuando un usuario solicita la eliminación de sus datos a través de los mecanismos de Meta (por ejemplo, desde la configuración de privacidad de Facebook), Meta notifica a nuestra plataforma mediante una petición firmada con HMAC-SHA256 usando nuestra <em>App Secret</em>. Al recibir esta solicitud:
            </p>
            <ol>
                <li>Validamos la firma criptográfica de la solicitud para garantizar que proviene genuinamente de Meta.</li>
                <li>Extraemos el <em>Facebook User ID</em> del payload.</li>
                <li>Eliminamos permanentemente todos los registros de Instagram Account asociados a ese usuario (incluyendo tokens de acceso, historial de publicaciones y medios vinculados).</li>
                <li>Registramos la solicitud con un <em>código de confirmación</em> único.</li>
                <li>Retornamos a Meta la URL de verificación del estado de la solicitud: <code>GET /facebook/deletion-status/{confirmation_code}</code></li>
            </ol>
            <p>
                Procesamos las solicitudes de eliminación en un plazo máximo de <strong>30 días hábiles</strong>.
            </p>

            <h3>6.2 Callback de desautorización (<em>Deauthorize Callback</em>)</h3>
            <p>
                <strong>Endpoint:</strong> <code>POST https://main.safeworsolutions.com/facebook/deauthorize</code>
            </p>
            <p>
                Cuando un usuario revoca los permisos de nuestra aplicación desde la configuración de Meta, este callback se activa. Al recibirlo, desactivamos la cuenta de Instagram vinculada (<code>is_active = false</code>) y anulamos el token de acceso almacenado. El historial de publicaciones anteriores se conserva como registro de auditoría pero sin información identificable del usuario de Meta.
            </p>

            <h3>6.3 Verificación del estado de eliminación</h3>
            <p>
                Cualquier usuario puede verificar el estado de su solicitud de eliminación en:<br>
                <code>GET https://main.safeworsolutions.com/facebook/deletion-status/{codigo_confirmacion}</code>
            </p>

            {{-- 7 --}}
            <h2 id="s7">7. Compartir información con terceros</h2>
            <p>
                Safewor Solutions <strong>no vende</strong> su información personal. Podemos compartir datos en los siguientes casos limitados:
            </p>
            <ul>
                <li><strong>Meta Platforms, Inc.:</strong> al usar la Graph API de Instagram, las imágenes y pies de foto que usted (administrador) configura son procesados por los servidores de Meta para su publicación. Esta transferencia está sujeta a la <a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener noreferrer">Política de Privacidad de Meta</a>.</li>
                <li><strong>Procesadores de pago:</strong> PayPal y SINPE Móvil procesan los pagos bajo sus propias políticas de privacidad. No almacenamos datos completos de tarjetas de crédito.</li>
                <li><strong>Autoridades legales:</strong> cuando sea requerido por ley, orden judicial o proceso legal válido.</li>
            </ul>

            {{-- 8 --}}
            <h2 id="s8">8. Eliminación de sus datos</h2>
            <p>Usted tiene derecho a solicitar la eliminación de su información personal en cualquier momento. Para hacerlo:</p>
            <ul>
                @if (!empty($tenantEmail))
                    <li>Correo electrónico: <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a></li>
                @else
                    <li>Correo electrónico: <a href="mailto:info@safeworsolutions.com">info@safeworsolutions.com</a></li>
                @endif
                @if (!empty($tenantWhatsapp))
                    <li>WhatsApp: <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenantWhatsapp) }}" target="_blank" rel="noopener noreferrer">{{ $tenantWhatsapp }}</a></li>
                @endif
                <li>A través del mecanismo automático de eliminación de Meta (ver sección 6).</li>
            </ul>
            <p>
                Procesaremos su solicitud en un plazo máximo de <strong>30 días hábiles</strong>. Tenga en cuenta que ciertos datos pueden conservarse si así lo requiere la ley (por ejemplo, registros de transacciones contables).
            </p>

            {{-- 9 --}}
            <h2 id="s9">9. Seguridad de la información</h2>
            <p>Implementamos las siguientes medidas técnicas y organizativas para proteger su información:</p>
            <ul>
                <li>Transmisión de datos exclusivamente mediante HTTPS (TLS 1.2 o superior).</li>
                <li>Contraseñas almacenadas con hash seguro (bcrypt).</li>
                <li>Tokens de acceso de Meta almacenados de forma cifrada en la base de datos.</li>
                <li>Validación criptográfica (HMAC-SHA256) de todos los callbacks de Meta.</li>
                <li>Protección CSRF en todos los formularios de la plataforma.</li>
                <li>Separación de bases de datos por tenant (arquitectura multi-tenant con Stancl/Tenancy).</li>
                <li>Mecanismos de bloqueo de concurrencia para evitar duplicación de procesos automatizados.</li>
            </ul>
            <p>
                Ningún sistema de transmisión de datos por internet es 100% seguro. Si detecta alguna vulnerabilidad de seguridad, por favor notifíquenos de inmediato a <a href="mailto:info@safeworsolutions.com">info@safeworsolutions.com</a>.
            </p>

            {{-- 10 --}}
            <h2 id="s10">10. Cookies y sesiones</h2>
            <ul>
                <li><strong>Cookies de sesión:</strong> esenciales para mantener el carrito de compras, la sesión de usuario y el estado de autenticación. No persisten después de cerrar el navegador.</li>
                <li><strong>Token de sesión CSRF:</strong> protege contra ataques de tipo Cross-Site Request Forgery.</li>
                <li><strong>Estado OAuth:</strong> cookie temporal usada durante el flujo de autorización con Meta (se elimina al completar el proceso).</li>
            </ul>
            <p>
                No usamos cookies de seguimiento publicitario de terceros.
            </p>

            {{-- 11 --}}
            <h2 id="s11">11. Retención de datos</h2>
            <ul>
                <li><strong>Cuentas de usuario:</strong> conservadas mientras la cuenta esté activa. Eliminadas a solicitud del usuario.</li>
                <li><strong>Historial de pedidos:</strong> conservado por hasta 5 años por requisitos contables y legales.</li>
                <li><strong>Tokens de acceso de Meta:</strong> eliminados inmediatamente al desconectar la cuenta de Instagram o al recibir un callback de eliminación/desautorización.</li>
                <li><strong>Publicaciones de Instagram:</strong> el registro de publicaciones (texto, estado, IDs de Meta) se conserva como historial interno del tenant. Las imágenes no se conservan en nuestros servidores una vez publicadas.</li>
                <li><strong>Registros de solicitudes de eliminación de Meta:</strong> conservados 90 días como evidencia de cumplimiento.</li>
            </ul>

            {{-- 12 --}}
            <h2 id="s12">12. Cambios a esta política</h2>
            <p>
                Podemos actualizar esta política ocasionalmente para reflejar cambios en nuestras prácticas o en los requisitos legales y de las plataformas de terceros (incluyendo las Políticas de Plataforma de Meta). Notificaremos cambios significativos publicando la nueva versión en esta página con la fecha de actualización. Para cambios que afecten la forma en que procesamos sus datos de Meta, procuraremos notificarle con al menos 7 días de anticipación.
            </p>

            {{-- 13 --}}
            <h2 id="s13">13. Contacto</h2>
            <p>Si tiene preguntas, solicitudes de acceso, rectificación o eliminación de datos, comuníquese con nosotros:</p>
            <ul>
                <li><strong>Empresa:</strong> Safewor Solutions</li>
                <li><strong>Sitio web:</strong> <a href="https://main.safeworsolutions.com">main.safeworsolutions.com</a></li>
                @if (!empty($tenantEmail))
                    <li><strong>Correo:</strong> <a href="mailto:{{ $tenantEmail }}">{{ $tenantEmail }}</a></li>
                @else
                    <li><strong>Correo:</strong> <a href="mailto:info@safeworsolutions.com">info@safeworsolutions.com</a></li>
                @endif
                @if (!empty($tenantWhatsapp))
                    <li><strong>WhatsApp:</strong> <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenantWhatsapp) }}" target="_blank" rel="noopener noreferrer">{{ $tenantWhatsapp }}</a></li>
                @endif
            </ul>

            <div class="p-t-35" style="border-top: 1px solid #eee; margin-top:30px; display:flex; gap:16px; flex-wrap:wrap;">
                <a href="{{ url('/') }}" class="stext-107 cl3" style="text-decoration:underline;">
                    <i class="fa fa-arrow-left m-r-5"></i> Volver al inicio
                </a>
                <a href="{{ url('/terminos') }}" class="stext-107 cl3" style="text-decoration:underline;">
                    Términos y Condiciones
                </a>
            </div>

        </div>
    </div>
</section>

@include('layouts.inc.design_ecommerce.footer')
@endsection
