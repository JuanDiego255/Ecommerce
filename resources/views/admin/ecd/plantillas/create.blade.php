@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.plantillas.index') }}">Plantillas</a></li>
    <li class="breadcrumb-item active">Nueva plantilla</li>
@endsection
@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ecd.plantillas.store') }}" method="POST" id="plantillaForm">
        @csrf

        {{-- Hidden JSON payload --}}
        <input type="hidden" name="campos_json" id="camposJson">

        <div class="page-header d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Nueva plantilla de ficha</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('ecd.plantillas.index') }}" class="s-btn-sec">Cancelar</a>
                <button type="submit" class="s-btn-primary" onclick="serializeCampos()">
                    <i class="fas fa-save me-1"></i> Guardar plantilla
                </button>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <label class="filter-label">Nombre de la plantilla *</label>
                <input type="text" name="nombre" class="filter-input" value="{{ old('nombre') }}" required
                       placeholder="Ej: Ficha facial, Ficha capilar...">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Categoría</label>
                <input type="text" name="categoria" class="filter-input" value="{{ old('categoria') }}"
                       placeholder="Ej: Facial, Corporal...">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Color etiqueta</label>
                <input type="color" name="color_etiqueta" class="filter-input" value="{{ old('color_etiqueta','#5e72e4') }}"
                       style="height:42px;padding:4px;">
            </div>
            <div class="col-md-8">
                <label class="filter-label">Descripción</label>
                <input type="text" name="descripcion" class="filter-input" value="{{ old('descripcion') }}"
                       placeholder="Breve descripción de cuándo usar esta plantilla">
            </div>
        </div>

        {{-- Builder canvas --}}
        @include('admin.ecd.plantillas._builder', ['existingCampos' => null])

    </form>

@endsection

@include('admin.ecd.plantillas._builder_js', ['existingCampos' => null])
