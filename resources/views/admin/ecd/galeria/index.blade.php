@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Galería</li>
@endsection
@section('content')
<style>
.filter-tab { padding:.3rem .8rem;border-radius:20px;border:1.5px solid #e2e8f0;background:#fff;font-size:.82rem;cursor:pointer;transition:all .13s; }
.filter-tab.active { border-color:#5e72e4;background:#eef2ff;color:#5e72e4;font-weight:600; }
</style>

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Galería de imágenes</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">{{ $paciente->nombre_completo }} · {{ $grouped->flatten()->count() }} imágenes</p>
        </div>
        <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    {{-- Filter tabs --}}
    <div class="surface p-3 mb-3">
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <button class="filter-tab active" data-tipo="todos" onclick="filterTipo('todos', this)">
                Todos <span class="s-pill pill-blue ms-1" style="font-size:.65rem;">{{ $grouped->flatten()->count() }}</span>
            </button>
            @php
                $tipoLabels = ['antes' => 'Antes', 'durante' => 'Durante', 'despues' => 'Después', 'referencia' => 'Referencia'];
                $tipoPills  = ['antes' => 'pill-yellow', 'durante' => 'pill-blue', 'despues' => 'pill-green', 'referencia' => 'pill-red'];
            @endphp
            @foreach($tipos as $tipo)
                @if($grouped->has($tipo))
                    <button class="filter-tab" data-tipo="{{ $tipo }}" onclick="filterTipo('{{ $tipo }}', this)">
                        {{ $tipoLabels[$tipo] }}
                        <span class="s-pill {{ $tipoPills[$tipo] }} ms-1" style="font-size:.65rem;">{{ $grouped[$tipo]->count() }}</span>
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    @if($grouped->isEmpty())
        <div class="surface p-5 text-center">
            <i class="fas fa-images" style="font-size:2.5rem;color:#cbd5e0;"></i>
            <p class="mt-2 text-muted">No hay imágenes registradas para este paciente.</p>
        </div>
    @else
        @foreach($tipos as $tipo)
            @if($grouped->has($tipo))
                <div class="galeria-seccion mb-4" data-tipo="{{ $tipo }}">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="s-pill {{ $tipoPills[$tipo] }}">{{ $tipoLabels[$tipo] }}</span>
                        <span style="font-size:.75rem;color:#94a3b8;">{{ $grouped[$tipo]->count() }} imagen{{ $grouped[$tipo]->count() !== 1 ? 'es' : '' }}</span>
                    </div>
                    <div class="row g-2">
                        @foreach($grouped[$tipo] as $img)
                            <div class="col-6 col-md-3 col-lg-2">
                                <div style="position:relative;border-radius:10px;overflow:hidden;aspect-ratio:1;background:#f1f5f9;cursor:pointer;"
                                     onclick="openLightbox('{{ $img->url }}', '{{ addslashes($img->sesion?->titulo ?? '') }}', '{{ $img->sesion?->fecha_sesion?->format('d/m/Y') ?? '' }}', '{{ $tipoLabels[$img->tipo] }}')">
                                    <img src="{{ $img->url }}" alt="{{ $img->titulo }}"
                                         style="width:100%;height:100%;object-fit:cover;">
                                    @if($img->es_favorita)
                                        <span style="position:absolute;top:4px;left:4px;background:#fbbf24;border-radius:4px;padding:1px 5px;font-size:.6rem;color:#fff;">
                                            <i class="fas fa-star"></i>
                                        </span>
                                    @endif
                                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.55));padding:.4rem .5rem;">
                                        <div style="font-size:.65rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $img->sesion?->titulo ?? '—' }}
                                        </div>
                                        @if($img->zona_corporal)
                                            <div style="font-size:.6rem;color:rgba(255,255,255,.75);">{{ $img->zona_corporal }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    {{-- Lightbox --}}
    <div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;flex-direction:column;"
         onclick="closeLightbox()">
        <img id="lbImg" style="max-width:90vw;max-height:78vh;border-radius:8px;object-fit:contain;">
        <div style="color:#fff;margin-top:.75rem;text-align:center;" onclick="event.stopPropagation()">
            <div id="lbTitulo" style="font-size:.9rem;font-weight:600;"></div>
            <div id="lbMeta" style="font-size:.75rem;color:rgba(255,255,255,.6);margin-top:.2rem;"></div>
        </div>
    </div>

@endsection


@section('script')
<script>
    function filterTipo(tipo, btn) {
        document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.galeria-seccion').forEach(sec => {
            sec.style.display = (tipo === 'todos' || sec.dataset.tipo === tipo) ? '' : 'none';
        });
    }

    function openLightbox(src, titulo, fecha, tipo) {
        document.getElementById('lbImg').src = src;
        document.getElementById('lbTitulo').textContent = titulo || 'Imagen';
        document.getElementById('lbMeta').textContent   = [tipo, fecha].filter(Boolean).join(' · ');
        document.getElementById('lightbox').style.display = 'flex';
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endsection
