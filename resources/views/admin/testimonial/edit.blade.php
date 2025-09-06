@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/comments/' . $comment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="approve" value="1">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar Testimonio') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre') }}</label>
                                    <input required value="{{ $comment->name }}" type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>{{ __('Rating') }}</label>
                                <div class="input-group input-group-static mb-4">
                                    <div class="rate">
                                        <input type="radio" {{$comment->stars == 5 ? 'checked' : ''}} id="star5" class="rate" name="rating" value="5" />
                                        <label for="star5" title="text">5 stars</label>
                                        <input type="radio" {{$comment->stars == 4 ? 'checked' : ''}} id="star4" class="rate" name="rating"
                                            value="4" />
                                        <label for="star4" title="text">4 stars</label>
                                        <input type="radio" {{$comment->stars == 3 ? 'checked' : ''}} id="star3" class="rate" name="rating" value="3" />
                                        <label for="star3" title="text">3 stars</label>
                                        <input type="radio" {{$comment->stars == 2 ? 'checked' : ''}} id="star2" class="rate" name="rating" value="2">
                                        <label for="star2" title="text">2 stars</label>
                                        <input type="radio" {{$comment->stars == 1 ? 'checked' : ''}} id="star1" class="rate" name="rating" value="1" />
                                        <label for="star1" title="text">1 star</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <textarea type="text" class="form-control form-control-lg" name="description" placeholder="Descripción del blog">{{ $comment->description }}</textarea>
                            </div>
                        </div>
                        @if ($comment->image)
                            <img class="img-fluid img-thumbnail" src="{{ route('file', $comment->image) }}"
                                style="width: 150px; height:150px;" alt="image">
                        @endif
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-accion">{{ __('Editar Testimonio') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('comments') }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
