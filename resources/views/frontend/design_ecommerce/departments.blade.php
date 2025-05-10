@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container p-t-30 p-b-30">
        <div class="bread-crumb flex-w p-r-15 p-lr-0-lg">
            <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Inicio
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Departamentos
            </span>
        </div>
        <div class="sec-banner bg0 m-t-20 p-b-20">
            <div>
                <h3 class="mtext-105 cl5 text-center mb-5">
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
