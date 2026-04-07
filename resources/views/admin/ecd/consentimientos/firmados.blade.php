@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Consentimientos firmados</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Consentimientos firmados</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">{{ $paciente->nombre_completo }}</p>
        </div>
        <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead class="thead-lite">
                    <tr>
                        <th>Consentimiento</th>
                        <th>Sesión</th>
                        <th>Firmado</th>
                        <th>Firma</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($firmados as $f)
                        <tr>
                            <td class="align-middle" style="font-size:.88rem;">
                                {{ $f->plantilla->nombre ?? '—' }}
                                <div style="font-size:.72rem;color:#94a3b8;">v{{ $f->plantilla->version ?? 1 }}</div>
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">
                                @if($f->sesion)
                                    <a href="{{ route('ecd.sesiones.show', [$paciente, $f->sesion]) }}">
                                        {{ $f->sesion->titulo }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">
                                {{ $f->firmado_en?->format('d/m/Y H:i') ?? '—' }}
                                @if($f->ip_firma)
                                    <div style="font-size:.72rem;color:#94a3b8;">IP: {{ $f->ip_firma }}</div>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($f->firma_path)
                                    <img src="{{ route('file', $f->firma_path) }}"
                                         alt="Firma"
                                         style="height:40px;border:1px solid #e2e8f0;border-radius:4px;background:#fff;cursor:pointer;"
                                         onclick="document.getElementById('lb-{{ $f->id }}').style.display='flex'">
                                    {{-- Lightbox --}}
                                    <div id="lb-{{ $f->id }}"
                                         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center;"
                                         onclick="this.style.display='none'">
                                        <div style="background:#fff;border-radius:12px;padding:1.5rem;max-width:420px;width:90%;">
                                            <p style="font-size:.8rem;color:#94a3b8;margin-bottom:.75rem;">{{ $f->plantilla->nombre ?? '' }} · {{ $f->firmado_en?->format('d/m/Y H:i') }}</p>
                                            <img src="{{ route('file', $f->firma_path) }}" style="width:100%;border:1px solid #e2e8f0;border-radius:6px;">
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:.8rem;">Sin imagen</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No hay consentimientos firmados para este paciente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
