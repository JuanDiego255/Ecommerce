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
    {{-- <link rel="icon" type="image/png" href="images/icons/favicon.png" /> --}}
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

<input type="hidden" value="{{ $tenantinfo->whatsapp }}" id="random_whats" name="random_whats">

<body class="animsition">
    {{-- @include('frontend.av.add-comment') --}}
    <div>
        @include('layouts.inc.design_ecommerce.front')
        @yield('content')
    </div>

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
        $(document).ready(function() {
            $('#search-select').select2({
                placeholder: "BUSCAR PRODUCTOS...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/get/products/select/',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term // Envía el término de búsqueda al servidor
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(buy) {
                                return {
                                    id: buy.url,
                                    text: buy.name
                                };
                            })
                        };
                    }
                }
            });
            $('#search-select').on('change', function(e) {
                var selectedId = $(this).val();
                if (selectedId) {
                    window.location.href = '/detail-clothing' + selectedId;
                }
            });
            $(document).on('click', '.btnQuantity', function(e) {
                e.preventDefault();

                var quantity = $(this).val();
                var itemId = $(this).data('cart-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "/edit-quantity",
                    data: {
                        'quantity': quantity,
                        'cart_id': itemId,
                    },
                    success: function(response) {
                        calcularTotal();
                    }
                });
            });

            $(document).on('click', '.btnDelete', function(e) {
                e.preventDefault();
                let $button = $(this);
                let cartId = $button.data('item-id');
                let $form = $button.closest('form.delete-form');
                $.ajax({
                    url: "/delete-item-cart/" + cartId,
                    type: 'POST', // Si usas el método DELETE, puedes sobreescribirlo en los datos o la cabecera según tu configuración
                    data: $form.serialize() + '&cart_id=' +
                        cartId, // Se envían los datos del form + el ID del item
                    success: function(response) {
                        var newCartNumber = response.cartNumber
                        const button = document.querySelector('.js-show-cart');
                        button.dataset.notify = newCartNumber;
                        $button.closest('.header-cart-item').remove();
                        calcularTotal();
                    },
                    error: function(error) {
                        console.error('Error eliminando el producto del carrito:', error);
                    }
                });
            });
            // Función de ejemplo para actualizar los totales. Ajusta esta función según cómo se manejen
            // los datos en front_new y la respuesta de tu servidor.
            function calcularTotal() {
                let total = 0;
                let total_cloth = 0;
                let iva = parseFloat(document.getElementById("iva_tenant").value);
                let total_iva = 0;
                let you_save = 0;
                // Obtener todos los elementos li que contienen los productos
                const items = document.querySelectorAll('.header-cart-item');

                items.forEach((item) => {
                    const precio = parseFloat(item.querySelector('.price').value);
                    const discount = parseFloat(item.querySelector('.discount').value);
                    const cantidad = parseInt(item.querySelector('.quantity').value);

                    const subtotal = precio * cantidad;
                    const subtotal_discount = discount * cantidad;
                    you_save += subtotal_discount;
                    total += subtotal;
                });

                total_iva = total * iva;
                total_cloth = total;
                total = total + total_iva;

                // Mostrar el total actualizado en los elementos correspondientes
                var divDescuento = $(
                    '#descuento'
                );
                const totalElement = document.getElementById('totalPriceElementDE');
                const totalIvaElement = document.getElementById('totalIvaElementDE');
                const totalDiscountElement = document.getElementById('totalDiscountElementDE');
                const totalCloth = document.getElementById('totalClothDE');


                totalElement.innerText = `₡${total.toLocaleString()}`;
                if (total_iva > 0) {
                    totalIvaElement.textContent = `₡${total_iva.toLocaleString()}`;
                }
                if (you_save > 0) {
                    divDescuento.removeClass('d-none');
                    totalDiscountElement.textContent = `₡${you_save.toLocaleString()}`;
                } else {
                    divDescuento.addClass('d-none');
                }
                totalCloth.textContent = `₡${total_cloth.toLocaleString()}`;
            }
        });
        window.companyName = "{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}";
       /*  document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".comment-button").addEventListener("click", function() {
                var myModal = new bootstrap.Modal(document.getElementById('add-comment-modal'));
                myModal.show();
            });

        }); */
        document.getElementById('toggleMenu').addEventListener('click', function() {
            const menu = document.getElementById('fullScreenMenu');
            menu.style.display = 'block'; // Se asegura de que el elemento esté visible
            setTimeout(() => {
                menu.classList.add('active'); // Agrega la animación
            }, 10); // Pequeño retraso para activar la transición
        });

        document.getElementById('closeMenu').addEventListener('click', function() {
            const menu = document.getElementById('fullScreenMenu');
            menu.classList.remove('active'); // Quita la animación

            // Espera a que termine la animación para ocultar el menú
            setTimeout(() => {
                menu.style.display = 'none';
            }, 500); // Debe coincidir con la duración de la transición en CSS (0.5s)
        });
        document.getElementById('toggleMenuMobile').addEventListener('click', function() {
            const menu = document.getElementById('fullScreenMenuMobile');
            menu.style.display = 'block'; // Asegura que se vea antes de la animación
            setTimeout(() => {
                menu.classList.add('active'); // Agrega la animación
            }, 10);
        });

        document.getElementById('closeMenuMobile').addEventListener('click', function() {
            const menu = document.getElementById('fullScreenMenuMobile');
            menu.classList.remove('active'); // Quita la animación

            setTimeout(() => {
                menu.style.display = 'none';
            }, 500); // Espera a que termine la animación antes de ocultarlo
        });
    </script>
</body>

</html>
