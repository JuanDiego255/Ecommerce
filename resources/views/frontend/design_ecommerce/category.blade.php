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
    <!-- Banner -->
    <div class="sec-banner bg0 p-t-60 p-b-70">
        <div class="container">
            <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') . '/') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $title_service }}
                    </span>
                @else
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') . '/') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'departments/index') }}"
                        class="stext-109 cl8 hov-cl1 trans-04">
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
                            <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                alt="IMG-BANNER">

                            <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $item->id . '/' . $department_id) }}"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span
                                        class="ltext-102 trans-04 p-b-8 {{ isset($tenantinfo->tenant) && ($tenantinfo->tenant != 'aclimate' && $tenantinfo->tenant != 'solociclismocrc') ? 'block1-name' : 'block1-name-ac' }}">
                                        {{ $item->name }}
                                    </span>
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
        <center>
            <div class="container mb-5">
                {{ $category ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
