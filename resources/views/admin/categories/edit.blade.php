@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">Editar categoría</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('update-category' . '/' . $categories->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Categoría (*)</label>
                            <input required value="{{ $categories->name }}" type="text"
                                class="form-control form-control-lg" name="name">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Meta Title (Opcional)</label>
                            <input type="text" value="{{ $categories->meta_title }}" class="form-control form-control-lg"
                                name="meta_title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Meta description (Opcional)</label>
                            <input type="text" value="{{ $categories->meta_descrip }}"
                                class="form-control form-control-lg" name="meta_descrip">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Meta Keywords (Opcional)</label>
                            <input value="{{ $categories->meta_keywords }}" type="text"
                                class="form-control form-control-lg" name="meta_keywords">
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Slug</label>
                            <input value="{{ $categories->slug }}" type="text" class="form-control form-control-lg"
                                name="slug">
                        </div>
                    </div> --}}
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-dynamic">
                            <label class="form-label">Descripción (*)</label><br>
                            <textarea required spellcheck="false" placeholder="Descripción" name="description" class="form-control" rows="3">
                                {{ $categories->description }}
                            </textarea>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-check">
                            <input {{ $categories->status == 1 ? 'checked' : '' }} class="form-check-input"
                                type="checkbox" value="" id="status" name="status">
                            <label class="custom-control-label" for="customCheck1">Estado</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input {{ $categories->popular == 1 ? 'checked' : '' }} class="form-check-input"
                                type="checkbox" value="" id="popular" name="popular">
                            <label class="custom-control-label" for="customCheck1">Popular</label>
                        </div>
                    </div> --}}
                    <input type="hidden" value="0" id="status" name="status">
                    <input type="hidden" value="0" id="status" name="popular">

                    @if ($categories->image)
                        <img class="img-fluid img-thumbnail" src="{{ route('file', $categories->image) }}"
                            style="width: 150px; height:150px;" alt="image">
                    @endif
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-velvet">Editar Categoría</button>
                </div>

            </form>
        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('categories') }}" class="btn btn-velvet">Volver</a>
        </div>
    </center>
@endsection
