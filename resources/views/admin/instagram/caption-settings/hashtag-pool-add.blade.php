@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>Nuevo Pool de Hashtags</strong></h2>
            <a href="{{ url('/instagram/caption-settings') }}" class="btn btn-outline-dark">Volver</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-settings/hashtag-pools/store') }}">
                            @csrf

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre del pool *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Ej: Hashtags Moda CR"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Hashtags *</label>
                                <textarea name="hashtags" class="form-control" rows="8"
                                    placeholder="#modaCR #outfit #fashion #tiendacr #mujer #style #lookdeldia #ootd #costarica #ropa #tendencias #moda2024 #estilo #fashionista"
                                    required>{{ old('hashtags') }}</textarea>
                                <small class="text-muted">
                                    Escribe los hashtags separados por espacio, coma o en líneas separadas.
                                    El # es opcional (se agrega automáticamente).
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Máximo de hashtags por post *</label>
                                    <input type="number" name="max_hashtags" class="form-control"
                                        value="{{ old('max_hashtags', 10) }}" min="1" max="30" required>
                                    <small class="text-muted">Instagram permite máximo 30</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="shuffle" value="0">
                                        <input type="checkbox" name="shuffle" value="1"
                                            class="form-check-input" id="shuffle" checked>
                                        <label class="form-check-label" for="shuffle">
                                            <strong>Mezclar orden</strong><br>
                                            <small class="text-muted">Los hashtags aparecerán en orden aleatorio</small>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accion">Crear pool</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Consejos</h5>
                        <ul class="text-muted small">
                            <li class="mb-2">Usa hashtags relevantes para tu nicho</li>
                            <li class="mb-2">Mezcla hashtags populares y de nicho</li>
                            <li class="mb-2">Instagram recomienda entre 3-5 hashtags muy relevantes</li>
                            <li class="mb-2">Evita hashtags prohibidos o spam</li>
                            <li class="mb-2">Crea varios pools para diferentes tipos de contenido</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
