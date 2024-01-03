@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <section class="text-center text-lg-start">
        <style>
            .cascading-right {
                margin-right: -50px;
            }

            @media (max-width: 991.98px) {
                .cascading-right {
                    margin-right: 0;
                }
            }
        </style>

        <!-- Jumbotron -->
        <div class="container py-4">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="card cascading-right card-login">
                        <div class="card-body p-5 shadow-5 text-center">
                            <h4 class="fw-bold mb-5">Ingresa ahora</h4>
                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>E-mail</label>
                                            <input value="{{ old('email') }}" autocomplete="email" required type="email"
                                                name="email" class="form-control float-left w-100">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Clave</label>
                                            <input value="{{ old('password') }}" required autocomplete="current-password"
                                                type="password" name="password" class="form-control float-left w-100">
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-12">
                                            <center>
                                                <button type="submit" class="btn btn-info">
                                                    {{ __('Ingresar') }}
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
                                        <i class="fa fa-facebook"></i>
                                    </a>

                                    <a href="{{ url('/google-auth/redirect') }}" class="m-5 text-google">
                                        <i class="fa fa-google"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="{{url('images/login.PNG');}}" class="w-100 rounded-4 shadow-4"
                        alt="" />
                </div>
            </div>
        </div>
        <!-- Jumbotron -->
    </section>
@endsection
