@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Iniciar Sesión') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="col-md-12 mt-2">
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
                    </div>
                </div>
            </div>
        </div>

        <center>
            <div class="mt-3 w-75">

                <a class="btn btn-google w-75 text-center" href="{{ url('/google-auth/redirect') }}">
                    <i class="fa fa-google"></i> Ingresar con Google
                </a>


                <a class="btn btn-facebook w-75 text-center" href="{{ url('/facebook-auth/redirect') }}">
                    <i class="fa fa-facebook"></i> Ingresar con Facebook
                </a>

            </div>

        </center>


    </div>
@endsection
