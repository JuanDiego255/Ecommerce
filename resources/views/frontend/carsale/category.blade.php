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

    <br>
    <section class="ftco-section ftco-no-pt bg-light pt-5">

        <!-- Header -->
        <div class="row justify-content-center bg-gray-200">
            <div class="col-md-12 text-center ftco-animate pt-3">
                <h3 class="mb-2 title align-text-center">Explora nuestra amplia variedad de categorías</h3>
            </div>
        </div>


        <!-- Categorías como tabs -->
        <div class="d-flex justify-content-center bg-gray-200">
            <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $categoryId }}-tab"
                            data-toggle="pill" href="#pills-{{ $categoryId }}" role="tab"
                            aria-controls="pills-{{ $categoryId }}">
                            {{ $vehicles->first()->category }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="container-fluid">
            <div class="tab-content" id="pills-tabContent">
                @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ $categoryId }}"
                        role="tabpanel" aria-labelledby="pills-{{ $categoryId }}-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="carousel-car-category owl-carousel">
                                    @foreach ($vehicles as $item)
                                        @php
                                            $precio = $item->price;
                                            if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
                                                $precio = $item->first_price;
                                            }
                                            if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                                $precio = $item->mayor_price;
                                            }
                                            $descuentoPorcentaje = $item->discount;
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            $precioConDescuento = $precio - $descuento;
                                        @endphp

                                        <div class="item">
                                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">
                                                <div class="car-wrap rounded ftco-animate">
                                                    <img class="img-category rounded d-flex align-items-end"
                                                        src="{{ isset($item->main_image) ? route('file', $item->main_image) : url('images/producto-sin-imagen.PNG') }}"
                                                        alt="Imagen del producto">

                                                    <div class="text">
                                                        @if ($item->created_at->diffInDays(now()) <= 7)
                                                            <span
                                                                class="badge badge-pill ml-2 badge-date text-white animacion"
                                                                id="comparison-count">
                                                                Nuevo Ingreso
                                                            </span>
                                                        @endif
                                                        <h2 class="mb-0">
                                                            <a href="#">
                                                                {{ $item->name . ' (' . $item->model . ')' }}
                                                            </a>
                                                        </h2>

                                                        <div class="d-flex mb-3">

                                                            <!-- <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p> -->
                                                        </div>
                                                        {{--  <span class="line"><span>Tendencia</span></span> --}}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @include('layouts.inc.carsale.footer')
@endsection
