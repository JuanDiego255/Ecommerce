@php
    $index = 0;
@endphp

<div class="row">

    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->description) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Descripción</label>
            <input value="{{ isset($item->description) ? $item->description : '' }}" required type="text"
                class="form-control form-control-lg @error('description') is-invalid @enderror" name="description"
                id="description">
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->url) ? 'is-filled' : '' }} my-3">
            <label class="form-label">URL</label>
            <input value="{{ isset($item->url) ? $item->url : '' }}" required
                type="text"
                class="form-control form-control-lg @error('url') is-invalid @enderror"
                name="url" id="url">
            @error('url')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-static mb-4">
            <input required class="form-control" type="file" name="image">
        </div>
    </div>

    <center>
        <input class="btn btn-velvet" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
