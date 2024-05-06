@php
    $index = 0;
@endphp

<div class="row">
    
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline  my-3">
            <label class="form-label">Pago</label>
            <input required value="" type="number"
                class="form-control form-control-lg @error('payment') is-invalid @enderror" name="payment"
                id="payment">
            @error('payment')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
        <div
            class="input-group input-group-lg input-group-outline is-filled  my-3">
            <label class="form-label">Fecha de pago</label>
            <input required value="" type="date"
                class="form-control form-control-lg @error('payment_date') is-invalid @enderror" name="payment_date"
                id="payment_date">
            @error('payment_date')
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
