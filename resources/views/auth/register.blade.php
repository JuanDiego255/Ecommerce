@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <section class="text-center text-lg-start">
        <!-- Jumbotron -->
        <div class="container py-4">

            <div class="text-center w-100 mb-5 mb-lg-0">
                <center>
                    <div class="card w-75 card-login">
                        <div class="card-body shadow-5 text-center">
                            <h4 class="fw-bold mb-5">Registrarse</h4>
                            <div class="card-body">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Nombre Completo</label>
                                                <input value="{{ old('name') }}" autocomplete="name" required
                                                    type="name" name="name" class="form-control float-left w-100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Teléfono</label>
                                                <input value="{{ old('telephone') }}" autocomplete="telephone" required
                                                    type="telephone" name="telephone" class="form-control float-left w-100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label>E-mail</label>
                                                <input value="{{ old('email') }}" autocomplete="email" required
                                                    type="email" name="email" class="form-control float-left w-100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Clave</label>
                                                <input value="{{ old('password') }}" required
                                                    autocomplete="current-password" type="password" name="password"
                                                    class="form-control float-left w-100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label>Confirmar Clave</label>
                                                <input id="password-confirm" required autocomplete="new-password"
                                                    type="password" name="password_confirmation"
                                                    class="form-control float-left w-100">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mb-0">
                                        <div class="col-md-12">
                                            <center>
                                                <button type="submit" class="btn btn-info">
                                                    {{ __('Registrarse') }}
                                                </button>
                                            </center>
                                        </div>
                                    </div>

                                </form>
                                <div class="divider d-flex align-items-center">
                                    <p class="text-center fw-bold mx-3 text-muted">O INGRESA CON:</p>
                                </div>
                                <div class="text-center">
                                    <a href="{{ url('/facebook-auth/redirect') }}" class="m-5 text-facebook">
                                        <i class="fab fa-facebook"></i>
                                    </a>

                                    <a href="{{ url('/google-auth/redirect') }}" class="m-5 text-google">
                                        <i class="fab fa-google"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </center>

            </div>

        </div>
        <!-- Jumbotron -->
    </section>
@endsection
