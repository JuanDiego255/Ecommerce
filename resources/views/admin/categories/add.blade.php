@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">Agregar categoría</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('insert-category') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" class="form-control form-control-lg" name="name">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control form-control-lg" name="slug">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-dynamic">
                            <label class="form-label">Descripción</label><br>
                            <textarea spellcheck="false" placeholder="Descripción" name="description" class="form-control" rows="3">

                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="status" name="status">
                            <label class="custom-control-label" for="customCheck1">Estado</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="popular" name="popular">
                            <label class="custom-control-label" for="customCheck1">Popular</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta Title</label>
                            <input type="text" class="form-control form-control-lg" name="meta_title">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta description</label>
                            <input type="text" class="form-control form-control-lg" name="meta_descrip">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control form-control-lg" name="meta_keywords">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <button type="submit" class="btn btn-velvet">Agregar Categoría</button>
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
