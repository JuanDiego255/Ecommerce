<div class="row">
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->name) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre Completo</label>
            <input value="{{ isset($item->name) ? $item->name : '' }}" required type="text"
                class="form-control form-control-lg @error('name') is-invalid @enderror" name="name"
                id="name">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->position) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Posición</label>
            <input value="{{ isset($item->position) ? $item->position : '' }}" required type="text"
                class="form-control form-control-lg @error('position') is-invalid @enderror" name="position"
                id="position">
            @error('position')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->url_face) ? 'is-filled' : '' }} my-3">
            <label class="form-label">URL Facebook</label>
            <input value="{{ isset($item->url_face) ? $item->url_face : '' }}" type="text"
                class="form-control form-control-lg @error('url_face') is-invalid @enderror" name="url_face"
                id="url_face">
            @error('url_face')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->url_insta) ? 'is-filled' : '' }} my-3">
            <label class="form-label">URL Insta</label>
            <input value="{{ isset($item->url_insta) ? $item->url_insta : '' }}" type="text"
                class="form-control form-control-lg @error('url_insta') is-invalid @enderror" name="url_insta"
                id="url_insta">
            @error('url_insta')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->url_linkedin) ? 'is-filled' : '' }} my-3">
            <label class="form-label">URL LinkedIn</label>
            <input value="{{ isset($item->url_linkedin) ? $item->url_linkedin : '' }}" type="text"
                class="form-control form-control-lg @error('url_linkedin') is-invalid @enderror" name="url_linkedin"
                id="url_linkedin">
            @error('url_linkedin')
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
        <input class="btn btn-accion" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
