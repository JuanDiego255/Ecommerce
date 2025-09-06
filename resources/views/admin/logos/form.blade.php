<div class="row">
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->name) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ isset($item->name) ? $item->name : '' }}" required type="text"
                class="form-control form-control-lg @error('name') is-invalid @enderror" name="name"
                id="name">
            @error('name')
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
        <div class="col-md-12 mb-3">
            <label>{{ __('Es un proveedor? (Si es proveedor se mostrará como un logo utilizado por la empresa)') }}</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_supplier"
                    name="is_supplier" {{ old('is_supplier') ? 'checked' : '' }}>
                <label class="custom-control-label" for="customCheck1">Es proveedor?</label>
            </div>
        </div>
    </div>
    <center>
        <input class="btn btn-accion" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
