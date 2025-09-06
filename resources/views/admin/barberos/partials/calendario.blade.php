{{-- resources/views/admin/barberos/partials/calendario.blade.php --}}

{{-- Título dentro del tab (si prefieres, puedes quitar este <h2> porque el profile ya muestra encabezado) --}}

<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif
        <div>
            <h4 class="mb-1 fw-bold">Calendario de {{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">💰 Salario base:
                    <strong>₡{{ number_format((int) $barbero->salario_base, 0, ',', '.') }}</strong>
                </span>
                <span class="chip">✂️ Por servicio:
                    <strong>₡{{ number_format((int) $barbero->monto_por_servicio, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        @can('barberos.manage')
            <a href="{{ url('barberos') }}" class="icon-btn" data-bs-toggle="tooltip" title="Volver a barberos">
                <i class="material-icons">arrow_back</i>
            </a>
            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-barbero-modal"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar barbero">
                <i class="material-icons">edit</i>
            </button>
        @endcan
    </div>
</div>
<div class="card mt-1">
    <div class="card-body">
        <div id="calendar"></div>

        {{-- Modal: Crear bloque rápido --}}
        <div class="modal fade" id="blockModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id="blockForm" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo bloque (horario no disponible)</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="blockError"></div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Fecha</label>
                                    <input type="text" id="blockDateHuman" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Inicio</label>
                                    <input type="text" id="blockStartHuman" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Fin</label>
                                    <input type="text" id="blockEndHuman" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Motivo (opcional)</label>
                                    <input type="text" name="motivo" id="blockMotivo" class="form-control"
                                        maxlength="120" placeholder="Ej. almuerzo, reunión, limpieza…">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="blockStartIso" name="start">
                        <input type="hidden" id="blockEndIso" name="end">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-admin-delete" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-accion">Crear bloque</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Confirmar eliminación de bloque --}}
        <div class="modal fade" id="deleteBlockModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Eliminar bloque</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteBlockText" class="mb-0">
                            ¿Deseas eliminar este bloque no disponible?
                        </p>
                        <div class="alert alert-danger d-none mt-3" id="deleteBlockError"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-accion" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="deleteBlockConfirmBtn"
                            class="btn btn-admin-delete">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="mt-3">
            <span class="badge" style="background:#9CA3AF">Pendiente</span>
            <span class="badge" style="background:#0ea5e9">Confirmada</span>
            <span class="badge" style="background:#10b981">Completada</span>
            <span class="badge" style="background:#ef4444">Cancelada</span>
            <span class="badge" style="background:#f59e0b">Bloque</span>
            <span class="badge" style="background:#ffe2e2; color:#333">Día libre</span>
        </div>
    </div>
</div>

{{-- Assets + JS (sección script del layout). Usamos @parent para no pisar otros scripts del tab --}}
@section('script')
    @parent
    {{-- FullCalendar v6 (global bundle + locales) --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Requiere que el layout tenga: <meta name="csrf-token" content="{{ csrf_token() }}">
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            // Estas 4 variables deben venir desde el controller que carga el tab:
            // $workDays (array de ints 0..6), $slot (int minutos), $workStart ("HH:mm"), $workEnd ("HH:mm")
            const workDays = @json($workDays); // ej [1,2,3,4,5]
            const slot = {{ (int) $slot }}; // ej 30
            const workStart = @json($workStart); // "09:00"
            const workEnd = @json($workEnd); // "18:00"

            let deleteBlockModal, pendingDeleteBlockId = null;
            const deleteBlockModalEl = document.getElementById('deleteBlockModal');
            const deleteBlockTextEl = document.getElementById('deleteBlockText');
            const deleteBlockErrorEl = document.getElementById('deleteBlockError');
            const deleteBlockConfirmBtn = document.getElementById('deleteBlockConfirmBtn');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                timeZone: '{{ config('app.timezone', 'America/Costa_Rica') }}',
                initialView: 'timeGridWeek',
                firstDay: 1,
                height: 'auto',
                expandRows: true,
                nowIndicator: true,
                slotDuration: '00:' + String(slot).padStart(2, '0') + ':00',
                slotMinTime: workStart + ':00',
                slotMaxTime: workEnd + ':00',
                allDaySlot: false,

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                businessHours: workDays.map(dow => ({
                    daysOfWeek: [dow],
                    startTime: workStart,
                    endTime: workEnd
                })),

                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },

                // Endpoint que ya tienes (asegúrate que agregue extendedProps.block_id para bloques)
                events: '{{ route('barberos.events', $barbero->id) }}',

                // DnD/Resize para citas
                editable: true,
                eventDurationEditable: true,
                eventStartEditable: true,

                // Selección para crear bloques
                selectable: true,
                selectMirror: true,
                selectOverlap: true,

                // Solo permitir mover/resize citas (no bloques/excepciones)
                eventAllow: function(dropInfo, draggedEvent) {
                    const type = draggedEvent?.extendedProps?.type;
                    return (type === 'cita');
                },

                eventClick: function(info) {
                    const ev = info.event;
                    const type = ev.extendedProps?.type;

                    if (type === 'cita') {
                        // Opción A: navegar con ?back= al detalle
                        const back = encodeURIComponent(window.location.href);
                        const showUrl = "{{ route('citas.show', 'CID') }}".replace('CID', ev.id) +
                            '?back=' + back;
                        window.location.href = showUrl;

                        // Opción B: abrir modal de detalle aquí (recomendado si quieres evitar navegar)
                        // showCitaModal(ev.id);
                        return;
                    }

                    if (type === 'bloque') {
                        const blockId = ev.extendedProps?.block_id;
                        if (!blockId) return;
                        pendingDeleteBlockId = blockId;
                        deleteBlockErrorEl.classList.add('d-none');
                        deleteBlockErrorEl.textContent = '';
                        deleteBlockTextEl.textContent = ev.title ||
                            '¿Deseas eliminar este bloque no disponible?';
                        ensureDeleteModal().show();
                    }
                },

                eventContent: function(arg) {
                    const type = arg.event.extendedProps?.type;
                    if (type === 'bloque') {
                        const el = document.createElement('div');
                        el.style.fontStyle = 'normal';
                        el.innerHTML = '🔒 ' + (arg.event.title || 'Bloque');
                        return {
                            domNodes: [el]
                        };
                    }
                    return true; // citas usan render por defecto
                },

                async eventDrop(info) {
                    // mover cita
                    if (info.event.extendedProps?.type !== 'cita') return;
                    const ok = await rescheduleAjax(info.event);
                    if (!ok) info.revert();
                },

                async eventResize(info) {
                    // redimensionar cita
                    if (info.event.extendedProps?.type !== 'cita') return;
                    const ok = await rescheduleAjax(info.event);
                    if (!ok) info.revert();
                },

                select(selectionInfo) {
                    openBlockModal(selectionInfo);
                },
            });

            calendar.render();

            // ------- Helpers -------
            async function rescheduleAjax(event) {
                try {
                    const url = "{{ route('citas.reschedule', 'CID') }}".replace('CID', event.id);
                    const res = await fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            start: event.startStr,
                            end: event.endStr
                        })
                    });
                    const json = await res.json();
                    if (json.ok) return true;
                    alert(json.msg || 'No se pudo reprogramar');
                    return false;
                } catch (e) {
                    console.error(e);
                    alert('Error de red');
                    return false;
                }
            }

            async function deleteBlockAjax(blockId) {
                try {
                    const url = "{{ route('barberos.bloques.destroy', [$barbero->id, 0]) }}".replace('/0',
                        '/' +
                        blockId);
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: '_method=DELETE'
                    });

                    // procesar según content-type
                    const ct = (res.headers.get('content-type') || '').toLowerCase();
                    if (ct.includes('application/json')) {
                        const json = await res.json();
                        if (json.ok) return true;
                        alert(json.msg || 'No se pudo eliminar el bloque');
                        return false;
                    } else {
                        return res.ok; // 204/2xx lo tomamos como OK
                    }
                } catch (e) {
                    console.error(e);
                    alert('Error de red');
                    return false;
                }
            }

            // ------- Modal Bloque -------
            const blockModalEl = document.getElementById('blockModal');
            const blockForm = document.getElementById('blockForm');
            const blockError = document.getElementById('blockError');
            const blockDateHuman = document.getElementById('blockDateHuman');
            const blockStartHuman = document.getElementById('blockStartHuman');
            const blockEndHuman = document.getElementById('blockEndHuman');
            const blockMotivo = document.getElementById('blockMotivo');
            const blockStartIso = document.getElementById('blockStartIso');
            const blockEndIso = document.getElementById('blockEndIso');

            let blockModal;

            function ensureModal() {
                if (!blockModal) blockModal = new bootstrap.Modal(blockModalEl, {
                    backdrop: true,
                    keyboard: true
                });
                return blockModal;
            }

            function ensureDeleteModal() {
                if (!deleteBlockModal) deleteBlockModal = new bootstrap.Modal(deleteBlockModalEl, {
                    backdrop: true,
                    keyboard: true
                });
                return deleteBlockModal;
            }

            function formatHuman(dtStr) {
                const d = new Date(dtStr.replace(' ', 'T'));
                const pad = n => String(n).padStart(2, '0');
                const fecha = pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear();
                const hora = pad(d.getHours()) + ':' + pad(d.getMinutes());
                return {
                    fecha,
                    hora
                };
            }

            function openBlockModal(selectionInfo) {
                const {
                    fecha: f1,
                    hora: h1
                } = formatHuman(selectionInfo.startStr);
                const {
                    fecha: f2,
                    hora: h2
                } = formatHuman(selectionInfo.endStr);

                blockError.classList.add('d-none');
                blockError.textContent = '';

                blockDateHuman.value = f1;
                blockStartHuman.value = h1;
                blockEndHuman.value = h2;
                blockMotivo.value = '';

                blockStartIso.value = selectionInfo.startStr;
                blockEndIso.value = selectionInfo.endStr;

                ensureModal().show();
            }

            document.getElementById('deleteBlockConfirmBtn').addEventListener('click', async function() {
                if (!pendingDeleteBlockId) return;
                const ok = await deleteBlockAjax(pendingDeleteBlockId);
                if (ok) {
                    ensureDeleteModal().hide();
                    pendingDeleteBlockId = null;
                    calendar.refetchEvents();
                } else {
                    deleteBlockErrorEl.textContent = 'No se pudo eliminar el bloque.';
                    deleteBlockErrorEl.classList.remove('d-none');
                }
            });

            deleteBlockModalEl.addEventListener('hidden.bs.modal', function() {
                pendingDeleteBlockId = null;
                deleteBlockErrorEl.classList.add('d-none');
                deleteBlockErrorEl.textContent = '';
            });

            blockForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                blockError.classList.add('d-none');

                const payload = {
                    start: blockStartIso.value,
                    end: blockEndIso.value,
                    motivo: blockMotivo.value || null
                };

                try {
                    const res = await fetch("{{ route('barberos.bloques.quick', $barbero->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')
                                .content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    const json = await res.json();
                    if (json.ok) {
                        ensureModal().hide();
                        calendar.unselect();
                        calendar.refetchEvents();
                    } else {
                        blockError.textContent = json.msg || 'No se pudo crear el bloque';
                        blockError.classList.remove('d-none');
                    }
                } catch (err) {
                    console.error(err);
                    blockError.textContent = 'Error de red';
                    blockError.classList.remove('d-none');
                }
            });

            blockModalEl.addEventListener('hidden.bs.modal', function() {
                calendar.unselect();
                blockForm.reset();
                blockError.classList.add('d-none');
                blockError.textContent = '';
            });
        });
    </script>
@endsection
