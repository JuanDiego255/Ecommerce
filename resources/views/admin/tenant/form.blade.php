@php
    $index = 0;
@endphp

<div class="row">
    
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($tenant->tenant) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Inquilino</label>
            <input required value="{{ isset($tenant->tenant) ? $tenant->tenant : '' }}" type="text"
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
        <input class="btn btn-velvet" type="submit"
            value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
