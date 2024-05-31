@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-book me-1"></i>Blog</a></li>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($blogs as $item)
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img
                                src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}" class="add-to-cart">{{ __('Ver Información') }}</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a
                                    href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>
            <div class="container mb-3">
                {{ $blogs ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div>
    @switch($tenantinfo->kind_business)
        @case(3)
            @include('layouts.inc.websites.indexfooter')
        @break

        @case(4)
            @include('layouts.inc.main.indexfooter')
        @break

        @default
            @include('layouts.inc.indexfooter')
    @endswitch
@endsection
@section('scripts')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
@endsection
