<div class="row">
    <div class="col-md-12">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->tipo) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Tipo de pago</label>
            <input value="{{ isset($item->tipo) ? $item->tipo : '' }}" required type="text"
                class="form-control form-control-lg @error('tipo') is-invalid @enderror" name="tipo" id="tipo">
            @error('tipo')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <center>
        <input class="btn btn-velvet" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
