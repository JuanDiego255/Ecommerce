@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">Agregar Metatag</h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('metatag') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">

                        <div class="input-group input-group-static">
                            <label>Sección</label>
                            <select id="section" name="section"
                                class="form-control form-control-lg @error('section') is-invalid @enderror" required
                                autocomplete="section" autofocus>

                                <option value="Inicio" selected>Inicio</option>

                                <option value="Inicio">Inicio
                                </option>
                                <option value="Categorias">Categorías
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
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Title</label>
                            <input required type="text" class="form-control form-control-lg" name="title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta Keywords</label>
                            <input required type="text" class="form-control form-control-lg" name="meta_keywords">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta description</label>
                            <input required type="text" class="form-control form-control-lg" name="meta_description">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta OG Title</label>
                            <input required type="text" class="form-control form-control-lg" name="meta_og_title">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta OG description</label>
                            <input required type="text" class="form-control form-control-lg" name="meta_og_description">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">URL Canonical</label>
                            <input type="text" class="form-control form-control-lg" name="url_canonical">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">OG Image</label>
                            <input type="text" class="form-control form-control-lg" name="url_image_og">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-lg input-group-outline my-3">
                            <label class="form-label">Meta Type</label>
                            <input required type="text" class="form-control form-control-lg" name="meta_type">
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <button type="submit" class="btn btn-velvet">Agregar Metatag</button>
                </div>

            </form>
        </div>
    </div>
@endsection
