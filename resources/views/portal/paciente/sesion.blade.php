@extends('portal.layout')
@section('title', $sesion->titulo)

@section('content')

    {{-- Back link --}}
    <a href="{{ route('portal.paciente.show', $token) }}"
       style="display:inline-flex;align-items:center;gap:.4rem;font-size:.82rem;color:#64748b;text-decoration:none;margin-bottom:.9rem;">
        <i class="fas fa-arrow-left"></i> Volver a mis sesiones
    </a>

    {{-- Session header --}}
    <div class="p-card">
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div>
                <h5 class="mb-1" style="font-size:1.05rem;font-weight:700;">{{ $sesion->titulo }}</h5>
                <div style="font-size:.8rem;color:#64748b;">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $sesion->fecha_sesion->isoFormat('dddd D [de] MMMM, YYYY') }}
                    @if($sesion->hora_inicio)
                        <span class="mx-1">·</span>
                        <i class="fas fa-clock me-1"></i>{{ $sesion->hora_inicio }}
                        @if($sesion->hora_fin) – {{ $sesion->hora_fin }} @endif
                    @endif
                </div>
                @if($sesion->especialista)
                    <div style="font-size:.78rem;color:#94a3b8;margin-top:.2rem;">
                        <i class="fas fa-user-md me-1"></i>{{ $sesion->especialista->nombre ?? '' }}
                    </div>
                @endif
            </div>
            <span class="pp pp-green flex-shrink-0"><i class="fas fa-check-circle"></i> Completada</span>
        </div>
    </div>

    {{-- Observations --}}
    @if($sesion->observaciones_pre || $sesion->observaciones_post)
    <div class="p-card">
        <div class="p-section-label">Observaciones</div>
        @if($sesion->observaciones_pre)
            <div class="mb-3">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:.3rem;">
                    Antes del procedimiento
                </div>
                <div style="font-size:.88rem;color:#1e293b;white-space:pre-line;">{{ $sesion->observaciones_pre }}</div>
            </div>
        @endif
        @if($sesion->observaciones_post)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;color:#94a3b8;margin-bottom:.3rem;">
                    Después del procedimiento
                </div>
                <div style="font-size:.88rem;color:#1e293b;white-space:pre-line;">{{ $sesion->observaciones_post }}</div>
            </div>
        @endif
    </div>
    @endif

    {{-- Products used --}}
    @if($sesion->productos_usados)
    <div class="p-card">
        <div class="p-section-label"><i class="fas fa-vial me-1"></i>Productos utilizados</div>
        <div style="font-size:.88rem;color:#1e293b;white-space:pre-line;">{{ $sesion->productos_usados }}</div>
    </div>
    @endif

    {{-- Recommendations --}}
    @if($sesion->recomendaciones)
    <div class="p-card" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;">
        <div class="p-section-label" style="color:#166534;"><i class="fas fa-star me-1"></i>Recomendaciones para ti</div>
        <div style="font-size:.9rem;color:#14532d;white-space:pre-line;">{{ $sesion->recomendaciones }}</div>
    </div>
    @endif

    {{-- Next appointment --}}
    @if($sesion->proxima_cita)
    <div class="next-appt">
        <div class="icon"><i class="fas fa-calendar-check"></i></div>
        <div>
            <div class="label">Próxima cita recomendada</div>
            <div class="date">{{ $sesion->proxima_cita->isoFormat('dddd D [de] MMMM, YYYY') }}</div>
        </div>
    </div>
    @endif

    {{-- Photo gallery --}}
    @if($sesion->imagenes->count())
    <div class="p-card">
        <div class="p-section-label"><i class="fas fa-images me-1"></i>Fotografías de la sesión ({{ $sesion->imagenes->count() }})</div>
        <div class="img-grid">
            @foreach($sesion->imagenes as $img)
                <div class="img-thumb" onclick="openLightbox('{{ $img->url }}', '{{ $img->titulo ?? $img->tipo_label }}')">
                    <img src="{{ $img->url }}" alt="{{ $img->titulo ?? $img->tipo_label }}" loading="lazy">
                    <span class="tipo-tag">{{ $img->tipo_label }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Lightbox --}}
    <div id="lightbox" class="lightbox-overlay" style="display:none;" onclick="closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
        <img id="lb-img" src="" alt="">
    </div>

@endsection

@push('scripts')
<script>
    function openLightbox(url, alt) {
        var lb = document.getElementById('lightbox');
        document.getElementById('lb-img').src = url;
        document.getElementById('lb-img').alt = alt;
        lb.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush
