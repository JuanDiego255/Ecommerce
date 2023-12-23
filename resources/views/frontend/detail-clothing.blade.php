@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="text-dark text-center mb-5">Detalles</h1>
    <div class="container">
        <center>
            @foreach ($clothes as $item)
                <div class="card w-75 product_data">
                    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-5">
                        <div class="col bg-transparent">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 pb-5 z-index-2">
                                <a class="d-block blur-shadow-image">
                                    <img src="{{ asset('storage') . '/' . $item->image }}" style="width: 600px"
                                        alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                </a>
                                <div class="colored-shadow"
                                    style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                                </div>
                            </div>
                        </div>
                        <div class="col bg-transparent">
                            <div class="card-body pb-4 pt-2">
                                <a href="javascript:;">
                                    <h3 class="font-weight-normal mt-3">
                                        {{ $item->name }}
                                    </h3>
                                </a>
                                <p>
                                    {{ $item->description }}
                                </p>
                                @php
                                    $sizes = explode(',', $item->available_sizes);
                                    $stockPerSize = explode(',', $item->stock_per_size);
                                @endphp

                                <input type="hidden" class="cloth_id" value="{{ $item->id }}">
                                <div class="input-group input-group-static mb-4">
                                    <label>Cantidad</label>
                                    <input min="1" max="{{ $item->stock }}" value="1" type="number"
                                        name="quantity" class="form-control float-left w-100 quantity">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label
                                        class="control-label control-label text-formulario {{ $errors->has('size_id') ? 'is-invalid' : '' }}"
                                        for="size_id">Tallas</label><br>
                                    @foreach ($size_active as $key => $size)
                                        <div class="form-check form-check-inline">
                                            <input required name="size_id" class="size_id form-check-input mb-2"
                                                type="radio" value="{{ $size->id }}" id="size_{{ $size->id }}"
                                                {{ $key === 0 ? 'checked' : '' }}>
                                            <label class="form-check-label table-text text-dark mb-2"
                                                for="size_{{ $size->id }}">
                                                {{ $size->size }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" @if ($item->total_stock > 0) @else disabled @endif
                                    class="btn btn-outline-info mt-3 mb-0 btnAddToCart">
                                    @if ($item->total_stock > 0)
                                        Agregar Al Carrito
                                    @else
                                        Vendido!
                                    @endif
                                </button>



                            </div>
                            <div class="card-footer d-flex">
                                <p class="font-weight-normal text-success text- my-auto">Precio:
                                    ₡{{ number_format($item->price) }}</p>
                                @if ($item->trending == 1)
                                    <i
                                        class="material-icons text-danger position-relative ms-auto text-lg me-1 my-auto">trending_up</i>
                                    <strong>
                                        <p class="text-danger my-auto">Tendencia</p>
                                    </strong>
                                @endif
                                @if ($item->total_stock > 0)
                                    <i
                                        class="material-icons text-info position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                                    <strong>
                                        <p class="text-info my-auto">In Stock</p>
                                    </strong>
                                @else
                                    <i
                                        class="material-icons text-info position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                                    <strong>
                                        <s>
                                            <p class="text-info my-auto">Not Stock</p>
                                        </s>
                                    </strong>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </center>

    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('clothes-category/' . $category_id) }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            $('.btnAddToCart').click(function(e) {
                e.preventDefault();
                var cloth_id = $(this).closest('.product_data').find('.cloth_id').val();
                var quantity = $(this).closest('.product_data').find('.quantity').val();
                var size_id = $('input[name="size_id"]:checked').val();

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
                        'size_id': size_id,
                    },
                    success: function(response) {
                        swal({
                            title: response.status,
                            icon: response.icon,
                        });
                        var newCartNumber = response.cartNumber
                        $('.badge').text(newCartNumber);

                    }
                });
            });

            var size_id = $('input[name="size_id"]:checked').val();
            var stockPerSize = <?php echo json_encode($stockPerSize); ?>;
            const sizes = {!! json_encode($sizes) !!};
            var index = sizes.indexOf(size_id.toString());
            var maxStock = stockPerSize[index];
            $('input[name="quantity"]').attr('max', maxStock);


            $('input[name="size_id"]').on('change', function() {
                // Obtener el ID de la talla seleccionada
                var selectedSizeId = $(this).val();

                // Buscar el índice correspondiente al ID de la talla seleccionada
                index = sizes.indexOf(selectedSizeId.toString());

                // Verificar si se encontró el índice y actualizar el valor máximo del input quantity
                if (index !== -1) {

                    maxStock = stockPerSize[index];
                    // Actualizar el atributo 'max' del input quantity
                    $('input[name="quantity"]').attr('max', maxStock);
                    $('input[name="quantity"]').val(1);
                }
            });
        });
    
    </script>
@endsection
