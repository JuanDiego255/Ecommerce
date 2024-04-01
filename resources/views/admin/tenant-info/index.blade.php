@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @include('admin.tenant-info.add')
    @include('admin.tenant-info.social-modal')
    @include('admin.tenant-info.carousel-modal')
    <div class="container">

        <h2 class="text-center font-title"><strong>Administra la información de tu negocio.</strong>
        </h2>

        <hr class="hr-servicios">

        @if ($tenantinfo->isEmpty())
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-tenant-info-modal" class="btn btn-velvet">Agregar
                Información</button>
        @else
            @foreach ($tenantinfo as $item)
                @include('admin.tenant-info.edit')
                <button type="button" data-bs-toggle="modal" data-bs-target="#edit-tenant-info-modal{{ $item->id }}"
                    class="btn btn-velvet">Editar
                    Información</button>
            @endforeach
        @endif

        <center>
            @foreach ($tenantinfo as $item)
                <hr class="dark horizontal text-danger mb-3">
                <div class="text-center">
                    <center>
                        <div class="row w-25">
                            @if ($item->logo)
                                <div class="col-md-6">
                                    <a href="{{ route('file', $item->logo) }}" target="_blank" rel="noopener noreferrer">
                                        <img loading="lazy" style="width: 100px; height:100px;"
                                            class="img-fluid img-thumbnail" src="{{ route('file', $item->logo) }}"
                                            alt="image">
                                    </a><br>
                                    <span class="text-s">Logo</span>
                                </div>
                            @endif
                            @if ($item->login_image)
                                <div class="col-md-6">
                                    <a href="{{ route('file', $item->login_image) }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <img loading="lazy" style="width: 100px; height:100px;"
                                            class="img-fluid img-thumbnail" src="{{ route('file', $item->login_image) }}"
                                            alt="image">
                                    </a><br>
                                    <span class="text-s">Imagen Login</span>
                                </div>
                            @endif
                        </div>
                    </center>

                </div>

                <hr class="dark horizontal text-danger mb-3">
                <div class="flex3 text-center" id="siteBrand">
                    {{ $item->title }}
                </div>
                <span class="text-s">Sección: Negocio, con este nombre te verán tus clientes</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <h3 class="text-center text-muted">{{ $item->title_discount }}</h3>
                </div>
                <span class="text-s">Sección: Productos en descuento, esta descripción se mostrará en la sección de
                    descuentos del cliente en la página principal</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <span class="text-muted text-center"><a href="#">Instagram</a> |
                        {{ $item->title_instagram }}</span>
                </div>
                <span class="text-s">Sección: Instagram, esta descripción se mostrará en la sección de Instagram en la
                    página principal</span>
                <hr class="dark horizontal text-danger">
                <div class="bg-footer p-3 text-center">
                    <h3 class="text-center text-title">{{ $item->title }}</h3>
                    <span class="text-center text-muted">{{ $item->mision }}</span>
                </div>
                <span class="text-s">Sección: misión de la empresa, esta misión se mostrará en la sección de Misión en la
                    página principal</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <h3 class="text-center text-muted">{{ $item->title_trend }}</h3>
                </div>
                <span class="text-s">Sección: Productos en tendencia, esta descripción se mostrará en la sección de
                    artículos en tendencia en la página principal</span>
                <hr class="dark horizontal text-danger mb-3">
                <span class="text-s">Sección: Carrusel, se mostrará al principio del Inicio de la página.<a href="#"
                        data-bs-toggle="modal" data-bs-target="#add-tenant-carousel-modal"><i
                            class="fa fa-plus me-3"></i></a></span>
                @if (count($tenantcarousel) != 0)
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner mb-4">
                            @foreach ($tenantcarousel as $key => $carousel)
                                @include('admin.tenant-info.carousel-modal-edit')
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="page-header min-vh-75 m-3 lazy-background"
                                        data-background="{{ tenant_asset('/') . '/' . $carousel->image }}"
                                        style="background-image: url('{{ tenant_asset('/') . '/' . $carousel->image }}');">
                                        <span class="mask bg-gradient-dark"></span>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-6 my-auto">
                                                    <h4 class="text-white mb-0 fadeIn1 fadeInBottom">{{ $carousel->text1 }}
                                                    </h4>
                                                    <h1 class="text-white fadeIn2 fadeInBottom">{{ $carousel->text2 }}</h1>
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#edit-tenant-carousel-modal{{ $carousel->id }}"
                                                        class="mr-5 text-white" href="#">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form id="deleteFormCarousel{{ $carousel->id }}" method="post"
                                                        action="{{ url('/delete/tenant-carousel/' . $carousel->id) }}"
                                                        style="display:inline">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <a href="#" class="mr-5 text-white"
                                                            onclick="confirmAndSubmitCar({{ $carousel->id }})"
                                                            style="text-decoration: none;"><i
                                                                class="fa fa-minus-circle me-3"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="min-vh-75 position-absolute w-100 top-0">
                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon position-absolute bottom-50"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon position-absolute bottom-50"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        </div>
                    </div>
                @endif
                <hr class="dark horizontal text-danger">
                <center>
                    <div class="container-fluid bg-footer">

                        <div class="row mt-5 pt-5">

                            <div class="col-md-4">
                                <h5 class="text-uppercase text-muted-title">Redes Sociales <a href="#"
                                        data-bs-toggle="modal" data-bs-target="#add-tenant-social-modal"><i
                                            class="fa fa-plus me-3"></i></a></h5>
                                <div>
                                    <p class="text-muted text-uppercase text-lg">
                                        @foreach ($tenantsocial as $social)
                                            @include('admin.tenant-info.social-modal-edit')
                                            @php
                                                $social_logo = null;
                                                if (stripos($social->social_network, 'Facebook') !== false) {
                                                    $social_logo = 'fa fa-facebook';
                                                } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                                    $social_logo = 'fa fa-instagram';
                                                }
                                                if (stripos($social->social_network, 'Twitter') !== false) {
                                                    $social_logo = 'fa fa-twitter';
                                                }
                                            @endphp
                                            <a data-bs-toggle="modal"
                                                data-bs-target="#edit-tenant-social-modal{{ $social->id }}"
                                                class="mr-5 text-muted" href="#">
                                                <i class="{{ $social_logo }}"> {{ $social->social_network }} <i
                                                        class="fa fa-edit"></i></i>
                                            </a>
                                            <form id="deleteForm{{ $social->id }}" method="post"
                                                action="{{ url('/delete/tenant-social/' . $social->id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <a href="#" class="mr-5 text-muted"
                                                    onclick="confirmAndSubmit({{ $social->id }})"
                                                    style="text-decoration: none;"><i class="fa fa-minus-circle me-3"></i>
                                                </a>
                                            </form><br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 mt-5">

                                <h5 class="text-uppercase">Queremos conocerte <i class="fa fa-heart"></i></h5>
                                <div>
                                    <p class="text-muted text-uppercase text-lg">
                                        <a style="text-decoration: none;" class="mr-5 text-muted text-lg"
                                            href="{{ route('register') }}">
                                            <i class="fa fa-envelope"></i>{{ $item->title_suscrib_a }}
                                        </a>
                                    </p>
                                    <span class="text-s">Sección: Título suscripción</span>
                                    <p class="text-muted text-uppercase text-lg">
                                        <i class="fas fa-percentage"></i>{{ $item->description_suscrib }}
                                    </p>
                                    <span class="text-s">Sección: Descripción suscripción</span>


                                </div>


                            </div>
                            <div class="col-md-4">
                                <h5 class="text-uppercase text-muted-title">Más Información!</h5>
                                <div>
                                    <p class="text-muted text-uppercase text-lg">
                                        <a style="text-decoration: none;" class="mr-5 text-muted" href="#">
                                            <i class="fa fa-envelope"></i> Envíos por correos de C.R
                                        </a><br>
                                        <a href="#" class="text-muted">
                                            <i class="fa fa-whatsapp"> {{ $item->whatsapp }}</i>
                                        </a>
                                        <a href="#" class="text-muted">
                                            <i class="fa fa-envelope"> {{ $item->email }}</i>
                                        </a>

                                    </p>
                                    <span class="text-s">Este correo es donde llegarán las notificaciones de las compras
                                        realizadas</span>
                                </div>
                            </div>

                        </div>
                        <hr class="dark horizontal text-danger my-0 mt-2">
                        <div class="copyright text-center text-lg text-muted pb-4 text-uppercase">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            <a href="#" class="font-weight-bold" target="_blank">{{ $item->title }}</a>
                            {{ $item->footer }}

                        </div>
                        <span class="text-s">Sección: Pie de página</span>
                    </div>


                </center>
            @endforeach

        </center>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        function confirmAndSubmit(id) {

            if (confirm('¿Deseas borrar esta red social?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }

        function confirmAndSubmitCar(id) {

            if (confirm('¿Deseas borrar esta imagen del carousel?')) {
                document.getElementById('deleteFormCarousel' + id).submit();
            }
            document.addEventListener("DOMContentLoaded", function() {
                        var lazyBackgrounds = document.querySelectorAll('.lazy-background');

                        lazyBackgrounds.forEach(function(background) {
                            var imageUrl = background.getAttribute('data-background');
                            background.style.backgroundImage = 'url(' + imageUrl + ')';
                        });
    </script>
@endsection
