@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>Editar CTA: {{ $cta->name }}</strong></h2>
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
                        <form method="POST" action="{{ url('/instagram/caption-settings/ctas/' . $cta->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre del CTA *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $cta->name) }}"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Texto del CTA * (soporta spintax)</label>
                                <textarea name="cta_text" class="form-control" rows="4"
                                    required>{{ old('cta_text', $cta->cta_text) }}</textarea>
                                <small class="text-muted">
                                    Puedes usar spintax para variaciones: {opción1|opción2|opción3}
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tipo de CTA *</label>
                                    <select name="type" class="form-control" required>
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('type', $cta->type) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Peso *</label>
                                    <input type="number" name="weight" class="form-control"
                                        value="{{ old('weight', $cta->weight) }}" min="1" max="100" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1"
                                            class="form-check-input" id="isActive"
                                            {{ old('is_active', $cta->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">Activo</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accion">Guardar cambios</button>
                        </form>

                        <hr class="my-4">

                        <form method="POST" action="{{ url('/instagram/caption-settings/ctas/' . $cta->id) }}"
                            onsubmit="return confirm('¿Eliminar este CTA?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar CTA</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Información</h5>
                        <ul class="list-unstyled text-muted small">
                            <li><strong>ID:</strong> {{ $cta->id }}</li>
                            <li><strong>Tipo:</strong> {{ $types[$cta->type] ?? $cta->type }}</li>
                            <li><strong>Creado:</strong> {{ $cta->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Actualizado:</strong> {{ $cta->updated_at->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
