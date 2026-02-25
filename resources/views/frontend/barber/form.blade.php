@extends('layouts.frontbarber')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
<style>
    /* ── Service checkboxes ─────────────────────────────── */
    .svc-item { margin-bottom: .75rem; }
    .svc-check {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .svc-card {
        display: block;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: .9rem 1rem;
        background: #fff;
        cursor: pointer;
        transition: all .18s ease-in-out;
        box-shadow: 0 1px 2px rgba(0,0,0,.04);
        user-select: none;
        overflow: hidden;
    }
    .svc-title {
        flex: 1;
        font-weight: 600;
        color: #1f2937;
        word-break: break-word;
        white-space: normal;
    }
    .svc-meta { font-size: .875rem; color: #6b7280; }
    .svc-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 14px rgba(0,0,0,.07); }
    .svc-check:checked + .svc-card {
        border-color: #6e00ff;
        background: #f6f0ff;
        box-shadow: 0 6px 18px rgba(110,0,255,.12);
    }
    .svc-check:focus + .svc-card { outline: 3px solid rgba(110,0,255,.25); outline-offset: 2px; }
    .svc-check:disabled + .svc-card { opacity: .6; cursor: not-allowed; }
    .svc-row { display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; }
    .svc-price { font-weight: 700; color: #111827; }
    .svc-dot { color: #9ca3af; margin: 0 .25rem; }

    /* ── Step sections ──────────────────────────────────── */
    .booking-step {
        border-left: 3px solid #e5e7eb;
        padding: 1.25rem 1.25rem 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        border-radius: 0 8px 8px 0;
        background: #fafafa;
        transition: border-color .25s, opacity .25s;
    }
    .booking-step.step-active  { border-left-color: #6e00ff; background: #fff; }
    .booking-step.step-done    { border-left-color: #22c55e; background: #f0fdf4; }
    .booking-step.step-locked  { opacity: .45; pointer-events: none; }

    .step-header { display: flex; align-items: center; gap: .75rem; margin-bottom: .9rem; }
    .step-badge {
        width: 30px; height: 30px; border-radius: 50%;
        background: #6e00ff; color: #fff;
        font-weight: 700; font-size: .85rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .step-done .step-badge   { background: #22c55e; }
    .step-locked .step-badge { background: #9ca3af; }
    .step-header h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #1f2937; }

    /* ── Progress indicator ─────────────────────────────── */
    .booking-progress { display: flex; align-items: center; margin-bottom: 2rem; }
    .bp-step { display: flex; flex-direction: column; align-items: center; flex: 1; position: relative; }
    .bp-step:not(:last-child)::after {
        content: ''; position: absolute; top: 13px; left: 50%;
        width: 100%; height: 2px; background: #e5e7eb; z-index: 0;
    }
    .bp-step.done:not(:last-child)::after  { background: #22c55e; }
    .bp-num {
        width: 28px; height: 28px; border-radius: 50%;
        background: #e5e7eb; color: #6b7280;
        font-size: .8rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        z-index: 1; position: relative; transition: background .25s, color .25s;
    }
    .bp-step.active .bp-num { background: #6e00ff; color: #fff; }
    .bp-step.done .bp-num   { background: #22c55e; color: #fff; }
    .bp-label { font-size: .7rem; color: #9ca3af; margin-top: .25rem; text-align: center; line-height: 1.2; }
    .bp-step.active .bp-label { color: #6e00ff; font-weight: 600; }
    .bp-step.done .bp-label   { color: #22c55e; }

    /* ── Barber cards inside modal ──────────────────────── */
    .barber-option-card {
        border: 2px solid #e5e7eb; border-radius: 10px;
        padding: 1rem; cursor: pointer; transition: all .2s;
        background: #fff; text-align: center; height: 100%;
    }
    .barber-option-card:hover { border-color: #6e00ff; box-shadow: 0 4px 14px rgba(110,0,255,.1); }
    .barber-option-card.selected { border-color: #6e00ff; background: #f6f0ff; }
    .barber-option-card img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: .5rem; }
    .barber-option-card .barber-name { font-weight: 700; color: #1f2937; font-size: .95rem; }
    .barber-option-card .slots-badge {
        display: inline-block; font-size: .75rem;
        background: #dcfce7; color: #16a34a;
        border-radius: 99px; padding: .15rem .6rem;
        margin-top: .3rem; font-weight: 600;
    }

    /* ── Selected barber chip ───────────────────────────── */
    #selectedBarberoChip {
        display: flex; align-items: center; gap: .75rem;
        background: #f6f0ff; border: 1px solid #6e00ff;
        border-radius: 8px; padding: .65rem 1rem;
    }
    #selectedBarberoChip img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    #selectedBarberoChip .barber-chip-name { font-weight: 700; color: #1f2937; flex: 1; }

    .step-hint { font-size: .82rem; color: #9ca3af; margin-bottom: .75rem; }
    #submitBtn:disabled { opacity: .5; cursor: not-allowed; }
</style>

@php
    $heroImg    = 'hero-img';
    $barberName = 'Andrés Barbería';
    $col        = '6';
    switch ($tenantinfo->tenant) {
        case 'barberiajp':
            $heroImg    = 'hero-img-jp';
            $barberName = 'J.P Barbería';
            $col        = '3';
            break;
        case 'andresbarberiacr':
            $heroImg = 'hero-img-andres';
            break;
    }
    $totalSteps = $isGeneral ? 5 : 4;
@endphp

@section('content')
<main>
    {{-- Hero --}}
    <div class="slider-area position-relative fix pb-120 {{ $heroImg }}">
        <div class="slider-active">
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                            <div class="hero__caption">
                                <span data-animation="fadeInUp" data-delay="0.2s">
                                    Reserva tu estilo, nosotros nos encargamos del resto ✨
                                </span>
                                <h1 data-animation="fadeInUp" data-delay="0.5s">{{ $barberName }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                            <div class="hero__caption">
                                <span data-animation="fadeInUp" data-delay="0.2s">
                                    {{ $isGeneral ? 'Elige tu servicio y barbero ideal' : 'Agendar cita con ' . $barbero->nombre }}
                                </span>
                                <h1 data-animation="fadeInUp" data-delay="0.5s">
                                    {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="stock-text"><h2>Confianza en cada detalle</h2></div>
    </div>

    {{-- Form container --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget newsletter_widget">
                        <h2 class="mb-1 text-center">Agenda tu cita</h2>
                        <p class="text-center step-hint mb-4">
                            Sigue los {{ $totalSteps }} pasos para completar tu reserva
                        </p>

                        {{-- Progress bar --}}
                        <div class="booking-progress" id="bookingProgress">
                            @if ($isGeneral)
                                <div class="bp-step active" data-step="1"><div class="bp-num">1</div><div class="bp-label">Servicios</div></div>
                                <div class="bp-step" data-step="2"><div class="bp-num">2</div><div class="bp-label">Fecha</div></div>
                                <div class="bp-step" data-step="3"><div class="bp-num">3</div><div class="bp-label">Barbero</div></div>
                                <div class="bp-step" data-step="4"><div class="bp-num">4</div><div class="bp-label">Horario</div></div>
                                <div class="bp-step" data-step="5"><div class="bp-num">5</div><div class="bp-label">Tus datos</div></div>
                            @else
                                <div class="bp-step active" data-step="1"><div class="bp-num">1</div><div class="bp-label">Servicios</div></div>
                                <div class="bp-step" data-step="2"><div class="bp-num">2</div><div class="bp-label">Fecha</div></div>
                                <div class="bp-step" data-step="3"><div class="bp-num">3</div><div class="bp-label">Horario</div></div>
                                <div class="bp-step" data-step="4"><div class="bp-num">4</div><div class="bp-label">Tus datos</div></div>
                            @endif
                        </div>

                        @if (session('ok'))
                            <div class="alert alert-success">{{ session('ok') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        <form action="{{ url('/reservas') }}" method="POST" id="formBooking">
                            @csrf
                            {{-- For general barber, JS will set this when a barber is selected from modal --}}
                            <input type="hidden" name="barbero_id" id="barberoIdInput"
                                   value="{{ $isGeneral ? '' : $barbero->id }}">

                            {{-- ══ STEP 1: Services ══════════════════════════════ --}}
                            <div class="booking-step step-active" id="step1">
                                <div class="step-header">
                                    <div class="step-badge">1</div>
                                    <h5>¿Qué servicios deseas?</h5>
                                </div>
                                <p class="step-hint">Puedes elegir uno o varios servicios.</p>
                                <div class="row">
                                    @foreach ($servicios as $s)
                                        <div class="col-md-{{ $col }} svc-item">
                                            <input class="svc-check" type="checkbox"
                                                   id="srv{{ $s['id'] }}" value="{{ $s['id'] }}"
                                                   name="servicios[]">
                                            <label class="svc-card" for="srv{{ $s['id'] }}">
                                                <div class="svc-row">
                                                    <span class="svc-title">{{ $s['nombre'] }}</span>
                                                    <span class="svc-price">
                                                        ₡{{ number_format($s['price_cents'] / 100, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="svc-meta mt-1">
                                                    <span class="svc-dot">•</span> {{ $s['duration_minutes'] }} min
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ══ STEP 2: Date ══════════════════════════════════ --}}
                            <div class="booking-step step-locked" id="step2">
                                <div class="step-header">
                                    <div class="step-badge">2</div>
                                    <h5>Elige la fecha de tu cita</h5>
                                </div>
                                <p class="step-hint">Selecciona el día que mejor te convenga.</p>
                                <div class="form-group mb-0">
                                    <input type="date" class="form-control" name="date" id="date"
                                           min="{{ now()->toDateString() }}"
                                           placeholder="Selecciona fecha" required>
                                </div>
                            </div>

                            @if ($isGeneral)
                            {{-- ══ STEP 3 (General): Choose barber ══════════════ --}}
                            <div class="booking-step step-locked" id="step3general">
                                <div class="step-header">
                                    <div class="step-badge">3</div>
                                    <h5>Elige tu barbero</h5>
                                </div>
                                <p class="step-hint">
                                    Veremos qué barberos están disponibles para tus servicios en esa fecha.
                                </p>
                                <div id="selectedBarberoWrap" style="display:none;" class="mb-3">
                                    <div id="selectedBarberoChip">
                                        <img id="chipPhoto" src="" alt="">
                                        <span class="barber-chip-name" id="chipName"></span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="btnCambiarBarbero">Cambiar</button>
                                    </div>
                                </div>
                                <button type="button" class="btn w-100" id="btnVerBarberos"
                                        style="background:var(--btn_cart);color:var(--btn_cart_text);font-weight:700;">
                                    <i class="fas fa-search mr-2"></i> Ver barberos disponibles
                                </button>
                            </div>

                            {{-- ══ STEP 4 (General): Time slot ════════════════ --}}
                            <div class="booking-step step-locked" id="step4hora">
                                <div class="step-header">
                                    <div class="step-badge">4</div>
                                    <h5>Escoge tu horario</h5>
                                </div>
                                <p class="step-hint">Horas disponibles para el barbero seleccionado.</p>
                                <div class="form-group mb-0">
                                    <select class="form-control" name="time" id="time" required>
                                        <option value="">Selecciona hora</option>
                                    </select>
                                </div>
                                <div id="no-slots-msg" class="alert alert-warning mt-2"
                                     style="display:none;"></div>
                            </div>

                            {{-- ══ STEP 5 (General): Client info ═══════════════ --}}
                            <div class="booking-step step-locked" id="step5datos">
                                <div class="step-header">
                                    <div class="step-badge">5</div>
                                    <h5>Tus datos de contacto</h5>
                                </div>
                                <p class="step-hint">Completa tus datos para confirmar la reserva.</p>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" name="cliente_nombre"
                                           placeholder="Tu nombre completo" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control" name="cliente_email"
                                           placeholder="Tu correo electrónico" required>
                                </div>
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" name="cliente_telefono"
                                           placeholder="Tu teléfono (opcional)">
                                </div>
                            </div>

                            @else
                            {{-- ══ STEP 3 (Regular): Time slot ═════════════════ --}}
                            <div class="booking-step step-locked" id="step3hora">
                                <div class="step-header">
                                    <div class="step-badge">3</div>
                                    <h5>Escoge tu horario</h5>
                                </div>
                                <p class="step-hint">Horas disponibles con {{ $barbero->nombre }}.</p>
                                <div class="form-group mb-0">
                                    <select class="form-control" name="time" id="time" required>
                                        <option value="">Selecciona hora</option>
                                    </select>
                                </div>
                                <div id="no-slots-msg" class="alert alert-warning mt-2"
                                     style="display:none;"></div>
                            </div>

                            {{-- ══ STEP 4 (Regular): Client info ═══════════════ --}}
                            <div class="booking-step step-locked" id="step4datos">
                                <div class="step-header">
                                    <div class="step-badge">4</div>
                                    <h5>Tus datos de contacto</h5>
                                </div>
                                <p class="step-hint">Completa tus datos para confirmar la reserva.</p>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" name="cliente_nombre"
                                           placeholder="Tu nombre completo" required>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control" name="cliente_email"
                                           placeholder="Tu correo electrónico" required>
                                </div>
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" name="cliente_telefono"
                                           placeholder="Tu teléfono (opcional)">
                                </div>
                            </div>
                            @endif

                            {{-- Submit --}}
                            <button type="submit" id="submitBtn" disabled
                                    class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn mt-3">
                                Reservar Cita
                            </button>
                        </form>

                        {{-- ══ MODAL: Barber selection (general barber only) ═══ --}}
                        @if ($isGeneral)
                        <div class="modal fade" id="barberModal" tabindex="-1" role="dialog"
                             aria-labelledby="barberModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content"
                                     style="background:#1a1a1a;border:1px solid #333;border-radius:8px;">
                                    <div class="modal-header"
                                         style="border-bottom:1px solid #333;background:var(--navbar);">
                                        <h5 class="modal-title" id="barberModalLabel"
                                            style="color:var(--navbar_text);font-weight:700;letter-spacing:1px;">
                                            <i class="fas fa-user-alt mr-2"></i>
                                            BARBEROS DISPONIBLES
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                style="color:var(--navbar_text);opacity:1;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:24px;">
                                        <div id="barberModalLoading" class="text-center py-4"
                                             style="display:none;">
                                            <div class="spinner-border text-primary" role="status"></div>
                                            <p class="mt-2" style="color:#aaa;">Buscando disponibilidad...</p>
                                        </div>
                                        <div id="barberModalEmpty" class="text-center py-4"
                                             style="display:none;">
                                            <i class="fas fa-calendar-times fa-2x mb-3"
                                               style="color:#6b7280;"></i>
                                            <p style="color:#aaa;">
                                                No hay barberos disponibles para esa fecha con los
                                                servicios seleccionados. Intenta con otra fecha.
                                            </p>
                                        </div>
                                        <div class="row" id="barberCardsList"></div>
                                    </div>
                                    <div class="modal-footer" style="border-top:1px solid #333;">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    </aside>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
(function () {
    /* ── Constants ───────────────────────────────────────── */
    const IS_GENERAL         = {{ $isGeneral ? 'true' : 'false' }};
    const BARBER_ID_FIXED    = {{ $isGeneral ? 'null' : $barbero->id }};
    const URL_BASE_DISP      = '{{ url('/barberos') }}/';   // + id + '/disponibilidad'
    const URL_DISPONIBLES    = '{{ url('/barberos/disponibles-para') }}';
    const PHOTO_FALLBACK     = '{{ url('images/barber.PNG') }}';

    /* ── State ───────────────────────────────────────────── */
    let selectedBarberoId = IS_GENERAL ? null : BARBER_ID_FIXED;

    /* ── DOM refs ────────────────────────────────────────── */
    const dateEl      = document.getElementById('date');
    const timeEl      = document.getElementById('time');
    const checks      = document.querySelectorAll('.svc-check');
    const form        = document.getElementById('formBooking');
    const submitBtn   = document.getElementById('submitBtn');
    const barberoInput = document.getElementById('barberoIdInput');

    const step1       = document.getElementById('step1');
    const step2       = document.getElementById('step2');
    const stepHoraEl  = document.getElementById(IS_GENERAL ? 'step4hora'  : 'step3hora');
    const stepDatosEl = document.getElementById(IS_GENERAL ? 'step5datos' : 'step4datos');
    const stepGeneral = IS_GENERAL ? document.getElementById('step3general') : null;
    const bpSteps     = document.querySelectorAll('#bookingProgress .bp-step');

    /* ── Helpers: step visual state ──────────────────────── */
    function setStepState(el, state) {
        if (!el) return;
        el.classList.remove('step-active', 'step-done', 'step-locked');
        el.classList.add('step-' + state);
    }

    function setProgressStep(activeNum) {
        bpSteps.forEach(s => {
            const n = parseInt(s.dataset.step);
            s.classList.remove('active', 'done');
            if (n < activeNum) s.classList.add('done');
            else if (n === activeNum) s.classList.add('active');
        });
    }

    /* ── Time select helpers ─────────────────────────────── */
    function clearTimeSelect() {
        timeEl.innerHTML = '<option value="">Selecciona hora</option>';
        triggerNiceSelect();
    }

    function triggerNiceSelect() {
        if (window.jQuery && jQuery.fn.niceSelect) {
            try { jQuery(timeEl).niceSelect('update'); } catch (e) {}
        }
    }

    function showNoSlotsMsg(txt) {
        const el = document.getElementById('no-slots-msg');
        if (el) { el.textContent = txt; el.style.display = 'block'; }
    }
    function hideNoSlotsMsg() {
        const el = document.getElementById('no-slots-msg');
        if (el) el.style.display = 'none';
    }

    /* ── Fetch available time slots for a barbero ────────── */
    async function fetchSlots(barberoId) {
        const date      = dateEl.value;
        const servicios = getSelectedServicios();
        clearTimeSelect();
        hideNoSlotsMsg();

        if (!date || servicios.length === 0 || !barberoId) return;

        const qs = '?date=' + encodeURIComponent(date)
                 + '&' + servicios.map(s => 'servicios[]=' + encodeURIComponent(s)).join('&');

        try {
            const res   = await fetch(URL_BASE_DISP + barberoId + '/disponibilidad' + qs);
            const data  = await res.json();
            const slots = data.slots || [];

            if (slots.length === 0) {
                showNoSlotsMsg('No hay horas disponibles para esa fecha. Intenta con otro día.');
                timeEl.innerHTML += '<option value="" disabled>No hay horas disponibles</option>';
            } else {
                slots.forEach(h => {
                    const opt = document.createElement('option');
                    opt.value = h; opt.textContent = h;
                    timeEl.appendChild(opt);
                });
            }
            triggerNiceSelect();
        } catch (e) {
            console.error(e);
            showNoSlotsMsg('Error al cargar disponibilidad. Recarga la página e intenta nuevamente.');
            triggerNiceSelect();
        }
    }

    /* ── Get selected service IDs ────────────────────────── */
    function getSelectedServicios() {
        return Array.from(form.querySelectorAll('.svc-check:checked')).map(el => el.value);
    }

    /* ── Recompute all step states & submit button ────────── */
    function updateSteps() {
        const hasServices = getSelectedServicios().length > 0;
        const hasDate     = !!dateEl.value;
        const hasBarber   = IS_GENERAL ? !!selectedBarberoId : true;
        const hasTime     = !!timeEl.value;

        /* Step 1 */
        setStepState(step1, hasServices ? 'done' : 'active');

        /* Step 2: date */
        if (!hasServices)     setStepState(step2, 'locked');
        else if (hasDate)     setStepState(step2, 'done');
        else                  setStepState(step2, 'active');

        if (IS_GENERAL) {
            /* Step 3: barber */
            if (!hasServices || !hasDate) setStepState(stepGeneral, 'locked');
            else if (hasBarber)           setStepState(stepGeneral, 'done');
            else                          setStepState(stepGeneral, 'active');

            /* Step 4: time */
            if (!hasBarber)   setStepState(stepHoraEl, 'locked');
            else if (hasTime) setStepState(stepHoraEl, 'done');
            else              setStepState(stepHoraEl, 'active');

            /* Step 5: client data */
            setStepState(stepDatosEl, hasTime ? 'active' : 'locked');

            /* Progress dot */
            if (!hasServices) setProgressStep(1);
            else if (!hasDate) setProgressStep(2);
            else if (!hasBarber) setProgressStep(3);
            else if (!hasTime) setProgressStep(4);
            else setProgressStep(5);

        } else {
            /* Step 3: time */
            if (!hasServices || !hasDate) setStepState(stepHoraEl, 'locked');
            else if (hasTime)             setStepState(stepHoraEl, 'done');
            else                          setStepState(stepHoraEl, 'active');

            /* Step 4: client data */
            setStepState(stepDatosEl, hasTime ? 'active' : 'locked');

            /* Progress dot */
            if (!hasServices) setProgressStep(1);
            else if (!hasDate) setProgressStep(2);
            else if (!hasTime) setProgressStep(3);
            else setProgressStep(4);
        }

        submitBtn.disabled = !(hasServices && hasDate && hasBarber && hasTime);
    }

    /* ── Events ──────────────────────────────────────────── */
    checks.forEach(c => c.addEventListener('change', () => {
        clearTimeSelect();
        hideNoSlotsMsg();
        if (!IS_GENERAL && dateEl.value && getSelectedServicios().length > 0) {
            fetchSlots(BARBER_ID_FIXED);
        } else if (IS_GENERAL && selectedBarberoId && dateEl.value && getSelectedServicios().length > 0) {
            fetchSlots(selectedBarberoId);
        }
        updateSteps();
    }));

    dateEl.addEventListener('change', () => {
        clearTimeSelect();
        hideNoSlotsMsg();
        if (IS_GENERAL && selectedBarberoId) {
            // Reset barber when date changes so the user re-validates availability
            resetBarberSelection();
        } else if (!IS_GENERAL && getSelectedServicios().length > 0) {
            fetchSlots(BARBER_ID_FIXED);
        }
        updateSteps();
    });

    /* nice-select fires jQuery's change, not the native DOM event */
    if (window.jQuery) {
        jQuery(timeEl).on('change', updateSteps);
    } else {
        timeEl.addEventListener('change', updateSteps);
    }

    /* ── General-barber modal ────────────────────────────── */
    @if ($isGeneral)
    const btnVerBarberos      = document.getElementById('btnVerBarberos');
    const btnCambiarBarbero   = document.getElementById('btnCambiarBarbero');
    const selectedBarberoWrap = document.getElementById('selectedBarberoWrap');
    const chipName            = document.getElementById('chipName');
    const chipPhoto           = document.getElementById('chipPhoto');
    const barberCardsList     = document.getElementById('barberCardsList');
    const barberModalLoading  = document.getElementById('barberModalLoading');
    const barberModalEmpty    = document.getElementById('barberModalEmpty');

    async function openBarberModal() {
        const servicios = getSelectedServicios();
        const date      = dateEl.value;
        if (servicios.length === 0 || !date) return;

        jQuery('#barberModal').modal('show');

        // Reset modal
        barberCardsList.innerHTML         = '';
        barberModalLoading.style.display  = 'block';
        barberModalEmpty.style.display    = 'none';

        const qs = '?date=' + encodeURIComponent(date)
                 + '&' + servicios.map(s => 'servicios[]=' + encodeURIComponent(s)).join('&');

        try {
            const res  = await fetch(URL_DISPONIBLES + qs);
            const data = await res.json();

            barberModalLoading.style.display = 'none';

            if (!Array.isArray(data) || data.length === 0) {
                barberModalEmpty.style.display = 'block';
                return;
            }

            data.forEach(b => {
                const photoSrc = b.photo_path
                    ? '{{ url('/file') }}/' + b.photo_path
                    : PHOTO_FALLBACK;

                const slotsJson = JSON.stringify(b.slots).replace(/'/g, "&#39;");

                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6 mb-3';
                col.innerHTML =
                    '<div class="barber-option-card ' + (b.id == selectedBarberoId ? 'selected' : '') + '"'
                    + ' data-id="' + b.id + '"'
                    + ' data-nombre="' + b.nombre + '"'
                    + ' data-photo="' + photoSrc + '"'
                    + ' data-slots=\'' + slotsJson + '\'>'
                    + '<img src="' + photoSrc + '" alt="' + b.nombre + '"'
                    + ' onerror="this.src=\'' + PHOTO_FALLBACK + '\'">'
                    + '<div class="barber-name">' + b.nombre + '</div>'
                    + '<div class="slots-badge">'
                    + '<i class="fas fa-clock"></i> '
                    + b.slots.length + ' horario' + (b.slots.length !== 1 ? 's' : '')
                    + '</div></div>';

                barberCardsList.appendChild(col);
            });

            // Barber card click
            barberCardsList.querySelectorAll('.barber-option-card').forEach(card => {
                card.addEventListener('click', () => {
                    const id     = card.dataset.id;
                    const nombre = card.dataset.nombre;
                    const photo  = card.dataset.photo;
                    const slots  = JSON.parse(card.dataset.slots);
                    selectBarber(id, nombre, photo, slots);
                    jQuery('#barberModal').modal('hide');
                });
            });

        } catch (e) {
            console.error(e);
            barberModalLoading.style.display = 'none';
            barberModalEmpty.style.display   = 'block';
        }
    }

    function selectBarber(id, nombre, photo, slots) {
        selectedBarberoId      = id;
        barberoInput.value     = id;
        chipName.textContent   = nombre;
        chipPhoto.src          = photo;
        chipPhoto.onerror      = () => { chipPhoto.src = PHOTO_FALLBACK; };

        selectedBarberoWrap.style.display = 'flex';
        btnVerBarberos.style.display      = 'none';

        // Populate time slots directly from the already-fetched data
        clearTimeSelect();
        hideNoSlotsMsg();
        if (slots && slots.length > 0) {
            slots.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h; opt.textContent = h;
                timeEl.appendChild(opt);
            });
            triggerNiceSelect();
        } else {
            showNoSlotsMsg('No hay horarios disponibles para este barbero en la fecha seleccionada.');
            triggerNiceSelect();
        }
        updateSteps();
    }

    function resetBarberSelection() {
        selectedBarberoId      = null;
        barberoInput.value     = '';
        selectedBarberoWrap.style.display = 'none';
        btnVerBarberos.style.display      = 'block';
        clearTimeSelect();
    }

    btnVerBarberos.addEventListener('click', openBarberModal);
    btnCambiarBarbero.addEventListener('click', () => {
        resetBarberSelection();
        updateSteps();
        openBarberModal();
    });
    @endif

    /* ── Prevent double-submit ───────────────────────────── */
    form.addEventListener('submit', function () {
        submitBtn.disabled  = true;
        submitBtn.innerText = 'Reservando...';
        submitBtn.classList.add('disabled');
    });

    /* ── Init niceSelect on time element ─────────────────── */
    if (window.jQuery && jQuery.fn.niceSelect) {
        jQuery(timeEl).niceSelect();
    }

    /* ── Initial render ──────────────────────────────────── */
    updateSteps();
})();
</script>
@endsection
