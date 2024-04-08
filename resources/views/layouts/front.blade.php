<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{tenant_asset('/') . '/'. (isset($tenantinfo->logo) ? $tenantinfo->logo : '')}}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    @yield('metatag')
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/owl.theme.default.min.css') }}" rel="stylesheet">
    
   {{--  <link href="{{ asset('css/material-dashboard.css.map') }}" rel="stylesheet">

    <link href="{{ asset('css/material-dashboard.min.css') }}" rel="stylesheet"> --}}


</head>
<style>
    :root {
        --navbar: {{ $settings->navbar }};
        --navbar_text: {{ $settings->navbar_text }};
        --btn_cart: {{ $settings->btn_cart }};
        --btn_cart_text: {{ $settings->btn_cart_text }};        
        --footer: {{ $settings->footer }};        
        --title_text: {{ $settings->title_text }};        
        --footer_text: {{ $settings->footer_text }};        
        --sidebar: {{ $settings->sidebar }};        
        --sidebar_text: {{ $settings->sidebar_text }};    
        --hover: {{ $settings->hover }}; 
        --cart_icon: {{ $settings->cart_icon }};      
    }
</style>

<body class="g-sidenav-show  bg-gray-200">

    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
       
        <div>
            @include('layouts.inc.frontnavbar')            
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}" defer></script>
    <script src="{{ asset('js/smooth-scrollbar.min.js') }}" defer></script>
    <script src="{{ asset('js/chartjs.min.js') }}" defer></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
   
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/jquery.js') }}" defer></script>
    @if (session('status'))
        <script>
            swal({
                title: "{{ session('status') }}",
                icon: "{{ session('icon') }}",
            });
        </script>
    @endif
   
    @yield('scripts')

    <script>
         window.companyName = "{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}";
    </script>


</body>

</html>
