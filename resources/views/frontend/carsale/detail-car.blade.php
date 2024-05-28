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
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a>
                        </li>
                        <li class="bread-standard"><a href="{{ url('category/') }}"><i
                                    class="fas fa-{{ $icon->categories }} me-1"></i>Categorías</a>
                        </li>
                        <li class="bread-standard"><a
                                href="{{ url('clothes-category/' . $category_id . '/' . $item->department_id) }}"><i
                                    class="fas fa-{{ $icon->services }} me-1"></i>{{ $item->category }}</a></li>
                        <li class="bread-standard"><a class="location" href="#"><i
                                    class="fas fa-{{ $icon->detail }} me-1"></i>Detalles</a>
                        </li>
                    @else
                        <li class="home"><a href="{{ url('/') }}"><i
                                    class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                        <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-standard"><a href="{{ url('category/' . $item->department_id) }}"><i
                                    class="fas fa-{{ $icon->categories }} me-1"></i>{{ $item->department_name }}</a></li>
                        <li class="bread-standard"><a
                                href="{{ url('clothes-category/' . $category_id . '/' . $item->department_id) }}"><i
                                    class="fas fa-{{ $icon->services }} me-1"></i>{{ $item->category }}</a></li>
                        <li class="bread-standard"><a class="location" href="#"><i
                                    class="fas fa-{{ $icon->detail }} me-1"></i>Detalles</a>
                        </li>
                    @endif


                </div>
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
                                                            href="{{ isset($firstImage) && $firstImage != '' ? route('file', $firstImage) : url('images/producto-sin-imagen.PNG') }}">
                                                            <img style="max-width: 100%; max-height: 100vh; margin: auto;"
                                                                class="rounded-4 fit"
                                                                src="{{ isset($firstImage) && $firstImage != '' ? route('file', $firstImage) : url('images/producto-sin-imagen.PNG') }}" />
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="item">
                                                    <div class="rounded-4 mb-3 d-flex">
                                                        <a data-fslightbox="mygallery" class="rounded-4" target="_blank"
                                                            data-type="image"
                                                            href="{{ url('images/producto-sin-imagen.PNG') }}">
                                                            <img style="max-width: 100%; max-height: 100vh; margin: auto;"
                                                                class="rounded-4 fit"
                                                                src="{{ url('images/producto-sin-imagen.PNG') }}" />
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
                                                            data-type="image"
                                                            href="{{ isset($image) && $image != '' ? route('file', $image) : url('images/producto-sin-imagen.PNG') }}">
                                                            <img style="max-width: 100%; max-height: 100vh; margin: auto;"
                                                                class="rounded-4 fit"
                                                                src="{{ isset($image) && $image != '' ? route('file', $image) : url('images/producto-sin-imagen.PNG') }}" />
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


                                        @if ($item->total_stock > 0)
                                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'mandicr')
                                                <span class="text-success"><i
                                                        class="fas fa-shopping-basket fa-sm mx-1"></i>In Stock</span>
                                            @else
                                                <span class="text-success ms-2">Disponible</span>
                                            @endif
                                        @else
                                            <span class="text-success ms-2">Vendido</span>
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

                                    <a target="blank" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                                        class="btn btn-add_to_cart shadow-0"> <i class="me-1 fa fa-user"></i>
                                        @if ($item->total_stock > 0)
                                            Contactar al vendedor
                                        @else
                                            Vendido!
                                        @endif
                                    </a>
                                </div>
                            </main>
                        </div>
                    </div>
                @break

            </section>
        @endforeach
    </div>

    <div class="text-center">
        <h3 class="text-center text-muted-title mt-5">Descubre más autos!</h3>
    </div>
    <hr class="dark horizontal text-danger mb-3">
    <div class="row mt-4">
        @foreach ($clothings_trending as $item)
            <input type="hidden" class="cloth_id" value="{{ $item->id }}">
            <input type="hidden" class="quantity" value="1">
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="product-grid product_data">
                    <div class="product-image">
                        <img
                            src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                        <ul class="product-links">
                            <li><a target="blank"
                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                        class="fas fa-eye"></i></a></li>
                        </ul>
                        <a href="{{ url('detail-car/' . $item->id . '/' . $item->category_id) }}"
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
@endsection
