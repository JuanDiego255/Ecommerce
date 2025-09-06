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
            <div class="container-fluid py-4">
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
    @yield('script')


</body>

</html>
