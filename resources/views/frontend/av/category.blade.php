@extends('layouts.frontmainav')
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
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Nuestros Servicios</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->
    @if (count($category) != 0)
        <div class="case_study_area case_page">
            <div class="container">
                {{-- <div class="row">
                    <div class="col-xl-12">
                        <div class="portfolio-menu text-center">
                            <button class="active" data-filter="*">All</button>
                        </div>
                    </div>
                </div> --}}
                <div class="row grid">
                    @foreach ($category as $key => $item)
                        <div class="col-xl-4 grid-item cat3">
                            <div class="single_case">
                                <div class="case_thumb">
                                    <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                        alt="" />
                                </div>
                                <div class="case_heading">
                                    <span>{{ $item->meta_title }}</span>
                                    <h3><a
                                            href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}">{{ $item->name }}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>               
            </div>
        </div>       
    @endif

    @include('layouts.inc.av.footer')
@endsection
