@extends('portal.layout')
@section('title', 'Consentimiento firmado')

@section('content')

    {{-- Back link --}}
    <a href="{{ route('portal.paciente.show', $token) }}"
       style="display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;color:#64748b;text-decoration:none;margin-bottom:.9rem;">
        <i class="fas fa-arrow-left"></i> Volver a mi expediente
    </a>

    {{-- Header --}}
    <div class="p-card">
        <div class="d-flex align-items-start gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:#ede9fe;color:#7c3aed;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.1rem;">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <h5 class="mb-1" style="font-size:1rem;font-weight:700;">{{ $firmado->plantilla?->nombre ?? 'Consentimiento informado' }}</h5>
                <div style="font-size:.8rem;color:#64748b;">
                    <i class="fas fa-check-circle me-1" style="color:#2dce89;"></i>
                    Firmado el {{ $firmado->firmado_en?->isoFormat('D [de] MMMM, YYYY [a las] HH:mm') }}
                </div>
                @if($firmado->sesion)
                    <div style="font-size:.76rem;color:#94a3b8;margin-top:.15rem;">
                        <i class="fas fa-clipboard me-1"></i>{{ $firmado->sesion->titulo }}
                        &middot; {{ $firmado->sesion->fecha_sesion->format('d/m/Y') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Document content --}}
    <div class="p-card">
        <div class="p-section-label"><i class="fas fa-file-alt me-1"></i>Contenido del documento</div>
        <div style="font-size:.88rem;color:#1e293b;line-height:1.7;white-space:pre-line;">{{ $firmado->contenido_al_firmar }}</div>
    </div>

    {{-- Signature --}}
    @if($firmado->firma_path)
    <div class="p-card text-center">
        <div class="p-section-label"><i class="fas fa-signature me-1"></i>Firma del paciente</div>
        <div style="background:#f8fafc;border:1px dashed #e2e8f0;border-radius:10px;padding:1rem;display:inline-block;">
            <img src="{{ route('file', $firmado->firma_path) }}"
                 alt="Firma"
                 style="max-width:280px;max-height:120px;object-fit:contain;">
        </div>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:.5rem;">
            Firmado por: {{ $paciente->nombre_completo }}
        </div>
    </div>
    @endif

    {{-- Print button --}}
    <div class="text-center mt-2 mb-4">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
            <i class="fas fa-print me-1"></i> Imprimir / Guardar PDF
        </button>
    </div>

@endsection

@push('styles')
<style>
    @media print {
        .portal-nav, .portal-footer, a[href]:before, button { display: none !important; }
        body { background: #fff; }
        .p-card { box-shadow: none; border: 1px solid #eee; }
    }
</style>
@endpush
