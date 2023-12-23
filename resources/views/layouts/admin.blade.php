<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
   
    {{-- <link href="{{ asset('css/material-dashboard.css.map') }}" rel="stylesheet">

    <link href="{{ asset('css/material-dashboard.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link href="{{ asset('css/material-dashboard.css') }}" rel="stylesheet">


</head>

<body class="g-sidenav-show  bg-gray-200">

    @include('layouts.inc.sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('layouts.inc.adminnav')
        <div class="container-fluid py-4">
            @yield('content')
        </div>
        @include('layouts.inc.adminfooter')

    </main>

    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}" defer></script>
    <script src="{{ asset('js/smooth-scrollbar.min.js') }}" defer></script>
    <script src="{{ asset('js/chartjs.min.js') }}" defer></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
   
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/material-dashboard.min.js') }}" defer></script>

    @if (session('status'))
        <script>
            swal({
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
