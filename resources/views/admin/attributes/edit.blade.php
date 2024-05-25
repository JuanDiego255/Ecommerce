@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/attribute/' . $attr->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar atributo') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Atributo') }}</label>
                                    <input value="{{ $attr->name }}" required type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">

                                <div class="input-group input-group-static">
                                    <label>{{ __('Estilo del atributo') }}</label>
                                    <select id="type" name="type"
                                        class="form-control form-control-lg @error('type') is-invalid @enderror"
                                        autocomplete="type" autofocus>
                                        <option selected value="{{ $attr->type }}">
                                            @switch($attr->type)
                                                @case(0)
                                                    {{ __('Botón simple') }}
                                                @break

                                                @case(1)
                                                    {{ __('Seleccionador') }}
                                                @break
                                            @endswitch
                                        </option>
                                        <option value="0">
                                            {{ __('Botón simple') }}
                                        </option>
                                        <option value="1">
                                            {{ __('Seleccionador') }}
                                        </option>

                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-velvet">{{ __('Editar atributo') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('attributes/') }}" class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
