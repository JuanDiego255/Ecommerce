@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php   
    $title_service = 'Categorías';
    $btn = 'Descubrir Estilos';
    switch ($tenantinfo->kind_business) {
        case 1:           
            $btn = 'Ver Vehículos';
            break;
        case 2:            
            $title_service = 'Servicios';
            break;
        case 3:            
            $title_service = 'Servicios';
            $btn = 'tratamientos';
            break;
        default:
            break;
    }
@endphp
@section('content')
    <div class="container mt-4 mb-5">
        <div class="breadcrumb-nav bc3x">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('category/') }}"><i
                            class="fas fa-{{ $icon->categories }} me-1"></i>{{ $title_service }}</a>
                </li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->services }} me-1"></i>{{ $category_name }}</a>
                </li>
            @else
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
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

        <div class="row mt-5">
            <div class="col-md-8 mb-2">
                <h1 class="text-title">{{ $category->name }}</h1>
                <div class="text-justify">
                    <p class="text-single">{{ $category->description }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="product-grid product_data">
                    <div class="product-image">
                        <img src="{{ route('file', $category->image) }}">
                        <ul class="product-links">
                            <li><a target="blank" href="{{ route('file', $category->image) }}"><i
                                        class="fas fa-eye"></i></a></li>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
    @if (count($clothings) > 0)
        <div class="bg-white">
            <h1 class="text-title text-center pt-3">Lista de servicios</h1>
            <div class="container">
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
                                    <img src="{{ route('file', $item->image) }}">
                                    @if ($item->discount)
                                        <span class="product-discount-label">-{{ $item->discount }}%</span>
                                    @endif

                                    <ul class="product-links">
                                        <li><a target="blank" href="{{ route('file', $item->image) }}"><i
                                                    class="fas fa-eye"></i></a></li>
                                    </ul>
                                    <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                        class="add-to-cart">Detallar</a>
                                </div>
                                <div class="product-content">
                                    <h3 class="title clothing-name"><a
                                            href="{{ isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1 ? url('detail-clothing/' . $item->id . '/' . $category_id) : url('detail-car/' . $item->id . '/' . $category_id) }}">{{ $item->name }}
                                            @if ($item->can_buy == 1)
                                                <s class="text-danger">{{ $item->total_stock > 0 ? '' : 'Agotado' }}</s>
                                            @endif
                                        </a>
                                    </h3>
                                    @if ($item->can_buy == 1)
                                        @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                            <h4 class="title">Stock: @if ($item->total_stock > 0)
                                                    {{ $item->total_stock }}
                                                @else
                                                    <s class="text-danger">{{ $item->total_stock > 0 ? '' : '0' }}</s>
                                                @endif
                                            </h4>
                                        @endif
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
            </div>

        </div>
        <center>
            <div class="container">
                {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    @endif

    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
@section('scripts')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
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
