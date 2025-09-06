<div class="row">
    
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline  my-3">
            <label class="form-label">Inquilino</label>
            <input required value="" type="text"
                class="form-control form-control-lg @error('tenant') is-invalid @enderror" name="tenant"
                id="tenant">
            @error('tenant')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>

    <center>
        <input class="btn btn-accion" type="submit"
            value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
