@extends('layouts.design_ecommerce.frontmain')
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
                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}">
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
    <!-- Banner -->
    <div class="sec-banner bg0 p-t-60 p-b-70">
        <div class="container">
            <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $title_service }}
                    </span>
                @else
                    <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('departments/index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Departamentos
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $department_name }}
                    </span>
                @endif
            </div>
            <div class="row">
                @foreach ($category as $item)
                    <div class="col-md-6 p-b-60 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}" alt="IMG-BANNER">

                            <a href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    {{-- <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $item->name }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        *Explorar productos*
                                    </span> --}}
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Detallar
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
