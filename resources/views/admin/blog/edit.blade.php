@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/blog/' . $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Editar Blog</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Título (Se muestra al inicio del blog)') }}</label>
                                    <input required value="{{ $blog->title }}" type="text"
                                        class="form-control form-control-lg" name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Título 2 Opcional(Se muestra cerca del inicio de la redacción del blog)') }}</label>
                                    <input type="text" value="{{ $blog->title_optional }}"
                                        class="form-control form-control-lg" name="title_optional">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre URL (Este nombre se mostrará en la url del blog)') }}</label>
                                    <input required value="{{ $blog->name_url }}" type="text"
                                        class="form-control form-control-lg" name="name_url">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">

                                <div class="input-group input-group-static">
                                    <label>Profesionales</label>
                                    <select id="personal_id" name="personal_id"
                                        class="form-control form-control-lg @error('personal_id') is-invalid @enderror"
                                        autocomplete="personal_id" autofocus>
                                        <option selected value="{{ $blog->personal_id }}">
                                            {{ $blog->name }}
                                        </option>
                                        @foreach ($profesionals as $item)
                                            <option value="0">Sin profesional</option>
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('personal_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 mb-3">
                            @if ($blog->image)
                                <img class="img-fluid img-thumbnail" src="{{ route('file', $blog->image) }}"
                                    style="width: 150px; height:150px;" alt="image">
                            @endif
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            @if ($blog->horizontal_images)
                                <img class="img-fluid img-thumbnail" src="{{ route('file', $blog->horizontal_images) }}"
                                    style="width: 150px; height:150px;" alt="image">
                            @endif
                            <label>{{ __('Imagen Horizontal') }}</label>
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="horizontal_images">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <textarea id="editor" type="text" class="form-control form-control-lg" name="body"
                                    placeholder="Descripción del blog">{{ $blog->body }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-velvet">{{ __('Editar Blog') }}</button>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog/indexadmin') }}" class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Párrafo',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Título',
                                class: 'ck-heading_heading1'
                            }
                        ]
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        });
    </script>
@endsection
