@extends('layouts.front')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}

@section('content')

    <div class="container mt-5 {{ $tenantinfo->kind_business != 3 ? 'd-block' : 'd-none' }}">
        <div class="breadcrumb-nav bc3x">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('blog/index') }}"><i class="fas fa-book me-1"></i>Blog</a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-tag me-1"></i>Artículo</a></li>
        </div>
    </div>
    <div class="bg-transparent mt-5">
        <h1 class="text-title-blog text-center">{{ $blog->title }}</h1>
    </div>
    {{-- salto de pagina --}}
    <div class="{{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}" style="height: 200px;"></div>
    {{-- cards info --}}
    <div class="text-center container {{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}">
        <div class="row text-center">
            <!-- Team item -->
            @foreach ($cards as $item)
                <div class="col-xl-3 col-sm-6 mb-5">
                    <div class="bg-white rounded shadow-sm py-5 px-4"><img src="{{ route('file', $item->image) }}"
                            alt="" width="100"
                            class="img-fluid rounded-circle border-0 mb-3 img-thumbnail shadow-sm">
                        <h5 class="mb-0">{{ $item->title }}</h5><span
                            class="small text-uppercase text-muted">{{ $item->description }}</span>
                    </div>
                </div>
            @endforeach

            <!-- End -->
        </div>
    </div>
    {{-- blog principal --}}
    <div class="mt-5 bg-white">
        <div class="container pt-3 pb-5">
            <div class="row mt-5">
                <div class="col-md-8 mb-2">
                    <h1 class="text-title">{{ $blog->title_optional }}</h1>
                    {!! $blog->body !!}
                    <div class="text-justify">
                        <h5 class="text-muted">Publicado por: {{ $blog->autor }}</h5>
                        <h5 class="text-muted">Fecha de publicación: {{ $fecha_letter }}</h5>
                    </div>

                    <a target="blank" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                        class="btn btn-icon btn-3 mt-2 btn-add_to_cart">
                        <span class="btn-inner--icon"><i class="material-icons">calendar_month</i></span>
                        <span class="btn-inner--text">{{ __('Solicitar una cita') }}</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{ route('file', $blog->image) }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ route('file', $blog->image) }}"><i
                                            class="fas fa-eye"></i></a>
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- articulo --}}
    <div class="bg-gray-light">

        <div class="container pt-3 pb-5">
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
                                    <input type="hidden" name="title" value="{{ $blog->title }}">
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
    </div>
    {{-- profesional info --}}
    @if ($blog->personal_id != null)
        {{-- salto de pagina --}}
        <div class="{{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}" style="height: 75px;"></div>
        <div class="mt-5 bg-white">

            <div class="container pt-3 pb-5">
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="product-grid product_data">
                            <div class="product-image">
                                <img src="{{ route('file', $blog->image_personal) }}">
                                <ul class="product-links">
                                    <li><a target="blank" href="{{ route('file', $blog->image_personal) }}"><i
                                                class="fas fa-eye"></i></a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    </div>
                    <div class="col-md-8 mb-2">
                        <h1 class="text-title">{{ $blog->name }}</h1>
                        {!! $blog->personal_body !!}
                        <a class="btn btn-icon btn-3 mt-2 btn-add_to_cart"
                            href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}">
                            <span class="btn-inner--icon"><i class="material-icons">calendar_month</i></span>
                            <span class="btn-inner--text">{{ __('Contactar') }}</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endif
    {{-- salto de pagina --}}
    <div class="{{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}" style="height: 75px;"></div>
    {{-- Carousel de resultados --}}
    @if (count($results) != 0)
        <div class="mt-5 bg-white">
            <div class="container pt-3 pb-5">
                <div class="text-center">
                    <h1 class="text-center text-title mb-1 pt-3">Resultados acerca de este tratamiento</h1>
                </div>
                <div class="row">
                    @foreach ($results as $result)
                        <div id="carouselExampleControls{{ $result->id }}" class="carousel slide col-md-4"
                            data-bs-ride="carousel">
                            <div class="carousel-inner mb-1 foto">
                                <div class="carousel-item active">
                                    <div class="item">
                                        <div class="product-grid product_data">
                                            <div class="product-image">
                                                <img src="{{ route('file', $result->before_image) }}">
                                                <a href="{{ route('file', $result->before_image) }}"
                                                    class="add-to-cart">{{ __('Detallar') }}</a>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="text-muted text-uppercase">
                                                    {{ __('Antes') }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="item">
                                        <div class="product-grid product_data">
                                            <div class="product-image">
                                                <img src="{{ route('file', $result->after_image) }}">
                                                <a href="{{ route('file', $result->after_image) }}"
                                                    class="add-to-cart">{{ __('Detallar') }}</a>
                                            </div>
                                            <div class="product-content">
                                                <h3 class="text-muted text-uppercase">
                                                    {{ __('Después') }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="min-vh-75 position-absolute w-100 top-0">
                                <a class="carousel-control-prev" href="#carouselExampleControls{{ $result->id }}"
                                    role="button" data-bs-slide="prev">
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls{{ $result->id }}"
                                    role="button" data-bs-slide="next">
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- salto de pagina --}}
        <div class="{{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}" style="height: 75px;">

        </div>
    @endif
    {{-- Video --}}
    @if ($blog->video_url)
        <div class="mt-2 bg-white">
            <div class="container pt-3 pb-5 text-center">
                <div class="text-center">
                    <h1 class="text-center text-title mb-1 pt-3">Video explicativo</h1>
                </div>
                <center>
                    <div class="mt-3 mb-3" style="width: 90%; height: 90%; overflow: hidden;">
                        <iframe style="width: 100%; height: 100%;" src="{{ $blog->video_url }}" title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </center>               
            </div>
        </div>
         {{-- salto de pagina --}}
         <div class="{{ $tenantinfo->kind_business == 3 ? 'd-block' : 'd-none' }}" style="height: 75px;"></div>
    @endif
    {{-- Comentarios --}}
    @if (count($comments) != 0)
        <div class="bg-gray-light">
            <div class="mt-3 mb-5 pt-1 pb-5">
                <div class="container">
                    <div class="text-center">
                        <h3 class="text-center text-title mt-5 mb-3">Testimonios de nuestros clientes</h3>
                    </div>

                    <div class="row">
                        <div class="owl-carousel featured-carousel owl-theme">
                            @foreach ($comments as $item)
                                <div class="item">
                                    <div>
                                        <div class="card text-center">
                                            <img class="card-img-top"
                                                @if ($item->image) src="{{ route('file', $item->image) }}"
                                             
                                         @else
                                         src="{{ url('images/sin-foto.PNG') }}" @endif
                                                src="{{ url('images/sin-foto.PNG') }}" alt="">
                                            <div class="card-body">
                                                <h5>{{ $item->name }}
                                                </h5>
                                                <div class="rated text-center">
                                                    @for ($i = 1; $i <= $item->stars; $i++)
                                                        {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                        <label class="star-rating-complete"
                                                            title="text">{{ $i }}
                                                            stars</label>
                                                    @endfor
                                                </div>
                                                <p class="card-text">“{{ $item->description }}” </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- antoher blogs --}}
    @if (count($another_blogs) != 0)
        <div class="bg-white">
            <div class="container pt-3">
                <div class="text-center">
                    <h1 class="text-center text-title mb-1">Otros servicios que pueden interesarte.</h1>
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
                                        <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="add-to-cart">Ver
                                            información</a>
                                    </div>
                                    <div class="product-content">

                                        <h3
                                            class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'title-frags' }}">
                                            <a
                                                href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
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
    </script>
@endsection
