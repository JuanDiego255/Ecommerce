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
    <link rel="icon" type="image/png" href="images/icons/favicon.png" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/design_ecommerce/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/design_ecommerce/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/design_ecommerce/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/MagnificPopup/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/design_ecommerce/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/design_ecommerce/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/design_ecommerce/css/main.css') }}">
</head>
{{-- <style>
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
</style> --}}

<input type="hidden" value="{{ $tenantinfo->whatsapp }}" id="random_whats" name="random_whats">

<body class="g-sidenav-show  bg-gray-200">
    {{-- @include('frontend.av.add-comment') --}}

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Botón flotante de WhatsApp -->
        {{--  <div class="whatsapp-button whatsapp-button-click">
            <span class="whatsapp-label">¡Contáctanos!</span> <!-- Etiqueta -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="40"
                height="40">
        </div>
        <div class="comment-button comment-button-click">
            <span class="comment-label">¡Deja un testimonio!</span> <!-- Etiqueta -->
            <img src="{{ asset('avstyles/img/svg_icon/comment.svg') }}" alt="WhatsApp" width="40" height="40">
        </div> --}}
        <div>
            @include('layouts.inc.design_ecommerce.front')
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('/design_ecommerce/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/select2/select2.min.js') }}"></script>
    <script>
        $(".js-select2").each(function() {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        })
    </script>
    <script src="{{ asset('/design_ecommerce/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/js/slick-custom.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/parallax100/parallax100.js') }}"></script>
    <script>
        $('.parallax100').parallax100();
    </script>
    <script src="{{ asset('/design_ecommerce/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
    <script>
        $('.gallery-lb').each(function() {
            $(this).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                },
                mainClass: 'mfp-fade'
            });
        });
    </script>
    <script src="{{ asset('/design_ecommerce/vendor/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('/design_ecommerce/vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $('.js-addwish-b2').on('click', function(e) {
            e.preventDefault();
        });

        $('.js-addwish-b2').each(function() {
            var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-b2');
                $(this).off('click');
            });
        });

        $('.js-addwish-detail').each(function() {
            var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-detail');
                $(this).off('click');
            });
        });

        /*---------------------------------------------*/

        $('.js-addcart-detail').each(function() {
            var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
            $(this).on('click', function() {
                swal(nameProduct, "is added to cart !", "success");
            });
        });
    </script>
    <script src="{{ asset('/design_ecommerce/vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script>
        $('.js-pscroll').each(function() {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function() {
                ps.update();
            })
        });
    </script>
    <script src="{{ asset('/design_ecommerce/js/main.js') }}"></script>

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
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".comment-button").addEventListener("click", function() {
                var myModal = new bootstrap.Modal(document.getElementById('add-comment-modal'));
                myModal.show();
            });
        });
    </script>
</body>

</html>
