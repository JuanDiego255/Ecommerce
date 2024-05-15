<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ tenant_asset('/') . '/' . (isset($tenantinfo->logo) ? $tenantinfo->logo : '') }}"
        type="image/x-icon">

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
    <script src="https://kit.fontawesome.com/82a78dd1a0.js" crossorigin="anonymous"></script>
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
        --cintillo: {{ $settings->cintillo }};
        --cintillo_text: {{ $settings->cintillo_text }};
    }
</style>

<body class="g-sidenav-show  bg-gray-200">


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <div>
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'main')
                @switch($tenantinfo->kind_business)
                    @case(1)
                        @include('layouts.inc.frontcar')
                    @break
                    @case(2)
                    @case(3)
                        @include('layouts.inc.websites.frontnavbar')
                    @break
                    @default
                        @include('layouts.inc.frontnavbar')
                @endswitch
            @else
                @include('layouts.inc.centralnavbar')
            @endif

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
    @if ($view_name != 'frontend_view-cart')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.btnQuantity', function(e) {
                    e.preventDefault();

                    var cloth_id = $(this).closest('.py-3.border-bottom').find('.prod_id').val();
                    var quantity = $(this).val();
                    var price = $(this).closest('.py-3.border-bottom').find('.price').val();
                    var size_id = $(this).closest('.py-3.border-bottom').find('.size_id').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        method: "POST",
                        url: "/edit-quantity",
                        data: {
                            'clothing_id': cloth_id,
                            'quantity': quantity,
                            'size': size_id,
                        },
                        success: function(response) {
                            calcularTotal();
                        }
                    });
                });

                $(document).on('click', '.btnDelete', function(e) {
                    e.preventDefault();

                    var itemId = $(this).data('item-id');
                    var sizeId = $(this).data('size-id');
                    let view_name = document.getElementById("view_name").value;

                    $.ajax({
                        method: "POST",
                        url: "/delete-item-cart/" + itemId,
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE',
                            size_id: sizeId
                        },
                        success: function(response) {
                            getCart();
                            var newCartNumber = response.cartNumber
                            $('.badge').text(newCartNumber);
                            $('.cartIcon').text(' ' + newCartNumber);
                            if (response.refresh == true && view_name == "frontend_view-cart") {
                                window.location.href = "{{ url('/') }}";
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejar errores si es necesario
                            console.error(xhr.responseText);
                        }
                    });
                });
            });

            function calcularTotal() {
                let total = 0;
                let total_cloth = 0;
                let iva = parseFloat(document.getElementById("iva_tenant").value);
                let total_iva = 0;
                let you_save = 0;
                // Obtener todos los elementos li que contienen los productos
                const items = document.querySelectorAll('.py-3.border-bottom');

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
                const totalElement = document.getElementById('totalPriceElement');
                const totalIvaElement = document.getElementById('totalIvaElement');
                const totalDiscountElement = document.getElementById('totalDiscountElement');
                const totalCloth = document.getElementById('totalCloth');

                totalElement.textContent = `₡${total.toLocaleString()}`;
                if (total_iva > 0) {
                    totalIvaElement.textContent = `₡${total_iva.toLocaleString()}`;
                }
                if (you_save > 0) {
                    totalDiscountElement.textContent = `₡${you_save.toLocaleString()}`;
                }
                totalCloth.textContent = `₡${total_cloth.toLocaleString()}`;
            }

            function getCart() {
                // Nueva solicitud AJAX para cargar el contenido del carrito actualizado
                $.ajax({
                    method: "GET",
                    url: "/get-cart-items", // Cambia esto por la ruta que devuelve los elementos del carrito
                    success: function(cartItems) {
                        // Limpiar la lista actual
                        $('.productsList').empty();
                        var imageBaseUrl = $('#modalMiniCart').data('image-base-url');

                        // Recorrer los elementos del carrito y agregarlos al modal
                        cartItems.forEach(function(item) {
                            var precio = item.price;
                            if (item.custom_size && item.stock_price > 0) {
                                precio = item.stock_price;
                            }
                            if (item.user_mayor && item.mayor_price > 0) {
                                precio = item.mayor_price;
                            }
                            var descuentoPorcentaje = item.discount;
                            // Calcular el descuento
                            var descuento = (precio * descuentoPorcentaje) / 100;
                            // Calcular el precio con el descuento aplicado
                            var precioConDescuento = precio - descuento;
                            var imageUrl = imageBaseUrl + '/' + item.image;
                            var listItem = `<li class="py-3 border-bottom">
                                <input type="hidden" name="prod_id" value="${item.id}" class="prod_id">
                                <input type="hidden" class="price" value="${item.discount > 0 ? precioConDescuento : (item.mayor_price > 0 ? item.mayor_price : (item.stock_price ? item.stock_price : item.price))}">
                                <input type="hidden" value="${item.size_id}" class="size_id" name="size">
                                <input type="hidden" value="${descuento}" class="discount" name="discount">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <a href="${imageUrl}">
                                            <img class="img-fluid border" src="${imageUrl}" alt="...">
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <p class="mb-2">
                                            <a class="text-muted fw-500" href="#">${item.name}</a>
                                            <span class="m-0 text-dark w-100 d-block ${item.manage_size == 0 ? 'd-none' : ''}">
                                                ${item.manage_size == 0 ? 'd-none' : ''}
                                                ${item.tenant != 'fragsperfumecr' ? 'Talla: ' : 'Tamaño: '}
                                                ${item.size}
                                            </span>
                                            <span class="m-0 text-muted w-100 d-block">₡${item.discount > 0 ? item.precioConDescuento : (item.mayor_price > 0 ? item.mayor_price : (item.stock_price ? item.stock_price : item.price))}</span>
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <div class="input-group text-center input-group-static w-100">
                                                <input min="1" max="${item.stock}" value="${item.quantity}" type="number" name="quantity" data-cloth-id="${item.id}" class="form-control btnQuantity text-center w-100 quantity">
                                            </div>
                                            <form name="delete-item-cart" id="delete-item-cart" class="delete-form">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="button" data-item-id="${item.id}" id="btnDelete-${item.id}"
                                                    data-size-id="${item.size_id}" class="btn btn-icon btn-3 btn-danger btnDelete">
                                                    <span class="btn-inner--icon"><i class="material-icons">delete</i></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>`;
                            $('.productsList').append(listItem);
                        });

                        calcularTotal();
                        // Actualizar cualquier otro dato del carrito en el modal (subtotal, descuento, total, etc.)
                    }
                });

            }
        </script>
    @endif

    @yield('scripts')

    <script>
        window.companyName = "{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}";
    </script>


</body>

</html>
