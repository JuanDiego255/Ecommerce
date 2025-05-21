@extends('layouts.frontmainav')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="bradcam_area bradcam_bg_about">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Nosotros</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->

    <!-- about_info_area start  -->
    <div class="about_info_area plus_padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="about_text">
                        <h3>¿Quienes Somos?</h3>
                        <div class="ck-content mb-5">
                            {!! isset($tenantinfo->about_us) ? $tenantinfo->about_us : '' !!}
                        </div>
                        {{-- <a href="#" class="boxed-btn3">About Us</a> --}}
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <!-- /about_info_area end  -->
    <!-- counter_area  -->
    <div class="counter_area counter_bg_1 mt-5 overlay_03">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <div class="single_counter text-center">
                        <div class="counter_icon">
                            <img src="img/svg_icon/cart.svg" alt="" />
                        </div>
                        <h3><span class="counter">100</span> <span>%</span></h3>
                        <p>Proyectos Exitosos</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <div class="single_counter text-center">
                        <div class="counter_icon">
                            <img src="img/svg_icon/heart.svg" alt="" />
                        </div>
                        <h3><span class="counter">91600</span></h3>
                        <p>Metros Cuadrados de Proyectos</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <div class="single_counter text-center">
                        <div class="counter_icon">
                            <img src="img/svg_icon/respect.svg" alt="" />
                        </div>
                        <h3><span class="counter">+400</span></h3>
                        <p>Proyectos Completados</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <div class="single_counter text-center">
                        <div class="counter_icon">
                            <img src="img/svg_icon/cart.svg" alt="" />
                        </div>
                        <h3><span class="counter">12</span> <span></span></h3>
                        <p>Años de Experiencia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /counter_area  -->
    @include('layouts.inc.av.footer')
@endsection
@section('scripts')
@endsection
