@extends('layouts.frontmainav')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $ruta = $tenantinfo->tenant != 'aclimate' ? 'file' : 'aclifile';
@endphp
@section('content')
    {{-- @include('layouts.inc.carsale.footer') --}}
    <style>
        @media screen and (max-width: 768px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .owl-bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->mobile_image ?? $carousel->image) }}');
                }
            @endforeach
        }

        @media screen and (min-width: 769px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .owl-bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->image) }}');
                }
            @endforeach
        }

        .owl-bg-carousel {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <div class="slider_area mb-1">
        <div class="slider_active owl-carousel">
            @if (isset($tenantcarousel) && count($tenantcarousel) > 0)
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="single_slider d-flex align-items-center owl-bg-carousel owl-bg-carousel-{{ $loop->index }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="slider_text">
                                        <p style="color: #92bb57;">{{ $carousel->text2 ?? '' }}</p>
                                        <h3>
                                            {!! $carousel->text1 ?? '' !!}
                                        </h3>
                                        {{-- <div class="video_service_btn">
                                    <a href="#" class="boxed-btn3">Our Services</a>
                                </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <!-- case_study_area  -->
    @if (count($categories) != 0)
        <div class="case_study_area">
            <div class="container">
                <div class="border_bottom">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title text-center mb-30">
                                <h3>Nuestros servicios</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="case_active owl-carousel">
                                @foreach ($category as $key => $item)
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
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="more_close_btn text-center">
                                <a href="{{ url('/category/1') }}" class="boxed-btn3-line">Explorar más</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /case_study_area  -->
    <!-- projects done  -->
    @if (count($blogs) != 0)
        <div class="case_study_area">
            <div class="container">
                <div class="border_bottom">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title text-center mb-30">
                                <h3>Proyectos Ejecutados</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="case_active owl-carousel">
                                @foreach ($blogs as $key => $item)
                                    @if ($item->is_project == 1)
                                        <div class="single_case">
                                            <div class="case_thumb">
                                                <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">
                                                    <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                        alt="" />
                                                </a>
                                            </div>
                                            <div class="case_heading">
                                                <span>Finalizado</span>
                                                <h3><a
                                                        href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                                                </h3>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="more_close_btn text-center">
                                <a href="#" class="boxed-btn3-line">Explorar más</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /projects done  -->
    <!-- accordion  -->
    <div class="accordion_area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="accordion_thumb">
                        <img src="{{ asset('/avstyles/img/ask.svg') }}" alt="" />
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="faq_ask">
                        <h3>Cualidades de AV Electromecánica</h3>
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <i class="fa fa-chevron-down mr-2"></i>
                                            Servicio al cliente
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                                    style="">
                                    <div class="card-body">
                                        Nuestras actividades y procesos se encuentran orientados para que de una manera
                                        amable, eficaz y rápida podamos satisfacer las necesidades de nuestros usuarios de
                                        servicios y productos.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            <i class="fa fa-chevron-down mr-2"></i>
                                            Excelencia
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"
                                    style="">
                                    <div class="card-body">
                                        Nuestras metas se encuentran destinadas a conseguirla máxima eficacia en la gestión
                                        para obtener los mejores resultados.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            <i class="fa fa-chevron-down mr-2"></i>
                                            Compromiso
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordion" style="">
                                    <div class="card-body">
                                        Tomamos consciencia de la importancia que tiene cumplir con el desarrollo de nuestro
                                        trabajo dentro del tiempo estipulado.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            <i class="fa fa-chevron-down mr-2"></i>
                                            Calidad
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordion" style="">
                                    <div class="card-body">
                                        Nuestros productos y servicios cumplen con los requisitos establecidos para lograr
                                        la satisfacción de nuestros clientes.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- logos clientes y proveedores  -->
    @if (count($logos) != 0)
        <div class="case_study_area">
            <div class="container">
                <div class="border_bottom">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title text-center mb-10">
                                <h3 class="text-uppercase uppercase text-prov">Clientes</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="case_active_logos owl-carousel">
                                @foreach ($logos as $key => $item)
                                    @if ($item->is_supplier == 0)
                                        <div class="single_case">
                                            <div class="case_thumb">
                                                <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                    alt="" />
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title text-center mb-10">
                                <h3 class="text-uppercase uppercase text-prov">Servicios de excelencia - respaldados por
                                    marcas líderes</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="case_active_logos owl-carousel">
                                @foreach ($logos as $key => $item)
                                    @if ($item->is_supplier == 1)
                                        <div class="single_case">
                                            <div class="case_thumb">
                                                <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                    alt="" />
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- logos clientes y proveedores  -->
    <!-- accordion  -->
    <!-- counter_area  -->
    <div class="counter_area counter_bg_1 mt-5 overlay_03">
        <div class="container">
            <div class="row">
                @foreach ($metricas as $key => $item)
                    @php
                        // Extraer el valor y verificar si tiene el símbolo de porcentaje
                        preg_match('/([\d,.]+)\s*(%?)/', $item->valor, $matches);
                        $numero = $matches[1] ?? '0';
                        $simbolo = $matches[2] ?? '';
                    @endphp

                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <div class="single_counter text-center">
                            <div class="counter_icon">
                                <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="" />
                            </div>
                            <h3>
                                <span class="counter">{{ $numero }}</span>
                                @if ($simbolo)
                                    <span>{{ $simbolo }}</span>
                                @endif
                            </h3>
                            <p>{{ $item->titulo }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- /counter_area  -->
    <!-- team_area  -->
    @if (count($sellers) != 0)
        <div class="team_area">
            <div class="container">
                <div class="border_bottom">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title mb-40 text-center">
                                <h3>Equipo de trabajo</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($sellers as $item)
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="single_team">
                                    <div class="team_thumb">
                                        <img src="img/team/3.png" alt="" />
                                    </div>
                                    <div class="team_info text-center">
                                        <h3>Milani Mou</h3>
                                        <p>Photographer</p>
                                        <div class="social_link">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-facebook"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-twitter"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="fa fa-instagram"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /team_area  -->
    @include('layouts.inc.av.footer')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var showMoreButtons = document.querySelectorAll('.show-more');

            showMoreButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var cardText = button.previousElementSibling;
                    if (cardText.classList.contains('expanded')) {
                        cardText.classList.remove('expanded');
                        button.textContent = 'Ver más';
                    } else {
                        cardText.classList.add('expanded');
                        button.textContent = 'Ver menos';
                    }
                });
            });
        });
    </script>
@endsection
