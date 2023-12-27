@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="alert alert-secondary alert-dismissible text-white fade show mt-4" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    checkroom
                </span>
            </span>
            <span class="alert-text"><strong>{{ $category_name }}</strong></span>
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
                                @for ($i = 0; $i < count($sizes); $i++)
                                    <p class="mb-0">Talla {{ $sizes[$i] }}: {{ $stockPerSize[$i] }}</p>
                                @endfor
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
    <center>
        <div class="container">
            {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
        </div>
        <div class="col-md-12">
            <a href="{{ url('category/') }}" class="btn btn-outline-secondary">Todas Las Categorías</a>
        </div>
    </center>
    @include('layouts.inc.indexfooter')
@endsection
