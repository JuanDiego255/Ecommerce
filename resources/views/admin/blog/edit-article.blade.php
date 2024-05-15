@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('tag/' . $tag->id . '/' . $tag->blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Editar Artículo</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Artículo</label>
                                <div class="input-group input-group-static mb-4">

                                    <input value="{{ $tag->title }}" id="title" required type="text"
                                        class="form-control form-control-lg" name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Keywords (Opcional)</label>
                                <div class="input-group input-group-static mb-4">

                                    <input value="{{ $tag->meta_keywords }}" type="text"
                                        class="form-control form-control-lg" name="meta_keywords">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta description (Opcional)</label>
                                <div class="input-group input-group-static mb-4">

                                    <input value="{{ $tag->meta_description }}" type="text"
                                        class="form-control form-control-lg" name="meta_description">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <textarea id="editor" type="text" class="form-control form-control-lg" name="context"
                                        placeholder="Descripción del artículo">{{ $tag->context }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <button type="submit" class="btn btn-velvet">Editar artículo</button>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog/' . $tag->blog_id . '/show') }}" class="btn btn-velvet w-25">Volver</a>
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
        });
    </script>
@endsection
