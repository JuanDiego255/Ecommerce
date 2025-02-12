@extends('layouts.frontmainav')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="case_details_area">
        <div class="container">
            <div class="border_bottom">
                <div class="row ">
                    <div class="col-xl-12">
                        <div class="details_title">
                            <span>{{ $category->meta_title }}</span>
                            <h3>{{ $category->name }}</h3>
                        </div>
                    </div>
                    {{--  <div class="col-xl-12">
                        <div class="case_thumb">
                            <img src="avstyles/img/case/img.png" alt="">
                        </div>
                    </div> --}}
                    <div class="col-xl-9">
                        <div class="details_main_wrap">
                            <div class="ck-content mb-5">
                                {!! $category->description !!}
                            </div>

                            <div class="single_details mb-60">

                                <ul>
                                    @foreach ($social_network as $social)
                                        @php
                                            $social_logo = null;
                                            if (stripos($social->social_network, 'Facebook') !== false) {
                                                $social_logo = 'fab fa-facebook';
                                            } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                                $social_logo = 'fab fa-instagram';
                                            }
                                            if (stripos($social->social_network, 'Twitter') !== false) {
                                                $social_logo = 'fab fa-twitter';
                                            }
                                            if (stripos($social->social_network, 'Linkedin') !== false) {
                                                $social_logo = 'fab fa-linkedin';
                                            }
                                        @endphp
                                        <li>
                                            <a href="{{ url($social->url) }}">
                                                <i class="{{ $social_logo }}"></i>
                                                {{ $social->social_network }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- case_study_area  -->
    @if (count($categories) != 0)
        <div class="case_study_area">
            <div class="container">
                <div class="border_bottom">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="section_title mb-40">
                                <h3>Pueden interesarte tambien</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="case_active owl-carousel">
                                @foreach ($categories as $key => $item)
                                    <div class="single_case">
                                        <div class="case_thumb">
                                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
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
                                <a href="#" class="boxed-btn3-line">Explorar más</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /case_study_area  -->
    <!-- Information_area  -->
    <div class="Information_area overlay">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-8">
                    <div class="info_text text-center">
                        <h3>Contáctanos para más información</h3>
                        <a class="boxed-btn3" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"><i class="fab fa-whatsapp"></i> {{$tenantinfo->whatsapp}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Information_area  end -->
    @include('layouts.inc.av.footer')
@endsection
@section('scripts')
@endsection
