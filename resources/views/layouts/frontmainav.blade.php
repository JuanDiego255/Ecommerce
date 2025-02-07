<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ isset($tenantinfo->logo_ico) ? route('file', $tenantinfo->logo_ico) : '' }}"
        type="image/x-icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('metatag')
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="{{ asset('/avstyles/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('/avstyles/css/style.css') }}">
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

@if ($view_name == 'frontend_blog_carsale_show-articles')
    <style>
        :root {
            --url_image: url('{{ route('file', $blog->horizontal_images) }}');
        }
    </style>
@endif


<input type="hidden" value="{{ $tenantinfo->whatsapp }}" id="random_whats" name="random_whats">

<body class="g-sidenav-show  bg-gray-200">
    @include('frontend.website.add-comment')
    <div class="{{ $view_name == 'frontend_blog_carsale_show-articles' ? 'main-container-front' : '' }}">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Botón flotante de WhatsApp -->
           {{--  <div class="whatsapp-button whatsapp-button-click">
                <span class="whatsapp-label">¡Contáctanos!</span> <!-- Etiqueta -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp"
                    width="40" height="40">
            </div> --}}
            <div>
                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'main')
                    @include('layouts.inc.av.frontav')
                @else
                    @include('layouts.inc.centralnavbar')
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{--  <script src="{{ asset('/car-styles/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/popper.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/aos.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/car-styles/js/jquery.timepicker.min.js') }}"></script>
    <script src="{{ asset('/car-styles/js/scrollax.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="{{ asset('/car-styles/js/google-map.js') }}"></script>
    <script src="{{ asset('/car-styles/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('/avstyles/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/popper.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/ajax-form.js') }}"></script>
    <script src="{{ asset('/avstyles/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/scrollIt.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/wow.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/plugins.js') }}"></script>
    <script src="{{ asset('/avstyles/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/slick.min.js') }}"></script>
    <!-- Contact JS -->
    <script src="{{ asset('/avstyles/js/contact.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.form.js') }}"></script>
    <script src="{{ asset('/avstyles/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/avstyles/js/mail-script.js') }}"></script>

    <script src="{{ asset('/avstyles/js/main.js') }}"></script>

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
