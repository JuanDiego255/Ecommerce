@extends('layouts.admin')

@section('content')
    <style>
        .config-card {
            border-radius: 16px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }
        .config-card .card-header {
            background: #E8E6E6;
            color: #fff;
            border-radius: 16px 16px 0 0;
            padding: 16px 20px;
        }
        .config-card .card-header h5 {
            margin: 0;
            font-weight: 700;
        }
        .stat-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }
        .stat-box .stat-number {
            font-size: 28px;
            font-weight: 900;
            color: #111;
        }
        .stat-box .stat-label {
            font-size: 12px;
            color: #6c757d;
        }
        .preview-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 16px;
            font-size: 14px;
            min-height: 150px;
        }
        .item-row {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .item-row:hover {
            border-color: #adb5bd;
        }
        .weight-badge {
            background: #e7f5ff;
            color: #1971c2;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px;
        }
        .type-badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
        }
        .type-dm { background: #d3f9d8; color: #2b8a3e; }
        .type-whatsapp { background: #d3f9d8; color: #2b8a3e; }
        .type-store { background: #fff3bf; color: #a07900; }
        .type-link { background: #e7f5ff; color: #1971c2; }
        .type-other { background: #f1f3f5; color: #495057; }
    </style>

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>Configuración de Descripciones</strong></h2>
            <a href="{{ url('/instagram') }}" class="btn btn-accion">Volver</a>
        </div>

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        {{-- Estadísticas --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="stat-box">
                    <div class="stat-number">{{ $info['templates_count'] }}</div>
                    <div class="stat-label">Plantillas activas</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="stat-box">
                    <div class="stat-number">{{ $info['hashtag_pools_count'] }}</div>
                    <div class="stat-label">Pools de hashtags</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="stat-box">
                    <div class="stat-number">{{ $info['ctas_count'] }}</div>
                    <div class="stat-label">CTAs activos</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="stat-box">
                    <div class="stat-number">{{ $info['max_hashtags'] }}</div>
                    <div class="stat-label">Max hashtags</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                {{-- Configuración general --}}
                <div class="card config-card">
                    <div class="card-header">
                        <h5>Configuración General</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-settings/update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-check mb-3">
                                <input type="hidden" name="auto_select_template" value="0">
                                <input type="checkbox" name="auto_select_template" value="1"
                                    class="form-check-input" id="autoTemplate"
                                    {{ $settingsCaption->auto_select_template ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoTemplate">
                                    <strong>Seleccionar plantilla automáticamente</strong><br>
                                    <small class="text-muted">Elige una plantilla al azar (por peso) cuando el caption está vacío</small>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input type="hidden" name="auto_add_hashtags" value="0">
                                <input type="checkbox" name="auto_add_hashtags" value="1"
                                    class="form-check-input" id="autoHashtags"
                                    {{ $settingsCaption->auto_add_hashtags ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoHashtags">
                                    <strong>Agregar hashtags automáticamente</strong><br>
                                    <small class="text-muted">Añade hashtags mezclados al final del caption</small>
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input type="hidden" name="auto_add_cta" value="0">
                                <input type="checkbox" name="auto_add_cta" value="1"
                                    class="form-check-input" id="autoCta"
                                    {{ $settingsCaption->auto_add_cta ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoCta">
                                    <strong>Agregar CTA automáticamente</strong><br>
                                    <small class="text-muted">Incluye un llamado a la acción rotativo</small>
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pool de hashtags predeterminado</label>
                                    <select name="hashtag_pool_id" class="form-control">
                                        <option value="">— Aleatorio —</option>
                                        @foreach ($hashtagPools as $pool)
                                            <option value="{{ $pool->id }}"
                                                {{ $settingsCaption->hashtag_pool_id == $pool->id ? 'selected' : '' }}>
                                                {{ $pool->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Máximo de hashtags</label>
                                    <input type="number" name="max_hashtags" class="form-control"
                                        value="{{ $settingsCaption->max_hashtags }}" min="1" max="30">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accion">Guardar configuración</button>
                        </form>
                    </div>
                </div>

                {{-- Vista previa --}}
                <div class="card config-card">
                    <div class="card-header">
                        <h5>Vista Previa</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-outline-dark mb-3" id="btnGeneratePreview">
                            Generar caption de prueba
                        </button>
                        <div class="preview-box text-center" id="previewBox">
                            Haz clic en "Generar caption de prueba" para ver cómo quedará un caption automático.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                {{-- Pools de hashtags --}}
                <div class="card config-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Pools de Hashtags</h5>
                        <a href="{{ url('/instagram/caption-settings/hashtag-pools/create') }}"
                            class="btn btn-light btn-sm">+ Nuevo</a>
                    </div>
                    <div class="card-body">
                        @forelse ($hashtagPools as $pool)
                            <div class="item-row">
                                <div>
                                    <strong>{{ $pool->name }}</strong>
                                    @if (!$pool->is_active)
                                        <span class="badge bg-secondary ms-2">Inactivo</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        {{ count($pool->getHashtagsArray()) }} hashtags · Max {{ $pool->max_hashtags }}
                                        @if ($pool->shuffle) · Mezclado @endif
                                    </small>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ url('/instagram/caption-settings/hashtag-pools/' . $pool->id . '/edit') }}"
                                        class="btn btn-sm btn-outline-dark">Editar</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No hay pools de hashtags. Crea uno para empezar.</p>
                        @endforelse
                    </div>
                </div>

                {{-- CTAs --}}
                <div class="card config-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>CTAs (Llamados a la Acción)</h5>
                        <a href="{{ url('/instagram/caption-settings/ctas/create') }}"
                            class="btn btn-light btn-sm">+ Nuevo</a>
                    </div>
                    <div class="card-body">
                        @forelse ($ctas as $cta)
                            <div class="item-row">
                                <div>
                                    <strong>{{ $cta->name }}</strong>
                                    <span class="type-badge type-{{ $cta->type }} ms-2">{{ strtoupper($cta->type) }}</span>
                                    @if (!$cta->is_active)
                                        <span class="badge bg-secondary ms-2">Inactivo</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">{{ Str::limit($cta->cta_text, 50) }}</small>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="weight-badge">Peso: {{ $cta->weight }}</span>
                                    <a href="{{ url('/instagram/caption-settings/ctas/' . $cta->id . '/edit') }}"
                                        class="btn btn-sm btn-outline-dark">Editar</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No hay CTAs configurados. Crea uno para empezar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script>
        document.getElementById('btnGeneratePreview')?.addEventListener('click', async function() {
            const btn = this;
            const previewBox = document.getElementById('previewBox');

            btn.disabled = true;
            btn.textContent = 'Generando...';
            previewBox.textContent = 'Cargando...';

            try {
                const resp = await fetch('{{ url("/instagram/caption-settings/preview") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        include_template: document.getElementById('autoTemplate').checked,
                        include_hashtags: document.getElementById('autoHashtags').checked,
                        include_cta: document.getElementById('autoCta').checked,
                    })
                });

                const data = await resp.json();

                if (data.ok) {
                    previewBox.textContent = data.caption || '(Caption vacío - configura plantillas, hashtags y CTAs)';
                } else {
                    previewBox.textContent = 'Error al generar preview';
                }
            } catch (e) {
                console.error(e);
                previewBox.textContent = 'Error de conexión';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Generar caption de prueba';
            }
        });
    </script>
@endsection
