<div class="row">
    <div class="col-md-12 mb-1">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($servicio->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ old('nombre', $servicio->nombre ?? '') }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                maxlength="120" autocomplete="off">
            @error('nombre')
                <span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($servicio->descripcion) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion"
                class="form-control form-control-lg @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <span class="invalid-feedback" role="alert"><strong>Campo inválido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Duración (min)</label>
            <input value="{{ old('duration_minutes', $servicio->duration_minutes ?? 30) }}" type="number"
                class="form-control form-control-lg @error('duration_minutes') is-invalid @enderror"
                name="duration_minutes" id="duration_minutes" min="5" max="480" step="5"
                inputmode="numeric" pattern="[0-9]*">
            @error('duration_minutes')
                <span class="invalid-feedback" role="alert"><strong>Valor inválido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Precio base (₡)</label>
            <input
                value="{{ old('base_price_view', isset($servicio->base_price_cents) ? (int) $servicio->base_price_cents / 100 : 0) }}"
                type="number" class="form-control form-control-lg @error('base_price_view') is-invalid @enderror"
                name="base_price_view" id="base_price_view" min="0" step="1" inputmode="numeric"
                pattern="[0-9]*">
            @error('base_price_view')
                <span class="invalid-feedback" role="alert"><strong>Valor inválido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        @php $activoOld = old('activo', isset($servicio) ? (int)$servicio->activo : 1); @endphp
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Estado</label>
            <select name="activo" id="activo" class="form-control form-control-lg">
                <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
    </div>

    <center>
        <input class="btn btn-accion" type="submit"
            value="{{ ($Modo ?? '') === 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>
</div>
