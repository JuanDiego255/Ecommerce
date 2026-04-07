<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expediente — {{ $paciente->nombre_completo }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; color: #1e293b; background: #fff; }
        .page { max-width: 820px; margin: 0 auto; padding: 2.5rem 2rem; }

        .rpt-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #5e72e4; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .rpt-clinic  { font-size: 1.1rem; font-weight: 700; color: #5e72e4; }

        /* Patient card */
        .patient-card { display: flex; gap: 1.25rem; align-items: flex-start; background: #f8fafc; border-radius: 12px; padding: 1.1rem 1.3rem; margin-bottom: 1.5rem; }
        .patient-card img { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .patient-name { font-size: 1.05rem; font-weight: 700; margin-bottom: .2rem; }
        .patient-meta { font-size: .76rem; color: #64748b; line-height: 1.6; }

        /* Section */
        .section { margin-bottom: 1.6rem; page-break-inside: avoid; }
        .section-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; border-bottom: 1px solid #f1f5f9; padding-bottom: .3rem; margin-bottom: .8rem; }

        /* Conditions */
        .condition-grid { display: flex; flex-wrap: wrap; gap: .4rem; }
        .condition-chip { padding: 2px 8px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
        .chip-danger  { background: #fee2e2; color: #991b1b; }
        .chip-warning { background: #fef9c3; color: #92400e; }
        .chip-info    { background: #dbeafe; color: #1e40af; }

        /* Fields */
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        .field-block { margin-bottom: .4rem; }
        .field-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; }
        .field-value { font-size: .85rem; color: #1e293b; white-space: pre-wrap; }

        /* Sessions timeline */
        .session-row { border-left: 3px solid #e2e8f0; padding-left: .9rem; margin-bottom: 1.1rem; page-break-inside: avoid; }
        .session-row.completada { border-color: #10b981; }
        .session-row.cancelada  { border-color: #ef4444; }
        .session-date { font-size: .72rem; font-weight: 700; color: #5e72e4; }
        .session-titulo { font-size: .9rem; font-weight: 700; margin-bottom: .15rem; }
        .session-sub  { font-size: .75rem; color: #64748b; }

        /* Consents */
        .consent-row { display: flex; justify-content: space-between; font-size: .82rem; border-bottom: 1px solid #f1f5f9; padding: .4rem 0; }

        /* Footer */
        .rpt-footer { margin-top: 2.5rem; border-top: 1px solid #f1f5f9; padding-top: .75rem; display: flex; justify-content: space-between; font-size: .7rem; color: #94a3b8; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page { padding: 1.5rem 1rem; }
            .session-row { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print button --}}
    <div class="no-print" style="text-align:right;margin-bottom:1rem;">
        <button onclick="window.print()" style="background:#5e72e4;color:#fff;border:none;padding:.5rem 1.2rem;border-radius:8px;font-size:.85rem;cursor:pointer;">
            Imprimir / Guardar PDF
        </button>
        <a href="{{ route('ecd.pacientes.show', $paciente) }}"
           style="margin-left:.5rem;background:#f1f5f9;color:#475569;border:none;padding:.5rem 1.2rem;border-radius:8px;font-size:.85rem;text-decoration:none;display:inline-block;">
            ← Volver
        </a>
    </div>

    {{-- Header --}}
    <div class="rpt-header">
        <div>
            <div class="rpt-clinic">{{ config('app.name', 'Clínica') }}</div>
            <div style="font-size:.78rem;color:#475569;margin-top:.2rem;">Expediente Clínico Digital Completo</div>
        </div>
        <div style="font-size:.75rem;color:#94a3b8;text-align:right;">
            Generado: {{ now()->format('d/m/Y H:i') }}<br>
            @if($paciente->expediente) Exp. #{{ $paciente->expediente->numero_expediente }} @endif
        </div>
    </div>

    {{-- Patient --}}
    <div class="patient-card">
        <img src="{{ $paciente->foto_url }}" alt="{{ $paciente->nombre_completo }}">
        <div>
            <div class="patient-name">{{ $paciente->nombre_completo }}</div>
            <div class="patient-meta">
                @if($paciente->cedula) <strong>Cédula:</strong> {{ $paciente->cedula }}<br> @endif
                @if($paciente->fecha_nacimiento) <strong>Fecha nac.:</strong> {{ $paciente->fecha_nacimiento->format('d/m/Y') }} ({{ $paciente->edad }} años)<br> @endif
                @if($paciente->telefono) <strong>Tel:</strong> {{ $paciente->telefono }} @endif
                @if($paciente->email) &nbsp;·&nbsp; {{ $paciente->email }} @endif
                @if($paciente->ocupacion) <br><strong>Ocupación:</strong> {{ $paciente->ocupacion }} @endif
            </div>
            @if($paciente->expediente)
                <div style="font-size:.72rem;color:#5e72e4;margin-top:.4rem;">
                    Apertura: {{ $paciente->expediente->fecha_apertura?->format('d/m/Y') ?? '—' }}
                    @if($paciente->expediente->ultima_visita)
                        &nbsp;·&nbsp; Última visita: {{ $paciente->expediente->ultima_visita->format('d/m/Y') }}
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Alerts --}}
    @if($paciente->alertas->count())
        <div class="section">
            <div class="section-title">Alertas activas</div>
            <div class="condition-grid">
                @foreach($paciente->alertas as $alerta)
                    <span class="condition-chip chip-{{ $alerta->nivel }}">{{ $alerta->tipo }}</span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Medical history --}}
    @if($paciente->expediente)
        @php $exp = $paciente->expediente; @endphp
        @if(count($exp->condiciones_activas))
            <div class="section">
                <div class="section-title">Condiciones médicas</div>
                <div class="condition-grid">
                    @foreach($exp->condiciones_activas as $cond)
                        <span class="condition-chip chip-{{ $cond['level'] }}">{{ $cond['label'] }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($exp->alergias || $exp->medicamentos_actuales || $exp->antecedentes_esteticos || $exp->condiciones_medicas)
            <div class="section">
                <div class="section-title">Historial médico</div>
                <div class="field-grid">
                    @if($exp->alergias)
                        <div class="field-block">
                            <div class="field-label">Alergias</div>
                            <div class="field-value">{{ $exp->alergias }}</div>
                        </div>
                    @endif
                    @if($exp->medicamentos_actuales)
                        <div class="field-block">
                            <div class="field-label">Medicamentos actuales</div>
                            <div class="field-value">{{ $exp->medicamentos_actuales }}</div>
                        </div>
                    @endif
                    @if($exp->condiciones_medicas)
                        <div class="field-block">
                            <div class="field-label">Otras condiciones</div>
                            <div class="field-value">{{ $exp->condiciones_medicas }}</div>
                        </div>
                    @endif
                    @if($exp->antecedentes_esteticos)
                        <div class="field-block">
                            <div class="field-label">Antecedentes estéticos</div>
                            <div class="field-value">{{ $exp->antecedentes_esteticos }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    {{-- Sessions --}}
    <div class="section">
        <div class="section-title">Sesiones completadas ({{ $paciente->sesiones->count() }})</div>
        @forelse($paciente->sesiones as $s)
            <div class="session-row {{ $s->estado }}">
                <div class="session-date">{{ $s->fecha_sesion->format('d/m/Y') }}</div>
                <div class="session-titulo">{{ $s->titulo }}</div>
                <div class="session-sub">
                    @if($s->especialista) <span>{{ $s->especialista->nombre ?? '' }}</span> @endif
                    @if($s->plantilla) &nbsp;·&nbsp; <span>{{ $s->plantilla->nombre }}</span> @endif
                    @if($s->imagenes->count()) &nbsp;·&nbsp; <span>{{ $s->imagenes->count() }} imágenes</span> @endif
                </div>
                @if($s->observaciones_post)
                    <div style="font-size:.78rem;color:#475569;margin-top:.2rem;">{{ Str::limit($s->observaciones_post, 180) }}</div>
                @endif
                @if($s->recomendaciones)
                    <div style="font-size:.75rem;color:#64748b;margin-top:.15rem;"><em>Recom.: {{ Str::limit($s->recomendaciones, 120) }}</em></div>
                @endif
            </div>
        @empty
            <p style="color:#94a3b8;font-size:.82rem;">Sin sesiones completadas registradas.</p>
        @endforelse
    </div>

    {{-- Consents --}}
    @if($paciente->consentimientosFirmados->count())
        <div class="section">
            <div class="section-title">Consentimientos firmados ({{ $paciente->consentimientosFirmados->count() }})</div>
            @foreach($paciente->consentimientosFirmados as $cf)
                <div class="consent-row">
                    <span>{{ $cf->plantilla?->nombre ?? '—' }}</span>
                    <span style="color:#64748b;">{{ $cf->firmado_en?->format('d/m/Y H:i') ?? '—' }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Footer --}}
    <div class="rpt-footer">
        <span>{{ $paciente->nombre_completo }} @if($paciente->cedula) · {{ $paciente->cedula }} @endif</span>
        <span>Expediente Clínico Digital · Generado {{ now()->format('d/m/Y') }}</span>
    </div>

</div>
</body>
</html>
