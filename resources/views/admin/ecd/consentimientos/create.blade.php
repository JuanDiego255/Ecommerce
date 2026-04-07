@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.consentimientos.index') }}">Consentimientos</a></li>
    <li class="breadcrumb-item active">Nueva plantilla</li>
@endsection
@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Nueva plantilla de consentimiento</h4>
        <a href="{{ route('ecd.consentimientos.index') }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <form action="{{ route('ecd.consentimientos.store') }}" method="POST">
        @csrf

        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                Información
            </div>
            <div class="row g-3">
                <div class="col-md-7">
                    <label class="filter-label">Nombre de la plantilla *</label>
                    <input type="text" name="nombre" class="filter-input" value="{{ old('nombre') }}" required
                           placeholder="Ej: Consentimiento facial, Consentimiento general...">
                </div>
                <div class="col-md-5">
                    <label class="filter-label">Tipo / Categoría *</label>
                    <input type="text" name="tipo" class="filter-input" value="{{ old('tipo') }}" required
                           placeholder="Ej: General, Facial, Corporal, Invasivo...">
                </div>
            </div>
        </div>

        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                Contenido del consentimiento
            </div>
            <div class="mb-2" style="font-size:.78rem;color:#64748b;">
                Puedes usar las siguientes variables dinámicas:
                <code style="background:#f1f5f9;padding:2px 5px;border-radius:4px;">{NOMBRE_PACIENTE}</code>
                <code style="background:#f1f5f9;padding:2px 5px;border-radius:4px;">{FECHA}</code>
                <code style="background:#f1f5f9;padding:2px 5px;border-radius:4px;">{CEDULA}</code>
                <code style="background:#f1f5f9;padding:2px 5px;border-radius:4px;">{TRATAMIENTO}</code>
            </div>
            <textarea name="contenido" id="contenidoEditor" class="filter-input" rows="18" required
                      style="font-family:monospace;font-size:.85rem;">{{ old('contenido') }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('ecd.consentimientos.index') }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary">
                <i class="fas fa-save me-1"></i> Guardar plantilla
            </button>
        </div>
    </form>

@endsection
