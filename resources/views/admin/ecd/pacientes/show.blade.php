@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item active">{{ $paciente->nombre_completo }}</li>
@endsection
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('portal_url'))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-3" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;">
            <i class="fas fa-link fa-lg flex-shrink-0"></i>
            <div class="flex-grow-1">
                <div style="font-weight:600;margin-bottom:.25rem;">Enlace del portal generado (válido 30 días)</div>
                <div id="portal-link-text" style="font-size:.82rem;word-break:break-all;">{{ session('portal_url') }}</div>
            </div>
            <button class="act-btn ab-blue flex-shrink-0" onclick="copyPortalLink('{{ session('portal_url') }}')" title="Copiar enlace">
                <i class="fas fa-copy"></i>
            </button>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header card --}}
    <div class="surface p-4 mb-3">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <img src="{{ $paciente->foto_url }}"
                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h4 class="mb-0 me-2">{{ $paciente->nombre_completo }}</h4>
                    @if($paciente->activo)
                        <span class="s-pill pill-green">Activo</span>
                    @else
                        <span class="s-pill pill-red">Inactivo</span>
                    @endif
                    @foreach($paciente->alertas as $alerta)
                        <span class="s-pill {{ $alerta->badge_class }}" title="{{ $alerta->descripcion }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $alerta->tipo }}
                        </span>
                    @endforeach
                </div>
                <div style="font-size:.82rem;color:#64748b;margin-top:.25rem;">
                    @if($paciente->cedula) <span class="me-3"><i class="fas fa-id-card me-1"></i>{{ $paciente->cedula }}</span> @endif
                    @if($paciente->edad)   <span class="me-3"><i class="fas fa-birthday-cake me-1"></i>{{ $paciente->edad }} años</span> @endif
                    @if($paciente->telefono) <span class="me-3"><i class="fas fa-phone me-1"></i>{{ $paciente->telefono }}</span> @endif
                    @if($paciente->email)  <span class="me-3"><i class="fas fa-envelope me-1"></i>{{ $paciente->email }}</span> @endif
                </div>
                @if($paciente->expediente)
                    <div style="font-size:.78rem;color:#94a3b8;margin-top:.2rem;">
                        Expediente #{{ $paciente->expediente->numero_expediente }} &middot; Apertura {{ $paciente->expediente->fecha_apertura?->format('d/m/Y') }}
                    </div>
                @endif
            </div>
            <div class="d-flex gap-2 flex-shrink-0">
                <a href="{{ route('ecd.pacientes.edit', $paciente) }}" class="act-btn ab-yellow" title="Editar datos" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('ecd.pacientes.historia', $paciente) }}" class="act-btn ab-blue" title="Historia clínica" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-heartbeat"></i>
                </a>
                <a href="{{ route('ecd.galeria.index', $paciente) }}" class="act-btn ab-purple" title="Galería de imágenes" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-images"></i>
                </a>
                <a href="{{ route('ecd.reportes.expediente', $paciente) }}" target="_blank" class="act-btn ab-teal" title="Imprimir expediente" data-bs-toggle="tooltip" data-bs-placement="top">
                    <i class="fas fa-print"></i>
                </a>
                <button class="act-btn ab-indigo" title="Portal del paciente" data-bs-toggle="modal" data-bs-target="#portalModal" data-bs-placement="top">
                    <i class="fas fa-share-alt"></i>
                </button>
                <a href="{{ route('ecd.sesiones.create', $paciente) }}" class="ph-btn ph-btn-add" title="Nueva sesión" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Conditions from expediente --}}
    @if($paciente->expediente && count($paciente->expediente->condiciones_activas))
        <div class="surface p-3 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.5rem;">
                Condiciones médicas relevantes
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($paciente->expediente->condiciones_activas as $cond)
                    <span class="s-pill {{ $cond['level'] === 'danger' ? 'pill-red' : 'pill-yellow' }}">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $cond['label'] }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- Sessions timeline --}}
        <div class="col-lg-8">
            <div class="surface p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
                        Sesiones clínicas ({{ $totalSesiones }})
                    </div>
                    <a href="{{ route('ecd.sesiones.create', $paciente) }}" class="act-btn ab-green" title="Nueva sesión">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>

                @forelse($paciente->sesiones as $sesion)
                    <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        <div style="flex-shrink:0;width:42px;text-align:center;">
                            <div style="font-size:.75rem;font-weight:700;color:#5e72e4;">
                                {{ $sesion->fecha_sesion->format('d') }}
                            </div>
                            <div style="font-size:.65rem;color:#94a3b8;text-transform:uppercase;">
                                {{ $sesion->fecha_sesion->format('M') }}
                            </div>
                            <div style="font-size:.65rem;color:#94a3b8;">
                                {{ $sesion->fecha_sesion->format('Y') }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}"
                                       style="font-size:.88rem;font-weight:600;color:#1e293b;text-decoration:none;">
                                        {{ $sesion->titulo }}
                                    </a>
                                    @if($sesion->plantilla)
                                        <div style="font-size:.75rem;color:#94a3b8;">{{ $sesion->plantilla->nombre }}</div>
                                    @endif
                                    @if($sesion->especialista)
                                        <div style="font-size:.75rem;color:#64748b;">
                                            <i class="fas fa-user-md me-1"></i>{{ $sesion->especialista->nombre ?? 'Especialista' }}
                                        </div>
                                    @endif
                                </div>
                                <span class="s-pill {{ $sesion->estado_badge }}">{{ ucfirst($sesion->estado) }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            <a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}"
                               class="act-btn ab-blue" title="Ver sesión">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('ecd.sesiones.edit', [$paciente, $sesion]) }}"
                               class="act-btn ab-yellow" title="Editar sesión">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="act-btn ab-red" title="Eliminar sesión"
                                    onclick="deleteSesion({{ $sesion->id }}, '{{ addslashes($sesion->titulo) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="ds-{{ $sesion->id }}"
                                  action="{{ route('ecd.sesiones.destroy', [$paciente, $sesion]) }}"
                                  method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3" style="font-size:.88rem;">
                        No hay sesiones registradas para este paciente.
                    </p>
                @endforelse

                @if($totalSesiones > 10)
                    <p class="text-center" style="font-size:.8rem;color:#94a3b8;margin-top:.5rem;">
                        Mostrando las 10 más recientes de {{ $totalSesiones }} sesiones.
                    </p>
                @endif
            </div>
        </div>

        {{-- Right sidebar: summary & history --}}
        <div class="col-lg-4">
            {{-- Quick stats --}}
            <div class="surface p-4 mb-3">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                    Resumen
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size:.85rem;">
                    <span style="color:#64748b;">Total sesiones</span>
                    <span class="fw-bold">{{ $totalSesiones }}</span>
                </div>
                @if($paciente->expediente?->ultima_visita)
                    <div class="d-flex justify-content-between mb-2" style="font-size:.85rem;">
                        <span style="color:#64748b;">Última visita</span>
                        <span>{{ $paciente->expediente->ultima_visita->format('d/m/Y') }}</span>
                    </div>
                @endif
                @if($paciente->expediente?->proxima_cita ?? $paciente->sesiones->first()?->proxima_cita)
                    @php $proxCita = $paciente->sesiones->first()?->proxima_cita @endphp
                    @if($proxCita)
                        <div class="d-flex justify-content-between mb-2" style="font-size:.85rem;">
                            <span style="color:#64748b;">Próxima cita</span>
                            <span class="{{ $proxCita->isPast() ? 'text-danger' : '' }}">{{ $proxCita->format('d/m/Y') }}</span>
                        </div>
                    @endif
                @endif
                <div class="d-flex justify-content-between" style="font-size:.85rem;">
                    <span style="color:#64748b;">Alertas activas</span>
                    <span>{{ $paciente->alertas->count() }}</span>
                </div>
            </div>

            {{-- Expediente shortcuts --}}
            <div class="surface p-4">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                    Historia clínica
                </div>
                @if($paciente->expediente)
                    @if($paciente->expediente->alergias)
                        <div class="mb-2">
                            <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Alergias</div>
                            <div style="font-size:.84rem;color:#1e293b;">{{ $paciente->expediente->alergias }}</div>
                        </div>
                    @endif
                    @if($paciente->expediente->medicamentos_actuales)
                        <div class="mb-2">
                            <div style="font-size:.72rem;font-weight:600;text-transform:uppercase;color:#94a3b8;">Medicamentos</div>
                            <div style="font-size:.84rem;color:#1e293b;">{{ $paciente->expediente->medicamentos_actuales }}</div>
                        </div>
                    @endif
                @else
                    <p style="font-size:.82rem;color:#94a3b8;">Sin historia clínica registrada.</p>
                @endif
                <a href="{{ route('ecd.pacientes.historia', $paciente) }}" class="s-btn-sec w-100 text-center mt-2">
                    <i class="fas fa-notes-medical me-1"></i> Ver / editar historia
                </a>
            </div>

            {{-- Portal del paciente --}}
            <div class="surface p-4 mt-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
                        Portal del paciente
                    </div>
                    @if($paciente->hasActivePortalToken())
                        <span class="s-pill pill-green" style="font-size:.68rem;">Activo</span>
                    @else
                        <span class="s-pill pill-red" style="font-size:.68rem;">Sin acceso</span>
                    @endif
                </div>
                @if($paciente->hasActivePortalToken())
                    <div style="font-size:.78rem;color:#64748b;margin-bottom:.5rem;">
                        Expira: {{ $paciente->portal_token_expires_at?->format('d/m/Y') }}
                    </div>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('ecd.portal.generate', $paciente) }}" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="s-btn-sec w-100" style="font-size:.78rem;">
                                <i class="fas fa-sync-alt me-1"></i> Renovar enlace
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ecd.portal.revoke', $paciente) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn ab-red" title="Revocar acceso"
                                    onclick="return confirm('¿Revocar acceso al portal?')">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <p style="font-size:.82rem;color:#94a3b8;margin-bottom:.5rem;">
                        El paciente no tiene acceso activo al portal.
                    </p>
                    <form method="POST" action="{{ route('ecd.portal.generate', $paciente) }}">
                        @csrf
                        <button type="submit" class="s-btn-primary w-100" style="font-size:.82rem;">
                            <i class="fas fa-link me-1"></i> Generar enlace de acceso
                        </button>
                    </form>
                @endif
            </div>

            {{-- Alertas panel --}}
            <div class="surface p-4 mt-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
                        Alertas activas
                    </div>
                    <button class="act-btn ab-green" data-bs-toggle="modal" data-bs-target="#alertaModal" title="Nueva alerta" style="width:24px;height:24px;font-size:.65rem;">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                @forelse($paciente->alertas as $alerta)
                    <div class="d-flex align-items-start justify-content-between mb-2 pb-2" style="border-bottom:1px solid #f1f5f9;">
                        <div>
                            <span class="s-pill {{ $alerta->badge_class }}" style="font-size:.68rem;">{{ $alerta->tipo }}</span>
                            @if($alerta->descripcion)
                                <div style="font-size:.78rem;color:#64748b;margin-top:.2rem;">{{ $alerta->descripcion }}</div>
                            @endif
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0 ms-2">
                            <form action="{{ route('ecd.alertas.resolve', [$paciente, $alerta]) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="act-btn ab-green" title="Resolver" style="width:22px;height:22px;font-size:.6rem;">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('ecd.alertas.destroy', [$paciente, $alerta]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn ab-red" title="Eliminar" style="width:22px;height:22px;font-size:.6rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="font-size:.8rem;color:#94a3b8;margin:0;">Sin alertas activas.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Portal modal --}}
    <div class="modal fade" id="portalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                    <h5 class="modal-title" style="font-size:.95rem;font-weight:700;">
                        <i class="fas fa-share-alt me-2" style="color:#5e72e4;"></i>Portal del paciente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    @if($paciente->hasActivePortalToken())
                        <p style="font-size:.88rem;color:#64748b;margin-bottom:1rem;">
                            Comparte este enlace con el paciente. Expira el <strong>{{ $paciente->portal_token_expires_at?->format('d/m/Y') }}</strong>.
                        </p>
                        @php $portalUrl = route('portal.paciente.show', $paciente->portal_token) @endphp
                        <div class="d-flex gap-2">
                            <input type="text" class="filter-input flex-grow-1" id="portal-url-input"
                                   value="{{ $portalUrl }}" readonly style="font-size:.78rem;">
                            <button class="act-btn ab-blue flex-shrink-0" onclick="copyPortalLink('{{ $portalUrl }}')" title="Copiar">
                                <i class="fas fa-copy" id="copy-icon"></i>
                            </button>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ $portalUrl }}" target="_blank" class="s-btn-sec flex-grow-1 text-center" style="font-size:.82rem;">
                                <i class="fas fa-external-link-alt me-1"></i> Vista previa
                            </a>
                            @if($paciente->email)
                                <a href="mailto:{{ $paciente->email }}?subject=Tu portal de seguimiento&body={{ urlencode('Hola ' . $paciente->nombre . ', accede a tu portal de seguimiento: ' . $portalUrl) }}"
                                   class="act-btn ab-teal flex-shrink-0" title="Enviar por email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($paciente->telefono)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $paciente->telefono) }}?text={{ urlencode('Hola ' . $paciente->nombre . ', accede a tu portal de seguimiento: ' . $portalUrl) }}"
                                   target="_blank" class="act-btn ab-green flex-shrink-0" title="Enviar por WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-lock fa-2x mb-3" style="color:#94a3b8;"></i>
                            <p style="font-size:.88rem;color:#64748b;">El paciente no tiene acceso activo al portal.</p>
                            <form method="POST" action="{{ route('ecd.portal.generate', $paciente) }}">
                                @csrf
                                <button type="submit" class="s-btn-primary">
                                    <i class="fas fa-link me-1"></i> Generar enlace de acceso
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Nueva alerta modal --}}
    <div class="modal fade" id="alertaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px;border:none;">
                <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                    <h5 class="modal-title" style="font-size:.95rem;font-weight:700;">Nueva alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('ecd.alertas.store', $paciente) }}" method="POST">
                    @csrf
                    <div class="modal-body" style="padding:1.5rem;">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="filter-label">Tipo de alerta *</label>
                                <input type="text" name="tipo" class="filter-input" required
                                       placeholder="Ej: Alergia a lidocaína, Keloides...">
                            </div>
                            <div class="col-md-4">
                                <label class="filter-label">Nivel</label>
                                <select name="nivel" class="filter-input">
                                    <option value="danger">Peligro</option>
                                    <option value="warning">Advertencia</option>
                                    <option value="info">Informativo</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="filter-label">Descripción</label>
                                <textarea name="descripcion" class="filter-input" rows="3" placeholder="Detalles adicionales..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:1rem 1.5rem;">
                        <button type="button" class="s-btn-sec" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="s-btn-primary"><i class="fas fa-save me-1"></i> Guardar alerta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    function deleteSesion(id, titulo) {
        Swal.fire({
            title: '¿Eliminar sesión?',
            text: `Se eliminará "${titulo}" y todos sus registros.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => { if (r.isConfirmed) document.getElementById('ds-' + id).submit(); });
    }

    function copyPortalLink(url) {
        navigator.clipboard.writeText(url).then(function() {
            var icon = document.getElementById('copy-icon');
            if (icon) { icon.className = 'fas fa-check'; setTimeout(() => icon.className = 'fas fa-copy', 1800); }
        });
    }
</script>
@endsection
