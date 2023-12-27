@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    
    <div class="product_data mb-3" style="margin-left: 100px; margin-right:80px;">
        <div class="alert alert-secondary alert-dismissible text-white fade show mt-4" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    waving_hand
                </span>
            </span>
            <span class="alert-text"><strong>Hola, <a class="text-white" href="{{url('category')}}">¿Desea realizar más compras?</a></strong></span>
        </div>
        <center>
            <div class=" ml-5 row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-5">
                <div class="col ml-5 bg-transparent">
                    <div class="card ml-5 w-100">

                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="cartTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Imagen</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                        </th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">
                                            Precio</th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">
                                            Talla</th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">
                                            Cant</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart_items as $item)
                                        <tr>
                                            <input type="hidden" name="prod_id" value="{{ $item->id }}"
                                                class="prod_id">

                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <a target="blank" data-fancybox="gallery"
                                                            href="{{ asset('storage') . '/' . $item->image }}">
                                                            <img src="{{ asset('storage') . '/' . $item->image }}"
                                                                class="img-fluid shadow border-radius-lg">
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-dark font-weight-bold mb-0">{{ $item->name }}</p>

                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-success mb-0">₡ {{ number_format($item->price) }}</p>
                                                <input type="hidden" class="price" value="{{ $item->price }}">
                                            </td>
                                            <td>
                                                <p class="text-dark text-center font-weight-bold mb-0">{{ $item->size }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <div class="input-group text-center input-group-static mb-4 w-100">

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
                <div class="col bg-transparent">
                    <div class="card card-frame w-100">
                        <div class="card-body">
                            <h5 class="font-weight-normal">
                                Total a Pagar

                            </h5>
                            <p class="mb-0">
                                Valor Absoluto + I.V.A
                            </p>
                            <a class="btn btn-icon btn-3 mt-4 btn-info" href="{{ url('checkout') }}">
                                <span class="btn-inner--icon"><i class="material-icons">local_atm</i></span>
                                <span class="btn-inner--text">Ir a pagar</span>
                            </a>

                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer d-flex">

                            <p class="font-weight-normal text-success my-auto" id="totalIvaElement">I.V.A:
                                ₡{{ number_format($iva) }}</p>
                            <i class="material-icons text-success position-relative ms-auto text-lg me-1 my-auto">paid</i>
                            <p class="font-weight-normal text-success my-auto" id="totalPriceElement">Total:
                                ₡{{ number_format($total_price) }}</p>

                            </p>
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
            total = total + total_iva;

            // Mostrar el total actualizado en el elemento correspondiente
            const totalElement = document.getElementById('totalPriceElement');
            const totalIvaElement = document.getElementById('totalIvaElement');
            totalElement.textContent = `Total: ₡${total.toLocaleString()}`;
            totalIvaElement.textContent = `Total: ₡${total_iva.toLocaleString()}`;
        }
    </script>
@endsection
