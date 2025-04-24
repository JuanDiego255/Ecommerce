@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container m-t-80">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Inicio
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <a href="{{ url('blog/index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Blog
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                {{ $blog->title }}
            </span>
        </div>
    </div>
    <section class="bg0 p-t-52 p-b-20">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-9 p-b-80">
                    <div class="p-r-45 p-r-0-lg">
                        <!--  -->

                        <div class="wrap-pic-w how-pos5-parent">
                            <img src="{{ route($ruta, $blog->image) }}" alt="IMG-BLOG">

                            @php
                                $fecha = \Carbon\Carbon::parse($blog->fecha_post);
                            @endphp

                            <div class="flex-col-c-m size-123 bg9 how-pos5">
                                <span class="ltext-107 cl2 txt-center">
                                    {{ $fecha->format('d') }}
                                </span>

                                <span class="stext-109 cl3 txt-center">
                                    {{ $fecha->translatedFormat('F Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-t-32">
                            <h4 class="p-b-15">
                                <a href="#" class="ltext-108 cl2 hov-cl1 trans-04">
                                    {{ $blog->title }}
                                </a>
                            </h4>

                            <div class="flex-w flex-sb-m p-t-18">
                                <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                                    <span>
                                        <span class="cl4">Publicado por:</span> {{ $blog->autor }}
                                        <span class="cl12 m-l-4 m-r-6">|</span>
                                    </span>

                                    <span>
                                        {{ $tenantinfo->title }}
                                        <span class="cl12 m-l-4 m-r-6"></span>
                                    </span>
                                </span>
                            </div>
                            <h4 class="p-b-5">
                                <a href="#" class="ltext-108 cl2 hov-cl1 trans-04">
                                    {{ $blog->title_optional }}
                                </a>
                            </h4>
                            <div class="blog-body">
                                {!! $blog->body !!}
                            </div>
                            @foreach ($tags as $tag)
                                <p class="stext-117 cl6 p-b-25 p-t-25">{{ $tag->title }}</p>
                                <div class="article-body">
                                    {!! $tag->context !!}
                                </div>
                            @endforeach
                        </div>

                        <!--  -->
                        <div class="p-t-40">
                            <h5 class="mtext-113 cl2 p-b-12">
                                No te quedes con la duda! Contáctanos
                            </h5>

                            <form action="{{ url('send-email/blog') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="title" value="{{ $blog->title }}">
                                <div class="bor19 m-b-20">
                                    <textarea class="stext-111 cl2 plh3 size-124 p-lr-18 p-tb-15" name="question" placeholder="Consulta..."></textarea>
                                </div>

                                <div class="bor19 size-218 m-b-20">
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="name"
                                        placeholder="Nombre *">
                                </div>

                                <div class="bor19 size-218 m-b-20">
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="email"
                                        placeholder="Email *">
                                </div>

                                <div class="bor19 size-218 m-b-30">
                                    <input class="stext-111 cl2 plh3 size-116 p-lr-18" type="text" name="telephone"
                                        placeholder="Teléfono *">
                                </div>

                                <button type="submit"
                                    class="flex-c-m stext-101 cl0 size-125 bg3 bor2 hov-btn3 p-lr-15 trans-04">
                                    Solicitar Información
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3 p-b-80">
                    <div class="side-menu">
                        {{--  <div class="bor17 of-hidden pos-relative">
                            <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search"
                                placeholder="Search">

                            <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                                <i class="zmdi zmdi-search"></i>
                            </button>
                        </div> --}}

                        {{--    <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Categories
                            </h4>

                            <ul>
                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Fashion
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Beauty
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Street Style
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        Life Style
                                    </a>
                                </li>

                                <li class="bor18">
                                    <a href="#" class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                        DIY & Crafts
                                    </a>
                                </li>
                            </ul>
                        </div> --}}

                        <div class="p-t-0">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Productos que pueden interesarte
                            </h4>

                            <ul>
                                @foreach ($clothings->take(4) as $item)
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

                                    <li class="flex-w flex-t p-b-30">
                                        <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                            class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                                            <img class="img-min"
                                                src="{{ isset($item->image) ? route($ruta, $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                alt="PRODUCT">
                                        </a>

                                        <div class="size-215 flex-col-t p-t-8">
                                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}" class="stext-116 cl8 hov-cl1 trans-04">
                                                {{ $item->name }}
                                            </a>

                                            <span class="stext-116 cl6 p-t-20">
                                                <div class="price">₡{{ number_format($precioConDescuento) }}
                                                    @if ($item->discount)
                                                        <s class="text-danger">
                                                            ₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                                        </s>
                                                    @endif
                                                </div>
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-t-50">
                            <h4 class="mtext-112 cl2 p-b-27">
                                Palabras Destacadas
                            </h4>

                            <div class="flex-w m-r--5">
                                @foreach ($tags as $tag)
                                    @foreach (explode(',', $tag->meta_keywords) as $key => $keyword)
                                        <a href="#" data-keyword="{{ trim($keyword) }}"
                                            class="keyword-link flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                            {{ trim($keyword) }}
                                        </a>
                                        @if ($key == 9)
                                            @break
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-20">
                                Archive
                            </h4>

                            <ul>
                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            July 2018
                                        </span>

                                        <span>
                                            (9)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            June 2018
                                        </span>

                                        <span>
                                            (39)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            May 2018
                                        </span>

                                        <span>
                                            (29)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            April 2018
                                        </span>

                                        <span>
                                            (35)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            March 2018
                                        </span>

                                        <span>
                                            (22)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            February 2018
                                        </span>

                                        <span>
                                            (32)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            January 2018
                                        </span>

                                        <span>
                                            (21)
                                        </span>
                                    </a>
                                </li>

                                <li class="p-b-7">
                                    <a href="#" class="flex-w flex-sb-m stext-115 cl6 hov-cl1 trans-04 p-tb-2">
                                        <span>
                                            December 2017
                                        </span>

                                        <span>
                                            (26)
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div> --}}

                        {{--  <div class="p-t-50">
                            <h4 class="mtext-112 cl2 p-b-27">
                                Palabras Destacadas
                            </h4>

                            <div class="flex-w m-r--5">
                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Fashion
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Lifestyle
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Denim
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Streetstyle
                                </a>

                                <a href="#"
                                    class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                    Crafts
                                </a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                    blogs: 1
                },
                600: {
                    blogs: 3
                },
                1000: {
                    blogs: 4
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
