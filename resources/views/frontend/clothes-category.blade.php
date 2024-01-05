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
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($clothings as $clothing)
                @if ($clothing->total_stock != 0)
                    <div class="col bg-transparent">
                        <div class="card" data-animation="false">

                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <a target="blank" data-fancybox="gallery" href="{{ asset('storage') . '/' . $clothing->image }}" class="d-block blur-shadow-image">
                                    <img src="{{ asset('storage') . '/' . $clothing->image }}" alt="img-blur-shadow"
                                        class="img-fluid shadow border-radius-lg w-100" style="height:300px;">
                                </a>
                                <div class="colored-shadow"
                                    style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                                </div>
                            </div>
                            <div class="card-body text-center">

                                <h5 class="font-weight-normal mt-3">
                                    <a
                                        href="{{ url('detail-clothing/' . $clothing->id . '/' . $category_id) }}">{{ $clothing->name }}</a>
                                </h5>
                                <p class="mb-0">
                                    {{ $clothing->description }}
                                </p>
                                @php
                                    $sizes = explode(',', $clothing->available_sizes);
                                    $stockPerSize = explode(',', $clothing->stock_per_size);
                                @endphp
                                {{-- @for ($i = 0; $i < count($sizes); $i++)
                                    <span class="text-center">Talla {{ $sizes[$i] }}: {{ $stockPerSize[$i] }}</span><br>
                                @endfor --}}
                                <a href="{{ url('detail-clothing/' . $clothing->id . '/' . $category_id) }}"
                                    class="btn btn-icon btn-3 mt-2 btn-outline-secondary">
                                    <span class="btn-inner--icon"><i class="material-icons">visibility</i></span>
                                    <span class="btn-inner--text">Ver Detalles</span>
                                </a>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer d-flex">
                                <p class="font-weight-normal my-auto">Precio: ₡{{ number_format($clothing->price) }}</p>


                                <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                                <p class="text-sm my-auto"> Stock: {{ $clothing->total_stock }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
    @include('layouts.inc.indexfooter')
@endsection
