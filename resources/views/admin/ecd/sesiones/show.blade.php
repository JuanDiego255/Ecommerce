@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Sesión</li>
@endsection
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Session header --}}
    <div class="surface p-4 mb-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h4 class="mb-0">{{ $sesion->titulo }}</h4>
                    <span class="s-pill {{ $sesion->estado_badge }}">{{ ucfirst($sesion->estado) }}</span>
                </div>
                <div style="font-size:.82rem;color:#64748b;">
                    <i class="fas fa-calendar me-1"></i>{{ $sesion->fecha_sesion->format('d/m/Y') }}
                    @if($sesion->hora_inicio)
                        <span class="mx-2">·</span><i class="fas fa-clock me-1"></i>{{ $sesion->hora_inicio }}
                        @if($sesion->hora_fin) – {{ $sesion->hora_fin }} @endif
                    @endif
                    @if($sesion->especialista)
                        <span class="mx-2">·</span><i class="fas fa-user-md me-1"></i>{{ $sesion->especialista->nombre ?? '' }}
                    @endif
                    @if($sesion->plantilla)
                        <span class="mx-2">·</span><i class="fas fa-clipboard me-1"></i>{{ $sesion->plantilla->nombre }}
                    @endif
                </div>
                <div style="font-size:.78rem;color:#94a3b8;margin-top:.25rem;">
                    Paciente: <a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ecd.sesiones.edit', [$paciente, $sesion]) }}" class="act-btn ab-yellow" title="Editar sesión" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('ecd.galeria.comparar', [$paciente, $sesion]) }}" class="act-btn ab-purple" title="Comparar antes/después" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-columns"></i>
                </a>
                <a href="{{ route('ecd.reportes.sesion', [$paciente, $sesion]) }}" target="_blank" class="act-btn ab-teal" title="Imprimir sesión" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-print"></i>
                </a>
                <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Left: clinical data --}}
        <div class="col-lg-8">
            {{-- Template responses --}}
            @if($sesion->plantilla && $sesion->respuestas->count() && count($camposPlano))
                <div class="surface p-4 mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        {{ $sesion->plantilla->nombre }}
                    </div>
                    @php
                        $respMap = $sesion->respuestas->keyBy('campo_key');
                        $secciones = $sesion->plantilla->campos['secciones'] ?? [];
                    @endphp
                    @foreach($secciones as $seccion)
                        @if($seccion['titulo'] ?? false)
                            <div style="font-size:.78rem;font-weight:700;color:#475569;margin:.75rem 0 .4rem;text-transform:uppercase;letter-spacing:.04em;">
                                {{ $seccion['titulo'] }}
                            </div>
                        @endif
                        <div class="row g-2 mb-2">
                            @foreach($seccion['campos'] ?? [] as $campo)
                                @if($respMap->has($campo['key']))
                                    <div class="col-md-6">
                                        <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">
                                            {{ $campo['etiqueta'] ?? $campo['key'] }}
                                        </div>
                                        <div style="font-size:.88rem;color:#1e293b;">
                                            {{ $respMap[$campo['key']]->valor ?: '—' }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Clinical notes --}}
            @if($sesion->observaciones_pre || $sesion->observaciones_post || $sesion->productos_usados || $sesion->recomendaciones)
                <div class="surface p-4 mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Notas clínicas
                    </div>
                    <div class="row g-3">
                        @if($sesion->observaciones_pre)
                            <div class="col-md-6">
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Pre-sesión</div>
                                <p style="font-size:.88rem;white-space:pre-line;margin-bottom:0;">{{ $sesion->observaciones_pre }}</p>
                            </div>
                        @endif
                        @if($sesion->observaciones_post)
                            <div class="col-md-6">
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Post-sesión</div>
                                <p style="font-size:.88rem;white-space:pre-line;margin-bottom:0;">{{ $sesion->observaciones_post }}</p>
                            </div>
                        @endif
                        @if($sesion->productos_usados)
                            <div class="col-md-6">
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Productos utilizados</div>
                                <p style="font-size:.88rem;white-space:pre-line;margin-bottom:0;">{{ $sesion->productos_usados }}</p>
                            </div>
                        @endif
                        @if($sesion->recomendaciones)
                            <div class="col-md-6">
                                <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Recomendaciones</div>
                                <p style="font-size:.88rem;white-space:pre-line;margin-bottom:0;">{{ $sesion->recomendaciones }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Images --}}
            <div class="surface p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
                        Imágenes ({{ $sesion->imagenes->count() }})
                    </div>
                    <button class="act-btn ab-green" data-bs-toggle="modal" data-bs-target="#uploadModal" title="Subir imágenes">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>

                @if($sesion->imagenes->count())
                    @php $grouped = $sesion->imagenes->groupBy('tipo'); @endphp
                    @foreach(['antes' => 'Antes', 'durante' => 'Durante', 'despues' => 'Después', 'referencia' => 'Referencia'] as $tipo => $label)
                        @if($grouped->has($tipo))
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#475569;margin-bottom:.5rem;">{{ $label }}</div>
                            <div class="row g-2 mb-3">
                                @foreach($grouped[$tipo] as $img)
                                    <div class="col-4 col-md-3">
                                        <div style="position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f1f5f9;">
                                            <img src="{{ $img->url }}" alt="{{ $img->titulo }}"
                                                 style="width:100%;height:100%;object-fit:cover;cursor:pointer;"
                                                 onclick="openLightbox('{{ $img->url }}', '{{ addslashes($img->titulo ?? '') }}')">
                                            @if($img->es_favorita)
                                                <span style="position:absolute;top:4px;left:4px;background:#fbbf24;border-radius:4px;padding:2px 5px;font-size:.65rem;">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            @endif
                                            <div style="position:absolute;top:4px;right:4px;display:flex;gap:3px;">
                                                <button class="act-btn ab-red" style="width:22px;height:22px;font-size:.6rem;"
                                                        onclick="deleteImg({{ $img->id }}, '{{ route('ecd.imagenes.destroy', [$paciente, $sesion, $img]) }}')"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if($img->zona_corporal)
                                            <div style="font-size:.7rem;color:#94a3b8;text-align:center;margin-top:2px;">{{ $img->zona_corporal }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="text-muted text-center py-3" style="font-size:.85rem;">
                        No hay imágenes cargadas para esta sesión.
                    </p>
                @endif
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="col-lg-4">
            <div class="surface p-4 mb-3">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                    Seguimiento
                </div>
                @if($sesion->proxima_cita)
                    <div class="mb-2" style="font-size:.85rem;">
                        <span style="color:#64748b;">Próxima cita:</span>
                        <span class="fw-bold ms-1 {{ $sesion->proxima_cita->isPast() ? 'text-danger' : '' }}">
                            {{ $sesion->proxima_cita->format('d/m/Y') }}
                        </span>
                    </div>
                @endif
                @if($sesion->notas_internas)
                    <div>
                        <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;margin-bottom:.25rem;">Notas internas</div>
                        <p style="font-size:.85rem;white-space:pre-line;margin-bottom:0;">{{ $sesion->notas_internas }}</p>
                    </div>
                @endif
            </div>

            {{-- Quick actions --}}
            <div class="surface p-4">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                    Acciones
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('ecd.sesiones.edit', [$paciente, $sesion]) }}" class="s-btn-sec w-100 text-center">
                        <i class="fas fa-edit me-1"></i> Editar sesión
                    </a>
                    <button class="s-btn-sec w-100" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-camera me-1"></i> Subir imágenes
                    </button>
                    <button class="s-btn-sec w-100" data-bs-toggle="modal" data-bs-target="#consentModal">
                        <i class="fas fa-file-signature me-1"></i> Firmar consentimiento
                    </button>
                    <a href="{{ route('ecd.consentimientos.firmados', $paciente) }}" class="s-btn-sec w-100 text-center">
                        <i class="fas fa-list me-1"></i> Ver firmados
                    </a>
                    <button class="s-btn-sec w-100 text-danger" style="color:#e53e3e!important;"
                            onclick="confirmDelete()">
                        <i class="fas fa-trash me-1"></i> Eliminar sesión
                    </button>
                    <form id="deleteForm" action="{{ route('ecd.sesiones.destroy', [$paciente, $sesion]) }}" method="POST" class="d-none">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Consent selector modal --}}
    <div class="modal fade" id="consentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                    <h5 class="modal-title" style="font-size:.95rem;font-weight:700;">Seleccionar consentimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    @php
                        $plantillasConsent = \App\Models\ConsentimientoPlantilla::where('activo', true)->orderBy('nombre')->get();
                    @endphp
                    @if($plantillasConsent->isEmpty())
                        <p class="text-muted text-center" style="font-size:.85rem;">
                            No hay plantillas de consentimiento activas.
                            <a href="{{ route('ecd.consentimientos.create') }}">Crear una</a>.
                        </p>
                    @else
                        <p style="font-size:.84rem;color:#64748b;margin-bottom:1rem;">
                            Selecciona la plantilla de consentimiento que deseas que el paciente firme:
                        </p>
                        <div class="d-flex flex-column gap-2">
                            @foreach($plantillasConsent as $pc)
                                <a href="{{ route('ecd.consentimientos.firmar.create', [$paciente, $sesion, $pc]) }}"
                                   style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 1rem;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;color:#1e293b;font-size:.88rem;">
                                    <div>
                                        <span class="fw-semibold">{{ $pc->nombre }}</span>
                                        <span style="font-size:.72rem;color:#94a3b8;display:block;">{{ $pc->tipo }} · v{{ $pc->version }}</span>
                                    </div>
                                    <i class="fas fa-chevron-right" style="color:#94a3b8;"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:1rem 1.5rem;">
                    <button type="button" class="s-btn-sec" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload images modal --}}
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                    <h5 class="modal-title" style="font-size:.95rem;font-weight:700;">Subir imágenes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('ecd.imagenes.store', [$paciente, $sesion]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="padding:1.5rem;">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="filter-label">Imágenes *</label>
                                <input type="file" name="imagenes[]" class="filter-input" multiple accept="image/*" required>
                            </div>
                            <div class="col-md-6">
                                <label class="filter-label">Tipo *</label>
                                <select name="tipo" class="filter-input" required>
                                    <option value="antes">Antes</option>
                                    <option value="durante">Durante</option>
                                    <option value="despues">Después</option>
                                    <option value="referencia">Referencia</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="filter-label">Zona corporal</label>
                                <input type="text" name="zona_corporal" class="filter-input" placeholder="Ej: Rostro, Espalda...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:1rem 1.5rem;">
                        <button type="button" class="s-btn-sec" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="s-btn-primary"><i class="fas fa-upload me-1"></i> Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;align-items:center;justify-content:center;"
         onclick="closeLightbox()">
        <img id="lightboxImg" style="max-width:90vw;max-height:90vh;border-radius:8px;object-fit:contain;">
    </div>

@endsection

@section('script')
<script>
    function openLightbox(src, title) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }

    function deleteImg(id, url) {
        Swal.fire({
            title: '¿Eliminar imagen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => {
            if (!r.isConfirmed) return;
            fetch(url, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest'},
            }).then(() => location.reload());
        });
    }

    function confirmDelete() {
        Swal.fire({
            title: '¿Eliminar sesión?',
            text: 'Se eliminarán todos los datos e imágenes de esta sesión.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => { if (r.isConfirmed) document.getElementById('deleteForm').submit(); });
    }
</script>
@endsection
