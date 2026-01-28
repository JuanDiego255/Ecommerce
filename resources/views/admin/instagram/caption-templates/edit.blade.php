@extends('layouts.admin')

@section('content')
    <style>
        .spintax-preview {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
        }
        .spintax-variation {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            font-size: 14px;
            white-space: pre-wrap;
        }
        .spintax-variation:last-child {
            margin-bottom: 0;
        }
        .spintax-stats {
            font-size: 12px;
            color: #6c757d;
            margin-top: 12px;
        }
    </style>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>{{ __('Editar Plantilla') }}: {{ $template->name }}</strong></h2>
            <a href="{{ url('/instagram/caption-templates') }}" class="btn btn-outline-dark">Volver</a>
        </div>

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-templates/' . $template->id) }}" id="templateForm">
                            @csrf
                            @method('PUT')

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre de la plantilla *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $template->name) }}"
                                    placeholder="Ej: Plantilla Nueva Colección"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Texto de la plantilla (con Spintax) *</label>
                                <textarea name="template_text" id="templateText" class="form-control" rows="12"
                                    placeholder="Escribe tu plantilla usando la sintaxis {opción1|opción2}..."
                                    required>{{ old('template_text', $template->template_text) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-static">
                                        <label>Peso (probabilidad) *</label>
                                        <input type="number" name="weight" class="form-control"
                                            value="{{ old('weight', $template->weight ?? 1) }}" min="1" max="100" required>
                                    </div>
                                    <small class="text-muted">Mayor peso = mayor probabilidad de selección</small>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-check mt-4">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                            id="isActive" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            Plantilla activa
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-dark" id="btnPreview">
                                    Ver variaciones
                                </button>
                                <button type="submit" class="btn btn-accion">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Vista previa de variaciones</h5>
                        <p class="text-muted small">Haz clic en "Ver variaciones" para generar ejemplos aleatorios de tu plantilla.</p>

                        <div id="previewContainer">
                            <div class="spintax-preview">
                                <div class="text-muted text-center py-4">
                                    Presiona "Ver variaciones" para generar ejemplos
                                </div>
                            </div>
                        </div>

                        <div class="spintax-stats" id="previewStats" style="display: none;">
                            <strong>Estadísticas:</strong>
                            <span id="statsText"></span>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="mb-3">Información</h5>
                        <ul class="list-unstyled text-muted small mb-0">
                            <li><strong>ID:</strong> {{ $template->id }}</li>
                            <li><strong>Creada:</strong> {{ $template->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Actualizada:</strong> {{ $template->updated_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Colecciones usando esta plantilla:</strong> {{ $template->collections()->count() }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        (function() {
            const btnPreview = document.getElementById('btnPreview');
            const templateText = document.getElementById('templateText');
            const previewContainer = document.getElementById('previewContainer');
            const previewStats = document.getElementById('previewStats');
            const statsText = document.getElementById('statsText');

            async function loadPreview() {
                const text = templateText.value.trim();
                if (!text) {
                    previewContainer.innerHTML = `
                        <div class="spintax-preview">
                            <div class="text-muted text-center py-4">
                                Escribe tu plantilla primero
                            </div>
                        </div>
                    `;
                    previewStats.style.display = 'none';
                    return;
                }

                btnPreview.disabled = true;
                btnPreview.textContent = 'Generando...';

                try {
                    const resp = await fetch('{{ url("/instagram/caption-templates/preview") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            template_text: text,
                            count: 3
                        })
                    });

                    const data = await resp.json();

                    if (!data.ok) {
                        previewContainer.innerHTML = `
                            <div class="spintax-preview">
                                <div class="text-danger">
                                    <strong>Error:</strong> ${data.message || 'No se pudo generar la vista previa'}
                                </div>
                            </div>
                        `;
                        previewStats.style.display = 'none';
                        return;
                    }

                    let html = '<div class="spintax-preview">';
                    data.variations.forEach((v, i) => {
                        html += `<div class="spintax-variation"><strong>Variación ${i + 1}:</strong><br>${escapeHtml(v)}</div>`;
                    });
                    html += '</div>';

                    previewContainer.innerHTML = html;

                    statsText.textContent = `${data.blocks_count} bloques de variación · ~${data.possible_count.toLocaleString()} combinaciones posibles`;
                    previewStats.style.display = 'block';

                } catch (e) {
                    console.error(e);
                    previewContainer.innerHTML = `
                        <div class="spintax-preview">
                            <div class="text-danger">Error de conexión</div>
                        </div>
                    `;
                } finally {
                    btnPreview.disabled = false;
                    btnPreview.textContent = 'Ver variaciones';
                }
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML.replace(/\n/g, '<br>');
            }

            btnPreview.addEventListener('click', loadPreview);

            // Auto-preview al escribir (con debounce)
            let debounceTimer;
            templateText.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(loadPreview, 800);
            });

            // Cargar preview inicial
            setTimeout(loadPreview, 500);
        })();
    </script>
@endsection
