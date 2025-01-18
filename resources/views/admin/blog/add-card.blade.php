@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('blog/add-card/' . $blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Agregar nueva tarjeta</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <label class="form-label">Tarjeta</label>
                                    <input id="title" required type="text" class="form-control form-control-lg"
                                        name="title">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <label class="form-label">{{ __('Descripción') }}</label>
                                    <input id="description" required type="text" class="form-control form-control-lg"
                                        name="description">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-lg input-group-outline my-3">
                                        <label class="form-label">Descripción 2(Opcional)</label>
                                        <input id="opcional_description" type="text" class="form-control form-control-lg"
                                            name="opcional_description">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-3">
                                <label>Imagen</label>
                                <div class="input-group input-group-lg input-group-outline my-3">
                                    <input required class="form-control" type="file" name="image">
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12">
                            <button type="submit" class="btn btn-velvet">Agregar tarjeta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('blog-cards/' . $blog_id . '/view-cards') }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
