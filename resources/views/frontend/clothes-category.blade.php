@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @switch($tenantinfo->tenant)
        @case('sakura318')
            <div class="container mt-4">
                <div class="breadcrumb-nav-sk">
                    <li class="home-sk">
                        <a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i> Inicio</a>
                    </li>
                
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="bread-sk">
                            <a href="{{ url('category/') }}"><i class="fas fa-{{ $icon->categories }} me-1"></i> Categorías</a>
                        </li>
                        <li class="bread-sk">
                            <a href="#"><i class="fas fa-{{ $icon->services }} me-1"></i> {{ $category_name }}</a>
                        </li>
                    @else
                        <li class="bread-sk">
                            <a href="{{ url('departments/index') }}"><i class="fas fa-shapes me-1"></i> Departamentos</a>
                        </li>
                        <li class="bread-sk">
                            <a href="{{ url('category/' . $department_id) }}"><i class="fas fa-{{ $icon->categories }} me-1"></i> {{ $department_name }}</a>
                        </li>
                        <li class="bread-sk">
                            <a href="#"><i class="fas fa-{{ $icon->services }} me-1"></i> {{ $category_name }}</a>
                        </li>
                    @endif
                </div>                
                <div class="row w-75">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label class="sakura-color">Filtrar</label>
                            <input value="" placeholder="Escribe para filtrar...." type="text"
                                class="form-control form-control-lg sakura-color" name="searchfor" id="searchfor">
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-2 mb-5">
                    @foreach ($clothings as $item)
                        <div class="col-md-3 col-sm-6 mb-2 card-container">
                            <input type="hidden" class="code" name="code" value="{{ $item->code }}">
                            <input type="hidden" class="clothing-name" name="clothing-name" value="{{ $item->name }}">
                            <div class="product-grid product_data">
                                <div class="product-image">
                                    <img 
                                        src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    @if ($item->discount)
                                        <span class="product-discount-label sakura-color sakura-font">-{{ $item->discount }}%</span>
                                    @endif

                                    <ul class="product-links">
                                        <li><a target="blank"
                                                href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                                    class="fas fa-eye"></i></a></li>
                                        @if (Auth::check())
                                            <li>
                                                <a class="add_favorite" data-clothing-id="{{ $item->id }}" href="#">
                                                    <i
                                                        class="fas fa-heart {{ $clothing_favs->contains('clothing_id', $item->id) ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                    <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                        class="add-to-cart sakura-font">Detallar</a>
                                </div>
                                <div class="product-content-sk">
                                    <h3
                                        class="text-muted text-uppercase sakura-color sakura-font {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                        {{ $item->casa }}
                                    </h3>
                                    <h3
                                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'text-muted' }}">
                                        <a class="sakura-color sakura-font" href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}">{{ $item->name }}
                                            @if ($item->total_stock == 0)
                                                <s class="text-danger"> Agotado</s>
                                            @endif
                                        </a>
                                    </h3>

                                    @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                        <h4 class="title sakura-color sakura-font">
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
                                    <div class="sakura-color sakura-font">₡{{ number_format($precioConDescuento) }}
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
            <center>
                <div class="container mt-3 mb-5">
                    {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
                </div>
            </center>
        @break

        @default
            <div class="container mt-4">
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
                            <input type="hidden" class="clothing-name" name="clothing-name" value="{{ $item->name }}">
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
                                        @if (Auth::check())
                                            <li>
                                                <a class="add_favorite" data-clothing-id="{{ $item->id }}" href="#">
                                                    <i
                                                        class="fas fa-heart {{ $clothing_favs->contains('clothing_id', $item->id) ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </li>
                                        @endif
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
                                        class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'text-muted' }}">
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
            </div>
        @break
    @endswitch

    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#searchfor').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.card-container').each(function() {
                    var name = $(this).find('.clothing-name').val().toLowerCase();
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
