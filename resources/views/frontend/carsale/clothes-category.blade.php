@extends('layouts.frontrent')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('category/') }}"><i
                            class="fas fa-{{ $icon->categories }} me-1"></i>Categorías</a>
                </li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->services }} me-1"></i>{{ $category_name }}</a>
                </li>
            @else
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                </li>
                <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                            class="fas fa-shapes me-1"></i>Departamentos</a></li>
                <li class="bread-standard"><a href="{{ url('category/' . $department_id) }}"><i
                            class="fas fa-{{ $icon->categories }} me-1"></i>{{ $department_name }}</a>
                </li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->services }} me-1"></i>{{ $category_name }}</a>
                </li>
            @endif

        </div>
        <div class="row w-75">
            <div class="col-md-6">
                <div class="input-group input-group-lg input-group-static my-3 w-100">
                    <label>Filtrar</label>
                    <input value="" placeholder="Escribe para filtrar...." type="text"
                        class="form-control form-control-lg" name="searchfor" id="searchfor">
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-2 mb-5">
            @foreach ($clothings as $item)
                <div class="col-md-3 col-sm-6 mb-2 card-container">
                    <input type="hidden" class="code" name="code" value="{{ $item->code }}">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img
                                src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                            @if ($item->discount)
                                <span class="product-discount-label">-{{ $item->discount }}%</span>
                            @endif

                            <ul class="product-links">
                                <li><a target="blank"
                                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                class="add-to-cart">Detallar</a>
                        </div>
                        <div class="product-content">
                            <h3
                                class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                {{ $item->casa }}
                            </h3>
                            <h3
                                class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'title-frags' }}">
                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}">{{ $item->name }}
                                    @if ($item->total_stock == 0)
                                        <s class="text-danger"> Agotado</s>
                                    @endif
                                </a>
                            </h3>

                            @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                <h4 class="title">
                                    Stock:
                                    @if ($item->total_stock > 0)
                                        {{ $item->total_stock }}
                                    @elseif ($item->total_stock == 0)
                                        <s class="text-danger">0</s>
                                    @else
                                        <span class="text-info">Sin manejo de stock</span>
                                    @endif
                                </h4>
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
                            <div class="price">₡{{ number_format($precioConDescuento) }}
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
        <center>
            <div class="container mb-5">
                {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div> --}}
    <div class="hero-wrap ftco-degree-bg"
        style="background-image: url('{{url('images/car-12.jpg') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">Vehículos {{$category_name}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                    <h2 class="mb-2">Detalla los vehículos de esta categoría</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="carousel-car owl-carousel">
                        @foreach ($clothings as $item)
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
                                <div class="car-wrap rounded ftco-animate">
                                    <div class="img rounded d-flex align-items-end"
                                        style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');">
                                    </div>
                                    <div class="text">
                                        <h2 class="mb-0"><a href="#">{{ $item->name }}</a></h2>
                                        <div class="d-flex mb-3">
                                            <span class="cat">Tendencia</span>
                                            <!-- <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p> -->
                                        </div>
                                        <p class="d-flex mb-0 d-block"><a
                                                href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                                class="btn btn-secondary py-2 ml-1">Detallar</a></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.inc.carsale.footer')
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#searchfor').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.card-container').each(function() {
                    var name = $(this).find('.clothing-name').text().toLowerCase();
                    var code = $(this).find('.code').val().toLowerCase();
                    if (name.includes(searchTerm) || code.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

        });
    </script>
@endsection
