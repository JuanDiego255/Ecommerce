@extends('layouts.admin')

@section('title', 'Landing Page - Apariencia')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-0"><i class="fa fa-paint-brush me-2"></i>Apariencia de la Landing Page</h3>
            <p class="text-muted mb-0 mt-1" style="font-size:.9rem;">
                Personaliza los colores, imagen hero y datos de contacto de tu sitio informativo.
            </p>
        </div>
        <a href="{{ route('admin.landing.sections') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Volver a Secciones
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-{{ session('icon') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show text-white">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.landing.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ── Colores ── --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-tint me-2"></i>Colores</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Color Primario</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" name="landing_primary" class="form-control form-control-color"
                                           value="{{ $settings->landing_primary ?? '#1a1a2e' }}"
                                           title="Color primario (navbar, botones, textos)">
                                    <input type="text" class="form-control" id="landing_primary_hex"
                                           value="{{ $settings->landing_primary ?? '#1a1a2e' }}"
                                           style="font-family:monospace;max-width:110px;"
                                           oninput="syncColor(this,'landing_primary')">
                                </div>
                                <small class="text-muted">Navbar, botones principales, títulos</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Color Secundario / Acento</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" name="landing_secondary" class="form-control form-control-color"
                                           value="{{ $settings->landing_secondary ?? '#c9a84c' }}"
                                           title="Color secundario / acento">
                                    <input type="text" class="form-control" id="landing_secondary_hex"
                                           value="{{ $settings->landing_secondary ?? '#c9a84c' }}"
                                           style="font-family:monospace;max-width:110px;"
                                           oninput="syncColor(this,'landing_secondary')">
                                </div>
                                <small class="text-muted">Botones acento, dividers, precios</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Color texto Hero</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" name="landing_text_hero" class="form-control form-control-color"
                                           value="{{ $settings->landing_text_hero ?? '#ffffff' }}">
                                    <input type="text" class="form-control" id="landing_text_hero_hex"
                                           value="{{ $settings->landing_text_hero ?? '#ffffff' }}"
                                           style="font-family:monospace;max-width:110px;"
                                           oninput="syncColor(this,'landing_text_hero')">
                                </div>
                                <small class="text-muted">Texto sobre la imagen hero</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fondo secciones alternas</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color" name="landing_bg_section" class="form-control form-control-color"
                                           value="{{ $settings->landing_bg_section ?? '#f8f9fa' }}">
                                    <input type="text" class="form-control" id="landing_bg_section_hex"
                                           value="{{ $settings->landing_bg_section ?? '#f8f9fa' }}"
                                           style="font-family:monospace;max-width:110px;"
                                           oninput="syncColor(this,'landing_bg_section')">
                                </div>
                                <small class="text-muted">Fondo de secciones con contraste</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sección Hero ── --}}
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-image me-2"></i>Sección Hero (Inicio)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Título principal</label>
                            <input type="text" name="landing_hero_titulo" class="form-control"
                                   value="{{ $settings->landing_hero_titulo ?? '' }}"
                                   placeholder="Ej: Bienvenidos a {{ $tenantinfo->title ?? 'Nuestra empresa' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subtítulo / Descripción breve</label>
                            <textarea name="landing_hero_subtitulo" class="form-control" rows="3"
                                      placeholder="Una breve descripción de tu empresa...">{{ $settings->landing_hero_subtitulo ?? '' }}</textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Texto del botón</label>
                                <input type="text" name="landing_hero_btn_texto" class="form-control"
                                       value="{{ $settings->landing_hero_btn_texto ?? '' }}"
                                       placeholder="Ej: Ver servicios">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-bold">URL del botón</label>
                                <input type="text" name="landing_hero_btn_url" class="form-control"
                                       value="{{ $settings->landing_hero_btn_url ?? '' }}"
                                       placeholder="Ej: /servicios o URL completa">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Imagen de fondo del Hero</label>
                            @if($settings->landing_hero_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($settings->landing_hero_image) }}"
                                         alt="Hero actual"
                                         style="height:100px;object-fit:cover;border-radius:8px;width:100%;">
                                </div>
                            @endif
                            <input type="file" name="landing_hero_image" class="form-control"
                                   accept="image/*">
                            <small class="text-muted">JPG/PNG, máx 4MB. Se superpone con opacidad al color primario.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Datos de contacto ── --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-map-marker me-2"></i>Datos de Contacto (sección Contacto)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Dirección</label>
                                <input type="text" name="landing_direccion" class="form-control"
                                       value="{{ $settings->landing_direccion ?? '' }}"
                                       placeholder="Ej: Avenida Central, San José, Costa Rica">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Horario de atención</label>
                                <input type="text" name="landing_horario" class="form-control"
                                       value="{{ $settings->landing_horario ?? '' }}"
                                       placeholder="Ej: Lun-Vie 8am-6pm">
                            </div>
                        </div>
                        <small class="text-muted">
                            El correo y WhatsApp se obtienen automáticamente desde la información del negocio.
                        </small>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 d-flex gap-3">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fa fa-save me-1"></i> Guardar configuración
            </button>
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary px-4">
                <i class="fa fa-eye me-1"></i> Ver sitio
            </a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function syncColor(input, name) {
    document.querySelector('[name="' + name + '"]').value = input.value;
}
// Sync hex → color input on change
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        const hexId = this.name + '_hex';
        const hex = document.getElementById(hexId);
        if (hex) hex.value = this.value;
    });
});
</script>
@endsection
