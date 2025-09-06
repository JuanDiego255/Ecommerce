<div class="row">
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ isset($item->nombre) ? $item->nombre : '' }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre"
                id="nombre">
            @error('nombre')
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
