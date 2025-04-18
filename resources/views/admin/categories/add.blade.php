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
                            <input value="{{ old('name') }}" required type="text" class="form-control form-control-lg"
                                name="name">
                        </div>
                    </div>
                    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <label class="form-label">{{ __('Meta title (Opcional)') }}</label>
                                <input value="{{ old('meta_title') }}" type="text" class="form-control form-control-lg"
                                    name="meta_title">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <label class="form-label">{{ __('Meta description (Opcional)') }}</label>
                                <input value="{{ old('meta_descrip') }}" type="text" class="form-control form-control-lg"
                                    name="meta_descrip">
                            </div>
                        </div>
                    @endif

                    {{-- <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Slug</label>
                            <input required type="text" class="form-control form-control-lg" name="slug">
                        </div>
                    </div> --}}
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <textarea id="editor" type="text" class="form-control form-control-lg" name="description"
                                placeholder="Descripción de la categoría">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
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
                    @endif
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
                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                    <div class="col-md-12 mb-3">
                        <label>{{ __('Promocionar categoría?') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="black_friday"
                                name="black_friday" {{ old('black_friday') ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="customCheck1">{{ __('Promocionar categoría') }}</label>
                        </div>
                    </div>
                @endif
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
    <script>
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
    <script src="{{ asset('js/add-tag.js') }}"></script>
@endsection
