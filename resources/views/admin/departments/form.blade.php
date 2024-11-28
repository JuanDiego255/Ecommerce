@php
    $index = 0;
@endphp

<div class="row">

    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->department) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Departamento</label>
            <input value="{{ isset($item->department) ? $item->department : '' }}" type="text"
                class="form-control form-control-lg @error('department') is-invalid @enderror" name="department"
                id="department">
            @error('department')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-lg input-group-outline my-3">
            <input required class="form-control" type="file" name="image">
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <label>{{ __('Black Friday?') }}</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="black_friday" name="black_friday"
                {{ old('black_friday') ? 'checked' : '' }}>
            <label class="custom-control-label" for="customCheck1">{{ __('Black Friday') }}</label>
        </div>
    </div>
    <center>
        <input class="btn btn-velvet" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
