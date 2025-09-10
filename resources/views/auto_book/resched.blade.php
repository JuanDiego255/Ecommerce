@extends('layouts.frontbarber')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
<style>
    /* Contenedor de cada opción */
    .svc-item {
        margin-bottom: .75rem;
    }

    /* Ocultamos el checkbox visualmente, pero accesible */
    .svc-check {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Tarjeta clickable */
    .svc-card {
        display: block;
        border: 1px solid #e5e7eb;
        /* gris suave */
        border-radius: 10px;
        padding: .9rem 1rem;
        background: #fff;
        cursor: pointer;
        transition: all .18s ease-in-out;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        user-select: none;
    }

    /* Título + meta */
    .svc-title {
        font-weight: 600;
        color: #1f2937;
    }

    /* gris 800 */
    .svc-meta {
        font-size: .875rem;
        color: #6b7280;
    }

    /* gris 500 */

    /* Hover/focus */
    .svc-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .07);
    }

    /* Estado seleccionado */
    .svc-check:checked+.svc-card {
        border-color: #6e00ff;
        /* tu “velvet” o primario */
        background: #f6f0ff;
        /* tint suave */
        box-shadow: 0 6px 18px rgba(110, 0, 255, .12);
    }

    /* Anillo de enfoque accesible al navegar con teclado */
    .svc-check:focus+.svc-card {
        outline: 3px solid rgba(110, 0, 255, .25);
        outline-offset: 2px;
    }

    /* Estado deshabilitado (si algún día marcas uno como no disponible) */
    .svc-check:disabled+.svc-card {
        opacity: .6;
        cursor: not-allowed;
        filter: grayscale(.15);
    }

    /* Layout interno opcional */
    .svc-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }

    .svc-price {
        font-weight: 700;
        color: #111827;
    }

    /* gris 900 */
    .svc-dot {
        color: #9ca3af;
        margin: 0 .25rem;
    }
</style>

@section('content')
    <main>
        <div class="slider-area position-relative fix">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">Reserva tu estilo, nosotros nos
                                        encargamos del resto ✨</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        Andres Barbería
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">Agendar cita con
                                        {{ $barbero->nombre }}</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stroke Text -->
            <div class="stock-text">
                <h2>Confianza en cada detalle</h2>
            </div>
        </div>
        <div class="container mt-3">
            <center>
                <h3>Reprogramar tu cita con {{ $barbero->nombre }}</h3>
            </center>

            @if (session('alert'))
                <div class="alert alert-{{ session('alert.type') }}">{{ session('alert.msg') }}</div>
            @endif

            <div class="card mt-3">
                <div class="card-body">
                    <form id="reschedForm" method="POST" action="{{ route('auto.resched.apply', $cita->id) }}">
                        @csrf

                        {{-- Fecha --}}
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" id="fecha" class="form-control" min="{{ now()->toDateString() }}"
                                required>
                        </div>

                        {{-- Selector de hora (llenas por AJAX con tus slots disponibles) --}}
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Hora</label>
                            <select id="hora" class="form-control" required>
                                <option value="">Selecciona una hora…</option>
                            </select>
                        </div>

                        {{-- Campos ocultos que enviamos al controlador --}}
                        <input type="hidden" name="start" id="startIso">
                        <input type="hidden" name="end" id="endIso">

                        <button type="submit" class="btn btn-velvet">Confirmar cambio</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        // Cargar horas por AJAX (reutiliza tu endpoint existente para getAvailableSlots)
        const fecha = document.getElementById('fecha');
        const horaSel = document.getElementById('hora');
        const tz = 'America/Costa_Rica';
        const slotMin = {{ (int) ($barbero->slot_minutes ?? 30) }};
        const durMin =
            {{ max(15, (int) ($cita->ends_at->diffInMinutes($cita->starts_at) ?: $barbero->slot_minutes ?? 30)) }};

        fecha.addEventListener('change', async () => {
            horaSel.innerHTML = '<option value="">Cargando…</option>';
            if (!fecha.value) return;

            try {
                // Ajusta a tu ruta real; por ejemplo: GET /barberos/{id}/slots?date=YYYY-MM-DD&duration=XX
                const url =
                    `{{ url('/barberos/' . $barbero->id . '/slots') }}?date=${fecha.value}&duration=${durMin}`;
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const json = await res.json();
                // json = [{start: 'YYYY-MM-DDTHH:mm:ss', end:'...'}]
                const opts = ['<option value="">Selecciona una hora…</option>'];
                json.forEach(s => {
                    const d = new Date(s.start.replace(' ', 'T'));
                    const hh = String(d.getHours()).padStart(2, '0');
                    const mm = String(d.getMinutes()).padStart(2, '0');
                    opts.push(
                        `<option data-start="${s.start}" data-end="${s.end}" value="${hh}:${mm}">${hh}:${mm}</option>`
                        );
                });
                horaSel.innerHTML = opts.join('');
            } catch (e) {
                console.error(e);
                horaSel.innerHTML = '<option value="">No hay horarios disponibles</option>';
            }
        });

        document.getElementById('reschedForm').addEventListener('submit', (e) => {
            const opt = horaSel.options[horaSel.selectedIndex];
            if (!opt || !opt.dataset.start) {
                e.preventDefault();
                alert('Selecciona fecha y hora');
                return;
            }
            document.getElementById('startIso').value = opt.dataset.start;
            document.getElementById('endIso').value = opt.dataset.end;
        });
    </script>
@endsection
