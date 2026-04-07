@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.protocolos.index') }}">Protocolos</a></li>
    <li class="breadcrumb-item active">{{ $protocolo->nombre }}</li>
@endsection
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $nivelPills  = ['basico' => 'pill-green', 'intermedio' => 'pill-yellow', 'avanzado' => 'pill-red'];
        $nivelLabels = ['basico' => 'Básico', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
    @endphp

    <div class="surface p-4 mb-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h4 class="mb-0">{{ $protocolo->nombre }}</h4>
                    <span class="s-pill {{ $nivelPills[$protocolo->nivel_dificultad] ?? 'pill-blue' }}">
                        {{ $nivelLabels[$protocolo->nivel_dificultad] ?? $protocolo->nivel_dificultad }}
                    </span>
                    @if(!$protocolo->activo)
                        <span class="s-pill pill-red">Inactivo</span>
                    @endif
                </div>
                <div style="font-size:.82rem;color:#64748b;">
                    @if($protocolo->categoria) <span class="me-3"><i class="fas fa-tag me-1"></i>{{ $protocolo->categoria }}</span> @endif
                    @if($protocolo->duracion_estimada_min) <span><i class="fas fa-clock me-1"></i>{{ $protocolo->duracion_estimada_min }} min</span> @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ecd.protocolos.edit', $protocolo) }}" class="act-btn ab-yellow" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
        @if($protocolo->descripcion)
            <p style="font-size:.88rem;color:#475569;margin-top:.75rem;margin-bottom:0;">{{ $protocolo->descripcion }}</p>
        @endif
    </div>

    <div class="row g-3">
        {{-- Steps --}}
        <div class="col-lg-8">
            <div class="surface p-4">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                    Pasos del protocolo ({{ count($protocolo->pasos ?? []) }})
                </div>
                @forelse($protocolo->pasos ?? [] as $idx => $paso)
                    <div class="d-flex gap-3 mb-3 pb-3" style="{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}">
                        <div style="flex-shrink:0;width:32px;height:32px;border-radius:50%;background:#5e72e4;color:#fff;font-size:.78rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                            {{ $idx + 1 }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="fw-semibold" style="font-size:.88rem;">{{ $paso['titulo'] ?? 'Paso ' . ($idx + 1) }}</div>
                                @if(!empty($paso['duracion_min']))
                                    <span class="s-pill pill-blue" style="font-size:.7rem;">{{ $paso['duracion_min'] }} min</span>
                                @endif
                            </div>
                            @if(!empty($paso['descripcion']))
                                <p style="font-size:.84rem;color:#475569;margin-top:.25rem;margin-bottom:0;white-space:pre-line;">{{ $paso['descripcion'] }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3" style="font-size:.85rem;">Sin pasos definidos.</p>
                @endforelse
            </div>
        </div>

        {{-- Side info --}}
        <div class="col-lg-4">
            @if(!empty($protocolo->materiales_necesarios))
                <div class="surface p-4 mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                        Materiales necesarios
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($protocolo->materiales_necesarios as $mat)
                            <span style="padding:.3rem .65rem;background:#eef2ff;border-radius:20px;font-size:.8rem;color:#5e72e4;">
                                {{ $mat }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($protocolo->contraindicaciones)
                <div class="surface p-4 mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                        Contraindicaciones
                    </div>
                    <p style="font-size:.85rem;color:#475569;white-space:pre-line;margin:0;">{{ $protocolo->contraindicaciones }}</p>
                </div>
            @endif

            @if($protocolo->notas_post)
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                        Notas post-tratamiento
                    </div>
                    <p style="font-size:.85rem;color:#475569;white-space:pre-line;margin:0;">{{ $protocolo->notas_post }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
