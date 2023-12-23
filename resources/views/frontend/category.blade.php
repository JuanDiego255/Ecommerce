@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <h1 class="text-center text-dark">Nuestras Categorías</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($category as $item)
                <div class="col bg-transparent">
                    <div class="card">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <a target="blank" data-fancybox="gallery" href="{{ asset('storage') . '/' . $item->image }}" class="d-block blur-shadow-image">
                                <img src="{{ asset('storage') . '/' . $item->image }}" alt="img-blur-shadow"
                                    class="img-fluid shadow border-radius-lg w-100" style="height:300px;">
                            </a>
                            <div class="colored-shadow"
                                style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                            </div>
                        </div>
                        <div class="card-body text-center">

                            <h5 class="font-weight-normal mt-3">
                                <a href="{{url('clothes-category/'.$item->id)}}">{{ $item->name }}</a>
                            </h5>
                            <p class="mb-0">
                                {{ $item->description }}
                            </p>
                           
                            <a href="{{url('clothes-category/'.$item->id)}}" class="btn btn-icon btn-3 mt-2 btn-outline-secondary">
                                <span class="btn-inner--icon"><i class="material-icons">play_arrow</i></span>
                                <span class="btn-inner--text">Ver Prendas</span>
                            </a>

                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer d-flex">
                            <p class="font-weight-normal my-auto">Tu Tienda CR</p>
                            <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">place</i>
                            <p class="text-sm my-auto"> Grecia, Alajuela, Costa Rica</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>            
            <div class="col-md-12">
                <a href="{{ url('/') }}" class="btn btn-velvet w-25">Volver</a>
            </div>
        </center>
    </div>
@endsection
