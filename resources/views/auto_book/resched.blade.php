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
        <div class="container py-5">
            <center>
                <h3>Reprogramar tu cita con {{ $barbero->nombre }}</h3>
            </center>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget newsletter_widget">
                            <h2 class="mb-4 text-center">Completar formulario</h2>

                            @if (session('ok'))
                                <div class="alert alert-success">{{ session('ok') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <form id="reschedForm" method="POST" action="{{ route('auto.resched.apply', $cita->id) }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="date" class="form-control" name="fecha" id="fecha"
                                        min="{{ now()->toDateString() }}" onfocus="this.placeholder=''"
                                        onblur="this.placeholder='Selecciona fecha'" placeholder="Selecciona fecha"
                                        required>
                                </div>

                                {{-- Hora --}}
                                <div class="form-group mb-3">
                                    <select class="form-control" name="hora" id="hora" required>
                                        <option value="">Selecciona hora</option>
                                    </select>
                                </div>

                                {{-- Campos ocultos que enviamos al controlador --}}
                                <input type="hidden" name="start" id="startIso">
                                <input type="hidden" name="end" id="endIso">

                                <button type="submit" class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn">
                                    Confirmar cambio
                                </button>
                            </form>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        (function() {
            const dateEl = document.getElementById('fecha');
            const timeEl = document.getElementById('hora');

            function clearSelect() {
                timeEl.innerHTML = '<option value="">Selecciona hora</option>';
                if (window.jQuery && jQuery.fn.niceSelect && jQuery(timeEl).data('nice-select')) {
                    jQuery(timeEl).niceSelect('update');
                }
            }

            async function fetchSlots() {
                const date = dateEl.value;
                const sel = $('#time');
                // Limpia opciones
                sel.empty().append('<option value="">Selecciona hora</option>');
                clearSelect();
                if (!date) {
                    sel.niceSelect('update'); // refresca la UI
                    return;
                }

                const url = '{{ url('/barberos/' . $barbero->id . '/disponibilidad') }}' +
                    '?date=' + encodeURIComponent(date);

                try {
                    const res = await fetch(url);
                    const data = await res.json();

                    console.log(data);
                    (data.slots || []).forEach(h => {
                        sel.append(new Option(h, h));
                    });

                    sel.niceSelect('update'); // 🔑 refrescar aquí
                } catch (e) {
                    console.error(e);
                }
            }

            dateEl.addEventListener('change', fetchSlots);
            //checks.forEach(c => c.addEventListener('change', fetchSlots));
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
        })();
    </script>
@endsection
