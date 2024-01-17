@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">

            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-box me-1"></i>Categorías</a></li>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($category as $item)
              
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{tenant_asset('/') . '/'. $item->image}}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{tenant_asset('/') . '/'. $item->image}}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('clothes-category/' . $item->id) }}"
                                class="add-to-cart">Descubrir estilos</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a href="{{ url('clothes-category/' . $item->id) }}">{{ $item->name }}</a></h3>                          
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>
            <div class="container">
                {{ $category ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div>
    @include('layouts.inc.indexfooter')
@endsection
