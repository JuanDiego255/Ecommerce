@extends('layouts.frontrent')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}

@section('content')
    <div class="bg-transparent">
        <h1 class="text-title-blog text-center">{{ $blog->title }}</h1>
    </div>
    {{-- salto de pagina --}}
    <div class="d-block" style="height: 200px;"></div>
    {{-- cards info --}}
    <div class="text-center container">
        <div class="row text-center">
            <!-- Team item -->
            @foreach ($cards as $item)
                <div class="col-xl-3 col-sm-6 mb-5">
                    <div class="bg-white rounded shadow-sm py-5 px-4"><img
                            src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
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
                        class="btn btn-secondary py-2 ml-1">
                        <span class="btn-inner--text">
                            @if ($tenantinfo->kind_business != 0)
                                {{ __('Solicitar una cita') }}
                            @else
                                {{ __('Ir a WhatsApp') }}
                            @endif
                        </span>
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
    {{-- salto de pagina --}}
    <div class="d-block" style="height: 200px;"></div>
    {{-- articulo --}}
    <div class="bg-light">
        <div class="container mt-3 pt-3 pb-5">
            <div class="row gx-5">
                <div class="col-md-8">
                    @foreach ($tags as $tag)
                        <h3 class="text-title">{{ $tag->title }}</h3>
                        <p>{!! $tag->context !!}</p>
                    @endforeach
                </div>
                <div class="col-md-4 pt-5">
                    <form class="request-form ftco-animate bg-primary" action="{{ url('send-email/blog') }}" method="POST"
                        enctype="multipart/form-data">
                        <h2>¿Tienes alguna duda?, ¡contáctanos!</h2>
                        @csrf
                        <div class="form-group">
                            <label for="" class="label">Nombre</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="Nombre completo">
                        </div>
                        <div class="form-group">
                            <label for="" class="label">Teléfono</label>
                            <input type="text" class="form-control" name="telephone" required
                                placeholder="Número de teléfono">
                        </div>
                        <div class="form-group">
                            <label for="" class="label">E-mail</label>
                            <input type="text" class="form-control" name="email" required
                                placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <label for="" class="label">Consulta</label>
                            <input type="text" class="form-control" name="question" required
                                placeholder="¿Cuál es tu duda?">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Enviar formulario" class="btn btn-secondary py-3 px-4">
                        </div>
                    </form>
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
    <div class="" style="height: 75px;"></div>
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
        <div class="" style="height: 75px;">

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
                        <iframe style="width: 100%; height: 100%;" src="{{ $blog->video_url }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </center>
            </div>
        </div>
        {{-- salto de pagina --}}
        <div class="" style="height: 75px;"></div>
    @endif
    {{-- Comments --}}
    @if (count($comments) != 0)
        <hr class="dark horizontal text-danger my-0">
        <section class="ftco-section testimony-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section ftco-animate">
                        <span class="subheading">Testimonios de nuestros clientes</span>
                        <h2 class="mb-3">Clientes satisfechos</h2>
                    </div>
                </div>
                <div class="row ftco-animate">
                    <div class="col-md-12">
                        <div class="carousel-testimony owl-carousel ftco-owl">
                            @foreach ($comments as $item)
                                <div class="item">
                                    <div class="testimony-wrap rounded text-center py-4 pb-5">
                                        <div class="user-img mb-2"
                                            style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}')">
                                        </div>
                                        <div class="text pt-4">
                                            <p class="name">{{ $item->name }}</p>
                                            <div class="rated text-center">
                                                @for ($i = 1; $i <= $item->stars; $i++)
                                                    {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                    <label class="star-rating-complete"
                                                        title="text">{{ $i }}
                                                        stars</label>
                                                @endfor
                                            </div>
                                            <p class="card-text card-comment">“{{ $item->description }}”</p>
                                            <span class="show-more">Ver más</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- antoher blogs --}}
    @if (count($another_blogs) != 0)
        {{-- salto de pagina --}}
        <div class="" style="height: 75px;"></div>
        <section class="ftco-section bg-white">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <span class="subheading">Otros blogs que podrían interesarte</span>
                    </div>
                </div>
                <div class="row d-flex">
                    @foreach ($another_blogs as $item)
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="blog-entry justify-content-end">
                                <a href="blog-single.html" class="block-20"
                                    style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');">
                                </a>
                                <div class="text pt-4">
                                    <div class="meta mb-3">
                                        <div><a href="#">{{ $item->fecha_post }}</a></div>
                                        <div><a href="#">{{ $item->autor }}</a></div>
                                    </div>
                                    <h3 class="heading mt-2"><a
                                            href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                                    </h3>
                                    <p><a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="btn btn-primary">Leer más</a></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @include('layouts.inc.carsale.footer')
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
