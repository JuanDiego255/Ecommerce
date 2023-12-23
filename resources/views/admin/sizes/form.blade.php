@php
    $index = 0;
@endphp

<div class="row">
    
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($size->size) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Talla</label>
            <input value="{{ isset($size->size) ? $size->size : '' }}" type="text"
                class="form-control form-control-lg @error('size') is-invalid @enderror" name="size"
                id="size">
            @error('size')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>

    <center>
        <input class="btn btn-velvet" type="submit"
            value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
