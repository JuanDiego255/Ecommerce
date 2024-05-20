@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div>
            @foreach ($clothes as $item)
                <input type="hidden" name="porcDescuento" value="{{ $item->discount }}" id="porcDescuento">
                <div class="breadcrumb-nav bc3x mt-4">
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-standard"><a href="{{ url('category/') }}"><i
                                    class="fas fa-box me-1"></i>Categorías</a>
                        </li>
                        <li class="bread-standard"><a
                                href="{{ url('clothes-category/' . $category_id . '/' . $item->department_id) }}"><i
                                    class="fas fa-tshirt me-1"></i>{{ $item->category }}</a></li>
                        <li class="bread-standard"><a class="location" href="#"><i
                                    class="fas fa-socks me-1"></i>Detalles</a>
                        </li>
                    @else
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-standard"><a href="{{ url('category/' . $item->department_id) }}"><i
                                    class="fas fa-box me-1"></i>{{ $item->department_name }}</a></li>
                        <li class="bread-standard"><a
                                href="{{ url('clothes-category/' . $category_id . '/' . $item->department_id) }}"><i
                                    class="fas fa-tshirt me-1"></i>{{ $item->category }}</a></li>
                        <li class="bread-standard"><a class="location" href="#"><i
                                    class="fas fa-socks me-1"></i>Detalles</a>
                        </li>
                    @endif


                </div>

                @php
                    $sizes = explode(',', $item->available_sizes);
                    $stockPerSize = explode(',', $item->stock_per_size);
                    $pricePerSize = explode(',', $item->price_per_size);
                @endphp
                <section class="pt-4">
                    <div class="container product_data">
                        <div class="row gx-5">
                            <aside class="col-lg-6">
                                <div class="outer">


                                    <!-- Carrusel big -->
                                    <div id="big" class="owl-carousel owl-theme">
                                        @foreach ($clothes as $clothing)
                                            @if (!empty($clothing->images))
                                                @php
                                                    $images = explode(',', $clothing->images);
                                                    // Convertir la lista de imágenes en un array
                                                    $firstImage = reset($images); // Obtener la primera imagen
                                                @endphp
                                                <div class="item">
                                                    <div class="rounded-4 mb-3 d-flex">
                                                        <a data-fslightbox="mygallery" class="rounded-4" target="_blank"
                                                            data-type="image"
                                                            href="{{ tenant_asset('/') . '/' . $firstImage }}">
                                                            <img style="max-width: 100%; max-height: 100vh; margin: auto;"
                                                                class="rounded-4 fit"
                                                                src="{{ tenant_asset('/') . '/' . $firstImage }}" />
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                </div>

                                <!-- thumbs-wrap.// -->
                                <!-- gallery-wrap .end// -->
                            </aside>

                            <main class="col-lg-6">
                                <div class="ps-lg-3">
                                    <div id="thumbs" class="owl-carousel owl-theme mb-2">
                                        @foreach ($clothes as $clothing)
                                            @php
                                                $images = explode(',', $clothing->images); // Convertir la lista de imágenes en un array
                                                $uniqueImages = array_unique($images); // Obtener imágenes únicas
                                            @endphp
                                            @foreach ($uniqueImages as $image)
                                                <div class="item">
                                                    <div class="rounded-4 mb-3 d-flex justify-content-center">
                                                        <a data-fslightbox="mygallery" class="rounded-4" target="_blank"
                                                            data-type="image" href="{{ route('file', $image) }}">
                                                            <img style="max-width: 100%; max-height: 100vh; margin: auto;"
                                                                class="rounded-4 fit" src="{{ route('file', $image) }}" />
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <hr class="dark horizontal text-danger mb-3">
                                    <h4
                                        class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                        {{ $item->casa }}
                                    </h4>
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
                                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'mandicr')
                                            <span class="text-muted-normal"><i
                                                    class="fas fa-shopping-basket fa-sm mx-1"></i>{{ $item->total_stock }}
                                                {{ $item->total_stock > 1 ? 'órdenes' : 'orden' }}</span>
                                        @endif
                                        <input type="hidden" name="custom_size" id="custom_size"
                                            value="{{ $tenantinfo->custom_size }}">

                                        @if ($item->total_stock > 0)
                                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr')
                                                <span class="text-success"><i
                                                        class="fas fa-shopping-basket fa-sm mx-1"></i>In Stock</span>
                                            @else
                                                <span class="text-success ms-2">In stock</span>
                                            @endif
                                        @else
                                            <s class="text-danger"><span class="text-danger ms-2">Agotado</span></s>
                                        @endif

                                    </div>

                                    <div class="mb-1">

                                        @php
                                            $precio = $item->price;
                                            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
                                                $precio = $item->first_price;
                                            }
                                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                                $precio = $item->mayor_price;
                                            }
                                            $descuentoPorcentaje = $item->discount;
                                            // Calcular el descuento
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            // Calcular el precio con el descuento aplicado
                                            $precioConDescuento = $precio - $descuento;
                                        @endphp

                                        <div class="price"><strong
                                                id="text_price">₡{{ number_format($precioConDescuento) }}</strong>
                                            @if ($item->discount)
                                                <s class="text-danger"><span class="text-danger"><strong
                                                            id="text_price_discount">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}</strong>
                                                    </span></s>
                                                / por unidad
                                            @endif
                                        </div>

                                    </div>

                                    <p>
                                        {!! $item->description !!}
                                    </p>

                                    <div class="row mb-3">
                                        <div class="col-md-6 col-12">
                                            <div class="input-group input-group-static w-25">
                                                <label>Cantidad</label>
                                                <input min="1" max="{{ $item->stock }}" id="quantityInput"
                                                    value="1" type="number" name="quantity"
                                                    class="form-control float-left w-100 quantity">
                                            </div>
                                        </div>
                                        <div
                                            class="col-md-12 col-12 {{ isset($tenantinfo->tenant) && $tenantinfo->manage_size == 0 ? 'd-none' : '' }}">
                                            <label
                                                class="">{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'Tallas' : 'Tamaños' }}</label><br>
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
                @break

            </section>
        @endforeach
    </div>



    <div class="text-center">
        <h3 class="text-center text-muted-title mt-5">Potencia tu outfit con estas opciones</h3>
    </div>
    <hr class="dark horizontal text-danger mb-3">
    <div class="row mt-4">
        @foreach ($clothings_trending as $item)
            <input type="hidden" class="cloth_id" value="{{ $item->id }}">
            <input type="hidden" class="quantity" value="1">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="product-grid product_data">
                    <div class="product-image">
                        <img src="{{ route('file', $item->image) }}">
                        <ul class="product-links">
                            <li><a target="blank" href="{{ route('file', $item->image) }}"><i
                                        class="fas fa-eye"></i></a></li>
                        </ul>
                        <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                            class="add-to-cart">Detallar</a>
                    </div>
                    <div class="product-content">
                        <h3 class="title"><a href="#">{{ $item->name }}</a></h3>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'mandicr')
                            <h4 class="title"><a href="#">Stock: {{ $item->total_stock }}</a></h4>
                        @endif
                        @php
                            $precio = $item->price;
                            if (
                                isset($tenantinfo->custom_size) &&
                                $tenantinfo->custom_size == 1 &&
                                $item->first_price > 0
                            ) {
                                $precio = $item->first_price;
                            }
                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                $precio = $item->mayor_price;
                            }
                            $descuentoPorcentaje = $item->discount;
                            // Calcular el descuento
                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                            // Calcular el precio con el descuento aplicado
                            $precioConDescuento = $precio - $descuento;
                        @endphp
                        <div class="price">
                            ₡{{ number_format($precioConDescuento) }}
                            @if ($item->discount)
                                <s class="text-danger"><span
                                        class="text-danger">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                    </span></s>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@include('layouts.inc.indexfooter')
