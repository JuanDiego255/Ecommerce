@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-shapes me-1"></i>Departamentos</a></li>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($departments as $item)
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('category/'.$item->id) }}"
                                class="add-to-cart">Categorías</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a
                                    href="{{ url('category/'.$item->id) }}">{{ $item->department }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>
            <div class="container">
                {{ $departments ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div> --}}
    <div class="container m-t-70">
        <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg mt-5">
            <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Inicio
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Departamentos
            </span>
        </div>
        <div class="sec-banner bg0 m-t-50 p-b-140">
            <div class="p-b-10">
                <h3 class="ltext-103 cl5 text-center mb-5">
                    Explora y sumérgete en nuestros departamentos
                </h3>
            </div>
            <div class="flex-w flex-c-m">
                @foreach ($departments as $item)
                    @if ($item->black_friday != 1)
                        <div class="size-202 m-lr-auto respon4">
                            <!-- Block1 -->
                            <div class="block1 wrap-pic-w">
                                <img src="{{ isset($item->image) ? route('file', $item->image) : url('design_ecommerce/images/producto-sin-imagen.PNG') }}"
                                    alt="IMG-BANNER">

                                <a href="{{ url('category/' . $item->id) }}"
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
                                            Explorar
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
    <!-- Banner -->

    @include('layouts.inc.design_ecommerce.footer')
@endsection
