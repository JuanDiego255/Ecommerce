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
                    <li class="bread-sk">
                        <a href="#"><i class="fas fa-shapes me-1"></i> Departamentos</a>
                    </li>
                </div>
                            
                <div class="mt-3 mb-5">

                    <div>
                        <h1 class="mt-5 mb-5 sakura-20 sakura-color">
                            No te pierdas la oportunidad de encontrar tú producto favorito explorando en nuestros departamentos
                        </h1>
                    </div>
                    <div class="row">
                        <div class="owl-carousel featured-carousel-circle owl-theme">
                            @foreach ($departments as $item)
                                <a href="{{ url('category/' . $item->id) }}">
                                    <div class="item">
                                        <div class="product-grid product_data">
                                            <div class="product-circle">
                                                <p class="category-name-circle">{{ $item->department }}</p>                                               
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @break

        @default
            <div class="container mt-4">
                <div class="breadcrumb-nav bc3x">
                    <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                    <li class="bread-standard"><a href="#"><i class="fas fa-shapes me-1"></i>Departamentos</a></li>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
                    @foreach ($departments as $item)
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="product-grid product_data">
                                <div class="product-image">
                                    <img
                                        src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    <ul class="product-links">
                                        <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                    class="fas fa-eye"></i></a></li>
                                    </ul>
                                    <a href="{{ url('category/' . $item->id) }}" class="add-to-cart">Categorías</a>
                                </div>
                                <div class="product-content">
                                    <h3 class="title"><a href="{{ url('category/' . $item->id) }}">{{ $item->department }}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <center>
                    <div class="container">
                        {{ $departments ?? ('')->links('pagination::simple-bootstrap-4') }}
                    </div>
                </center>
            </div>
        @break
    @endswitch
    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
