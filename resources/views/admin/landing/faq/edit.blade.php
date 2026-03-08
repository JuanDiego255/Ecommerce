@extends('layouts.admin')

@section('title', 'Editar Pregunta Frecuente')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0"><i class="fa fa-pencil me-2"></i>Editar Pregunta Frecuente</h3>
        <a href="{{ route('admin.landing.faq') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card" style="max-width:760px;">
        <div class="card-body">
            <form action="{{ route('admin.landing.faq.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-bold">Pregunta *</label>
                    <input type="text" name="pregunta"
                           class="form-control form-control-lg @error('pregunta') is-invalid @enderror"
                           value="{{ old('pregunta', $faq->pregunta) }}"
                           required>
                    @error('pregunta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Respuesta *</label>
                    <textarea name="respuesta" rows="6"
                              class="form-control @error('respuesta') is-invalid @enderror"
                              required>{{ old('respuesta', $faq->respuesta) }}</textarea>
                    @error('respuesta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Orden</label>
                        <input type="number" name="orden" class="form-control"
                               value="{{ old('orden', $faq->orden) }}" min="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox"
                                   name="activo" value="1"
                                   {{ $faq->activo ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">Activo</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save me-1"></i> Guardar cambios
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
