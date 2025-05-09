@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @switch($tenantinfo->tenant)
        @case('sakura318')
            <div class="product_data container mb-3 mt-4">
                <div class="breadcrumb-nav-sk">
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home-sk"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                        <li class="bread-sk"><a href="{{ url('category/') }}"><i class="fas fa-box me-1"></i>Categorías</a></li>
                        <li class="bread-sk"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
                    @else
                        <li class="home-sk"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-sk"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-sk"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
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
                                                <th class="sakura-color font-weight-bolder opacity-7">Imagen</th>
                                                <th class="sakura-color text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                                </th>
                                                <th class="sakura-color text-center text-secondary font-weight-bolder opacity-7">
                                                    Precio</th>
                                                <th class="sakura-color text-center text-secondary font-weight-bolder opacity-7">
                                                    Atributos
                                                </th>
                                                <th class="sakura-color text-center text-secondary font-weight-bolder opacity-7">
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
                                                        $attributesValues = !empty($item->attributes_values)
                                                            ? explode(', ', $item->attributes_values)
                                                            : [];
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
                                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
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
                                                                // Verifica que el atributo tenga el formato esperado antes de hacer explode
                                                                $parts = explode(': ', $attributeValue, 2);
                                                                $attribute = $parts[0] ?? '';
                                                                $value = $parts[1] ?? '';
                                                            @endphp

                                                            @if ($attribute !== '')
                                                                {{ $attribute }}: {{ $value }}<br>
                                                            @endif
                                                        @endforeach

                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <div class="input-group text-center input-group-static w-100">
                                                            <input min="1" max="{{ $item->stock > 0 ? $item->stock : '' }}"
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
                                            class="list-group-item d-flex sakura-font justify-content-between align-items-center border-0 px-0 pb-0">
                                            Productos
                                            <span id="totalCloth"
                                                class="sakura-color sakura-font">₡{{ number_format($cloth_price) }}</span>
                                        </li>
                                        @if ($iva > 0)
                                            <li
                                                class="list-group-item sakura-font d-flex justify-content-between align-items-center px-0">
                                                I.V.A
                                                <span id="totalIvaElement" class="sakura-color">₡{{ number_format($iva) }}</span>
                                            </li>
                                        @endif

                                        @if ($you_save > 0)
                                            <li
                                                class="list-group-item sakura-font d-flex justify-content-between align-items-center px-0">
                                                Ahorraste
                                                <span id="totalDiscountElement"
                                                    class="sakura-color">₡{{ number_format($you_save) }}</span>
                                            </li>
                                        @endif

                                        <li class="list-group-item sakura-font d-flex justify-content-between border-0 px-0 mb-3">

                                            <strong>Total</strong>
                                            <span class="sakura-color"><strong
                                                    id="totalPriceElement">₡{{ number_format($total_price) }}</strong></span>
                                        </li>
                                    </ul>

                                    <a class="btn btn-icon btn-3 mt-2 btn-add_to_cart" href="{{ url('checkout') }}">
                                        <span class="btn-inner--icon"><i class="material-icons">local_atm</i></span>
                                        <span class="btn-inner--text sakura-font">Ir a pagar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </center>

            </div>
        @break

        @default
            <div class="product_data container mb-3 mt-4">
                <div class="breadcrumb-nav bc3x">
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a>
                        </li>
                        <li class="bread-standard"><a href="{{ url('category/') }}"><i
                                    class="fas fa-box me-1"></i>Categorías</a>
                        </li>
                        <li class="bread-standard"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a>
                        </li>
                    @else
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-standard"><a href="#"><i class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a>
                        </li>
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
                                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
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
                                                            <input min="1"
                                                                max="{{ $item->stock > 0 ? $item->stock : '' }}"
                                                                data-cart-id="{{ $item->cart_id }}"
                                                                value="{{ $item->quantity }}" type="number" name="quantity"
                                                                id="quantity{{ $item->quantity }}"
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
                                            <span><strong
                                                    id="totalPriceElement">₡{{ number_format($total_price) }}</strong></span>
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
            </div>
        @break
    @endswitch
    @include('layouts.inc.indexfooter')
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
                        Swal.fire(response.status);
                    }
                });
            });
            $('.btnQuantity').click(function(e) {
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
                        'cart_id': itemId,
                        'quantity': quantity,
                    },
                    success: function(response) {
                        calcularTotal();
                    }
                });
            });
        });

        $('.btnDeleteCart').click(function(e) {
            e.preventDefault();

            var itemId = $(this).data('item-id');
            // Confirmar la eliminación
            var confirmDelete = confirm('¿Deseas borrar este artículo?');

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
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores si es necesario
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        function calcularTotal() {
            let total = 0;
            let total_cloth = 0;
            let iva = parseFloat(document.getElementById("iva_tenant").value);
            let total_iva = 0;
            let you_save = 0;
            // Obtener todas las filas de la tabla
            const filas = document.querySelectorAll('#cartTable tbody tr');

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
