@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.protocolos.index') }}">Protocolos</a></li>
    <li class="breadcrumb-item active">Editar protocolo</li>
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

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Editar protocolo</h4>
        <a href="{{ route('ecd.protocolos.show', $protocolo) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <form action="{{ route('ecd.protocolos.update', $protocolo) }}" method="POST" id="protocoloForm">
        @csrf @method('PUT')
        <input type="hidden" name="materiales_json" id="materialesJson">
        <input type="hidden" name="pasos_json" id="pasosJson">

        @include('admin.ecd.protocolos._form', ['protocolo' => $protocolo])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('ecd.protocolos.show', $protocolo) }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary" onclick="serializeProtocolo()">
                <i class="fas fa-save me-1"></i> Guardar cambios
            </button>
        </div>
    </form>

@endsection

@section('script')
@include('admin.ecd.protocolos._form_js', ['protocolo' => $protocolo])
@endsection
