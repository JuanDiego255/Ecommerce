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
    @switch($tenantinfo->tenant)
        @case('sakura318')
            <div class="container">
                <div class="breadcrumb-nav-sk">
                    <li class="home-sk">
                        <a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i> Inicio</a>
                    </li>
                
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="bread-sk">
                            <a href="#"><i class="fas fa-{{ $icon->services }} me-1"></i> {{ $title_service }}</a>
                        </li>
                    @else
                        <li class="bread-sk">
                            <a href="{{ url('departments/index') }}"><i class="fas fa-shapes me-1"></i> Departamentos</a>
                        </li>
                        <li class="bread-sk">
                            <a href="#"><i class="fas fa-{{ $icon->categories }} me-1"></i> {{ $department_name }}</a>
                        </li>
                    @endif
                </div>                
                <div class="mt-5 mb-5">
                    <div class="mt-5">
                        <h1 class="mt-5 mb-5 sakura-20 sakura-color">
                            Categorías | {{ $department_name }}</h1>
                    </div>
                    <div class="row">
                        <div class="owl-carousel featured-carousel-circle owl-theme">
                            @foreach ($category as $item)
                                <a href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}">
                                    <div class="item">
                                        <div class="product-grid product_data">
                                            <div class="product-circle">
                                                <p class="category-name-circle">{{ $item->name }}</p>
                                                <span></span>
                                                <span></span>
                                                <span></span>
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
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                        <li class="bread-standard"><a href="#"><i
                                    class="fas fa-{{ $icon->services }} me-1"></i>{{ $title_service }}</a>
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
                                    <img
                                        src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    <ul class="product-links">
                                        <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                                    class="fas fa-eye"></i></a></li>
                                    </ul>
                                    <a href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}"
                                        class="add-to-cart">{{ $btn }}</a>
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
                    <div class="container mb-5">
                        {{ $category ?? ('')->links('pagination::simple-bootstrap-4') }}
                    </div>
                </center>
            </div>
        @break
    @endswitch

    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
