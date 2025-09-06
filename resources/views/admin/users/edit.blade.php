@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/user/' . $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar usuario') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre') }}</label>
                                    <input value="{{ $user->name }}" required type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Teléfono') }}</label>
                                    <input value="{{ $user->telephone }}" required type="text"
                                        class="form-control form-control-lg" name="telephone">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('E-mail') }}</label>
                                    <input value="{{ $user->email }}" required type="text"
                                        class="form-control form-control-lg" name="email">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Código (Favoritos)') }}</label>
                                    <input value="{{ $user->code_love }}" required type="text"
                                        class="form-control form-control-lg" name="code">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">

                                <div class="input-group input-group-static">
                                    <label>Roles</label>
                                    <select id="role_id" name="role_id"
                                        class="form-control form-control-lg @error('role_id') is-invalid @enderror"
                                        autocomplete="role_id" autofocus>
                                        <option selected value="{{ $user->role_as }}">
                                            @if ($user->role_as == 1)
                                                Admin
                                            @else
                                                Usuario
                                            @endif
                                        </option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->rol }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-accion">{{ __('Editar usuario') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('users/') }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
