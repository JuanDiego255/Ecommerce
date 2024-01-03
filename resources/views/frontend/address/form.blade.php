<div class="row">

    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-static {{ isset($item->address) ? 'is-filled' : '' }} my-3">
            <label>Dirección</label>
            <input value="{{ isset($item->address) ? $item->address : '' }}" required type="text"
                class="form-control form-control-lg @error('address') is-invalid @enderror" name="address" id="address">
            @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div
            class="input-group input-group-lg input-group-static {{ isset($item->address_two) ? 'is-filled' : '' }} my-3">
            <label>Dirección 2</label>
            <input value="{{ isset($item->address_two) ? $item->address_two : '' }}" type="text"
                class="form-control form-control-lg @error('address_two') is-invalid @enderror" name="address_two"
                id="address_two">
            @error('address_two')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-static {{ isset($item->country) ? 'is-filled' : '' }} my-3">
            <label>País</label>
            <input value="{{ isset($item->country) ? $item->country : '' }}" required type="text"
                class="form-control form-control-lg @error('country') is-invalid @enderror" name="country" id="country">
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-static {{ isset($item->province) ? 'is-filled' : '' }} my-3">
            <label>Provincia</label>
            <input value="{{ isset($item->province) ? $item->province : '' }}" required type="text"
                class="form-control form-control-lg @error('province') is-invalid @enderror" name="province" id="province">
            @error('province')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-static {{ isset($item->city) ? 'is-filled' : '' }} my-3">
            <label>Ciudad</label>
            <input value="{{ isset($item->city) ? $item->city : '' }}" required type="text"
                class="form-control form-control-lg @error('city') is-invalid @enderror" name="city" id="city">
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-static {{ isset($item->postal_code) ? 'is-filled' : '' }} my-3">
            <label>Código Postal</label>
            <input value="{{ isset($item->postal_code) ? $item->postal_code : '' }}" required type="text"
                class="form-control form-control-lg @error('postal_code') is-invalid @enderror" name="postal_code" id="postal_code">
            @error('postal_code')
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
