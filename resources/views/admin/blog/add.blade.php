@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('blog/') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Agregar Nuevo Blog</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Título (Se muestra al inicio del blog)') }}</label>
                                    <input required type="text" class="form-control form-control-lg" name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Título 2 Opcional(Se muestra cerca del inicio de la redacción del blog)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="title_optional">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre URL (Este nombre se mostrará en la url del blog)') }}</label>
                                    <input required type="text" class="form-control form-control-lg" name="name_url">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Video URL Opcional(adjuntar link You Tube)') }}</label>
                                        <input type="text" class="form-control form-control-lg" name="video_url">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">

                                    <div class="input-group input-group-static">
                                        <label>Profesionales</label>
                                        <select id="personal_id" name="personal_id"
                                            class="form-control form-control-lg @error('personal_id') is-invalid @enderror"
                                            autocomplete="personal_id" autofocus>
                                            <option selected value="0">
                                                Sin profesional
                                            </option>
                                            @foreach ($profesionals as $item)
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
                            @endif
                            <div class="col-md-12 mb-3">
                                <label>{{ __('Imagen') }}</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input required class="form-control" type="file" name="image">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>{{ __('Video del blog (opcional)') }}</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input class="form-control" type="file" name="video_file"
                                        accept="video/mp4,video/webm,video/x-matroska">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>{{ __('Imagen Horizontal') }}</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input required class="form-control" type="file" name="horizontal_images">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <textarea id="editor" type="text" class="form-control form-control-lg" name="body"
                                        placeholder="Descripción del blog"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <textarea id="editor_note" type="text" class="form-control form-control-lg" name="note"
                                        placeholder="Nota del blog"></textarea>
                                </div>
                            </div>
                            @if (
                                (isset($tenantinfo->tenant) && $tenantinfo->tenant == 'avelectromecanica') ||
                                    (isset($tenantinfo->tenant) && $tenantinfo->tenant == 'aclimate'))
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Es un proyecto?') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_project"
                                            name="is_project" {{ old('is_project') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1"> Es un proyecto?</label>
                                    </div>
                                </div>
                            @endif
                        </div>


                        <div class="col-md-12">
                            <button type="submit" class="btn btn-velvet"> {{ __('Agregar blog') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog/indexadmin') }}" class="btn btn-velvet w-25"> {{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    fontSize: {
                        options: [
                            'tiny',
                            'default',
                            'big'
                        ]
                    },
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
                            },
                            {
                                model: 'headingFancy',
                                view: {
                                    name: 'p',
                                },
                                title: 'Párrafo 28px',
                                class: 'ck-heading_paragraph-p28',

                                // It needs to be converted before the standard 'heading2'.
                                converterPriority: 'high'
                            }
                        ]
                    },
                    ckfinder: {
                        uploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}"
                    },

                })
                .catch(error => {
                    console.log(error);
                });

            ClassicEditor
                .create(document.querySelector('#editor_note'), {
                    fontSize: {
                        options: [
                            'tiny',
                            'default',
                            'big'
                        ]
                    },
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
                            },
                            {
                                model: 'headingFancy',
                                view: {
                                    name: 'p',
                                },
                                title: 'Párrafo 28px',
                                class: 'ck-heading_paragraph-p28',

                                // It needs to be converted before the standard 'heading2'.
                                converterPriority: 'high'
                            }
                        ]
                    },
                    ckfinder: {
                        uploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}"
                    },

                })
                .catch(error => {
                    console.log(error);
                });
        });
    </script>
@endsection
