@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('blog/more-info/' . $blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Agregar Nuevo Artículo</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <label class="form-label">Artículo</label>
                                    <input id="title" required type="text" class="form-control form-control-lg"
                                        name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <label class="form-label">Meta Keywords (Opcional)</label>
                                    <input type="text" class="form-control form-control-lg" name="meta_keywords">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <label class="form-label">Meta description (Opcional)</label>
                                    <input type="text" class="form-control form-control-lg" name="meta_description">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <textarea id="editor" type="text" class="form-control form-control-lg" name="context"
                                        placeholder="Descripción del artículo"></textarea>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12">
                            <button type="submit" class="btn btn-velvet">Agregar artículo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog/'.$blog_id.'/show') }}" class="btn btn-velvet w-25">Volver</a>
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
                    },
                    ckfinder: {
                        uploadUrl: "{{ route('upload', ['_token' => csrf_token()]) }}"
                    },
                    
                    fontSize: {
                        options: [9, 10, 11, 12, 14, 16, 18, 20, 22, 24]
                    },
                })
                .catch(error => {
                    console.log(error);
                });
        });
    </script>
@endsection
