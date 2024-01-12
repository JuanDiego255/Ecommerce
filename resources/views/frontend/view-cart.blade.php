@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="product_data mb-3 mt-4" style="margin-left: 100px; margin-right:80px;">
        <div class="breadcrumb-nav bc3x">

            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('category/') }}"><i class="fas fa-box me-1"></i>Categorías</a></li>
            <li class="bread-standard"><a href="#"><i class="fa fa-shopping-cart me-1"></i>Carrito</a></li>
        </div>
        <center>
            <div class=" ml-5 row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-2">
                <div class="col-lg-8 ml-5 bg-transparent">
                    <div class="card ml-5 w-100">

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="cartTable">
                                <thead>
                                    <tr>
                                        <th class="text-secondary font-weight-bolder opacity-7">Imagen</th>
                                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                        </th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Precio</th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Talla</th>
                                        <th class="text-center text-secondary font-weight-bolder opacity-7">
                                            Cant</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart_items as $item)
                                        <tr>
                                            <input type="hidden" name="prod_id" value="{{ $item->id }}"
                                                class="prod_id">

                                            <td class="w-50">
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <a target="blank" data-fancybox="gallery"
                                                            href="{{ asset('storage') . '/' . $item->image }}">
                                                            <img src="{{ asset('storage') . '/' . $item->image }}"
                                                                class="img-fluid shadow border-radius-lg w-25">
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-justify text-truncate para mb-0">{{ $item->name }}</p>

                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @php
                                                    $precio = $item->price;
                                                    $descuentoPorcentaje = $item->discount;
                                                    // Calcular el descuento
                                                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                    // Calcular el precio con el descuento aplicado
                                                    $precioConDescuento = $precio - $descuento;
                                                @endphp
                                                <p class="text-success mb-0">₡
                                                    {{ $item->discount > 0 ? $precioConDescuento : $item->price }}
                                                    @if ($item->discount > 0)
                                                        <s class="text-danger">{{ $item->price }}</s>
                                                    @endif
                                                </p>
                                                <input type="hidden" class="price"
                                                    value="{{ $item->discount > 0 ? $precioConDescuento : $item->price }}">
                                            </td>
                                            <td>
                                                <p class="text-center text-truncate para mb-0">{{ $item->size }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <div class="input-group text-center input-group-static w-100">

                                                    <input min="1" max="{{ $item->stock }}"
                                                        value="{{ $item->quantity }}" type="number" name="quantity"
                                                        id="quantity{{ $item->quantity }}"
                                                        class="form-control btnQuantity text-center w-100 quantity">
                                                </div>
                                            </td>

                                            <td class="align-middle">
                                                <form name="delete-item-cart{{ $item->id }}"
                                                    id="delete-item-cart{{ $item->id }}" method="post"
                                                    action="{{ url('/delete-item-cart/' . $item->id . '/' . $item->size_id) }}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button onclick="return confirm('Deseas borrar este artículo?')"
                                                        type="submit" form="delete-item-cart{{ $item->id }}"
                                                        class="btn btn-icon btn-3 btn-danger">
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
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    I.V.A
                                    <span id="totalIvaElement">₡{{ number_format($iva) }}</span>
                                </li>
                                @if ($you_save > 0)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        Ahorraste
                                        <span id="totalIvaElement">₡{{ number_format($you_save) }}</span>
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

    </div>
    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            function updateQuantity() {

            };
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
                        swal(response.status);
                    }
                });
            });
            $('.btnQuantity').click(function(e) {
                e.preventDefault();

                var cloth_id = $(this).closest('tr').find('.prod_id').val();
                var quantity = $(this).val();
                var price = $(this).closest('tr').find('.price').val();

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
                    },
                    success: function(response) {
                        calcularTotal();
                    }
                });
            });
        });

        function calcularTotal() {
            let total = 0;
            let total_cloth = 0;
            let iva = 0.13;
            let total_iva = 0;
            // Obtener todas las filas de la tabla
            const filas = document.querySelectorAll('#cartTable tbody tr');

            filas.forEach((fila) => {
                const precio = parseFloat(fila.querySelector('.price').value);
                const cantidad = parseInt(fila.querySelector('.quantity').value);

                const subtotal = precio * cantidad;
                total += subtotal;
            });

            total_iva = total * iva;
            total_cloth = total;
            total = total + total_iva;

            // Mostrar el total actualizado en el elemento correspondiente
            const totalElement = document.getElementById('totalPriceElement');
            const totalIvaElement = document.getElementById('totalIvaElement');
            const totalCloth = document.getElementById('totalCloth');
            totalElement.textContent = `₡${total.toLocaleString()}`;
            totalIvaElement.textContent = `₡${total_iva.toLocaleString()}`;
            totalCloth.textContent = `₡${total_cloth.toLocaleString()}`;
        }
    </script>
@endsection
