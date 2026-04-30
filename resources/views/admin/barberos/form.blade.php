@php
    $isEditar = ($Modo ?? '') === 'editar';
    $b        = $barbero ?? null;

    /* Existing horarios for edit mode */
    $existingHorarios = [];
    if ($isEditar && $b) {
        foreach ($b->horarios ?? [] as $h) {
            $existingHorarios[] = [
                'dias'        => is_array($h->dias) ? $h->dias : json_decode($h->dias, true),
                'hora_inicio' => substr($h->hora_inicio, 0, 5),
                'hora_fin'    => substr($h->hora_fin,    0, 5),
            ];
        }
    }

    /* Legacy fallback values (used when no horarios exist yet) */
    $legacyStart = old('work_start', $b ? substr($b->work_start ?? '09:00', 0, 5) : '09:00');
    $legacyEnd   = old('work_end',   $b ? substr($b->work_end   ?? '18:00', 0, 5) : '18:00');
    $legacyDays  = old('work_days',  $b && $b->work_days ? json_decode($b->work_days, true) : [1,2,3,4,5]);
    $legacyDays  = is_array($legacyDays) ? $legacyDays : [];

    $dayLabels = [0 => 'Dom', 1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb'];
    $slotOld   = old('slot_minutes', $b ? $b->slot_minutes : 30);
    $activoOld = old('activo', $b ? (int)$b->activo : 1);
@endphp

{{-- ── Identidad ──────────────────────────────────────────────────────── --}}
<div class="row g-3">
    <div class="col-12">
        <label class="filter-label" for="nombre">Nombre <span class="text-danger">*</span></label>
        <input id="nombre" name="nombre" type="text" required maxlength="120"
               value="{{ old('nombre', $b->nombre ?? '') }}"
               class="filter-input @error('nombre') is-invalid @enderror"
               autocomplete="name">
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="filter-label" for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" type="text" maxlength="50"
               value="{{ old('telefono', $b->telefono ?? '') }}"
               class="filter-input @error('telefono') is-invalid @enderror"
               autocomplete="tel">
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="filter-label" for="email">Email</label>
        <input id="email" name="email" type="email" maxlength="120"
               value="{{ old('email', $b->email ?? '') }}"
               class="filter-input @error('email') is-invalid @enderror"
               autocomplete="email">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ── Economía ──────────────────────────────────────────────────── --}}
    <div class="col-md-6">
        <label class="filter-label" for="salario_base">Salario base (₡)</label>
        <input id="salario_base" name="salario_base" type="number" min="0" step="1"
               value="{{ old('salario_base', $b->salario_base ?? '') }}"
               class="filter-input @error('salario_base') is-invalid @enderror"
               inputmode="numeric">
    </div>

    <div class="col-md-6">
        <label class="filter-label" for="monto_por_servicio">Monto por servicio (₡)</label>
        <input id="monto_por_servicio" name="monto_por_servicio" type="number" min="0" step="1"
               value="{{ old('monto_por_servicio', $b->monto_por_servicio ?? '') }}"
               class="filter-input @error('monto_por_servicio') is-invalid @enderror"
               inputmode="numeric">
    </div>

    {{-- ── Configuración de agenda ───────────────────────────────────── --}}
    <div class="col-md-4">
        <label class="filter-label" for="slot_minutes">Intervalo de citas (min)</label>
        <select id="slot_minutes" name="slot_minutes" class="filter-input" required>
            @foreach ([15, 20, 30, 45, 60] as $m)
                <option value="{{ $m }}" {{ (int)$slotOld === $m ? 'selected' : '' }}>{{ $m }} min</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="filter-label" for="buffer_minutes">Buffer entre citas (min)</label>
        <input id="buffer_minutes" name="buffer_minutes" type="number" min="0" max="120" step="5"
               value="{{ old('buffer_minutes', $b->buffer_minutes ?? 0) }}"
               class="filter-input">
    </div>

    <div class="col-md-4">
        <label class="filter-label" for="activo">Estado</label>
        <select id="activo" name="activo" class="filter-input">
            <option value="1" {{ (int)$activoOld === 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ (int)$activoOld === 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="filter-label">Foto</label>
        <input type="file" name="image" accept="image/*" class="filter-input">
    </div>
</div>

{{-- ── Horarios de atención ────────────────────────────────────────────── --}}
<hr class="my-4" style="border-color:var(--border-color,#e5e7eb);">
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <p class="mb-0 fw-semibold" style="color:var(--text-primary,#111);">Horarios de atención</p>
        <small style="color:var(--text-secondary,#6b7280);">
            Agrega uno o más bloques con los días y horas en que este colaborador atiende.
            Los bloques del mismo día no pueden solaparse.
        </small>
    </div>
    <button type="button" class="s-btn-primary w-auto" id="btn-add-horario" style="white-space:nowrap;flex-shrink:0;">
        <span class="material-icons" style="font-size:.85rem;vertical-align:middle;">add</span>
        Agregar bloque
    </button>
</div>

@error('horarios')
    <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
@enderror

<div id="horarios-container">
    {{-- Blocks rendered by JS from initialHorarios data --}}
</div>

<div id="horarios-empty-msg" class="text-center py-3" style="color:var(--text-secondary,#6b7280);display:none;">
    <span class="material-icons" style="font-size:2rem;opacity:.4;">schedule</span>
    <p class="mt-1 mb-0" style="font-size:.85rem;">Sin bloques. Agrega al menos uno o usa el modo heredado abajo.</p>
</div>

{{-- ── Modo heredado (fallback cuando no hay bloques) ─────────────────── --}}
<div id="legacy-schedule" class="{{ !empty($existingHorarios) ? 'd-none' : '' }} mt-3">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="filter-label" for="work_start">Hora inicio</label>
            <input id="work_start" name="work_start" type="time"
                   value="{{ $legacyStart }}"
                   class="filter-input @error('work_start') is-invalid @enderror">
            @error('work_start')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="filter-label" for="work_end">Hora fin</label>
            <input id="work_end" name="work_end" type="time"
                   value="{{ $legacyEnd }}"
                   class="filter-input @error('work_end') is-invalid @enderror">
            @error('work_end')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="filter-label d-block mb-2">Días laborables</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($dayLabels as $idx => $lbl)
                    <label class="day-chip {{ in_array($idx, $legacyDays) ? 'day-chip--active' : '' }}" for="lday{{ $idx }}">
                        <input type="checkbox" name="work_days[]" id="lday{{ $idx }}" value="{{ $idx }}"
                               {{ in_array($idx, $legacyDays) ? 'checked' : '' }}
                               style="display:none;" onchange="this.closest('.day-chip').classList.toggle('day-chip--active', this.checked)">
                        {{ $lbl }}
                    </label>
                @endforeach
            </div>
            @error('work_days')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{-- ── Submit ──────────────────────────────────────────────────────────── --}}
<div class="mt-4 text-center">
    <button type="submit" class="s-btn-primary">
        {{ $isEditar ? 'Guardar cambios' : 'Crear barbero' }}
    </button>
</div>

{{-- ── Block template (hidden) ─────────────────────────────────────────── --}}
<template id="horario-block-tpl">
    <div class="horario-block surface mb-3 p-3" data-index="__IDX__">
        <div class="d-flex align-items-start justify-content-between mb-2">
            <span class="fw-semibold" style="font-size:.85rem;color:var(--text-primary,#111);">Bloque <span class="bloque-num">1</span></span>
            <button type="button" class="btn-remove-horario act-btn ab-danger btn-sm" title="Eliminar bloque">
                <span class="material-icons" style="font-size:.85rem;">delete</span>
            </button>
        </div>

        <div class="mb-2">
            <label class="filter-label mb-1">Días</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($dayLabels as $idx => $lbl)
                    <label class="day-chip" for="hday_{{ $idx }}___IDX__">
                        <input type="checkbox"
                               name="horarios[__IDX__][dias][]"
                               id="hday_{{ $idx }}___IDX__"
                               value="{{ $idx }}"
                               style="display:none;"
                               onchange="this.closest('.day-chip').classList.toggle('day-chip--active', this.checked)">
                        {{ $lbl }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="row g-2">
            <div class="col-6">
                <label class="filter-label" for="hinicio___IDX__">Hora inicio</label>
                <input id="hinicio___IDX__" type="time"
                       name="horarios[__IDX__][hora_inicio]"
                       class="filter-input" required>
            </div>
            <div class="col-6">
                <label class="filter-label" for="hfin___IDX__">Hora fin</label>
                <input id="hfin___IDX__" type="time"
                       name="horarios[__IDX__][hora_fin]"
                       class="filter-input" required>
            </div>
        </div>
    </div>
</template>

<style>
.day-chip {
    cursor:pointer;
    padding:.25rem .55rem;
    border-radius:.4rem;
    font-size:.78rem;
    font-weight:600;
    border:1.5px solid var(--border-color,#d1d5db);
    color:var(--text-secondary,#6b7280);
    background:transparent;
    user-select:none;
    transition:background .15s,color .15s,border-color .15s;
}
.day-chip--active {
    background:var(--primary,#4f46e5);
    color:#fff;
    border-color:var(--primary,#4f46e5);
}
</style>

<script>
(function () {
    const container   = document.getElementById('horarios-container');
    const emptyMsg    = document.getElementById('horarios-empty-msg');
    const legacySec   = document.getElementById('legacy-schedule');
    const btnAdd      = document.getElementById('btn-add-horario');
    const tpl         = document.getElementById('horario-block-tpl');
    let counter = 0;

    const initialHorarios = @json($existingHorarios);

    function updateEmptyState() {
        const blocks = container.querySelectorAll('.horario-block');
        emptyMsg.style.display   = blocks.length === 0 ? 'block' : 'none';
        // Show legacy fields only when no blocks
        if (blocks.length > 0) {
            legacySec.classList.add('d-none');
        } else {
            legacySec.classList.remove('d-none');
        }
        // Renumber labels
        blocks.forEach((b, i) => {
            const span = b.querySelector('.bloque-num');
            if (span) span.textContent = i + 1;
        });
    }

    function addBlock(preset) {
        const idx  = counter++;
        const html = tpl.innerHTML.replace(/__IDX__/g, idx);
        const wrap = document.createElement('div');
        wrap.innerHTML = html;
        const block = wrap.firstElementChild;

        // Pre-fill days
        if (preset && preset.dias) {
            preset.dias.forEach(function (d) {
                const cb = block.querySelector('input[value="' + d + '"]');
                if (cb) {
                    cb.checked = true;
                    cb.closest('.day-chip').classList.add('day-chip--active');
                }
            });
        }
        if (preset && preset.hora_inicio) {
            const el = block.querySelector('[name$="[hora_inicio]"]');
            if (el) el.value = preset.hora_inicio;
        }
        if (preset && preset.hora_fin) {
            const el = block.querySelector('[name$="[hora_fin]"]');
            if (el) el.value = preset.hora_fin;
        }

        // Remove button
        block.querySelector('.btn-remove-horario').addEventListener('click', function () {
            block.remove();
            updateEmptyState();
        });

        container.appendChild(block);
        updateEmptyState();
    }

    btnAdd.addEventListener('click', function () { addBlock(null); });

    // Load existing horarios on edit
    if (initialHorarios && initialHorarios.length > 0) {
        initialHorarios.forEach(function (h) { addBlock(h); });
    }

    updateEmptyState();
})();
</script>
