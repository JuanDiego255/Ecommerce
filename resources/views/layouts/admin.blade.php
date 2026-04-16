<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ isset($tenantinfo->logo_ico) ? route('file', $tenantinfo->logo_ico) : '' }}"
        type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @yield('metatag') --}}
    <title>@yield('title', isset($tenantinfo->title) ? $tenantinfo->title . ' - Admin' : '')</title>
    <meta name="description" content="@yield('description', 'Gestiona el sitio web desde el módulo administrativo')">
    <meta property="og:title" content="@yield('og_title', isset($tenantinfo->title) ? $tenantinfo->title . ' - Admin' : '')">
    <meta property="og:description" content="@yield('og_description', 'Gestiona el sitio web desde el módulo administrativo')">
    <meta property="og:image" content="@yield('og_image', isset($tenantinfo->logo_ico) ? route('file', $tenantinfo->logo_ico) : '')">
    <!--     Fonts and icons     -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->

    {{-- <link href="{{ asset('css/material-dashboard.css.map') }}" rel="stylesheet">

    <link href="{{ asset('css/material-dashboard.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
    <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/admin-ui.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css">
</head>
<style>
    :root {
        --navbar: {{ $settings->navbar }};
        --navbar_text: {{ $settings->navbar_text }};
        --btn_cart: {{ $settings->btn_cart }};
        --btn_cart_text: {{ $settings->btn_cart_text }};
        --footer: {{ $settings->footer }};
        --footer_text: {{ $settings->footer_text }};
        --sidebar: {{ $settings->sidebar }};
        --sidebar_text: {{ $settings->sidebar_text }};
        --hover: {{ $settings->hover }};
        --cintillo: {{ $settings->cintillo }};
        --cintillo_text: {{ $settings->cintillo_text }};
    }
</style>
@php
    $class = 'main-container';
    switch ($tenantinfo->tenant) {
        case 'autosgreciacr':
            $class = 'main-container-ag';
            break;
        case 'avelectromecanica':
        case 'aclimate':
            $class = 'main-container-ac';
            break;
        default:
            # code...
            break;
    }
@endphp

<body class="g-sidenav-show  bg-gray-200">
    <div class="{{ $class }}">
        @include('layouts.inc.sidebar')
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            @if ($tenantinfo->tenant == 'aclimate' || $tenantinfo->tenant == 'avelectromecanica')
                <a href="{{ route('tenant.switch', ['identifier' => $tenantinfo->tenant == 'avelectromecanica' ? 'aclimate' : 'avelectromecanica']) }}"
                    class="tenant-button {{ $tenantinfo->tenant == 'avelectromecanica' ? 'tenant-ac-color' : 'tenant-av-color' }}">
                    <span class="tenant-label">
                        {{ $tenantinfo->tenant == 'avelectromecanica' ? 'AClimate' : 'AV Electromecanica' }}
                    </span>
                    <img src="{{ $tenantinfo->tenant == 'avelectromecanica' ? asset('avstyles/img/svg_icon/copo.svg') : asset('avstyles/img/svg_icon/av.svg') }}"
                        alt="Icono" width="40" height="40">
                </a>
            @endif


            @include('layouts.inc.adminnav')
            <div class="container-fluid admin-page-container">
                @hasSection('breadcrumb')
                <nav class="admin-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <span class="material-icons" style="font-size:.75rem;vertical-align:middle;color:var(--gray3);">home</span>
                            Admin
                        </li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
                @endif
                @yield('content')
            </div>
            @include('layouts.inc.adminfooter')

        </main>
    </div>

    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}" defer></script>
    <script src="{{ asset('js/smooth-scrollbar.min.js') }}" defer></script>
    {{-- Chart.js v4 --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.4/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

    <script src="{{ asset('js/material-dashboard.min.js') }}" defer></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


    @if (session('status'))
        <script>
            Swal.fire({
                title: "{{ session('status') }}",
                icon: "{{ session('icon') }}",
            });
        </script>
    @endif
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>
    <script>
        // Config pública de tu proyecto Firebase (usa valores de tu .env)
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}",
        };

        // Inicializa Firebase en el navegador
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        // Registrar service worker, necesario para notificaciones en background
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js').then(async (registration) => {

                // 👇 OJO: en Firebase 9/10 ya no existe messaging.useServiceWorker
                // messaging.useServiceWorker(registration); // <-- esto daba el error

                // Pedir permiso al usuario para notificaciones
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    console.warn('Notificaciones bloqueadas por el usuario');
                    return;
                }

                try {
                    // Obtener el registration token (el que FCM necesita)
                    const currentToken = await messaging.getToken({
                        vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}", // tu VAPID KEY
                        serviceWorkerRegistration: registration, // 👈 se pasa aquí ahora
                    });

                    if (currentToken) {
                        console.log('FCM token (admin panel):', currentToken);

                        // Enviarlo al backend para guardarlo en BD
                        fetch("{{ route('admin.push-token.store') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                },
                                body: JSON.stringify({
                                    token: currentToken,
                                    platform: 'web',
                                }),
                            })
                            .then(async r => {
                                const text = await r.text();
                                console.log('Respuesta cruda backend /admin/push-token:', r.status,
                                    text);

                                // si quieres seguir esperando JSON solo cuando sea 200:
                                if (r.ok) {
                                    try {
                                        const data = JSON.parse(text);
                                        console.log('Token registrado en backend:', data);
                                    } catch (e) {
                                        console.error('Error parseando JSON de backend:', e);
                                    }
                                }
                            })
                            .catch(err => {
                                console.error('Error registrando token en backend:', err);
                            });


                    } else {
                        console.warn('No pude obtener token FCM (quizá no tiene permiso)');
                    }
                } catch (e) {
                    console.error('Error al pedir token FCM:', e);
                }

                // Mensajes cuando la pestaña está en primer plano
                messaging.onMessage((payload) => {
                    console.log('Push recibido en foreground:', payload);

                    // Extraer información del mensaje
                    const title = payload?.notification?.title || 'Nueva notificación';
                    const body = payload?.notification?.body || 'Tienes una nueva cita.';
                    const url = payload?.data?.url || '/mis-citas';

                    // 🔊 Reproducir sonido de alerta
                    const audio = new Audio('/sounds/notify.mp3');
                    audio.play().catch(err => console.warn('No se pudo reproducir el sonido:', err));

                    // 🪄 Mostrar SweetAlert2
                    Swal.fire({
                        title: title,
                        text: body,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ver mis citas',
                        cancelButtonText: 'Cerrar',
                        allowOutsideClick: false,
                        customClass: {
                            confirmButton: 'bg-primary text-white',
                            cancelButton: 'bg-gray-300'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // 🔁 Redirigir al panel de citas
                            window.location.href = url;
                        }
                    });
                });


            }).catch(err => {
                console.error('Error registrando Service Worker para FCM:', err);
            });
        } else {
            console.warn('Service workers no soportados en este navegador.');
        }
    </script>
    @yield('script')

    {{-- ── Global search (E1) ──────────────────────────────────── --}}
    @include('layouts.inc.global-search')
    <script>
    (function() {
        var palette  = document.getElementById('gs-palette');
        var backdrop = document.getElementById('gs-backdrop');
        var input    = document.getElementById('gs-input');
        var results  = document.getElementById('gs-results');
        var timer    = null;
        var activeIdx = -1;

        function open() {
            palette.style.display  = 'block';
            backdrop.style.display = 'block';
            input.value = '';
            results.innerHTML = '<div class="gs-hint">Escribe al menos 2 caracteres…</div>';
            activeIdx = -1;
            setTimeout(() => input.focus(), 50);
        }
        function close() {
            palette.style.display  = 'none';
            backdrop.style.display = 'none';
        }

        // Cmd/Ctrl + K
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                palette.style.display === 'none' ? open() : close();
            }
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') navigate(e);
            if (e.key === 'Enter') selectActive();
        });
        backdrop.addEventListener('click', close);

        // Debounced search
        input.addEventListener('input', function() {
            clearTimeout(timer);
            var q = this.value.trim();
            if (q.length < 2) {
                results.innerHTML = '<div class="gs-hint">Escribe al menos 2 caracteres…</div>';
                return;
            }
            results.innerHTML = '<div class="gs-hint gs-loading"><span class="material-icons" style="font-size:1.1rem;vertical-align:middle">sync</span> Buscando…</div>';
            timer = setTimeout(function() { doSearch(q); }, 280);
        });

        function doSearch(q) {
            fetch('/admin/search?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(render)
                .catch(() => { results.innerHTML = '<div class="gs-hint">Error al buscar.</div>'; });
        }

        var typeLabel = { product: 'Producto', order: 'Pedido', category: 'Categoría' };

        function render(data) {
            activeIdx = -1;
            if (!data.length) {
                results.innerHTML = '<div class="gs-hint">Sin resultados.</div>';
                return;
            }
            var html = '';
            var lastType = null;
            data.forEach(function(r, i) {
                if (r.type !== lastType) {
                    html += '<div class="gs-group-label">' + (typeLabel[r.type] ?? r.type) + '</div>';
                    lastType = r.type;
                }
                html += '<a class="gs-item" href="' + r.url + '" data-idx="' + i + '">'
                    + '<span class="material-icons gs-item-icon">' + r.icon + '</span>'
                    + '<span class="gs-item-text"><span class="gs-item-label">' + r.label + '</span>'
                    + '<span class="gs-item-sub">' + r.sub + '</span></span>'
                    + '</a>';
            });
            results.innerHTML = html;
        }

        function navigate(e) {
            var items = results.querySelectorAll('.gs-item');
            if (!items.length) return;
            e.preventDefault();
            if (activeIdx >= 0) items[activeIdx].classList.remove('gs-active');
            activeIdx += e.key === 'ArrowDown' ? 1 : -1;
            activeIdx = Math.max(0, Math.min(activeIdx, items.length - 1));
            items[activeIdx].classList.add('gs-active');
            items[activeIdx].scrollIntoView({ block: 'nearest' });
        }

        function selectActive() {
            var items = results.querySelectorAll('.gs-item');
            if (activeIdx >= 0 && items[activeIdx]) {
                window.location.href = items[activeIdx].getAttribute('href');
            }
        }
    })();
    </script>

    {{-- ── ECD Tour & Promo ────────────────────────────────────────────── --}}
    <style>
        /* ── Tour tooltip ── */
        .ecd-tour-tooltip .introjs-tooltip {
            min-width: 320px;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(0,0,0,.18);
            border: none;
            padding: 0;
            overflow: hidden;
        }
        .ecd-tour-tooltip .introjs-tooltiptext { padding: 0; }
        .ecd-tour-tooltip .introjs-tooltipbuttons {
            border-top: 1px solid #f1f5f9;
            padding: 12px 16px;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .ecd-tour-tooltip .introjs-button {
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            padding: 7px 14px;
            text-shadow: none;
            border: none;
        }
        .ecd-tour-tooltip .introjs-nextbutton {
            background: #5e72e4;
            color: #fff;
        }
        .ecd-tour-tooltip .introjs-nextbutton:hover { background: #4a5fd4; }
        .ecd-tour-tooltip .introjs-prevbutton {
            background: #f1f5f9;
            color: #475569;
        }
        .ecd-tour-tooltip .introjs-skipbutton {
            color: #94a3b8;
            font-size: .9rem;
            padding: 6px 8px;
        }
        .ecd-tour-tooltip .introjs-progressbar { background: #5e72e4 !important; }
        .ecd-tour-tooltip .introjs-progress { background: #e2e8f0; border-radius: 99px; }
        .introjs-helperLayer { border-radius: 10px; }

        /* ── Tooltip content ── */
        .ecd-tip { padding: 18px 20px 14px; }
        .ecd-tip-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .ecd-tip-body {
            font-size: .82rem;
            color: #475569;
            line-height: 1.6;
        }
        .ecd-tip-body ul { padding-left: 1.1rem; margin: .4rem 0 0; }
        .ecd-tip-body li { margin-bottom: .25rem; }
        .ecd-tip-body strong { color: #1e293b; }
        .ecd-tip-body code {
            background: #f1f5f9;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: .78rem;
            color: #5e72e4;
        }

        /* ── Floating replay button ── */
        #ecd-tour-fab {
            position: fixed;
            bottom: 22px;
            right: 22px;
            z-index: 9990;
            display: flex;
            align-items: center;
            gap: 6px;
            background: #5e72e4;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 9px 16px 9px 12px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(94,114,228,.45);
            transition: all .2s;
        }
        #ecd-tour-fab:hover {
            background: #4a5fd4;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(94,114,228,.5);
        }
        #ecd-tour-fab i { font-size: .9rem; }

        @media (max-width: 576px) {
            #ecd-tour-fab span { display: none; }
            #ecd-tour-fab { padding: 10px 12px; border-radius: 50%; }
        }

    </style>

    @if(isset($tenantinfo) && ($tenantinfo->tenant ?? '') === 'gestionarecr')
    <style>
        /* ── Promo toast ── */
        #ecd-promo-toast {
            position: fixed;
            bottom: 22px;
            right: 22px;
            z-index: 9995;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            color: #fff;
            border-radius: 14px;
            padding: 12px 16px;
            max-width: 310px;
            box-shadow: 0 6px 24px rgba(94,114,228,.5);
            animation: ecd-toast-in .4s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes ecd-toast-in {
            from { opacity:0; transform: translateY(20px) scale(.95); }
            to   { opacity:1; transform: translateY(0)    scale(1); }
        }
        .ecd-toast-icon {
            font-size: 1.6rem;
            flex-shrink: 0;
            animation: ecd-pulse 2.2s ease-in-out infinite;
        }
        @keyframes ecd-pulse {
            0%,100% { transform: scale(1); }
            50%      { transform: scale(1.12); }
        }
        .ecd-toast-body { flex: 1; min-width: 0; }
        .ecd-toast-body strong {
            display: block;
            font-size: .82rem;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .ecd-toast-body span {
            font-size: .73rem;
            opacity: .88;
            line-height: 1.4;
        }
        .ecd-toast-link {
            flex-shrink: 0;
            background: rgba(255,255,255,.22);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            border-radius: 8px;
            padding: 6px 10px;
            text-decoration: none;
            white-space: nowrap;
            transition: background .2s;
        }
        .ecd-toast-link:hover {
            background: rgba(255,255,255,.35);
            color: #fff;
        }
        /* Push the tour FAB above the promo toast */
        #ecd-tour-fab { bottom: 92px; }
    </style>
    @endif

    @if(isset($tenantinfo) && ($tenantinfo->tenant ?? '') === 'gestionarecr')
    {{-- Persistent promo toast — never goes away --}}
    <div id="ecd-promo-toast">
        <div class="ecd-toast-icon">🗂️</div>
        <div class="ecd-toast-body">
            <strong>Expediente Digital activo</strong>
            <span>Gestioná cada paciente de forma profesional. ¡Ya está habilitado para vos!</span>
        </div>
        <a href="/ecd/dashboard" class="ecd-toast-link">Explorar →</a>
    </div>

    <script>
    (function () {
        // One-time SweetAlert promo per browser session
        if (sessionStorage.getItem('ecd_promo_seen')) return;
        sessionStorage.setItem('ecd_promo_seen', '1');

        // Small delay so the page finishes rendering first
        setTimeout(function () {
            Swal.fire({
                title: '🗂️ Expediente Digital',
                html: '<div style="text-align:left;font-size:.9rem;line-height:1.6;">' +
                    '<p style="margin-bottom:.8rem;">Gestiona cada paciente de forma <strong>profesional y centralizada</strong> con nuestro módulo de Expediente Clínico Digital:</p>' +
                    '<ul style="padding-left:1.2rem;color:#475569;">' +
                    '<li style="margin-bottom:.4rem;">📋 <strong>Fichas clínicas</strong> personalizadas por tipo de tratamiento</li>' +
                    '<li style="margin-bottom:.4rem;">📸 <strong>Galería fotográfica</strong> de evolución del paciente</li>' +
                    '<li style="margin-bottom:.4rem;">✍️ <strong>Consentimientos</strong> informados con firma digital</li>' +
                    '<li style="margin-bottom:.4rem;">🗂️ <strong>Protocolos</strong> de tratamiento estandarizados</li>' +
                    '<li>📊 <strong>Dashboard</strong> con métricas de tu práctica</li>' +
                    '</ul>' +
                    '</div>',
                confirmButtonText: 'Explorar el módulo →',
                cancelButtonText:  'Más tarde',
                showCancelButton:  true,
                confirmButtonColor: '#5e72e4',
                cancelButtonColor:  '#94a3b8',
                background:         '#fff',
                customClass: {
                    popup:          'rounded-4',
                    confirmButton:  'px-4',
                    cancelButton:   'px-3',
                },
                width: '480px',
                padding: '2rem',
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = '/ecd/dashboard';
                }
            });
        }, 1200);
    })();
    </script>
    @endif

    {{-- ECD tour (active on /ecd/* for any tenant) --}}
    <script src="{{ asset('js/ecd-tour.js') }}"></script>

</body>

</html>
