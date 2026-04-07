@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.plantillas.index') }}">Plantillas</a></li>
    <li class="breadcrumb-item active">Editar plantilla</li>
@endsection
@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ecd.plantillas.update', $plantilla) }}" method="POST" id="plantillaForm">
        @csrf @method('PUT')

        <input type="hidden" name="campos_json" id="camposJson">

        <div class="page-header d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Editar plantilla</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('ecd.plantillas.index') }}" class="s-btn-sec">Cancelar</a>
                <button type="submit" class="s-btn-primary" onclick="serializeCampos()">
                    <i class="fas fa-save me-1"></i> Guardar cambios
                </button>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-5">
                <label class="filter-label">Nombre de la plantilla *</label>
                <input type="text" name="nombre" class="filter-input" value="{{ old('nombre', $plantilla->nombre) }}" required>
            </div>
            <div class="col-md-3">
                <label class="filter-label">Categoría</label>
                <input type="text" name="categoria" class="filter-input" value="{{ old('categoria', $plantilla->categoria) }}">
            </div>
            <div class="col-md-2">
                <label class="filter-label">Color etiqueta</label>
                <input type="color" name="color_etiqueta" class="filter-input"
                       value="{{ old('color_etiqueta', $plantilla->color_etiqueta ?? '#5e72e4') }}"
                       style="height:42px;padding:4px;">
            </div>
            <div class="col-md-8">
                <label class="filter-label">Descripción</label>
                <input type="text" name="descripcion" class="filter-input" value="{{ old('descripcion', $plantilla->descripcion) }}">
            </div>
        </div>

        @include('admin.ecd.plantillas._builder', ['existingCampos' => $plantilla->campos])

    </form>

@endsection

@include('admin.ecd.plantillas._builder_js', ['existingCampos' => $plantilla->campos])
