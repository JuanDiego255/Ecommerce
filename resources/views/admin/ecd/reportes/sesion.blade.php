<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sesión — {{ $paciente->nombre_completo }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 13px; color: #1e293b; background: #fff; }
        .page { max-width: 800px; margin: 0 auto; padding: 2.5rem 2rem; }

        /* Header */
        .rpt-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #5e72e4; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .rpt-clinic  { font-size: 1.1rem; font-weight: 700; color: #5e72e4; }
        .rpt-date    { font-size: .75rem; color: #94a3b8; text-align: right; }

        /* Patient row */
        .patient-row { display: flex; gap: 1rem; align-items: center; background: #f8fafc; border-radius: 10px; padding: .9rem 1.1rem; margin-bottom: 1.5rem; }
        .patient-row img { width: 54px; height: 54px; border-radius: 50%; object-fit: cover; }
        .patient-name { font-size: 1rem; font-weight: 700; }
        .patient-meta { font-size: .75rem; color: #64748b; margin-top: .2rem; }

        /* Session header */
        .session-title { font-size: 1rem; font-weight: 700; margin-bottom: .25rem; }
        .session-meta  { font-size: .78rem; color: #64748b; margin-bottom: 1.2rem; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: .7rem; font-weight: 600; }
        .badge-borrador  { background: #fef9c3; color: #92400e; }
        .badge-completada{ background: #d1fae5; color: #065f46; }
        .badge-cancelada { background: #fee2e2; color: #991b1b; }

        /* Sections */
        .section { margin-bottom: 1.4rem; }
        .section-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; border-bottom: 1px solid #f1f5f9; padding-bottom: .3rem; margin-bottom: .75rem; }
        .field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        .field-block { margin-bottom: .5rem; }
        .field-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; color: #94a3b8; }
        .field-value { font-size: .85rem; color: #1e293b; white-space: pre-wrap; }
        .full-width   { grid-column: 1 / -1; }

        /* Images */
        .img-grid { display: flex; flex-wrap: wrap; gap: .5rem; }
        .img-cell { width: 120px; }
        .img-cell img { width: 120px; height: 90px; object-fit: cover; border-radius: 6px; }
        .img-tipo { font-size: .65rem; color: #64748b; margin-top: 2px; text-align: center; }

        /* Signature */
        .signature-box { border: 1px dashed #cbd5e0; border-radius: 8px; padding: 1rem; margin-top: .5rem; min-height: 70px; text-align: center; color: #94a3b8; font-size: .8rem; }

        /* Footer */
        .rpt-footer { margin-top: 2rem; border-top: 1px solid #f1f5f9; padding-top: .75rem; display: flex; justify-content: space-between; font-size: .7rem; color: #94a3b8; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page { padding: 1.5rem 1rem; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Print button --}}
    <div class="no-print" style="text-align:right;margin-bottom:1rem;">
        <button onclick="window.print()" style="background:#5e72e4;color:#fff;border:none;padding:.5rem 1.2rem;border-radius:8px;font-size:.85rem;cursor:pointer;">
            <i class="fas fa-print" style="margin-right:.35rem;"></i> Imprimir / Guardar PDF
        </button>
        <a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}"
           style="margin-left:.5rem;background:#f1f5f9;color:#475569;border:none;padding:.5rem 1.2rem;border-radius:8px;font-size:.85rem;cursor:pointer;text-decoration:none;display:inline-block;">
            ← Volver
        </a>
    </div>

    {{-- Header --}}
    <div class="rpt-header">
        <div>
            <div class="rpt-clinic">{{ config('app.name', 'Clínica') }}</div>
            <div style="font-size:.78rem;color:#475569;margin-top:.2rem;">Expediente Clínico Digital</div>
        </div>
        <div class="rpt-date">
            Generado: {{ now()->format('d/m/Y H:i') }}<br>
            Exp. #{{ $paciente->expediente?->numero_expediente ?? '—' }}
        </div>
    </div>

    {{-- Patient info --}}
    <div class="patient-row">
        <img src="{{ $paciente->foto_url }}" alt="{{ $paciente->nombre_completo }}">
        <div>
            <div class="patient-name">{{ $paciente->nombre_completo }}</div>
            <div class="patient-meta">
                @if($paciente->cedula) Cédula: {{ $paciente->cedula }} &nbsp;·&nbsp; @endif
                @if($paciente->edad) {{ $paciente->edad }} años &nbsp;·&nbsp; @endif
                @if($paciente->telefono) {{ $paciente->telefono }} @endif
            </div>
        </div>
    </div>

    {{-- Session header --}}
    <div class="session-title">{{ $sesion->titulo }}</div>
    <div class="session-meta">
        <span class="badge badge-{{ $sesion->estado }}">{{ ucfirst($sesion->estado) }}</span>
        &nbsp; {{ $sesion->fecha_sesion->format('d/m/Y') }}
        @if($sesion->hora_inicio) &nbsp;·&nbsp; {{ $sesion->hora_inicio }}@if($sesion->hora_fin) – {{ $sesion->hora_fin }}@endif @endif
        @if($sesion->especialista) &nbsp;·&nbsp; {{ $sesion->especialista->nombre ?? '' }} @endif
        @if($sesion->plantilla) &nbsp;·&nbsp; {{ $sesion->plantilla->nombre }} @endif
    </div>

    {{-- Template responses --}}
    @if($sesion->plantilla && $sesion->respuestas->count() && count($camposPlano))
        <div class="section">
            <div class="section-title">{{ $sesion->plantilla->nombre }}</div>
            @php $respMap = $sesion->respuestas->keyBy('campo_key'); @endphp
            <div class="field-grid">
                @foreach($camposPlano as $key => $campo)
                    @if($respMap->has($key))
                        <div class="field-block {{ in_array($campo['ancho'] ?? '', ['completo']) ? 'full-width' : '' }}">
                            <div class="field-label">{{ $campo['etiqueta'] ?? $key }}</div>
                            <div class="field-value">{{ $respMap[$key]->valor ?: '—' }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Clinical notes --}}
    @if($sesion->observaciones_pre || $sesion->observaciones_post || $sesion->productos_usados || $sesion->recomendaciones)
        <div class="section">
            <div class="section-title">Notas clínicas</div>
            <div class="field-grid">
                @if($sesion->observaciones_pre)
                    <div class="field-block">
                        <div class="field-label">Pre-sesión</div>
                        <div class="field-value">{{ $sesion->observaciones_pre }}</div>
                    </div>
                @endif
                @if($sesion->observaciones_post)
                    <div class="field-block">
                        <div class="field-label">Post-sesión</div>
                        <div class="field-value">{{ $sesion->observaciones_post }}</div>
                    </div>
                @endif
                @if($sesion->productos_usados)
                    <div class="field-block">
                        <div class="field-label">Productos utilizados</div>
                        <div class="field-value">{{ $sesion->productos_usados }}</div>
                    </div>
                @endif
                @if($sesion->recomendaciones)
                    <div class="field-block">
                        <div class="field-label">Recomendaciones</div>
                        <div class="field-value">{{ $sesion->recomendaciones }}</div>
                    </div>
                @endif
            </div>
            @if($sesion->proxima_cita)
                <div class="field-block" style="margin-top:.5rem;">
                    <div class="field-label">Próxima cita</div>
                    <div class="field-value">{{ $sesion->proxima_cita->format('d/m/Y') }}</div>
                </div>
            @endif
        </div>
    @endif

    {{-- Images --}}
    @if($sesion->imagenes->count())
        <div class="section">
            <div class="section-title">Imágenes ({{ $sesion->imagenes->count() }})</div>
            <div class="img-grid">
                @foreach($sesion->imagenes->take(8) as $img)
                    <div class="img-cell">
                        <img src="{{ $img->url }}" alt="{{ $img->tipo }}">
                        <div class="img-tipo">{{ $img->tipo_label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Signature --}}
    <div class="section">
        <div class="section-title">Firma del paciente</div>
        @if($sesion->firma_paciente_path)
            <img src="{{ route('file', $sesion->firma_paciente_path) }}" style="max-height:70px;border:1px solid #e2e8f0;border-radius:6px;background:#fff;">
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.25rem;">Firmado: {{ $sesion->firmado_en?->format('d/m/Y H:i') }}</div>
        @else
            <div class="signature-box">Sin firma registrada en esta sesión</div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="rpt-footer">
        <span>{{ $paciente->nombre_completo }} &middot; Exp. #{{ $paciente->expediente?->numero_expediente ?? '—' }}</span>
        <span>{{ $sesion->titulo }} · {{ $sesion->fecha_sesion->format('d/m/Y') }}</span>
    </div>

</div>
</body>
</html>
