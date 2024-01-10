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
                                <img src="{{ asset('storage') . '/' . $item->image }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ asset('storage') . '/' . $item->image }}"><i
                                                class="fas fa-eye"></i></a></li>
                                </ul>
                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}"
                                    class="add-to-cart">Detallar</a>
                            </div>
                            <div class="product-content">
                                <h3 class="title"><a href="{{ url('detail-clothing/' . $item->id . '/' . $category_id) }}">{{ $item->name }}</a></h3>
                                <h4 class="title">Stock: {{ $item->total_stock }}</h4>
                                <div class="price">₡{{ number_format($item->price) }}</span></div>
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
