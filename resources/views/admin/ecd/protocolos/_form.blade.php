{{-- Basic data --}}
<div class="surface p-4 mb-3">
    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
        Información general
    </div>
    <div class="row g-3">
        <div class="col-md-7">
            <label class="filter-label">Nombre del protocolo *</label>
            <input type="text" name="nombre" class="filter-input"
                   value="{{ old('nombre', $protocolo?->nombre) }}" required
                   placeholder="Ej: Protocolo facial hidratación profunda">
        </div>
        <div class="col-md-3">
            <label class="filter-label">Categoría</label>
            <input type="text" name="categoria" class="filter-input"
                   value="{{ old('categoria', $protocolo?->categoria) }}"
                   placeholder="Facial, Corporal...">
        </div>
        <div class="col-md-2">
            <label class="filter-label">Duración (min)</label>
            <input type="number" name="duracion_estimada_min" class="filter-input" min="1" max="999"
                   value="{{ old('duracion_estimada_min', $protocolo?->duracion_estimada_min) }}">
        </div>
        <div class="col-md-3">
            <label class="filter-label">Nivel de dificultad</label>
            <select name="nivel_dificultad" class="filter-input">
                @foreach(['basico' => 'Básico', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('nivel_dificultad', $protocolo?->nivel_dificultad ?? 'basico') === $val ? 'selected' : '' }}>
                        {{ $lbl }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-9">
            <label class="filter-label">Descripción general</label>
            <textarea name="descripcion" class="filter-input" rows="3"
                      placeholder="¿En qué consiste este protocolo? ¿Para qué tipo de paciente?">{{ old('descripcion', $protocolo?->descripcion) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="filter-label">Contraindicaciones</label>
            <textarea name="contraindicaciones" class="filter-input" rows="4"
                      placeholder="Lista de condiciones en las que NO se debe aplicar...">{{ old('contraindicaciones', $protocolo?->contraindicaciones) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="filter-label">Notas post-tratamiento</label>
            <textarea name="notas_post" class="filter-input" rows="4"
                      placeholder="Cuidados posteriores, recomendaciones para el paciente...">{{ old('notas_post', $protocolo?->notas_post) }}</textarea>
        </div>
    </div>
</div>

{{-- Materials --}}
<div class="surface p-4 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
            Materiales y productos necesarios
        </div>
        <button type="button" class="act-btn ab-green" id="addMaterialBtn" title="Agregar material">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    <div id="materialesList" class="d-flex flex-wrap gap-2 mb-2">
        {{-- Chips rendered by JS --}}
    </div>
    <div class="d-flex gap-2">
        <input type="text" id="materialInput" class="filter-input" style="max-width:320px;"
               placeholder="Ej: Ácido hialurónico, Mascarilla enzimática...">
        <button type="button" class="s-btn-sec" id="addMaterialBtn2">Agregar</button>
    </div>
</div>

{{-- Steps builder --}}
<div class="surface p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
            Pasos del protocolo
        </div>
        <button type="button" class="s-btn-sec" id="addPasoBtn" style="font-size:.8rem;padding:.35rem .75rem;">
            <i class="fas fa-plus me-1"></i> Agregar paso
        </button>
    </div>
    <div id="pasosList">
        {{-- Steps rendered by JS --}}
    </div>
    <div id="pasoEmpty" class="text-center py-3" style="color:#94a3b8;font-size:.85rem;">
        Haz clic en "Agregar paso" para definir el procedimiento.
    </div>
</div>
