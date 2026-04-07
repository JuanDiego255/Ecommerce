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
                <a href="{{ route('ecd.pacientes.edit', $paciente) }}" class="act-btn ab-yellow" title="Editar datos">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('ecd.pacientes.historia', $paciente) }}" class="act-btn ab-blue" title="Historia clínica">
                    <i class="fas fa-heartbeat"></i>
                </a>
                <a href="{{ route('ecd.sesiones.create', $paciente) }}" class="s-btn-primary" style="font-size:.82rem;padding:.4rem .9rem;">
                    <i class="fas fa-plus me-1"></i> Nueva sesión
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
                @if($paciente->expediente?.proxima_cita ?? $paciente->sesiones->first()?->proxima_cita)
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
        </div>
    </div>

@endsection

@push('js')
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
</script>
@endpush
