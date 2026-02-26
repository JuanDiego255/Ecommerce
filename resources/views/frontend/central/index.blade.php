@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <style>
        @media screen and (max-width: 768px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->mobile_image ?? $carousel->image) }}');
                }
            @endforeach
        }

        @media screen and (min-width: 769px) {
            @foreach ($tenantcarousel as $index => $carousel)
                .bg-carousel-{{ $index }} {
                    background-image: url('{{ route($ruta, $carousel->image) }}');
                }
            @endforeach
        }

        .item-slick1 {
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }

        .video-container {
            width: 1930px;
            height: 920px;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
        }

        .video-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
    {{-- Carousel Start --}}
    @if (count($tenantcarousel) != 0)
        <div class="wrap-slick1">
            <div class="slick1">
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="item-slick1 bg-carousel-{{ $loop->index }}">

                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        {!! $carousel->text1 !!}
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        {{ $carousel->text2 }}
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . $carousel->url) }}"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        {{ $carousel->link_text }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($tenantinfo->tenant == 'main')
                    {{-- 🔥 Slide manual con video quemado --}}
                    <div class="item-slick1">
                        <div class="video-container">
                            <video autoplay muted loop playsinline class="video-bg">
                                <source src="{{ url('/design_ecommerce/videos/main.mp4') }}" type="video/mp4">
                                Tu navegador no soporta video HTML5.
                            </video>
                        </div>

                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        ¡Bienvenido a nuestro mundo!
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        Mira nuestro video promocional
                                    </h2>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                    <a href="/promo"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Fin slide manual --}}
                @endif
            </div>
        </div>
    @endif
    {{-- Carousel End --}}
    <!-- Banner Start-->
    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
        @if (count($category) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($category->take($take) as $key => $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('/design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'clothes-category/' . $item->category_id . '/' . $item->department_id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span
                                                    class="ltext-102 trans-04 p-b-8 {{ isset($tenantinfo->tenant) && ($tenantinfo->tenant != 'aclimate' && $tenantinfo->tenant != 'solociclismocrc') ? 'block1-name' : 'block1-name-ac' }}">
                                                    {{ $item->name }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">
                                                    {!! $item->description !!}
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
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        @if (count($departments) != 0)
            <div class="sec-banner bg0 p-t-80 p-b-30">
                <div class="container">
                    <div class="row">
                        @foreach ($departments as $item)
                            @if ($item->black_friday != 1)
                                <div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
                                    <!-- Block1 -->
                                    <div class="block1 wrap-pic-w">
                                        <img src="{{ isset($item->image) ? route($ruta, $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                            alt="IMG-BANNER">

                                        <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') . 'category/' . $item->id) }}"
                                            class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                            <div class="block1-txt-child1 flex-col-l">
                                                <span class="block1-name ltext-102 trans-04 p-b-8">
                                                    {{ $item->department }}
                                                </span>

                                                <span class="block1-info stext-102 trans-04">

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
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif

    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        $('.featured-carousel').owlCarousel({
            loop: true,
            margin: 10,

            dots: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        })

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
