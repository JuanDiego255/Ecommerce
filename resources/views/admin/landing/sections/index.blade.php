@extends('layouts.admin')

@section('title', 'Landing Page - Secciones')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-0"><i class="fa fa-th-list me-2"></i>Secciones de la Landing Page</h3>
            <p class="text-muted mb-0 mt-1" style="font-size:.9rem;">
                Activa, desactiva y reordena las secciones de tu sitio informativo.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.landing.faq') }}" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-question-circle me-1"></i>Preguntas Frecuentes
            </a>
            <a href="{{ route('admin.landing.settings') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-paint-brush me-1"></i>Apariencia
            </a>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('status'))
        <div class="alert alert-{{ session('icon') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show text-white">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info ruta pública --}}
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="font-size:.88rem;">
        <i class="fa fa-info-circle fa-lg"></i>
        <span>
            Tu sitio informativo está disponible en:
            <strong>{{ url('/') }}</strong>
        </span>
    </div>

    {{-- Tabla de secciones --}}
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0">Secciones disponibles</h5>
            <small class="text-muted">Arrastra para reordenar. Haz clic en el toggle para activar/desactivar.</small>
        </div>
        <div class="card-body p-0">
            <div id="sections-sortable">
                @foreach($sections as $section)
                <div class="d-flex align-items-center border-bottom px-4 py-3 section-row"
                     data-id="{{ $section->id }}"
                     style="background:{{ $section->activo ? '#fff' : '#f8f9fa' }};cursor:move;">

                    {{-- Handle arrastre --}}
                    <div class="me-3 text-muted" style="cursor:grab;">
                        <i class="fa fa-bars"></i>
                    </div>

                    {{-- Icono sección --}}
                    @php
                        $icons = [
                            'inicio'    => 'fa-home',
                            'nosotros'  => 'fa-users',
                            'servicios' => 'fa-star',
                            'faq'       => 'fa-question-circle',
                            'blog'      => 'fa-pencil',
                            'contacto'  => 'fa-envelope',
                        ];
                        $publicRoutes = [
                            'inicio'    => url('/'),
                            'nosotros'  => route('landing.nosotros'),
                            'servicios' => route('landing.servicios'),
                            'faq'       => route('landing.faq'),
                            'blog'      => route('landing.blog'),
                            'contacto'  => route('landing.contacto'),
                        ];
                    @endphp
                    <div class="me-3" style="width:42px;height:42px;border-radius:10px;
                         background:{{ $section->activo ? 'var(--sidebar)' : '#dee2e6' }};
                         display:flex;align-items:center;justify-content:center;color:#fff;">
                        <i class="fa {{ $icons[$section->section_key] ?? 'fa-circle' }}"></i>
                    </div>

                    {{-- Nombre e info --}}
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                            <strong>{{ $section->titulo ?? ucfirst($section->section_key) }}</strong>
                            <span class="badge {{ $section->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $section->activo ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                        @if($section->subtitulo)
                            <small class="text-muted">{{ $section->subtitulo }}</small>
                        @endif
                        <div>
                            <small class="text-muted">
                                <i class="fa fa-link me-1"></i>
                                <a href="{{ $publicRoutes[$section->section_key] ?? '#' }}" target="_blank"
                                   class="text-muted">
                                    {{ $publicRoutes[$section->section_key] ?? '' }}
                                </a>
                            </small>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="d-flex gap-2 ms-3">
                        {{-- Editar --}}
                        <button class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $section->id }}">
                            <i class="fa fa-pencil"></i>
                        </button>

                        {{-- Toggle activo --}}
                        <form action="{{ route('admin.landing.sections.toggle', $section) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="btn btn-sm {{ $section->activo ? 'btn-success' : 'btn-outline-secondary' }}"
                                    title="{{ $section->activo ? 'Desactivar' : 'Activar' }}">
                                <i class="fa fa-{{ $section->activo ? 'toggle-on' : 'toggle-off' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Modal editar sección --}}
                <div class="modal fade" id="editModal{{ $section->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Editar: {{ $section->titulo ?? ucfirst($section->section_key) }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.landing.sections.update', $section) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Título en el menú</label>
                                        <input type="text" name="titulo" class="form-control"
                                               value="{{ $section->titulo }}"
                                               placeholder="Ej: Nosotros">
                                        <small class="text-muted">Este título se mostrará en el menú de navegación.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Subtítulo de la sección</label>
                                        <input type="text" name="subtitulo" class="form-control"
                                               value="{{ $section->subtitulo }}"
                                               placeholder="Ej: Conoce quiénes somos">
                                        <small class="text-muted">Aparece debajo del título principal en la página.</small>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="activo" value="1"
                                               {{ $section->activo ? 'checked' : '' }}>
                                        <label class="form-check-label">Sección activa</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
        <div class="card-footer py-3">
            <small class="text-muted">
                <i class="fa fa-info-circle me-1"></i>
                Para reordenar: arrastra las secciones. El orden se guarda automáticamente.
            </small>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const sortable = Sortable.create(document.getElementById('sections-sortable'), {
        handle: '.fa-bars',
        animation: 150,
        onEnd: function () {
            const ids = [...document.querySelectorAll('.section-row')].map(el => el.dataset.id);
            fetch('{{ route('admin.landing.sections.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ orden: ids.map(Number) }),
            });
        },
    });
</script>
@endsection
