@extends('layouts.admin')

@section('title', 'Landing Page - Preguntas Frecuentes')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-0"><i class="fa fa-question-circle me-2"></i>Preguntas Frecuentes</h3>
            <p class="text-muted mb-0 mt-1" style="font-size:.9rem;">
                Gestiona las preguntas y respuestas que aparecen en tu sitio informativo.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.landing.sections') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Secciones
            </a>
            <a href="{{ route('admin.landing.faq.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-1"></i> Nueva Pregunta
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-{{ session('icon') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show text-white">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($faqs->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-question-circle fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aún no hay preguntas frecuentes</h5>
                <p class="text-muted">Crea la primera para que aparezca en tu sitio.</p>
                <a href="{{ route('admin.landing.faq.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Crear primera pregunta
                </a>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Pregunta</th>
                            <th style="width:120px">Estado</th>
                            <th style="width:150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $faq)
                        <tr>
                            <td class="text-muted">{{ $faq->orden }}</td>
                            <td>
                                <div class="fw-semibold">{{ $faq->pregunta }}</div>
                                <small class="text-muted">
                                    {{ Str::limit($faq->respuesta, 100) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge {{ $faq->activo ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $faq->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.landing.faq.edit', $faq) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.landing.faq.destroy', $faq) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar esta pregunta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
