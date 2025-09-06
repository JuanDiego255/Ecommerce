<div class="row">
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->rol) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Rol</label>
            <input value="{{ isset($item->rol) ? $item->rol : '' }}" required type="text"
                class="form-control form-control-lg @error('rol') is-invalid @enderror" name="rol"
                id="rol">
            @error('rol')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <center>
        <input class="btn btn-accion" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
