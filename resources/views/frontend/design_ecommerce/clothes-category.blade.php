@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <section class="bg0 p-t-100 p-b-140">
        <div class="container">
            <div class="bread-crumb flex-w p-r-15 p-t-30 p-lr-0-lg">
                @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                    <a href="{{ url('/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Inicio
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <a href="{{ url('category/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                        Categorías
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $category_name }}
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
                    <a href="{{ url('category/' . $department_id) }}" class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $department_name }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                    <span class="stext-109 cl4">
                        {{ $category_name }}
                    </span>
                @endif
            </div>
            <div class="p-b-10">
                <h3 class="ltext-103 cl5 text-center">
                    {{ $category_name }}
                </h3>
            </div>

            <!-- Botones de filtros -->
            <div class="flex-w flex-sb-m p-b-52">
                <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                    @php
                        $categories_pluck = $clothings->pluck('category')->unique();
                    @endphp
                    <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                        Todos
                    </button>
                    @foreach ($categories_pluck as $category)
                        <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5"
                            data-filter=".{{ strtolower(str_replace(' ', '', $category)) }}">
                            {{ $category }}
                        </button>
                    @endforeach
                </div>

                <!-- Opciones de filtro adicionales (search, etc.) -->
                <div class="flex-w flex-c-m m-tb-10">
                    <div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                        <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                        <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Filtro
                    </div>

                    <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                        <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                        <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                        Buscar
                    </div>
                </div>
            </div>

            <!-- Paneles de búsqueda y filtro (si los requieres) -->
            <div class="dis-none panel-search w-full p-t-10 p-b-15">
                <div class="bor8 dis-flex p-l-15">
                    <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                    <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="searchfor" id="searchfor"
                        placeholder="Buscar">
                </div>
            </div>
            <!-- Aquí podrías agregar un panel-filter similar si lo requieres -->

            <!-- Grid de productos -->
            <div class="row isotope-grid" id="product-container">
                @include('frontend.design_ecommerce.partial', ['clothings' => $clothings])
            </div>
        </div>
        <div class="flex-c-m flex-w w-full p-t-45">
            <button id="btnPrev" class="lex-c-m stext-101 cl5 m-r-5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"
                data-prev="{{ $clothings->previousPageUrl() }}" data-id="{{ $category_id }}">Anterior</button>
            <button id="circleNumber"
                class="lex-c-m stext-101 m-r-5 cl5 size-103-clothes bg2 bor1 hov-btn1 p-lr-15 w-5 trans-04">1</button>
            <button id="btnNext" class="lex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04"
                data-next="{{ $clothings->nextPageUrl() }}" data-id="{{ $category_id }}">Siguiente</button>
        </div>
    </section>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        $(document).on('click', '#btnPrev, #btnNext', function() {
            let pageUrl = $(this).data('next') || $(this).data('prev'); // Detecta si es "next" o "prev"
            if (!pageUrl) return;

            let urlParams = new URLSearchParams(new URL(pageUrl).search);
            let page = urlParams.get('page'); // Extrae el número de página
            let id = $(this).data('id');

            $.ajax({
                method: "GET",
                url: "/paginate/" + Number(page) + "/" + id,
                success: function(response) {
                    var items = response.items;

                    // 🔹 Destruir la instancia anterior de Isotope
                    var $grid = $('.isotope-grid').data('isotope');
                    if ($grid) {
                        $grid.destroy();
                    }

                    $('#product-container').empty();
                    $('#product-container').append(response.html);
                    $('#circleNumber').text(response.page);

                    // 🔹 Volver a inicializar Isotope
                    var $newGrid = $('#product-container').isotope({
                        itemSelector: '.isotope-item',
                        layoutMode: 'fitRows',
                        percentPosition: true,
                        animationEngine: 'best-available',
                        masonry: {
                            columnWidth: '.isotope-item'
                        }
                    });
                    $('html, body').animate({ scrollTop: 0 }, 600);

                    setTimeout(function() {
                        $newGrid.isotope('layout');
                    }, 50);

                    // 🔹 Actualizar data-next y data-prev con las nuevas URLs
                    if (response.next_page_url) {
                        $('#btnNext').data('next', response.next_page_url);
                    } else {
                        $('#btnNext').removeData('next');
                    }

                    if (response.prev_page_url) {
                        $('#btnPrev').data('prev', response.prev_page_url);
                    } else {
                        $('#btnPrev').removeData('prev');
                    }
                   
                }
            });
        });


        $(document).on('click', '.js-show-modal1', function(e) {
            e.preventDefault();
            $('.js-modal1').addClass('show-modal1');
        });
    </script>
@endsection
