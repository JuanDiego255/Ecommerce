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
        .spintax-help {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .spintax-help code {
            background: rgba(255,255,255,0.2);
            padding: 2px 6px;
            border-radius: 4px;
            color: #fff;
        }
        .spintax-example {
            background: rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            font-family: monospace;
            font-size: 13px;
            white-space: pre-wrap;
        }
    </style>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>{{ __('Nueva Plantilla de Caption') }}</strong></h2>
            <a href="{{ url('/instagram/caption-templates') }}" class="btn btn-outline-dark">Volver</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="spintax-help">
            <h5 class="mb-2">Sintaxis Spintax + Variables de Imagen</h5>
            <p class="mb-2">Usa <code>{opción1|opción2|opción3}</code> para crear variaciones aleatorias.</p>
            <p class="mb-2">Con <strong>Analizar imágenes</strong> activado, también puedes usar estas variables:</p>
            <ul class="small mb-2" style="opacity: 0.9;">
                <li><code>{color}</code> → Color detectado (negro, rojo, azul...)</li>
                <li><code>{tipo_prenda}</code> → Tipo detectado (vestido, blusa...)</li>
                <li><code>{adjetivo_color}</code> → Ej: "elegante negro"</li>
                <li><code>{caracteristica}</code> → Estampado, tela suave...</li>
                <li><code>{estilo}</code> → casual, elegante, femenino...</li>
                <li><code>{ocasion}</code> → salidas, día a día...</li>
            </ul>
            <div class="spintax-example">{Nueva|Hermosa|Linda} {tipo_prenda} en {adjetivo_color} ✨
{Disponible|Ya disponible} {hoy|esta semana}.

{Detalles:|Características:}
• {caracteristica}
• {Ideal para|Perfecto para} {ocasion}
• Estilo {estilo}

{Envíos a todo CR|Entrega rápida|Pick-up disponible}
{Escríbenos por DM|Pídelo por WhatsApp} 💬</div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-templates/store') }}" id="templateForm">
                            @csrf

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre de la plantilla *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Ej: Plantilla Nueva Colección"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Texto de la plantilla (con Spintax) *</label>
                                <textarea name="template_text" id="templateText" class="form-control" rows="12"
                                    placeholder="Escribe tu plantilla usando la sintaxis {opción1|opción2}..."
                                    required>{{ old('template_text') }}</textarea>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Peso (probabilidad de selección) *</label>
                                <input type="number" name="weight" class="form-control"
                                    value="{{ old('weight', 1) }}" min="1" max="100" required>
                                <small class="text-muted">
                                    Mayor peso = mayor probabilidad de ser seleccionada automáticamente.
                                    Ejemplo: peso 3 tiene 3x más probabilidad que peso 1.
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-dark" id="btnPreview">
                                    Ver variaciones
                                </button>
                                <button type="submit" class="btn btn-accion">
                                    Guardar plantilla
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
                                    Escribe tu plantilla y presiona "Ver variaciones"
                                </div>
                            </div>
                        </div>

                        <div class="spintax-stats" id="previewStats" style="display: none;">
                            <strong>Estadísticas:</strong>
                            <span id="statsText"></span>
                        </div>
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
        })();
    </script>
@endsection
