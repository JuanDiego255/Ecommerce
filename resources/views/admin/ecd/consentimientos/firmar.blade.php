@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Firma de consentimiento</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Consentimiento informado</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">
                {{ $plantilla->nombre }} · {{ $paciente->nombre_completo }} · {{ $sesion->titulo }}
            </p>
        </div>
        <a href="{{ route('ecd.sesiones.show', [$paciente, $sesion]) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="row g-3">
        {{-- Consent text --}}
        <div class="col-lg-7">
            <div class="surface p-4" id="consentContent">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                    Texto del consentimiento
                </div>
                <div style="font-size:.88rem;line-height:1.7;color:#1e293b;white-space:pre-line;">{{ $contenido }}</div>
            </div>
        </div>

        {{-- Signature pad --}}
        <div class="col-lg-5">
            <div class="surface p-4" style="position:sticky;top:80px;">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                    Firma del paciente
                </div>

                {{-- Patient confirmation --}}
                <div class="d-flex align-items-center gap-3 mb-3 p-3" style="background:#f8fafc;border-radius:10px;">
                    <img src="{{ $paciente->foto_url }}" style="width:42px;height:42px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div>
                        <div class="fw-semibold" style="font-size:.88rem;">{{ $paciente->nombre_completo }}</div>
                        @if($paciente->cedula)
                            <div style="font-size:.75rem;color:#94a3b8;">Cédula: {{ $paciente->cedula }}</div>
                        @endif
                    </div>
                </div>

                <div style="font-size:.78rem;color:#64748b;margin-bottom:.5rem;">
                    Firme con el dedo o mouse en el área de abajo:
                </div>

                <canvas id="firmaCanvas"
                        style="width:100%;height:180px;border:2px solid #e2e8f0;border-radius:10px;cursor:crosshair;background:#fff;touch-action:none;">
                </canvas>

                <div class="d-flex justify-content-between mt-2 mb-3">
                    <button type="button" id="clearBtn" class="s-btn-sec" style="font-size:.78rem;padding:.3rem .7rem;">
                        <i class="fas fa-eraser me-1"></i> Limpiar
                    </button>
                    <span id="firmaStatus" style="font-size:.75rem;color:#94a3b8;align-self:center;"></span>
                </div>

                <div style="font-size:.75rem;color:#94a3b8;margin-bottom:1rem;">
                    Al firmar, el paciente declara haber leído y comprendido el contenido del presente
                    consentimiento informado. Fecha y hora: <strong id="fechaHora"></strong>
                </div>

                <form action="{{ route('ecd.consentimientos.firmar.store', [$paciente, $sesion, $plantilla]) }}"
                      method="POST" id="firmaForm">
                    @csrf
                    <input type="hidden" name="firma_base64" id="firmaBase64">
                    <input type="hidden" name="contenido_al_firmar" value="{{ $contenido }}">

                    <button type="button" id="guardarFirmaBtn" class="s-btn-primary w-100" disabled>
                        <i class="fas fa-file-signature me-1"></i> Confirmar y guardar firma
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    // ── Date/time display ─────────────────────────────────────────────────────
    function updateFechaHora() {
        const now = new Date();
        document.getElementById('fechaHora').textContent = now.toLocaleString('es-CR');
    }
    updateFechaHora();
    setInterval(updateFechaHora, 1000);

    // ── Signature pad ─────────────────────────────────────────────────────────
    const canvas  = document.getElementById('firmaCanvas');
    const ctx     = canvas.getContext('2d');
    let drawing   = false;
    let hasFirma  = false;

    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        const ratio = window.devicePixelRatio || 1;
        canvas.width  = rect.width  * ratio;
        canvas.height = rect.height * ratio;
        ctx.scale(ratio, ratio);
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth   = 2.5;
        ctx.lineCap     = 'round';
        ctx.lineJoin    = 'round';
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const src  = e.touches ? e.touches[0] : e;
        return { x: src.clientX - rect.left, y: src.clientY - rect.top };
    }

    canvas.addEventListener('pointerdown', e => {
        drawing = true;
        const pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    });

    canvas.addEventListener('pointermove', e => {
        if (!drawing) return;
        const pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        hasFirma = true;
        document.getElementById('firmaStatus').textContent = 'Firma registrada ✓';
        document.getElementById('guardarFirmaBtn').disabled = false;
    });

    ['pointerup', 'pointerleave'].forEach(ev => {
        canvas.addEventListener(ev, () => { drawing = false; });
    });

    // Clear
    document.getElementById('clearBtn').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasFirma = false;
        document.getElementById('firmaStatus').textContent = '';
        document.getElementById('guardarFirmaBtn').disabled = true;
    });

    // Submit
    document.getElementById('guardarFirmaBtn').addEventListener('click', () => {
        if (!hasFirma) {
            Swal.fire({ icon: 'warning', title: 'Falta la firma', text: 'El paciente debe firmar en el área indicada.' });
            return;
        }

        Swal.fire({
            title: '¿Confirmar firma?',
            text: 'Una vez guardada, la firma no puede modificarse.',
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, guardar',
            confirmButtonColor: '#5e72e4',
        }).then(r => {
            if (!r.isConfirmed) return;
            // Export canvas as PNG base64
            document.getElementById('firmaBase64').value = canvas.toDataURL('image/png');
            document.getElementById('firmaForm').submit();
        });
    });
</script>
@endsection