@endsection
@section('scripts')
<script src="{{ asset('js/image-error-handler.js') }}"></script>
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
                    $('.cartIcon').text(' ' + newCartNumber);

                    getCart();                    
                }
            });            
        });

        var size_id = $('input[name="size_id"]:checked').val();
        var stockPerSize = <?php echo json_encode($stockPerSize); ?>;
        var pricePerSize = <?php echo json_encode($pricePerSize); ?>;
        const sizes = {!! json_encode($sizes) !!};
        var index = sizes.indexOf(size_id.toString());
        var maxStock = stockPerSize[index];
        $('input[name="quantity"]').attr('max', maxStock);
        custom_size = document.getElementById("custom_size").value
        $('input[name="size_id"]').on('change', function() {
            // Obtener el ID de la talla seleccionada
            if (custom_size == 1) {
                var selectedSizeId = $(this).val();

                // Buscar el índice correspondiente al ID de la talla seleccionada
                index = sizes.indexOf(selectedSizeId.toString());

                // Verificar si se encontró el índice y actualizar el valor máximo del input quantity
                if (index !== -1) {

                    maxStock = stockPerSize[index];

                    // Actualizar el atributo 'max' del input quantity
                    $('input[name="quantity"]').attr('max', maxStock);
                    $('input[name="quantity"]').val(1);                    
                    porcDescuento = document.getElementById("porcDescuento").value


                    perPrice = pricePerSize[index];
                    const price = document.getElementById('text_price');
                    const price_discount = document.getElementById('text_price_discount');
                    if (porcDescuento > 0) {
                        var descuento = (perPrice * porcDescuento) / 100;
                        var precioConDescuento = perPrice - descuento;
                        price.textContent = `₡${precioConDescuento.toLocaleString()}`;
                        price_discount.textContent = `₡${perPrice.toLocaleString()}`;
                    } else {
                        price.textContent = `₡${perPrice.toLocaleString()}`;
                    }


                }
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
