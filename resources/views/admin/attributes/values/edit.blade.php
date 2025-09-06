@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/value/' . $value->id . '/' .$attr_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar valor') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Atributo') }}</label>
                                    <input value="{{ $value->value }}" required type="text"
                                        class="form-control form-control-lg" name="value">
                                </div>
                            </div>                           
                        </div>

                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-accion">{{ __('Editar valor') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('attribute-values/'.$attr_id) }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
