@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner mb-4">

            <div class="carousel-item">
                <div class="page-header min-vh-75 m-3 border-radius-xl"
                    style="background-image: url('images/carousel2.png');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 my-auto">
                                <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Para todas las chicas.</h4>
                                <h1 class="text-white fadeIn2 fadeInBottom">Gran Variedad De Estilos</h1>
                                <p class="lead text-white opacity-8 fadeIn3 fadeInBottom">No dudes en contactarnos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item active">
                <div class="page-header min-vh-75 m-3 border-radius-xl"
                    style="background-image: url('images/carousel1.png');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 my-auto">
                                <h4 class="text-white mb-0 fadeIn1 fadeInBottom">Somos Un Nuevo Emprendimiento.</h4>
                                <h1 class="text-white fadeIn2 fadeInBottom">Velvet Boutique</h1>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="min-vh-75 position-absolute w-100 top-0">
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon position-absolute bottom-50" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon position-absolute bottom-50" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
    <h1 class="text-center text-dark">Prendas En Tendencia</h1>
    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="owl-carousel featured-carousel owl-theme mt-3">
                    @foreach ($clothings as $clothing)
                        <div class="item">
                            <div class="card" data-animation="true">

                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <a class="d-block blur-shadow-image">
                                        <img src="{{ asset('storage') . '/' . $clothing->image }}" alt="img-blur-shadow"
                                            class="img-fluid shadow border-radius-lg w-100" style="height:300px;">
                                    </a>
                                    <div class="colored-shadow"
                                        style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <div class="d-flex mt-n6 mx-auto">
                                        {{-- <form name="delete-clothing{{ $clothing->id }}"
                                            id="delete-clothing{{ $clothing->id }}" method="post"
                                            action="{{ url('/delete-clothing/' . $clothing->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-clothing{{ $clothing->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar esta prenda?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Delete">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/edit-clothing') . '/' . $clothing->id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="material-icons text-lg">edit</i>
                                        </a> --}}
                                    </div>
                                    <h5 class="font-weight-normal mt-3">
                                        <a href="{{url('detail-clothing/' . $clothing->id . '/' .$clothing->category_id)}}">{{ $clothing->name }}</a>
                                    </h5>
                                    <p class="mb-0">
                                        {{ $clothing->description }}
                                    </p>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer d-flex">
                                    <p class="font-weight-normal my-auto">Precio: ₡{{ number_format($clothing->price) }}
                                    </p>
                                    <i
                                        class="material-icons position-relative ms-auto text-lg me-1 my-auto">electric_bolt</i>
                                   
                                    <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                                    <p class="text-sm my-auto"> Stock: {{ $clothing->stock }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
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
    </script>
@endsection
