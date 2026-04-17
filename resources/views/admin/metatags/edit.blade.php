@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('meta-tags/indexadmin') }}">Meta Tags</a></li>
    <li class="breadcrumb-item active">Editar — {{ $metatag->section }}</li>
@endsection
@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Editar — {{ $metatag->section }}</h4>
        <a href="{{ url('meta-tags/indexadmin') }}" class="ph-btn ph-btn-back"
           title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <form method="POST" action="{{ url('/metatags/' . $metatag->id) }}">
        @csrf
        @method('PUT')

        {{-- Identificación --}}
        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                Identificación
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label">Sección *</label>
                    <select name="section" class="filter-input" required>
                        @php
                            $sections = ['Inicio','Departamentos','Categorias','Categoría Específica','Acerca De Nosotros','Carrito','Mis Compras','Checkout','Registrarse','Ingresar','Blog'];
                        @endphp
                        @foreach($sections as $s)
                            <option value="{{ $s }}" {{ $metatag->section === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                        {{-- Preserve any custom section not in the standard list --}}
                        @if(!in_array($metatag->section, $sections))
                            <option value="{{ $metatag->section }}" selected>{{ $metatag->section }}</option>
                        @endif
                    </select>
                    @error('section')
                        <span style="font-size:.75rem;color:#ef4444;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-8">
                    <label class="filter-label">Title *</label>
                    <input type="text" name="title" class="filter-input" value="{{ old('title', $metatag->title) }}" required
                           placeholder="Ej: Tienda online en Costa Rica | Ropa y accesorios">
                    <span style="font-size:.72rem;color:#94a3b8;">Aparece en el tab del navegador. El sistema añade <strong>| Costa Rica</strong> automáticamente.</span>
                </div>
            </div>
        </div>

        {{-- Meta descripción y keywords --}}
        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                Meta descripción y palabras clave
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="filter-label">Meta Description *</label>
                    <textarea name="meta_description" class="filter-input" rows="3" required
                              placeholder="Descripción que aparece en los resultados de Google. Máximo 160 caracteres recomendados.">{{ old('meta_description', $metatag->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="filter-label">
                        Meta Keywords
                        <span style="font-weight:400;color:#94a3b8;">(opcional — presioná Enter para agregar)</span>
                    </label>
                    <div class="tags-input">
                        <ul id="tags"></ul>
                        <input type="text" id="input-tag" placeholder="Escribá la palabra clave y presioná Enter...">
                    </div>
                    <input id="meta_keywords" type="hidden" name="meta_keywords"
                           value="{{ old('meta_keywords', $metatag->meta_keywords) }}">
                    <span style="font-size:.72rem;color:#94a3b8;">Incluí términos geográficos: "Costa Rica", "CR", el nombre de la ciudad, etc.</span>
                </div>
            </div>
        </div>

        {{-- Open Graph --}}
        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.25rem;">
                Open Graph
            </div>
            <div style="font-size:.75rem;color:#94a3b8;margin-bottom:1rem;">
                Controla cómo se ve la página al compartirla en WhatsApp, Facebook e Instagram.
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="filter-label">OG Title *</label>
                    <input type="text" name="meta_og_title" class="filter-input"
                           value="{{ old('meta_og_title', $metatag->meta_og_title) }}" required
                           placeholder="Título para compartir en redes sociales">
                </div>
                <div class="col-md-6">
                    <label class="filter-label">OG Description *</label>
                    <input type="text" name="meta_og_description" class="filter-input"
                           value="{{ old('meta_og_description', $metatag->meta_og_description) }}" required
                           placeholder="Descripción al compartir en redes sociales">
                </div>
                <div class="col-12">
                    <label class="filter-label">OG Image URL</label>
                    <input type="text" name="url_image_og" class="filter-input"
                           value="{{ old('url_image_og', $metatag->url_image_og) }}"
                           placeholder="https://... — URL de la imagen que aparece al compartir">
                    <span style="font-size:.72rem;color:#94a3b8;">Tamaño recomendado: 1200 × 630 px.</span>
                </div>
            </div>
        </div>

        {{-- SEO técnico --}}
        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.25rem;">
                SEO técnico
            </div>
            <div style="font-size:.75rem;color:#94a3b8;margin-bottom:1rem;">
                Parámetros avanzados para motores de búsqueda.
            </div>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="filter-label">URL Canonical</label>
                    <input type="text" name="url_canonical" class="filter-input"
                           value="{{ old('url_canonical', $metatag->url_canonical) }}"
                           placeholder="https://mitaicr.com/...">
                    <span style="font-size:.72rem;color:#94a3b8;">Indica a Google cuál es la URL principal. Evita penalizaciones por contenido duplicado.</span>
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Meta Type *</label>
                    <input type="text" name="meta_type" class="filter-input"
                           value="{{ old('meta_type', $metatag->meta_type) }}" required
                           placeholder="website / article / product">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ url('meta-tags/indexadmin') }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary">
                <i class="fas fa-save me-1"></i> Guardar cambios
            </button>
        </div>
    </form>

@endsection
@section('script')
    <script src="{{ asset('js/edit-tag.js') }}"></script>
@endsection
