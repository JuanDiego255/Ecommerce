@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">{{ __('Editar categoría') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('update-category' . '/' . $categories->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>{{ __('Categoría') }}</label>
                            <input required value="{{ $categories->name }}" type="text"
                                class="form-control form-control-lg" name="name">
                        </div>
                    </div>
                    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Meta title (Opcional)') }}</label>
                                <input type="text" value="{{ $categories->meta_title }}"
                                    class="form-control form-control-lg" name="meta_title">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Meta description (Opcional)') }}</label>
                                <input type="text" value="{{ $categories->meta_descrip }}"
                                    class="form-control form-control-lg" name="meta_descrip">
                            </div>
                        </div>
                    @endif

                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-dynamic">
                            <textarea required spellcheck="false" placeholder="Escriba aquí la descripción..." name="description"
                                class="form-control" rows="3">{{ $categories->description }}</textarea>
                        </div>
                    </div>
                    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="col-md-12 mb-3">

                            <label>{{ __('Meta Keywords (Opcional - Presione enter para agregar la palabra clave)') }}</label><br>
                            <div class="tags-input">
                                <ul id="tags"></ul>
                                <input type="text" id="input-tag" placeholder="Escriba la palabra clave.." />
                            </div>
                            <input id="meta_keywords" type="hidden" name="meta_keywords"
                                value="{{ $categories->meta_keywords }}">

                        </div>
                    @endif
                    <input type="hidden" value="0" id="status" name="status">
                    <input type="hidden" value="0" id="popular" name="popular">

                    @if ($categories->image)
                        <img class="img-fluid img-thumbnail" src="{{ route('file', $categories->image) }}"
                            style="width: 150px; height:150px;" alt="image">
                    @endif
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                    @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Promocionar categoría?') }}</label>
                            <div class="form-check">
                                <input {{ $categories->black_friday == 1 ? 'checked' : '' }} class="form-check-input"
                                    type="checkbox" value="1" id="black_friday" name="black_friday">
                                <label class="custom-control-label" for="customCheck1">{{ __('Promocionar categoría') }}</label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-velvet">{{ __('Editar categoría') }}</button>
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
    <script src="{{ asset('js/edit-tag.js') }}"></script>
@endsection
