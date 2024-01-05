@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">


        @foreach ($clothes as $item)
            <div class="breadcrumb-nav bc3x mt-4">

                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('category/') }}"><i class="fas fa-box me-1"></i>Categorías</a></li>
                <li class="bread-standard"><a href="{{ url('clothes-category/' . $category_id) }}"><i
                            class="fas fa-tshirt me-1"></i>{{ $item->category }}</a></li>
                <li class="bread-standard"><a class="location" href="#"><i class="fas fa-socks me-1"></i>Detalles</a>
                </li>
            </div>
            @php
                $sizes = explode(',', $item->available_sizes);
                $stockPerSize = explode(',', $item->stock_per_size);
            @endphp
            <section class="pt-4">
                <div class="container product_data">
                    <div class="row gx-5">
                        <aside class="col-lg-6">
                            <div class="rounded-4 mb-3 d-flex justify-content-center">
                                <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image"
                                    href="{{ asset('storage') . '/' . $item->image }}">
                                    <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit"
                                        src="{{ asset('storage') . '/' . $item->image }}" />
                                </a>
                            </div>
                            <!-- thumbs-wrap.// -->
                            <!-- gallery-wrap .end// -->
                        </aside>
                        <main class="col-lg-6">
                            <div class="ps-lg-3">
                                <h4 class="title text-dark">
                                    {{ $item->name }}
                                </h4>
                                <div class="d-flex flex-row my-3">
                                    @if ($item->trending == 1)
                                        <div class="text-warning mb-1 me-2">

                                            <i
                                                class="material-icons text-danger position-relative ms-auto text-lg me-1 my-auto">trending_up</i>

                                            <span class="text-danger my-auto">Tendencia</span>

                                        </div>
                                    @endif
                                    <span class="text-muted-normal"><i
                                            class="fas fa-shopping-basket fa-sm mx-1"></i>{{ $item->total_stock }}
                                        {{ $item->total_stock > 1 ? 'órdenes' : 'orden' }}</span>
                                    @if ($item->total_stock > 0)
                                        <span class="text-success ms-2">In stock</span>
                                    @else
                                        <span class="text-success ms-2">Not stock</span>
                                    @endif

                                </div>

                                <div class="mb-1">
                                    <span class="text-muted"> ₡{{ number_format($item->price) }}</span>
                                    <span class="text-muted">/ por unidad</span>
                                </div>

                                <p>
                                    {{ $item->description }}
                                </p>

                                {{-- <div class="row">
                                    <dt class="col-3">Type:</dt>
                                    <dd class="col-9">Regular</dd>

                                    <dt class="col-3">Color</dt>
                                    <dd class="col-9">Brown</dd>

                                    <dt class="col-3">Material</dt>
                                    <dd class="col-9">Cotton, Jeans</dd>

                                    <dt class="col-3">Brand</dt>
                                    <dd class="col-9">Reebook</dd>
                                </div> --}}

                                <div class="row mb-3">
                                    <div class="col-md-6 col-12">
                                        <div class="input-group input-group-static w-25">
                                            <label>Cantidad</label>
                                            <input min="1" max="{{ $item->stock }}" id="quantityInput"
                                                value="1" type="number" name="quantity"
                                                class="form-control float-left w-100 quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <label class="">Tallas</label><br>
                                        @foreach ($size_active as $key => $size)
                                            <div class="form-check form-check-inline">
                                                <input required name="size_id" class="size_id form-check-input mb-2"
                                                    type="radio" value="{{ $size->id }}"
                                                    id="size_{{ $size->id }}" {{ $key === 0 ? 'checked' : '' }}>
                                                <label class="form-check-label table-text text-dark mb-2"
                                                    for="size_{{ $size->id }}">
                                                    {{ $size->size }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- col.// -->
                                    <input type="hidden" class="cloth_id" value="{{ $item->id }}">


                                </div>

                                <button @if ($item->total_stock > 0) @else disabled @endif
                                    class="btn btn-add_to_cart shadow-0 btnAddToCart"> <i
                                        class="me-1 fa fa-shopping-basket"></i>
                                    @if ($item->total_stock > 0)
                                        Agregar Al Carrito
                                    @else
                                        Vendido!
                                    @endif
                                </button>
                            </div>
                        </main>
                    </div>
                </div>
            </section>
        @endforeach

        <div class="text-center">
            <h3 class="text-center text-dark mt-5">Potencia tu outfit con estas opciones</h3>
        </div>
        <hr class="dark horizontal text-danger mb-3">
        <div class="row mt-4">
            @foreach ($clothings_trending as $item)
            <input type="hidden" class="cloth_id" value="{{ $item->id }}">
            <input type="hidden" class="quantity" value="1">
                <div class="col-md-3 col-sm-6">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{ asset('storage') . '/' . $item->image }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ asset('storage') . '/' . $item->image }}"><i class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                class="add-to-cart">Detallar</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a href="#">{{ $item->name }}</a></h3>
                            <h4 class="title"><a href="#">Stock: {{ $item->total_stock }}</a></h4>
                            <div class="price">₡{{ number_format($item->price) }}</span></div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    @include('layouts.inc.indexfooter')
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

            const quantityInput = document.getElementById('quantityInput');

            quantityInput.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                    return true;
                } else {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
