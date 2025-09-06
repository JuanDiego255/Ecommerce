@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('blog/result/'.$blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Nuevo resultado') }}</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">                           
                           
                            <div class="col-md-6 mb-3">
                                <label>{{ __('Imagen Antes') }}</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input required class="form-control" type="file" name="before_image">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>{{ __('Imagen Después') }}</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input required class="form-control" type="file" name="after_image">
                                </div>
                            </div>

                           
                        </div>


                        <div class="col-md-12">
                            <button type="submit" class="btn btn-accion"> {{ __('Agregar resultado') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('results/'.$blog_id) }}" class="btn btn-accion w-25"> {{ __('Volver') }}</a>
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
