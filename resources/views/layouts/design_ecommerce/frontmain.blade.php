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
<input hidden type="user_id" value="{{ isset(Auth::user()->id) ? Auth::user()->id : '' }}" name="user_id"
    id="user_id">
<input type="hidden" class="prefix" id="prefix" value="{{ $prefix }}">

<body class="animsition">
    {{-- @include('frontend.av.add-comment') --}}
    <div>
        @if ($tenantinfo->license == 1)
            @include('layouts.inc.design_ecommerce.front')
            @yield('content')
            @include('layouts.inc.social-footer')
        @else
            @include('layouts.inc.design_ecommerce.frontvenc')
            @yield('content')
        @endif

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
            swal("Mensaje Del Sistema", "{{ session('status') }}", "{{ session('icon') }}");
        </script>
    @endif

    @yield('scripts')

    <script>
        var isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        var isMayor = {{ Auth::check() && Auth::user()->mayor == '1' ? 'true' : 'false' }};

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
            if (total == 0 && total_cloth == 0) {
                totalDiscountElement.textContent = `₡0`;
            }
        }

        function getCart() {
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
                .value : '';
            var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/get-cart-items";
            $.ajax({
                method: "GET",
                url: url,
                success: function(cartItems) {
                    $('.productsList').empty();
                    var imageBaseUrl = $('#modalMiniCart').data('image-base-url');

                    cartItems.forEach(function(item) {
                        var precio = item.price;
                        if (item.custom_size && item.stock_price > 0) {
                            precio = item.stock_price;
                        }
                        if (isAuthenticated === 'true' && isMayor === 'true' && item
                            .user_mayor && item.mayor_price > 0) {
                            precio = item.mayor_price;
                        }
                        var descuento = (precio * item.discount) / 100;
                        var precioConDescuento = precio - descuento;
                        var imageUrl = item.image ? `${imageBaseUrl}/${item.image}` :
                            '/images/producto-sin-imagen.PNG';
                        var attributesHtml = '';

                        if (item.attributes_values) {
                            var attributesValues = item.attributes_values.split(', ');
                            attributesHtml +=
                                '<span class="m-0 text-muted w-100 d-block">Atributos</span>';
                            attributesValues.forEach(attributeValue => {
                                var [attribute, value] = attributeValue.includes(
                                    ': ') ? attributeValue.split(': ') : [
                                    attributeValue, ''
                                ];
                                attributesHtml +=
                                    `<span>${attribute === 'Stock' ? 'Predeterminado' : attribute + ':'} ${attribute === 'Stock' ? '' : value}</span><br>`;
                            });
                        }

                        var cartItemHtml = `
                    <li class="header-cart-item flex-w flex-t m-b-12">
                        <input type="hidden" name="prod_id" value="${item.id}" class="prod_id">
                        <input type="hidden" class="price" value="${item.discount > 0 ? precioConDescuento : precio}">
                        <input type="hidden" value="${descuento}" class="discount" name="discount">
                        <div class="header-cart-item-img">
                            <img src="${imageUrl}" alt="IMG">
                        </div>
                        <div class="header-cart-item-txt p-t-8">
                            <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">${item.name}</a>
                            ${attributesHtml}
                            <span class="header-cart-item-info">₡${precioConDescuento}</span>
                            <div class="d-flex align-items-center">
                                <div class="input-group text-center input-group-static w-100">
                                    <input min="1" max="${item.stock > 0 ? item.stock : ''}" value="${item.quantity}" type="number" name="quantityCart" data-cart-id="${item.cart_id}" class="form-control btnQuantity text-center w-100 quantity">
                                </div>
                                <form name="delete-item-cart" class="delete-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button data-item-id="${item.cart_id}" class="btn btn-icon btn-3 btn-danger btnDelete">
                                        <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                `;
                        $('.productsList').append(cartItemHtml);
                    });
                    calcularTotal();
                }
            });
        }
        $(document).ready(function() {
            var url = null;
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
                .value : '';
            url = (prefix === 'aclimate' ? '/' + prefix : '') + "/get/products/select/";
            $('#search-select').select2({
                placeholder: "BUSCAR PRODUCTOS...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: url,
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
                url = (prefix === 'aclimate' ? '/' + prefix : '') + '/detail-clothing' + selectedId;
                if (selectedId) {
                    window.location.href = url;
                }
            });
            $(document).on('click', '.btnQuantity', function(e) {
                e.preventDefault();
                url = (prefix === 'aclimate' ? '/' + prefix : '') + '/edit-quantity';
                var quantity = $(this).val();
                var itemId = $(this).data('cart-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: "POST",
                    url: url,
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
                url = (prefix === 'aclimate' ? '/' + prefix : '') + '/delete-item-cart/' + cartId;
                $.ajax({
                    url: url,
                    type: 'POST', // Si usas el método DELETE, puedes sobreescribirlo en los datos o la cabecera según tu configuración
                    data: $form.serialize() + '&cart_id=' +
                        cartId, // Se envían los datos del form + el ID del item
                    success: function(response) {
                        var newCartNumber = response.cartNumber
                        const button = document.querySelector('.js-show-cart');
                        const buttonMobile = document.querySelector(
                            '.icon-cart-mobile');
                        button.dataset.notify = newCartNumber;
                        buttonMobile.dataset.notify = newCartNumber;
                        $('.cart-badge')
                            .text(newCartNumber);
                        $button.closest('.header-cart-item').remove();
                        calcularTotal();
                    }
                });
            });
            $('#searchfor').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.isotope-item').each(function() {
                    var name = $(this).find('.clothing-name').val() ||
                        ''; // Si es undefined, usa ''
                    var code = $(this).find('.code').val() || ''; // Si es undefined, usa ''
                    name = name.toLowerCase();
                    code = code.toLowerCase();
                    if (name.includes(searchTerm) || code.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            $(document).on('click', '.add_favorite', function(event) {
                event.preventDefault();
                var selected_attributes = [];
                $('input[type="hidden"][name$="_id"]').each(function() {
                    var selected_value = $(this).val();
                    var regex = /^\d+-\d+-\d+$/;
                    if (selected_value && regex.test(selected_value)) {
                        selected_attributes.push(selected_value);
                    }
                });
                var attributes = JSON.stringify(selected_attributes);
                let clothing_id = $(this).data('clothing-id');
                let category_id = $(this).data('category-id');
                let attr_id = $(this).data('attr_id');
                let value_attr = $(this).data('value_attr');
                var user_id = document.getElementById('user_id').value;
                let token = $('meta[name="csrf-token"]').attr('content');
                let icon = $(this).find('i');
                url = (prefix === 'aclimate' ? '/' + prefix : '') + '/add-favorite';
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        user_id: user_id,
                        clothing_id: clothing_id,
                        category_id: category_id,
                        _token: token,
                        attributes: attributes
                    },
                    success: function(response) {
                        const button = document.querySelector('.icon-fav');
                        const buttonMobile = document.querySelector('.icon-fav-mobile');

                        if (response.status === 'added') {
                            icon.addClass('text-danger');
                        } else {
                            icon.removeClass('text-danger');
                        }

                        // Si aún usas estos data-notify:
                        if (button) button.dataset.notify = response.favNumber;
                        if (buttonMobile) buttonMobile.dataset.notify = response.favNumber;
                        // 🔥 Actualizar badge numérico de la barra inferior
                        $('.wishlist-badge')
                            .text(response.favNumber);
                    },
                    error: function(xhr) {
                        console.error('Error al añadir a favoritos', xhr.responseText);
                    }
                });
            });
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
        document.addEventListener("click", function(event) {
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
                .value : '';
            var url = null;
            if (event.target.closest(".js-show-modal1")) {
                let button = event.target.closest(".js-show-modal1");

                let productName = button.getAttribute("data-name");
                let quantity = 1;
                let attr_id = null;
                let value_attr = null;
                let porcDiscount = button.getAttribute("data-discount");
                let productPrice = button.getAttribute("data-price");
                let productPriceOrig = button.getAttribute("data-original-price");
                let productDesc = button.getAttribute("data-description");
                let productId = button.getAttribute("data-id");
                let productImages = JSON.parse(button.getAttribute("data-images"));
                let productAttributes = JSON.parse(button.getAttribute("data-attributes"));

                // Actualizar los elementos de texto en el modal
                document.querySelector(".js-name-detail").innerText = productName;
                document.querySelector(".mtext-106").innerText = `₡${productPrice}`;
                document.querySelector(".text-desc").innerHTML = productDesc;

                // Seleccionar el contenedor del slider
                let slickContainer = document.querySelector(".slick3");

                // Si Slick ya está inicializado, eliminarlo correctamente
                if ($(slickContainer).hasClass("slick-initialized")) {
                    $(slickContainer).slick("unslick");
                }

                // Limpiar completamente el contenedor antes de agregar nuevas imágenes
                slickContainer.innerHTML = "";

                // Generar dinámicamente las imágenes
                productImages.forEach(image => {
                    let slide = `
                    <div class="item-slick3" data-thumb="${image}">
                        <div class="wrap-pic-w pos-relative">
                            <img src="${image}" alt="IMG-PRODUCT">
                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="${image}">
                                <i class="fa fa-expand"></i>
                            </a>
                        </div>
                    </div>`;
                    slickContainer.insertAdjacentHTML("beforeend", slide);
                });

                // Generar dinámicamente los select para atributos
                let attributesContainer = document.querySelector(".p-t-33");

                // Limpiar cualquier contenido anterior
                attributesContainer.innerHTML = '';
                let selectHTML = '';
                let quantityHTML = '';

                productAttributes.forEach(attribute => {
                    let attributeName = attribute.columna_atributo;
                    let attributeValues = attribute.valores.split(
                        "/"); // Valores de los atributos
                    let attributeIds = attribute.ids.split("/"); // IDs de los valores
                    let attributeStock = attribute.stock.split(
                        "/"); // Stock de los valores

                    selectHTML = `
                <div class="flex-w flex-r-m p-b-10">
                <div class="size-203 flex-c-m respon6">${attributeName}</div>
                <div class="size-204 respon6-next">
                    <div class="rs1-select2 bor8 bg0">
                        <select class="js-select2" name="${attributeName.toLowerCase()}"
                                data-attribute="${attributeName}" data-value="${attribute.attr_id}-${attributeName}">
                                
                                <option>Selecciona una opción</option>`;
                    // Crear opciones dinámicas para el select
                    attributeValues.forEach((value, index) => {
                        let optionId = attributeIds[index]; // ID del valor
                        let selected = index === 0 ? 'selected' :
                            ''; // Seleccionar la primera opción por defecto
                        let stock = attributeStock[index]; // Stock del valor

                        // Agregar la opción al select con los atributos adecuados
                        selectHTML += `
                    <option value="${optionId}" 
                            data-stock="${stock}" 
                            ${selected} 
                            id="${attributeName}_${attribute.attr_id}">
                        ${value}
                    </option>`;
                    });

                    selectHTML += `
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </div>
                </div>`;

                    // Insertar el select generado al contenedor
                    attributesContainer.insertAdjacentHTML("beforeend", selectHTML);
                });
                quantityHTML = `
                <div class="flex-w flex-r-m p-b-10">
                <div class="size-204 flex-w flex-m respon6-next">
                    <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                        <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                            <i class="fs-16 zmdi zmdi-minus"></i>
                        </div>

                        <input class="mtext-104 cl3 txt-center num-product" type="number" min="1" max="1" name="quantity" value="1">

                        <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                            <i class="fs-16 zmdi zmdi-plus"></i>
                        </div>
                    </div>

                    <button class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                        Agregar
                    </button>
                </div>
                </div>`;

                // Insertar el bloque de cantidad al final del contenedor de atributos
                attributesContainer.insertAdjacentHTML("beforeend", quantityHTML);

                // Inicializar select2 después de insertar los select
                $(".js-select2").each(function() {
                    $(this).select2({
                        minimumResultsForSearch: 20,
                        dropdownParent: $(this).next('.dropDownSelect2')
                    });
                    $(this).on("change", function() {
                        let selectedValue = $(this)
                            .val(); // Obtener el valor seleccionado
                        let selectedOption = $(this).find(
                            "option:selected"); // Opción seleccionada
                        let stock = selectedOption.data(
                            "stock"
                        ); // Obtener el stock de la opción seleccionada
                        let partes = selectedOption.attr('id').split("_");
                        attr_id = partes[1];
                        value_attr = selectedValue;
                        if (partes.length === 2) {
                            // Obtener id del atributo y valor y ejecutar la función getStock
                            getStock(productId, attr_id,
                                selectedValue, porcDiscount
                            ); // Llamar a getStock con el producto, atributo, y valor seleccionado
                        }
                    });
                });

                // Esperar un poco antes de volver a inicializar Slick
                setTimeout(() => {
                    $(slickContainer).slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        fade: true,
                        infinite: true,
                        autoplay: false,
                        autoplaySpeed: 6000,

                        arrows: true,
                        appendArrows: $(".wrap-slick3-arrows"),
                        prevArrow: '<button class="arrow-slick3 prev-slick3"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                        nextArrow: '<button class="arrow-slick3 next-slick3"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',

                        dots: true,
                        appendDots: $(".wrap-slick3-dots"),
                        dotsClass: 'slick3-dots',
                        customPaging: function(slick, index) {
                            var portrait = $(slick.$slides[index]).data(
                                'thumb');
                            return '<img src="' + portrait +
                                '"/><div class="slick3-dot-overlay"></div>';
                        },
                    });
                }, 200);
                $(document).on('click', '.btn-num-product-down', function() {
                    var numProduct = Number($(this).next().val());
                    let maxStock = Number($(this).prev().attr("max"));
                    if (numProduct > 1) {
                        $(this).next().val(numProduct - 1);
                        quantity = $(this).next().val();
                    }
                });

                $(document).on('click', '.btn-num-product-up', function() {
                    var numProduct = Number($(this).prev().val());
                    let maxStock = Number($(this).prev().attr("max"));
                    if (numProduct < maxStock) {
                        $(this).prev().val(numProduct + 1); // Incrementar la cantidad
                        quantity = $(this).prev().val();
                    }
                });
                // Ejecutar la lógica de getStock() para el primer valor seleccionado por defecto
                let firstSelect = document.querySelector(".js-select2");
                let firstSelectedValue = firstSelect ? firstSelect.value : null;

                if (firstSelectedValue) {
                    let selectedOption = firstSelect.selectedOptions[
                        0]; // Obtener la opción seleccionada
                    let partes = selectedOption ? selectedOption.id.split("_") : [];
                    value_attr = firstSelectedValue;
                    if (partes.length === 2) {
                        // Ejecutar getStock con el primer valor seleccionado
                        attr_id = partes[1];
                        getStock(productId, partes[1],
                            firstSelectedValue, porcDiscount
                        ); // Llamar a getStock con el producto, atributo, y valor seleccionado
                    }
                }
                //Metodo para agregar al carrito
                $(document).on('click', '.js-addcart-detail', function(e) {
                    e.preventDefault();
                    var attributes_array = [];
                    var concat_attr = value_attr + "-" + attr_id + "-" + productId;
                    if (value_attr != null && attr_id != null) {
                        attributes_array.push(concat_attr);
                    }
                    var attributes = JSON.stringify(attributes_array);
                    var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/add-to-cart";

                    console.log(attributes);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            'clothing_id': productId,
                            'quantity': quantity,
                            'attributes': attributes,
                        },
                        success: function(response) {
                            swal(response.status,
                                "Producto agregado al carrito", response
                                .icon);
                            if (response.icon === "success") {
                                var newCartNumber = response.cartNumber;
                                const button = document.querySelector(
                                    '.js-show-cart');
                                const buttonMobile = document.querySelector(
                                    '.icon-cart-mobile');
                                button.dataset.notify = newCartNumber;
                                buttonMobile.dataset.notify = newCartNumber;
                                $('.cart-badge')
                                    .text(newCartNumber);
                                getCart();
                            }
                        }
                    });

                    /* const quantityInput = document.getElementById('quantityInput');

                    quantityInput.addEventListener('keydown', function(event) {
                        if (event.key === 'ArrowUp' || event.key ===
                            'ArrowDown') {
                            return true;
                        } else {
                            event.preventDefault();
                            return false;
                        }
                    }); */
                });
            }
        });

        function getStock(cloth_id, attr_id, value_attr, porcDescuento) {
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
                .value : '';
            var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/get-stock/" + cloth_id + '/' + attr_id + '/' +
                value_attr;
            $.ajax({
                method: "GET",
                url: url,
                success: function(stock) {
                    var maxStock = stock.stock > 0 ? stock.stock : '';
                    var perPrice = stock.price;
                    if (perPrice > 0) {
                        $('input[name="quantity"]').attr('max', maxStock);
                        $('input[name="quantity"]').val(1);
                        if (porcDescuento > 0) {
                            var descuento = (perPrice * porcDescuento) / 100;
                            var precioConDescuento = perPrice - descuento;
                            document.querySelector(".mtext-106").innerText =
                                `₡${precioConDescuento.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                            /* price_discount.textContent =
                                `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`; */
                        } else {
                            document.querySelector(".mtext-106").innerText =
                                `₡${perPrice.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
                        }
                    }
                }
            });
        }

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
            if (total == 0 && total_cloth == 0) {
                totalDiscountElement.textContent = `₡0`;
            }
        }

        function getCart() {
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
                .value : '';
            var url = (prefix === 'aclimate' ? '/' + prefix : '') + "/get-cart-items";
            $.ajax({
                method: "GET",
                url: url,
                success: function(cartItems) {
                    $('.productsList').empty();
                    var imageBaseUrl = $('#modalMiniCart').data('image-base-url');

                    cartItems.forEach(function(item) {
                        var precio = item.price;
                        if (item.custom_size && item.stock_price > 0) {
                            precio = item.stock_price;
                        }
                        if (isAuthenticated === 'true' && isMayor === 'true' && item
                            .user_mayor && item.mayor_price > 0) {
                            precio = item.mayor_price;
                        }
                        var descuento = (precio * item.discount) / 100;
                        var precioConDescuento = precio - descuento;
                        var imageUrl = item.image ? `${imageBaseUrl}/${item.image}` :
                            '/images/producto-sin-imagen.PNG';
                        var attributesHtml = '';

                        if (item.attributes_values) {
                            var attributesValues = item.attributes_values.split(', ');
                            attributesHtml +=
                                '<span class="m-0 text-muted w-100 d-block">Atributos</span>';
                            attributesValues.forEach(attributeValue => {
                                var [attribute, value] = attributeValue.includes(
                                    ': ') ? attributeValue.split(': ') : [
                                    attributeValue, ''
                                ];
                                attributesHtml +=
                                    `<span>${attribute === 'Stock' ? 'Predeterminado' : attribute + ':'} ${attribute === 'Stock' ? '' : value}</span><br>`;
                            });
                        }

                        var cartItemHtml = `
                    <li class="header-cart-item flex-w flex-t m-b-12">
                        <input type="hidden" name="prod_id" value="${item.id}" class="prod_id">
                        <input type="hidden" class="price" value="${item.discount > 0 ? precioConDescuento : precio}">
                        <input type="hidden" value="${descuento}" class="discount" name="discount">
                        <div class="header-cart-item-img">
                            <img src="${imageUrl}" alt="IMG">
                        </div>
                        <div class="header-cart-item-txt p-t-8">
                            <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">${item.name}</a>
                            ${attributesHtml}
                            <span class="header-cart-item-info">₡${precioConDescuento}</span>
                            <div class="d-flex align-items-center">
                                <div class="input-group text-center input-group-static w-100">
                                    <input min="1" max="${item.stock > 0 ? item.stock : ''}" value="${item.quantity}" type="number" name="quantityCart" data-cart-id="${item.cart_id}" class="form-control btnQuantity text-center w-100 quantity">
                                </div>
                                <form name="delete-item-cart" class="delete-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button data-item-id="${item.cart_id}" class="btn btn-icon btn-3 btn-danger btnDelete">
                                        <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                `;
                        $('.productsList').append(cartItemHtml);
                    });
                    calcularTotal();
                }
            });
        }
        // Cerrar modal
        document.querySelectorAll(".js-hide-modal1").forEach(button => {
            button.addEventListener("click", function() {
                document.querySelector(".js-modal1").classList.remove("show-modal1");
                $(document).off("click", ".btn-num-product-up");
                $(document).off("click", ".btn-num-product-down");
                $(document).off("click", ".js-addcart-detail");
            });
        });
    </script>
</body>

</html>
