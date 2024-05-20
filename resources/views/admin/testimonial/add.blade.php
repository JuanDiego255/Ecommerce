@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('comments/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Nuevo testimonio') }}</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre') }}</label>
                                    <input required type="text" class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>{{ __('Rating') }}</label>
                                <div class="input-group input-group-static mb-4">
                                    <div class="rate">
                                        <input type="radio" checked id="star5" class="rate" name="rating" value="5" />
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" id="star4" class="rate" name="rating"
                                            value="4" />
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" id="star3" class="rate" name="rating" value="3" />
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" id="star2" class="rate" name="rating" value="2">
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" id="star1" class="rate" name="rating" value="1" />
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>{{ __('Descripción') }}</label>
                            <div class="input-group input-group-static mb-4">
                                <textarea type="text" class="form-control form-control-lg" name="description"
                                    placeholder="Descripción del testimonio"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Imagen') }}</label>
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-velvet"> {{ __('Agregar Testimonio') }}</button>
                        </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </form>

    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('comments/') }}" class="btn btn-velvet w-25"> {{ __('Volver') }}</a>
        </div>
    </center>
@endsection
