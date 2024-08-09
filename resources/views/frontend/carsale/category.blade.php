@extends('layouts.frontrent')
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
    {{-- <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->services }} me-1"></i>{{$title_service}}</a>
                </li>
            @else
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                            class="fas fa-shapes me-1"></i>Departamentos</a></li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->categories }} me-1"></i>{{ $department_name }}</a>
                </li>
            @endif

        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($category as $item)
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}"
                                class="add-to-cart">{{$btn}}</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a
                                    href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}">{{ $item->name }}</a>
                            </h3>
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
    </div> --}}
    {{-- Trending --}}
    {{-- Main Banner --}}
    <div class="hero-wrap ftco-degree-bg"
        style="background-image: url('{{ url('images/bg_1.jpg') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">Nuestras categorías</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container-fluid mt-5">
            <div class="row justify-content-center">
                <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                    <span class="subheading">Explora en la categoría de su preferencia</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="carousel-car owl-carousel">
                        @foreach ($category as $item)
                            <div class="item">
                                <div class="car-wrap rounded ftco-animate">
                                    <div class="img rounded d-flex align-items-end"
                                        style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');">
                                    </div>
                                    <div class="text">
                                        <h2 class="mb-0"><a href="#">{{ $item->name }}</a></h2>                                        
                                        <p class="d-flex mb-0 d-block"><a
                                                href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}"
                                                class="btn btn-secondary py-2 ml-1">Ver vehículos</a></p>
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
