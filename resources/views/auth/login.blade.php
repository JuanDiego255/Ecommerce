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
                                    <input value="{{ old('email') }}" autocomplete="email" required type="email" name="email"
                                        class="form-control float-left w-100">
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="input-group input-group-static mb-4">
                                    <label>Contraseña</label>
                                    <input value="{{ old('password') }}"  required autocomplete="current-password"
                                     type="password" name="password"
                                        class="form-control float-left w-100">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-info">
                                        {{ __('Ingresar') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Olvidó su contraseña?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
