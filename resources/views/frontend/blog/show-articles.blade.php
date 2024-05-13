@extends('layouts.front')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}

@section('content')
    <div class="container mt-4 mb-5">

        <div class="breadcrumb-nav bc3x">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('blog/index') }}"><i class="fas fa-book me-1"></i>Blog</a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-tag me-1"></i>Artículo</a></li>
        </div>

        <div class="row mt-5">
            <div class="col-md-8 mb-2">
                <h1 class="text-title">{{ $blog->title }}</h1>
                {!!$blog->body!!}
                <div class="text-justify">
                    <h5 class="text-muted">Publicado por: {{ $blog->autor }}</h5>
                    <h5 class="text-muted">Fecha de publicación: {{ $fecha_letter }}</h5>
                </div>
                
                <a class="btn btn-icon btn-3 mt-2 btn-add_to_cart" href="#">
                    <span class="btn-inner--icon"><i class="material-icons">calendar_month</i></span>
                    <span class="btn-inner--text">Solicitar una cita</span>
                </a>
            </div>
            <div class="col-md-4">
                <div class="product-grid product_data">
                    <div class="product-image">
                        <img src="{{ route('file', $blog->image) }}">
                        <ul class="product-links">
                            <li><a target="blank" href="{{ route('file', $blog->image) }}"><i class="fas fa-eye"></i></a>
                            </li>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 bg-white mb-4">
        <div class="container">
            <div class="row gx-5">
                <div class="col-md-8">
                    <hr class="hr-servicios">
                    @foreach ($tags as $tag)
                        <h3 class="text-title">{{ $tag->title }}</h3>
                        <p>{!! $tag->context !!}</p>
                    @endforeach
                </div>
                <div class="col-md-4 pt-5">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4 class="text-dark">No te quedes con la duda! Contáctanos</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('send-email/blog') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nombre</label>
                                        <div class="input-group input-group-static">

                                            <input required type="text" class="form-control form-control-lg"
                                                name="name">
                                        </div>
                                    </div>
                                    <input type="hidden" name="title" value="{{$blog->title}}">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <div class="input-group input-group-static">

                                            <input required type="text" class="form-control form-control-lg"
                                                name="telephone">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">E-mail</label>
                                        <div class="input-group input-group-static">

                                            <input required type="email" class="form-control form-control-lg"
                                                name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Consulta?</label>
                                        <div class="input-group input-group-static">

                                            <input required type="text" class="form-control form-control-lg"
                                                name="question">
                                        </div>
                                    </div>
                                    <center>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-velvet">Solicitar Información</button>
                                        </div>
                                    </center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <hr class="dark horizontal text-danger my-0">
    {{-- Trending --}}
    @if (count($another_blogs) != 0)
        <div class="mt-3 mb-2">
            <div class="container">
                <div class="text-center">
                    <h3 class="text-center text-muted mb-1">Otros servicios que pueden interesarte.</h3>
                </div>

                <div class="row">
                    <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-2 mb-5">
                        @foreach ($another_blogs as $item)
                            <div class="item">
                                <div class="product-grid product_data">
                                    <div class="product-image">
                                        <img src="{{ route('file', $item->image) }}">

                                        <ul class="product-links">
                                            <li><a target="blank" href="{{ route('file', $item->image) }}"><i
                                                        class="fas fa-eye"></i></a></li>
                                        </ul>
                                        <a href="{{ url('/blog/' . $item->id . '/show-index') }}" class="add-to-cart">Ver
                                            información</a>
                                    </div>
                                    <div class="product-content">

                                        <h3
                                            class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'title-frags' }}">
                                            <a
                                                href="{{ url('/blog/' . $item->id . '/show-index') }}">{{ $item->title }}</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    @endif
    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
@section('scripts')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
@endsection