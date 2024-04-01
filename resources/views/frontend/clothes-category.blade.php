@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mt-4">

        <div class="breadcrumb-nav bc3x">

            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('category/') }}"><i class="fas fa-box me-1"></i>Categorías</a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-tshirt me-1"></i>{{ $category_name }}</a></li>
        </div>
        <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($clothings as $item)
                @if ($item->total_stock != 0)
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img src="{{ route('file',$item->image) }}">
                                @if ($item->discount)
                                    <span class="product-discount-label">-{{ $item->discount }}%</span>
                                @endif

                                <ul class="product-links">
                                    <li><a target="blank" href="{{ route('file',$item->image) }}"><i
                                                class="fas fa-eye"></i></a></li>
                                </ul>
                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                    class="add-to-cart">Detallar</a>
                            </div>
                            <div class="product-content">
                                <h3 class="title"><a
                                        href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}">{{ $item->name }}</a>
                                </h3>
                                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'mandicr')
                                    <h4 class="title">Stock: {{ $item->total_stock }}</h4>
                                @endif

                                @php
                                    $precio = $item->price;
                                    $descuentoPorcentaje = $item->discount;
                                    // Calcular el descuento
                                    $descuento = ($precio * $descuentoPorcentaje) / 100;
                                    // Calcular el precio con el descuento aplicado
                                    $precioConDescuento = $precio - $descuento;
                                @endphp
                                <div class="price">₡{{ number_format($precioConDescuento) }}
                                    @if ($item->discount)
                                        <s class="text-danger"><span class="text-danger">₡{{ number_format($item->price) }}
                                            </span></s>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
        <center>
            <div class="container">
                {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div>
    @include('layouts.inc.indexfooter')
@endsection
