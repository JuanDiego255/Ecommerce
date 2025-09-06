@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('/update-user-info/' . $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar Profesional') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Nombre') }}</label>
                                    <input required value="{{ $user->name }}" type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>                          
                        </div>
                        @if ($user->image)
                            <img class="img-fluid img-thumbnail" src="{{ route('file', $user->image) }}"
                                style="width: 150px; height:150px;" alt="image">
                        @endif
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <textarea id="editor" type="text" class="form-control form-control-lg" name="body"
                                    placeholder="Descripción del blog">{{ $user->body }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-accion">{{ __('Editar Profesional') }}</button>
                        </div>


                    </div>
                </div>
            </div>
          
        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('user-info') }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
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
