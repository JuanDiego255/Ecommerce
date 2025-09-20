@php
    $eventIdOld = old('event_id', isset($category) ? $category->event_id : null);
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Evento</label>
            <select name="event_id" class="form-control form-control-lg" required>
                <option value="" disabled {{ $eventIdOld ? '' : 'selected' }}>Selecciona un evento</option>
                @foreach ($events as $ev)
                    <option value="{{ $ev->id }}" {{ (int) $eventIdOld === (int) $ev->id ? 'selected' : '' }}>
                        {{ $ev->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-12 mb-1">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($category->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ old('nombre', $category->nombre ?? '') }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre"
                maxlength="120">
            @error('nombre')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Edad mínima</label>
            <input type="number" name="edad_min" min="0" step="1"
                value="{{ old('edad_min', $category->edad_min ?? '') }}"
                class="form-control form-control-lg @error('edad_min') is-invalid @enderror">
            @error('edad_min')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Edad máxima</label>
            <input type="number" name="edad_max" min="0" step="1"
                value="{{ old('edad_max', $category->edad_max ?? '') }}"
                class="form-control form-control-lg @error('edad_max') is-invalid @enderror">
            @error('edad_max')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <center>
        <input class="btn btn-accion" type="submit"
            value="{{ ($Modo ?? '') === 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>
</div>
