@extends('portal.layout')
@section('title', 'Mi expediente')

@section('content')

    {{-- Hero header --}}
    <div class="p-card d-flex align-items-center gap-3">
        <img src="{{ $paciente->foto_url }}"
             style="width:70px;height:70px;border-radius:50%;object-fit:cover;flex-shrink:0;border:3px solid #e2e8f0;">
        <div>
            <h5 class="mb-0" style="font-size:1.1rem;font-weight:700;">{{ $paciente->nombre_completo }}</h5>
            <div style="font-size:.8rem;color:#64748b;margin-top:.2rem;">
                @if($paciente->edad) <span class="me-2"><i class="fas fa-birthday-cake me-1"></i>{{ $paciente->edad }} años</span> @endif
                @if($paciente->cedula) <span><i class="fas fa-id-card me-1"></i>{{ $paciente->cedula }}</span> @endif
            </div>
            @if($paciente->expediente)
                <div style="font-size:.72rem;color:#94a3b8;margin-top:.15rem;">
                    Expediente #{{ $paciente->expediente->numero_expediente }}
                </div>
            @endif
        </div>
    </div>

    {{-- Next appointment banner --}}
    @if($proximaCita)
        <div class="next-appt">
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="label">Próxima cita</div>
                <div class="date">{{ \Carbon\Carbon::parse($proximaCita)->isoFormat('dddd D [de] MMMM, YYYY') }}</div>
            </div>
        </div>
    @endif

    {{-- Stats row --}}
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="stat-tile">
                <div class="val">{{ $sesiones->count() }}</div>
                <div class="lbl">Sesiones</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-tile">
                <div class="val">{{ $firmados->count() }}</div>
                <div class="lbl">Consentimientos</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-tile">
                <div class="val">{{ $sesiones->sum(fn($s) => $s->imagenes->count()) }}</div>
                <div class="lbl">Fotos</div>
            </div>
        </div>
    </div>

    {{-- Sessions list --}}
    <div class="p-card">
        <div class="p-section-label"><i class="fas fa-clipboard-list me-1"></i>Mis sesiones</div>

        @forelse($sesiones as $sesion)
            <a href="{{ route('portal.paciente.sesion', [$token, $sesion->id]) }}" class="ses-item">
                <div class="ses-date">
                    <div class="d">{{ $sesion->fecha_sesion->format('d') }}</div>
                    <div class="m">{{ $sesion->fecha_sesion->isoFormat('MMM') }}</div>
                </div>
                <div class="flex-grow-1">
                    <div style="font-size:.9rem;font-weight:600;margin-bottom:.2rem;">{{ $sesion->titulo }}</div>
                    @if($sesion->especialista)
                        <div style="font-size:.76rem;color:#64748b;">
                            <i class="fas fa-user-md me-1"></i>{{ $sesion->especialista->nombre ?? '' }}
                        </div>
                    @endif
                    @if($sesion->recomendaciones)
                        <div style="font-size:.75rem;color:#94a3b8;margin-top:.15rem;" class="text-truncate">
                            {{ Str::limit($sesion->recomendaciones, 80) }}
                        </div>
                    @endif
                </div>
                <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                    @if($sesion->imagenes->count())
                        <span class="pp pp-blue"><i class="fas fa-camera"></i> {{ $sesion->imagenes->count() }}</span>
                    @endif
                    @if($sesion->proxima_cita)
                        <span class="pp pp-green" style="font-size:.65rem;"><i class="fas fa-calendar"></i> {{ $sesion->proxima_cita->format('d/m') }}</span>
                    @endif
                    <i class="fas fa-chevron-right" style="color:#cbd5e1;font-size:.7rem;margin-top:.2rem;"></i>
                </div>
            </a>
        @empty
            <div class="text-center py-4" style="color:#94a3b8;font-size:.88rem;">
                <i class="fas fa-clipboard fa-2x mb-2 d-block"></i>
                Aún no hay sesiones completadas registradas.
            </div>
        @endforelse
    </div>

    {{-- Signed consents --}}
    @if($firmados->count())
    <div class="p-card">
        <div class="p-section-label"><i class="fas fa-file-signature me-1"></i>Mis consentimientos firmados</div>
        @foreach($firmados as $firmado)
            <a href="{{ route('portal.paciente.consentimiento', [$token, $firmado->id]) }}" class="consent-item">
                <div class="consent-icon"><i class="fas fa-file-signature"></i></div>
                <div class="flex-grow-1">
                    <div style="font-size:.88rem;font-weight:600;">{{ $firmado->plantilla?->nombre ?? 'Consentimiento' }}</div>
                    <div style="font-size:.75rem;color:#64748b;">
                        Firmado el {{ $firmado->firmado_en?->format('d/m/Y') }}
                        @if($firmado->sesion) &middot; {{ $firmado->sesion->titulo }} @endif
                    </div>
                </div>
                <i class="fas fa-chevron-right" style="color:#cbd5e1;font-size:.75rem;"></i>
            </a>
        @endforeach
    </div>
    @endif

@endsection
