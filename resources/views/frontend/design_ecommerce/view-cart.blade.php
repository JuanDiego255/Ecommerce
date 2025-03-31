@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- <div class="product_data container mb-3 mt-4">
        <div class="breadcrumb-nav bc3x">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('category/') }}"><i class="fas fa-box me-1"></i>Categorías</a></li>
                <li class="bread-standard"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
            @else
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                            class="fas fa-shapes me-1"></i>Departamentos</a></li>
                <li class="bread-standard"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
            @endif

        </div>
        <center>
            <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
                <div class="col-lg-8 bg-transparent">
                    <div class="card w-100">

                        <div class="table-responsive">
                            <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva }}">
                            <table class="table align-items-center mb-0" id="cartTable">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7">Imagen</th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                        </th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Precio</th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Atributos
                                        </th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Cant</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart_items as $item)
                                        <tr>
                                            @php
                                                $precio = $item->price;
                                                if (
                                                    isset($tenantinfo->custom_size) &&
                                                    $tenantinfo->custom_size == 1 &&
                                                    $item->stock_price > 0
                                                ) {
                                                    $precio = $item->stock_price;
                                                }
                                                if (
                                                    Auth::check() &&
                                                    Auth::user()->mayor == '1' &&
                                                    $item->mayor_price > 0
                                                ) {
                                                    $precio = $item->mayor_price;
                                                }
                                                $descuentoPorcentaje = $item->discount;
                                                // Calcular el descuento
                                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                // Calcular el precio con el descuento aplicado
                                                $precioConDescuento = $precio - $descuento;
                                                if (
                                                    Auth::check() &&
                                                    Auth::user()->mayor == '1' &&
                                                    $item->mayor_price > 0
                                                ) {
                                                    $precio = $item->mayor_price;
                                                }
                                                $descuentoPorcentaje = $item->discount;
                                                // Calcular el descuento
                                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                // Calcular el precio con el descuento aplicado
                                                $precioConDescuento = $precio - $descuento;
                                                $attributesValues = explode(', ', $item->attributes_values);
                                            @endphp
                                            <input type="hidden" name="prod_id" value="{{ $item->id }}"
                                                class="prod_id">
                                            <input type="hidden" class="price"
                                                value="{{ $item->discount > 0
                                                    ? $precioConDescuento
                                                    : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0
                                                        ? $item->mayor_price
                                                        : ($tenantinfo->custom_size == 1
                                                            ? $item->stock_price
                                                            : $item->price)) }}
                                                ">
                                            <input type="hidden" value="{{ $descuento }}" class="discount"
                                                name="discount">
                                            <td class="w-50">
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <a target="blank" data-fancybox="gallery"
                                                            href="{{ isset($item->image) ? route('file', $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}">
                                                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                                                class="img-fluid shadow border-radius-lg w-25">
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-justify text-truncate para mb-0">{{ $item->name }}</p>

                                            </td>
                                            <td class="align-middle text-center text-sm">

                                                <p class="text-success mb-0">₡
                                                    {{ $item->discount > 0
                                                        ? $precioConDescuento
                                                        : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0
                                                            ? $item->mayor_price
                                                            : ($tenantinfo->custom_size == 1
                                                                ? $item->stock_price
                                                                : $item->price)) }}

                                                    @if ($item->discount > 0)
                                                        <s
                                                            class="text-danger">{{ Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : ($tenantinfo->custom_size == 1 ? $item->stock_price : $item->price) }}</s>
                                                    @endif
                                                </p>

                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                @foreach ($attributesValues as $attributeValue)
                                                    @php
                                                        // Separa el atributo del valor por ": "
                                                        [$attribute, $value] = explode(': ', $attributeValue);
                                                    @endphp

                                                    {{ $attribute }}: {{ $value }}<br>
                                                @endforeach
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <div class="input-group text-center input-group-static w-100">
                                                    <input min="1" max="{{ $item->stock > 0 ? $item->stock : ''}}"
                                                        data-cart-id="{{ $item->cart_id }}" value="{{ $item->quantity }}"
                                                        type="number" name="quantity" id="quantity{{ $item->quantity }}"
                                                        class="form-control btnQuantity text-center w-100 quantity">
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <form name="delete-item-cart" id="delete-item-cart" class="delete-form">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button data-item-id="{{ $item->cart_id }}"
                                                        class="btn btn-icon btn-3 btn-danger btnDeleteCart">
                                                        <span class="btn-inner--icon"><i
                                                                class="material-icons">delete</i></span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 bg-transparent">
                    <div class="card mb-4">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    Productos
                                    <span id="totalCloth">₡{{ number_format($cloth_price) }}</span>
                                </li>
                                @if ($iva > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        I.V.A
                                        <span id="totalIvaElement">₡{{ number_format($iva) }}</span>
                                    </li>
                                @endif

                                @if ($you_save > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        Ahorraste
                                        <span id="totalDiscountElement">₡{{ number_format($you_save) }}</span>
                                    </li>
                                @endif

                                <li class="list-group-item d-flex justify-content-between border-0 px-0 mb-3">

                                    <strong>Total</strong>
                                    <span><strong id="totalPriceElement">₡{{ number_format($total_price) }}</strong></span>
                                </li>
                            </ul>

                            <a class="btn btn-icon btn-3 mt-2 btn-add_to_cart" href="{{ url('checkout') }}">
                                <span class="btn-inner--icon"><i class="material-icons">local_atm</i></span>
                                <span class="btn-inner--text">Ir a pagar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </center>

    </div> --}}
    <!-- breadcrumb -->
    <div class="container m-t-80">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Inicio
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url('/category') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Categorías
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <span class="stext-109 cl4">
                    Carrito
                </span>
            @else
                <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Inicio
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url('departments/index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Departamentos
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <span class="stext-109 cl4">
                    Carrito
                </span>
            @endif
        </div>
    </div>


    <!-- Shoping Cart -->
    <form class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table id="cartTable" class="table-shopping-cart product_data">
                                <tr class="table_head">
                                    <th class="column-1">Imagen</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Precio</th>
                                    <th class="column-4">Atributos</th>
                                    <th class="column-5">Cantidad</th>
                                </tr>
                                <tbody>
                                    @foreach ($cart_items as $item)
                                        @php
                                            $precio = $item->price;
                                            if (
                                                isset($tenantinfo->custom_size) &&
                                                $tenantinfo->custom_size == 1 &&
                                                $item->stock_price > 0
                                            ) {
                                                $precio = $item->stock_price;
                                            }
                                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                                $precio = $item->mayor_price;
                                            }
                                            $descuentoPorcentaje = $item->discount;
                                            // Calcular el descuento
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            // Calcular el precio con el descuento aplicado
                                            $precioConDescuento = $precio - $descuento;
                                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                                $precio = $item->mayor_price;
                                            }
                                            $descuentoPorcentaje = $item->discount;
                                            // Calcular el descuento
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            // Calcular el precio con el descuento aplicado
                                            $precioConDescuento = $precio - $descuento;
                                            $attributesValues = explode(', ', $item->attributes_values);
                                        @endphp
                                        <tr class="table_row">
                                            <input type="hidden" name="prod_id" value="{{ $item->id }}"
                                                class="prod_id">
                                            <input type="hidden" class="price"
                                                value="{{ $item->discount > 0
                                                    ? $precioConDescuento
                                                    : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0
                                                        ? $item->mayor_price
                                                        : ($tenantinfo->custom_size == 1
                                                            ? $item->stock_price
                                                            : $item->price)) }}
                                            ">
                                            <input type="hidden" value="{{ $descuento }}" class="discount"
                                                name="discount">
                                            <td class="column-1">
                                                <div class="how-itemcart1 btnDeleteCart"
                                                    data-item-id="{{ $item->cart_id }}">
                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                                        alt="IMG">
                                                </div>
                                            </td>
                                            <td class="column-2">{{ $item->name }}</td>
                                            <td class="column-3">
                                                <p class="text-success mb-0">₡
                                                    {{ $item->discount > 0
                                                        ? $precioConDescuento
                                                        : (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0
                                                            ? $item->mayor_price
                                                            : ($tenantinfo->custom_size == 1
                                                                ? $item->stock_price
                                                                : $item->price)) }}

                                                    @if ($item->discount > 0)
                                                        <s
                                                            class="text-danger">{{ Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : ($tenantinfo->custom_size == 1 ? $item->stock_price : $item->price) }}</s>
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="column-5">
                                                @foreach ($attributesValues as $attributeValue)
                                                    @php
                                                        // Separa el atributo del valor por ": "
                                                        [$attribute, $value] = explode(': ', $attributeValue);
                                                    @endphp

                                                    {{ $attribute }}: {{ $value }}
                                                    <br>
                                                @endforeach
                                            </td>
                                            <td class="column-4">
                                                <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                    <div
                                                        class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m btnQuantity">
                                                        <i class="fs-16 zmdi zmdi-minus"></i>
                                                    </div>
                                                    <input min="1" max="{{ $item->stock > 0 ? $item->stock : '' }}"
                                                        data-cart-id="{{ $item->cart_id }}" value="{{ $item->quantity }}"
                                                        type="number" name="quantity" id="quantity{{ $item->quantity }}"
                                                        class="quantity mtext-104 cl3 txt-center num-product">
                                                    <div
                                                        class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m btnQuantity">
                                                        <i class="fs-16 zmdi zmdi-plus"></i>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Totales
                        </h4>
                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-208">
                                <span class="stext-110 cl2">
                                    Subtotal:
                                </span>
                            </div>

                            <div class="size-209">
                                <span id="totalCloth" class="mtext-110 cl2">
                                    ₡{{ number_format($cloth_price) }}
                                </span>
                            </div>
                        </div>
                        @if ($iva > 0)
                            <div class="flex-w flex-t bor12 p-b-13">
                                <div class="size-208">
                                    <span class="stext-110 cl2">
                                        IVA:
                                    </span>
                                </div>

                                <div class="size-209">
                                    <span id="totalIvaElement" class="mtext-110 cl2">
                                        ₡{{ number_format($iva) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if ($you_save > 0)
                            <div class="flex-w flex-t p-t-27 p-b-33">
                                <div class="size-208">
                                    <span class="mtext-101 cl2">
                                        Ahorraste:
                                    </span>
                                </div>

                                <div class="size-209 p-t-1">
                                    <span class="mtext-110 cl2" id="totalDiscountElement">
                                        ₡{{ number_format($you_save) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="flex-w flex-t bor12 p-t-15 p-b-30">
                            <div class="size-208 w-full-ssm">
                                <span class="stext-110 cl2">
                                    Pago:
                                </span>
                            </div>

                            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
                                <p class="stext-111 cl6 p-t-2">
                                    En estos momentos no contamos con el método de pago con tarjeta, puedes cancelar por
                                    SINPE Movil, o transferencia bancaria
                                </p>

                                {{-- <div class="p-t-15">
                                    <span class="stext-112 cl8">
                                        Calculate Shipping
                                    </span>

                                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 m-t-9">
                                        <select class="js-select2" name="time">
                                            <option>Select a country...</option>
                                            <option>USA</option>
                                            <option>UK</option>
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>

                                    <div class="bor8 bg0 m-b-12">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="state"
                                            placeholder="State /  country">
                                    </div>

                                    <div class="bor8 bg0 m-b-22">
                                        <input class="stext-111 cl8 plh3 size-111 p-lr-15" type="text" name="postcode"
                                            placeholder="Postcode / Zip">
                                    </div>

                                    <div class="flex-w">
                                        <div
                                            class="flex-c-m stext-101 cl2 size-115 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer">
                                            Update Totals
                                        </div>
                                    </div>

                                </div> --}}
                            </div>
                        </div>
                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Total:
                                </span>
                            </div>

                            <div class="size-209 p-t-1">
                                <span id="totalPriceElement" class="mtext-110 cl2">
                                    ₡{{ number_format($total_price) }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ url('checkout') }}" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer text-white">
                            Finalizar Pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.btnAddToCart').click(function(e) {
                e.preventDefault();
                var cloth_id = $(this).closest('.product_data').find('.prod_id').val();
                var quantity = $(this).closest('.product_data').find('.quantity').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/add-to-cart",
                    data: {
                        'clothing_id': cloth_id,
                        'quantity': quantity,
                    },
                    success: function(response) {
                        swal(response.status,
                            "Producto agregado al carrito", response
                            .icon);
                    }
                });
            });
            $(document).on('click', '.btnQuantity', function(e) {
                e.preventDefault();
                var $row = $(this).closest('.flex-w'); // Encuentra la fila específica
                var $quantityInput = $row.find('.quantity');
                var quantity = parseInt($quantityInput.val()) || 1;
                var itemId = $quantityInput.data('cart-id');
                console.log(quantity);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/edit-quantity",
                    data: {
                        'cart_id': itemId,
                        'quantity': quantity,
                    },
                    success: function(response) {
                        calcularTotalCart();
                    }
                });
            });
        });

        $('.btnDeleteCart').click(function(e) {
            e.preventDefault();

            var itemId = $(this).data('item-id');
            // Confirmar la eliminación
            var confirmDelete = confirm('¿Deseas borrar este artículo?');
            var row = $(this).closest('.table_row');

            if (confirmDelete) {
                $.ajax({
                    method: "POST",
                    url: "/delete-item-cart/" + itemId,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE',
                    },
                    success: function(response) {
                        if (response.refresh == true) {
                            window.location.href = "{{ url('/') }}";
                        } else {
                            row.remove(); // Elimina la fila antes de actualizar los totales
                            calcularTotalCart(); // Recalcula los totales después de la eliminación
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        function calcularTotalCart() {
            let total = 0;
            let total_cloth = 0;
            let iva = parseFloat(document.getElementById("iva_tenant").value);
            let total_iva = 0;
            let you_save = 0;
            // Obtener todas las filas de la tabla
            const filas = document.querySelectorAll('#cartTable tbody .table_row');
            console.log(filas);


            filas.forEach((fila) => {
                const precio = parseFloat(fila.querySelector('.price').value);
                const discount = parseFloat(fila.querySelector('.discount').value);
                const cantidad = parseInt(fila.querySelector('.quantity').value);

                const subtotal = precio * cantidad;
                const subtotal_discount = discount * cantidad;
                you_save += subtotal_discount;
                total += subtotal;
            });

            total_iva = total * iva;
            total_cloth = total;
            total = total + total_iva;

            // Mostrar el total actualizado en el elemento correspondiente
            const totalElement = document.getElementById('totalPriceElement');
            const totalIvaElement = document.getElementById('totalIvaElement');
            const totalDiscountElement = document.getElementById('totalDiscountElement');
            const totalCloth = document.getElementById('totalCloth');
            const btnPay = document.getElementById('btnPay');

            totalElement.textContent =
                `₡${total.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
            if (total_iva > 0) {
                totalIvaElement.textContent =
                    `₡${total_iva.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
            }
            if (you_save > 0) {
                totalDiscountElement.textContent =
                    `₡${you_save.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
            }
            totalCloth.textContent =
                `₡${total_cloth.toLocaleString('es-CR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(',', '.')}`;
        }
    </script>
@endsection
