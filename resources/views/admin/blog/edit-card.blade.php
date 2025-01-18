@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('card/' . $card->id . '/' . $card->blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Editar Tarjeta</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Título') }}</label>
                                <div class="input-group input-group-static mb-4">

                                    <input value="{{ $card->title }}" id="title" required type="text"
                                        class="form-control form-control-lg" name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Descripción') }}</label>
                                <div class="input-group input-group-static mb-4">

                                    <input value="{{ $card->description }}" id="description" required type="text"
                                        class="form-control form-control-lg" name="description">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Descripción 2 (Opcional)</label>
                                    <div class="input-group input-group-static mb-4">

                                        <input value="{{ $card->opcional_description }}" id="opcional_description"
                                            type="text" class="form-control form-control-lg" name="opcional_description">
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 mb-3">
                                @if ($card->image)
                                    <img class="img-fluid img-thumbnail" src="{{ route('file', $card->image) }}"
                                        style="width: 150px; height:150px;" alt="image">
                                @endif
                                <div class="input-group input-group-static mb-4">
                                    <input class="form-control" type="file" name="image">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <button type="submit" class="btn btn-velvet">Editar tarjeta</button>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog-cards/' . $card->blog_id . '/view-cards') }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
