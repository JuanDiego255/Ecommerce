@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}">Sesión</a></li>
    <li class="breadcrumb-item active">Comparar</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Comparación antes / después</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">
                {{ $sesion->titulo }} · {{ $sesion->fecha_sesion->format('d/m/Y') }}
            </p>
        </div>
        <a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    @if($antes->isEmpty() && $despues->isEmpty())
        <div class="surface p-5 text-center">
            <i class="fas fa-images" style="font-size:2rem;color:#cbd5e0;"></i>
            <p class="mt-2 text-muted">Esta sesión no tiene imágenes de tipo "Antes" ni "Después".</p>
        </div>
    @else
        {{-- Slider comparison --}}
        <div class="surface p-4 mb-3">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                Vista comparativa de imágenes seleccionadas
            </div>

            <div class="row g-3 align-items-start mb-4">
                <div class="col-md-6">
                    <label class="filter-label">Imagen Antes</label>
                    <select class="filter-input" id="selectAntes" onchange="updateComparison()">
                        <option value="">— No seleccionada —</option>
                        @foreach($antes as $img)
                            <option value="{{ $img->url }}">
                                {{ $img->zona_corporal ?: 'Antes #' . ($loop->iteration) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="filter-label">Imagen Después</label>
                    <select class="filter-input" id="selectDespues" onchange="updateComparison()">
                        <option value="">— No seleccionada —</option>
                        @foreach($despues as $img)
                            <option value="{{ $img->url }}">
                                {{ $img->zona_corporal ?: 'Después #' . ($loop->iteration) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Side by side display --}}
            <div class="row g-3" id="comparisonDisplay" style="display:none!important;">
                <div class="col-md-6">
                    <div style="border-radius:10px;overflow:hidden;background:#f1f5f9;aspect-ratio:4/3;position:relative;">
                        <img id="imgAntes" style="width:100%;height:100%;object-fit:cover;" src="" alt="Antes">
                        <span style="position:absolute;top:8px;left:8px;" class="s-pill pill-yellow">Antes</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="border-radius:10px;overflow:hidden;background:#f1f5f9;aspect-ratio:4/3;position:relative;">
                        <img id="imgDespues" style="width:100%;height:100%;object-fit:cover;" src="" alt="Después">
                        <span style="position:absolute;top:8px;left:8px;" class="s-pill pill-green">Después</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- All images grid --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                        Antes ({{ $antes->count() }})
                    </div>
                    <div class="row g-2">
                        @forelse($antes as $img)
                            <div class="col-4">
                                <div style="border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f1f5f9;cursor:pointer;"
                                     onclick="openFull('{{ $img->url }}')">
                                    <img src="{{ $img->url }}" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                                @if($img->zona_corporal)
                                    <div style="font-size:.65rem;color:#94a3b8;text-align:center;margin-top:2px;">{{ $img->zona_corporal }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted" style="font-size:.82rem;">Sin imágenes de tipo "Antes".</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                        Después ({{ $despues->count() }})
                    </div>
                    <div class="row g-2">
                        @forelse($despues as $img)
                            <div class="col-4">
                                <div style="border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f1f5f9;cursor:pointer;"
                                     onclick="openFull('{{ $img->url }}')">
                                    <img src="{{ $img->url }}" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                                @if($img->zona_corporal)
                                    <div style="font-size:.65rem;color:#94a3b8;text-align:center;margin-top:2px;">{{ $img->zona_corporal }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted" style="font-size:.82rem;">Sin imágenes de tipo "Después".</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Lightbox --}}
    <div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;"
         onclick="this.style.display='none'">
        <img id="lbImg" style="max-width:90vw;max-height:90vh;border-radius:8px;object-fit:contain;">
    </div>

@endsection

@section('script')
<script>
    function updateComparison() {
        const antes   = document.getElementById('selectAntes').value;
        const despues = document.getElementById('selectDespues').value;
        const display = document.getElementById('comparisonDisplay');

        if (antes || despues) {
            display.style.setProperty('display', 'flex', 'important');
            display.classList.add('row');
            if (antes)   { document.getElementById('imgAntes').src   = antes; }
            if (despues) { document.getElementById('imgDespues').src = despues; }
        } else {
            display.style.setProperty('display', 'none', 'important');
        }
    }

    function openFull(src) {
        document.getElementById('lbImg').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.getElementById('lightbox').style.display = 'none';
    });
</script>
@endsection
