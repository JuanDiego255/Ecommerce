@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">Editar Sección</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('/metatags/' . $metatag->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-3">

                        <div class="input-group input-group-static">
                            <label>Sección</label>
                            <select id="section" name="section"
                                class="form-control form-control-lg @error('section') is-invalid @enderror" required
                                autocomplete="section" autofocus>

                                <option value="{{$metatag->section}}" selected>{{$metatag->section}}</option>

                                <option value="Inicio">Inicio
                                </option>
                                <option value="Categorías">Categorías
                                </option>
                                <option value="Categoría Específica">Categoría Específica
                                </option>
                                <option value="Carrito">Carrito
                                </option>
                                <option value="Mis Compras">Mis Compras
                                </option>
                                <option value="Checkout">Checkout </option>
                                <option value="Registrarse">Registrarse </option>
                                <option value="Ingresar">Ingresar </option>

                            </select>
                            @error('section')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div  class="input-group input-group-lg input-group-outline {{ isset($metatag->title) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Title</label>
                            <input value="{{$metatag->title}}" required type="text" class="form-control form-control-lg" name="title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->meta_keywords) ? 'is-filled' : '' }} my-3"">
                            <label class="form-label">Meta Keywords</label>
                            <input value="{{$metatag->meta_keywords}}" required type="text" class="form-control form-control-lg" name="meta_keywords">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->meta_description) ? 'is-filled' : '' }} my-3"">
                            <label class="form-label">Meta description</label>
                            <input value="{{$metatag->meta_description}}" required type="text" class="form-control form-control-lg" name="meta_description">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->meta_og_title) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Meta OG Title</label>
                            <input value="{{$metatag->meta_og_title}}" required type="text" class="form-control form-control-lg" name="meta_og_title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->meta_og_description) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Meta OG description</label>
                            <input value="{{$metatag->meta_og_description}}" required type="text" class="form-control form-control-lg" name="meta_og_description">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->url_canonical) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">URL Canonical</label>
                            <input value="{{$metatag->url_canonical}}" type="text" class="form-control form-control-lg" name="url_canonical">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->url_image_og) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">OG Image</label>
                            <input value="{{$metatag->url_image_og}}" type="text" class="form-control form-control-lg" name="url_image_og">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline {{ isset($metatag->meta_type) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Meta Type</label>
                            <input value="{{$metatag->meta_type}}" required type="text" class="form-control form-control-lg" name="meta_type">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-velvet">Editar Sección</button>
                </div>

            </form>
        </div>
    </div>
@endsection
