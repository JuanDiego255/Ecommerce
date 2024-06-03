@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">{{ __('Agregar categoría') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('insert-category') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="department_id" value="{{ $id }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">{{ __('Categoría (*)') }}</label>
                            <input required type="text" class="form-control form-control-lg" name="name">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">{{ __('Meta title (Opcional)') }}</label>
                            <input type="text" class="form-control form-control-lg" name="meta_title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">{{ __('Meta description (Opcional)') }}</label>
                            <input type="text" class="form-control form-control-lg" name="meta_descrip">
                        </div>
                    </div>

                    {{-- <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Slug</label>
                            <input required type="text" class="form-control form-control-lg" name="slug">
                        </div>
                    </div> --}}
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-dynamic">
                            <label class="form-label">{{ __('Descripción (*)') }}</label><br>
                            <textarea required spellcheck="false" placeholder="Descripción" name="description" class="form-control" rows="3">

                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label
                            class="form-label">{{ __('Meta Keywords (Opcional - Presione enter para agregar la palabra
                                                        clave)') }}</label><br>
                        <div class="tags-input">
                            <ul id="tags"></ul>
                            <input type="text" id="input-tag" placeholder="Escriba la palabra clave.." />
                            <input type="hidden" value="" id="meta_keywords" name="meta_keywords">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <input required class="form-control" type="file" name="image">
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
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
                    </div> --}}

                </div>
                <input type="hidden" value="0" id="status" name="status">
                <input type="hidden" value="0" id="status" name="popular">

                <div class="col-md-6">
                    <button type="submit" class="btn btn-velvet">{{ __('Agregar categoría') }}</button>
                </div>

            </form>
        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('categories') }}" class="btn btn-velvet">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/add-tag.js') }}"></script>
@endsection
