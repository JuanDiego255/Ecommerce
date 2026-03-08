<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metatag')
    <title>@yield('title', $tenantinfo->title ?? config('app.name'))</title>

    @if(isset($tenantinfo->logo_ico) && $tenantinfo->logo_ico)
        <link rel="shortcut icon" href="{{ route('file', $tenantinfo->logo_ico) }}" type="image/x-icon">
    @endif

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('/design_ecommerce/vendor/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/design_ecommerce/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    @php
        $primary   = $settings->landing_primary   ?? '#1a1a2e';
        $secondary = $settings->landing_secondary  ?? '#c9a84c';
        $textHero  = $settings->landing_text_hero  ?? '#ffffff';
        $bgSection = $settings->landing_bg_section ?? '#f8f9fa';
    @endphp

    <style>
        :root {
            --lp-primary:    {{ $primary }};
            --lp-secondary:  {{ $secondary }};
            --lp-text-hero:  {{ $textHero }};
            --lp-bg-section: {{ $bgSection }};
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            color: #2d2d2d;
            margin: 0;
        }

        /* ── Navbar ── */
        .lp-navbar {
            background: var(--lp-primary);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,.25);
        }
        .lp-navbar .navbar-brand img { height: 44px; object-fit: contain; }
        .lp-navbar .nav-link {
            color: rgba(255,255,255,.82) !important;
            font-weight: 500;
            font-size: .9rem;
            letter-spacing: .04em;
            padding: .5rem 1rem !important;
            transition: color .2s;
        }
        .lp-navbar .nav-link:hover,
        .lp-navbar .nav-link.active {
            color: var(--lp-secondary) !important;
        }
        .lp-navbar .navbar-toggler { border-color: rgba(255,255,255,.3); }
        .lp-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ── Section commons ── */
        .lp-section { padding: 80px 0; }
        .lp-section-alt { background: var(--lp-bg-section); }

        .lp-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--lp-primary);
            margin-bottom: .5rem;
        }
        .lp-section-subtitle {
            color: #6c757d;
            font-size: 1.05rem;
            margin-bottom: 3rem;
        }
        .lp-divider {
            width: 60px;
            height: 3px;
            background: var(--lp-secondary);
            margin: 1rem auto 1.5rem;
        }

        /* ── Buttons ── */
        .btn-lp-primary {
            background: var(--lp-primary);
            color: #fff;
            border: none;
            padding: .75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: .04em;
            transition: opacity .2s, transform .15s;
        }
        .btn-lp-primary:hover { opacity: .88; transform: translateY(-1px); color: #fff; }

        .btn-lp-secondary {
            background: var(--lp-secondary);
            color: #fff;
            border: none;
            padding: .75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: .04em;
            transition: opacity .2s;
        }
        .btn-lp-secondary:hover { opacity: .88; color: #fff; }

        /* ── Footer ── */
        .lp-footer {
            background: var(--lp-primary);
            color: rgba(255,255,255,.75);
            padding: 60px 0 30px;
        }
        .lp-footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .lp-footer a { color: rgba(255,255,255,.65); text-decoration: none; transition: color .2s; }
        .lp-footer a:hover { color: var(--lp-secondary); }
        .lp-footer .social-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            color: #fff;
            margin-right: 8px;
            transition: background .2s;
        }
        .lp-footer .social-icon:hover { background: var(--lp-secondary); color: #fff; }
        .lp-footer .border-top { border-color: rgba(255,255,255,.12) !important; }

        @yield('styles')
    </style>

    @yield('head')
</head>
<body>

    @include('layouts.landing.inc.nav')

    <main>
        @if(session('success'))
            <div class="container pt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('layouts.landing.inc.footer')

    <!-- Bootstrap JS -->
    <script src="{{ asset('/design_ecommerce/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
