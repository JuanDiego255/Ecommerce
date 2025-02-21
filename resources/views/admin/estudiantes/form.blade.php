<div class="row">
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre Completo</label>
            <input value="{{ isset($item->nombre) ? $item->nombre : '' }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre" id="nombre">
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->telefono) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Teléfono</label>
            <input value="{{ isset($item->telefono) ? $item->telefono : '' }}" type="text"
                class="form-control form-control-lg @error('telefono') is-invalid @enderror" name="telefono"
                id="telefono">
            @error('telefono')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->email) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Correo</label>
            <input value="{{ isset($item->email) ? $item->email : '' }}" type="text"
                class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->edad) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Edad</label>
            <input value="{{ isset($item->edad) ? $item->edad : '' }}" type="number"
                class="form-control form-control-lg @error('edad') is-invalid @enderror" name="edad" id="edad">
            @error('edad')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->fecha_pago) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Día de pago</label>
            <input value="{{ isset($item->fecha_pago) ? $item->fecha_pago : '' }}" required type="number"
                class="form-control form-control-lg @error('fecha_pago') is-invalid @enderror" name="fecha_pago"
                id="fecha_pago">
            @error('edad')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<center>
    <input class="btn btn-velvet" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
</center>
