@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>Editar Pool: {{ $pool->name }}</strong></h2>
            <a href="{{ url('/instagram/caption-settings') }}" class="btn btn-outline-dark">Volver</a>
        </div>

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-settings/hashtag-pools/' . $pool->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre del pool *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $pool->name) }}"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Hashtags *</label>
                                <textarea name="hashtags" class="form-control" rows="8"
                                    required>{{ old('hashtags', $pool->hashtags) }}</textarea>
                                <small class="text-muted">
                                    Escribe los hashtags separados por espacio, coma o en líneas separadas.
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Máximo de hashtags *</label>
                                    <input type="number" name="max_hashtags" class="form-control"
                                        value="{{ old('max_hashtags', $pool->max_hashtags) }}" min="1" max="30" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="shuffle" value="0">
                                        <input type="checkbox" name="shuffle" value="1"
                                            class="form-check-input" id="shuffle"
                                            {{ old('shuffle', $pool->shuffle) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shuffle">Mezclar orden</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1"
                                            class="form-check-input" id="isActive"
                                            {{ old('is_active', $pool->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">Activo</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-accion">Guardar cambios</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <form method="POST" action="{{ url('/instagram/caption-settings/hashtag-pools/' . $pool->id) }}"
                            onsubmit="return confirm('¿Eliminar este pool de hashtags?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar pool</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Información</h5>
                        <ul class="list-unstyled text-muted small">
                            <li><strong>Total hashtags:</strong> {{ count($pool->getHashtagsArray()) }}</li>
                            <li><strong>Creado:</strong> {{ $pool->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Actualizado:</strong> {{ $pool->updated_at->format('d/m/Y H:i') }}</li>
                        </ul>

                        <h6 class="mt-4 mb-2">Vista previa (mezclado):</h6>
                        <div class="bg-light p-2 rounded small">
                            {{ $pool->generateHashtagsString() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
