<div class="row">
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->titulo) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Título</label>
            <input value="{{ isset($item->titulo) ? $item->titulo : '' }}" required type="text"
                class="form-control form-control-lg @error('titulo') is-invalid @enderror" name="titulo" id="titulo">
            @error('titulo')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
        <div class="input-group input-group-lg input-group-outline {{ isset($item->valor) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Valor</label>
            <input value="{{ isset($item->valor) ? $item->valor : '' }}" required type="text"
                class="form-control form-control-lg @error('valor') is-invalid @enderror" name="valor"
                id="valor">
            @error('valor')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-12 mb-3">
            <div class="input-group input-group-static mb-4">
                <input required class="form-control" type="file" name="image">
            </div>
        </div>
    </div>
    <center>
        <input class="btn btn-accion" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
