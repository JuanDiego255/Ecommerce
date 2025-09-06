<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ isset($tenantinfo->logo_ico) ? route('file', $tenantinfo->logo_ico) : '' }}"
        type="image/x-icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('/barber/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/slicknav.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/gijgo.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/animated-headline.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/barber/css/style.css') }}" rel="stylesheet">

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
        --cintillo: {{ $settings->cintillo }};
        --cintillo_text: {{ $settings->cintillo_text }};
    }
</style>

@if ($view_name == 'frontend_blog_show-articles')
    <style>
        :root {
            --url_image: url('{{ route('file', $blog->horizontal_images) }}');
        }
    </style>
@endif

<body class="g-sidenav-show  bg-gray-200">
    @include('frontend.website.add-comment')
    <div class="{{ $view_name == 'frontend_blog_show-articles' ? 'main-container-front' : '' }}">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

            <div>
                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'main')
                    @include('layouts.inc.barber.frontnavbar')
                @else
                    @include('layouts.inc.centralnavbar')
                @endif

                @yield('content')
                @include('layouts.inc.social-footer')
            </div>
        </main>
    </div>


    <script src="{{ asset('/barber/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{ asset('/barber/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('/barber/js/popper.min.js') }}"></script>
    <script src="{{ asset('/barber/js/bootstrap.min.js') }}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{ asset('/barber/js/jquery.slicknav.min.js') }}"></script>

    <!-- Jquery Slick, Owl-Carousel Plugins -->
    <script src="{{ asset('/barber/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/barber/js/slick.min.js') }}"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="{{ asset('/barber/js/wow.min.js') }}"></script>
    <script src="{{ asset('/barber/js/animated.headline.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.magnific-popup.js') }}"></script>

    <!-- Date Picker -->
    <script src="{{ asset('/barber/js/gijgo.min.js') }}"></script>
    <!-- Nice-select, sticky -->
    <script src="{{ asset('/barber/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.sticky.js') }}"></script>

    <!-- counter, waypoint, Hover Direction -->
    <script src="{{ asset('/barber/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('/barber/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('/barber/js/hover-direction-snake.min.js') }}"></script>

    <!-- contact js -->
    <script src="{{ asset('/barber/js/contact.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.form.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/barber/js/mail-script.js') }}"></script>
    <script src="{{ asset('/barber/js/jquery.ajaxchimp.min.js') }}"></script>

    <!-- Jquery Plugins, main Jquery -->
    <script src="{{ asset('/barber/js/plugins.js') }}"></script>
    <script src="{{ asset('/barber/js/main.js') }}"></script>

    @if (session('status'))
        <script>
            Swal.fire({
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
