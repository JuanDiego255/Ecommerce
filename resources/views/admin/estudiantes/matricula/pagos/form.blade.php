<div class="row">
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-outline is-filled }} my-3">
            <label class="form-label">Monto pago</label>
            <input value="{{ $info_estudiante->monto_curso }}" required type="number"
                class="form-control form-control-lg @error('monto_pago') is-invalid @enderror" name="monto_pago" id="monto_pago">
            @error('monto_pago')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>    
    <div class="col-md-6 mb-3">
        <div class="input-group input-group-lg input-group-outline {{ isset($item->descuento) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Monto descuento</label>
            <input value="{{ isset($item->descuento) ? $item->descuento : '' }}" type="number"
                class="form-control form-control-lg @error('descuento') is-invalid @enderror" name="descuento" id="descuento">
            @error('descuento')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="input-group input-group-static">
            <label>Tipo de pago</label>
            <select id="tipo_pago" name="tipo_pago"
                class="form-control form-control-lg @error('tipo_pago') is-invalid @enderror"
                autocomplete="tipo_pago" autofocus>
                @foreach ($tipo_pagos as $key => $item)
                    <option @if ($key == 0) selected @endif
                        value="{{ $item->id }}">
                        {{ $item->tipo }}
                    </option>
                @endforeach

            </select>
            @error('tipo_pago')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Fecha de pago</label>
            <input value="{{ isset($item->fecha_pago) ? $item->fecha_pago : '' }}" required type="date"
                class="form-control form-control-lg @error('fecha_pago') is-invalid @enderror" name="fecha_pago"
                id="fecha_pago">
            @error('edad')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<center>
    <input class="btn btn-velvet" type="submit" value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
</center>
