@extends('layouts.frontrent')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- Main Banner --}}
    @foreach ($clothes as $item)
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
        <div class="hero-wrap ftco-degree-bg"
            style="background-image: url('{{ isset($item->main_image) && $item->main_image != '' ? route('file', $item->main_image) : url('images/producto-sin-imagen.PNG') }}');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                    <div class="col-lg-8 ftco-animate">
                        <div class="text w-100 text-center mb-md-5 pb-md-5">
                            <h1 class="mb-2">
                                {{ $item->name }}</h1>
                            <p style="font-size: 36px;">
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12 pills">
                    <div class="bd-example bd-example-tabs">
                        <div class="d-flex justify-content-center">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-manufacturer-tab" data-toggle="pill"
                                        href="#pills-manufacturer" role="tab" aria-controls="pills-manufacturer"
                                        aria-expanded="true">Características</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-details-tab" data-toggle="pill" href="#pills-details"
                                        role="tab" aria-controls="pills-details"
                                        aria-expanded="true">Especificaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-review-tab" data-toggle="pill" href="#pills-review"
                                        role="tab" aria-controls="pills-review" aria-expanded="true">Detalle</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-manufacturer" role="tabpanel"
                                aria-labelledby="pills-manufacturer-tab">
                                <p>
                                    {!! $item->description !!}
                                </p>

                                <button type="button"
                                    class="btn btn-secondary py-2 ml-1 whatsapp-button-click"> <i class="me-1 fa fa-user"></i>
                                    @if ($item->total_stock > 0)
                                        Contactar al vendedor
                                    @else
                                        Vendido!
                                    @endif
                                </button>
                            </div>
                            <div class="tab-pane fade" id="pills-details" role="tabpanel"
                                aria-labelledby="pills-details-tab">
                                <h4 class="text-muted text-center text-uppercase">
                                    Especificaciones
                                </h4>
                                <h4 class="title text-center text-dark">
                                    {{ $item->name }}
                                </h4>
                                <div class="col-md-12 d-flex align-items-center text-center">
                                    <div class="services-wrap rounded-right w-100">
                                        <div class="row d-flex mb-4">
                                            
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center"><span
                                                            class="flaticon-diesel"></span></div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Tipo combustible:
                                                            {{ $details->combustible != '' ? $details->combustible : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center"><span
                                                            class="flaticon-car-seat"></span></div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Pasajeros:
                                                            {{ $details->pasajeros != '' ? $details->pasajeros : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center"><span
                                                            class="flaticon-dashboard"></span></div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Puertas:
                                                            {{ $details->potencia != '' ? $details->potencia : '--' }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="flaticon-pistons"></span>
                                                    </div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Motor:
                                                            {{ $details->motor != '' ? $details->motor . 'CC' : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="flaticon-transportation"></span>
                                                    </div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Kilometraje:
                                                            {{ $details->kilometraje != '' ? number_format($details->kilometraje) . ' MI' : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="flaticon-car"></span>
                                                    </div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Modelo o año:
                                                            {{ $details->modelo != '' ? $details->modelo : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="flaticon-pistons"></span>
                                                    </div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Transmisión:
                                                            {{ $details->transmision != '' ? $details->transmision : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                                <div class="services w-100 text-center">
                                                    <div class="icon d-flex align-items-center justify-content-center">
                                                        <span class="flaticon-suv"></span>
                                                    </div>
                                                    <div class="text w-100">
                                                        <h3 class="heading mb-2">Tracción:
                                                            {{ $details->traccion != '' ? $details->traccion : '--' }}
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-review" role="tabpanel"
                                aria-labelledby="pills-review-tab">
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
                                                                        <a data-fslightbox="mygallery" class="rounded-4"
                                                                            target="_blank" data-type="image"
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
                                                                        <a data-fslightbox="mygallery" class="rounded-4"
                                                                            target="_blank" data-type="image"
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
                                                                    <div
                                                                        class="rounded-4 mb-3 d-flex justify-content-center">
                                                                        <a data-fslightbox="mygallery" class="rounded-4"
                                                                            target="_blank" data-type="image"
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
                                                </div>
                                            </main>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break
@endforeach
@if (count($clothings_trending) != 0)
    <section class="ftco-section ftco-no-pt bg-light mt-5">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center ftco-animate mt-5 mb-5">
                    <h2 class="title align-text-center">Otros vehículos que pueden interesarte</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="carousel-car owl-carousel">
                        @foreach ($clothings_trending as $item)
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
                            <div class="item">
                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">
                                    <div class="car-wrap rounded ftco-animate">
                                        <div class="img rounded d-flex align-items-end"
                                            style="background-image: url('{{ isset($item->main_image) ? route('file', $item->main_image) : url('images/producto-sin-imagen.PNG') }}');">
                                        </div>
                                        <div class="text">
                                            <h2 class="mb-0"><a href="#">{{ $item->name }}</a></h2>
                                            <div class="d-flex mb-3">

                                                <!-- <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p> -->
                                            </div>
                                            <span class="line"><span>Tendencia</span></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
{{--     <div class="container">
        <div>
            @foreach ($clothes as $item)
                <input type="hidden" name="porcDescuento" value="{{ $item->discount }}" id="porcDescuento">
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
    </div> --}}
</div>
@include('layouts.inc.carsale.footer')
@endsection
@section('scripts')
<script>
    $('.featured-carousel').owlCarousel({
        loop: true,
        margin: 10,

        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
</script>
@endsection
