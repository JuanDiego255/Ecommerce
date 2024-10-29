@extends('layouts.frontrent')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- Main Banner --}}
    <div class="hero-wrap ftco-degree-bg"
        style="background-image: url('{{ isset($tenantcarousel[0]->image) ? route('file', $tenantcarousel[0]->image) : url('images/producto-sin-imagen.PNG') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">Formulario de registro</h1>
                        <p style="font-size: 18px;">Al formar parte de AUTOS GRECIA puede recibir noticias, sobre nuevas
                            tendencias y demás.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Contact form --}}
    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-12	featured-top">
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center">
                            <form class="request-form ftco-animate bg-primary" method="POST" action="{{ route('register') }}">
                                <h2>Formulario de inicio de sesión. Ingresa a nuestro sistema</h2>
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label">Nombre Completo</label>
                                            <input value="{{ old('name') }}" autocomplete="name" required
                                                type="name" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label">Teléfono</label>
                                            <input value="{{ old('telephone') }}" autocomplete="telephone" required
                                                type="telephone" name="telephone" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label">E-mail</label>
                                            <input value="{{ old('email') }}" autocomplete="email" required
                                                type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label">Clave</label>
                                            <input value="{{ old('password') }}" required
                                                autocomplete="current-password" type="password" name="password"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="label">Confirmar Clave</label>
                                            <input id="password-confirm" required autocomplete="new-password"
                                                type="password" name="password_confirmation"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Registrarse" class="btn btn-secondary py-3 px-4">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="services-wrap rounded-right w-100">
                                <h3 class="heading-section mb-1">¿Qué consigues al registrarte?</h3>
                                <div class="row d-flex mb-1">
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-rent"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Ofertas</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-handshake"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Promociones</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-suv"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2">Vehículos Nuevos</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    @include('layouts.inc.carsale.footer')
@endsection
