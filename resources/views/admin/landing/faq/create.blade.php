@extends('layouts.admin')

@section('title', 'Nueva Pregunta Frecuente')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0"><i class="fa fa-plus me-2"></i>Nueva Pregunta Frecuente</h3>
        <a href="{{ route('admin.landing.faq') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-body">
            <form action="{{ route('admin.landing.faq.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold">Pregunta *</label>
                    <input type="text" name="pregunta"
                           class="form-control form-control-lg @error('pregunta') is-invalid @enderror"
                           value="{{ old('pregunta') }}"
                           placeholder="Ej: ¿Cuáles son sus horarios de atención?"
                           required>
                    @error('pregunta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Respuesta *</label>
                    <textarea name="respuesta" rows="6"
                              class="form-control @error('respuesta') is-invalid @enderror"
                              placeholder="Escribe la respuesta completa..."
                              required>{{ old('respuesta') }}</textarea>
                    @error('respuesta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4" style="max-width:200px;">
                    <label class="form-label fw-bold">Orden</label>
                    <input type="number" name="orden"
                           class="form-control"
                           value="{{ old('orden', 0) }}"
                           min="0"
                           placeholder="0">
                    <small class="text-muted">Número menor aparece primero.</small>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save me-1"></i> Guardar
                    </button>
                    <a href="{{ route('admin.landing.faq') }}" class="btn btn-outline-secondary px-4">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
