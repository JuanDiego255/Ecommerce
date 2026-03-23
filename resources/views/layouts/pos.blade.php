<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ isset($tenantinfo->logo_ico) ? route('file', $tenantinfo->logo_ico) : '' }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ventas')</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700">
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Material Dashboard (only for shared component styles) -->
    <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; background: #f5f5f7; font-family: Inter, system-ui, sans-serif; }

        /* ── POS top bar ─────────────────────────────────── */
        .pos-topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            height: 48px;
            background: #fff;
            border-bottom: 1px solid #e5e5ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .pos-topbar-brand {
            font-size: .8rem;
            font-weight: 600;
            color: #1d1d1f;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pos-topbar-brand img { height: 22px; object-fit: contain; border-radius: 4px; }
        .pos-back-btn {
            font-size: .75rem;
            color: #007aff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 3px;
            font-weight: 500;
        }
        .pos-back-btn:hover { opacity: .75; }

        /* ── Main content ────────────────────────────────── */
        .pos-content {
            padding: 24px 16px;
            min-height: calc(100vh - 48px);
        }

        /* Suppress material-dashboard sidebar styles leaking */
        .sidenav { display: none !important; }
    </style>
</head>
<body>

    {{-- Top bar --}}
    <div class="pos-topbar">
        <div class="pos-topbar-brand">
            @if(isset($tenantinfo->logo) && $tenantinfo->logo)
                <img src="{{ route('file', $tenantinfo->logo) }}" alt="logo">
            @endif
            <span>{{ $tenantinfo->title ?? 'Panel de ventas' }}</span>
        </div>
        <a href="javascript:window.close()" class="pos-back-btn" onclick="if(window.opener){window.close();}else{window.history.back();}return false;">
            <i class="material-icons" style="font-size:.95rem;">close</i>
            Cerrar
        </a>
    </div>

    {{-- Page content --}}
    <div class="pos-content">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "{{ session('status') }}",
                    icon: "{{ session('icon') }}",
                });
            });
        </script>
    @endif

    @yield('script')
</body>
</html>
