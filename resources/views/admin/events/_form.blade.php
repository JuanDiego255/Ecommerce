@php
    $activoOld = old('activo', isset($event) ? (int) $event->activo : 0);
@endphp

<div class="row">
    <div class="col-md-8 mb-1">
        <div class="input-group input-group-lg input-group-outline {{ isset($event->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ old('nombre', $event->nombre ?? '') }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre" maxlength="180"
                autocomplete="off">
            @error('nombre')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4 mb-1">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Activo</label>
            <select name="activo" class="form-control form-control-lg">
                <option value="1" {{ (int) $activoOld === 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ (int) $activoOld === 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Fecha y hora de inscripción</label>
            <input type="datetime-local" name="fecha_inscripcion"
                value="{{ old('fecha_inscripcion', isset($event->fecha_inscripcion) ? \Illuminate\Support\Carbon::parse($event->fecha_inscripcion)->format('Y-m-d\TH:i') : '') }}"
                class="form-control form-control-lg @error('fecha_inscripcion') is-invalid @enderror">
            @error('fecha_inscripcion')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($event->costo_crc) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Costo en colones (₡)</label>
            <input type="number" name="costo_crc" min="0" step="1"
                value="{{ old('costo_crc', $event->costo_crc ?? 0) }}"
                class="form-control form-control-lg @error('costo_crc') is-invalid @enderror">
            @error('costo_crc')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($event->ubicacion) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" maxlength="255"
                value="{{ old('ubicacion', $event->ubicacion ?? '') }}"
                class="form-control form-control-lg @error('ubicacion') is-invalid @enderror">
            @error('ubicacion')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($event->detalles) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Detalles del evento</label>
            <textarea name="detalles" rows="4" class="form-control form-control-lg @error('detalles') is-invalid @enderror">{{ old('detalles', $event->detalles ?? '') }}</textarea>
            @error('detalles')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    {{-- Cuentas de pago (JSON simple) --}}
    <div class="col-md-12">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Cuenta SINPE (cuenta + nombre)</label>
            <input name="cuenta_sinpe"
                placeholder='Cuenta + Nombre del propietario' type="text" value="{{ old('cuenta_sinpe', isset($event->cuenta_sinpe) ? $event->cuenta_sinpe : '') }}"
                class="form-control form-control-lg @error('cuenta_sinpe') is-invalid @enderror">
            @error('cuenta_sinpe')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Cuentas IBAN (cuenta + nombre)</label>
            <input name="cuenta_iban"
                placeholder='Cuenta + Nombre del propietario' type="text" value="{{ old('cuenta_iban', isset($event->cuenta_iban) ? $event->cuenta_iban : '') }}"
                class="form-control form-control-lg @error('cuenta_iban') is-invalid @enderror">
            @error('cuenta_iban')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    {{-- Imagen premios (opcional, pública) --}}
    <div class="col-md-12">
        <div class="input-group input-group-lg input-group-outline my-3">
            <label class="form-label">Imagen de premios (JPG/PNG)</label>
            <input type="file" name="imagen_premios"
                class="form-control form-control-lg @error('imagen_premios') is-invalid @enderror"
                accept="image/jpeg,image/png">
            @error('imagen_premios')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <center>
        <input class="btn btn-accion" type="submit"
            value="{{ ($Modo ?? '') === 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>
</div>
