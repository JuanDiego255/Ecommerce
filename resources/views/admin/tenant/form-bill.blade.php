@php
    $index = 0;
@endphp

<div class="row">
    
    <div class="col-md-12 mb-3">
        <div
            class="input-group input-group-lg input-group-outline  my-3">
            <label class="form-label">Gasto</label>
            <input required value="" type="number"
                class="form-control form-control-lg @error('bill') is-invalid @enderror" name="bill"
                id="bill">
            @error('bill')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
        <div
            class="input-group input-group-lg input-group-outline  my-3">
            <label class="form-label">Detalle</label>
            <input required value="" type="text"
                class="form-control form-control-lg @error('detail') is-invalid @enderror" name="detail"
                id="detail">
            @error('detail')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
        <div
            class="input-group input-group-lg input-group-outline is-filled  my-3">
            <label class="form-label">Fecha del gasto</label>
            <input required value="" type="date"
                class="form-control form-control-lg @error('bill_date') is-invalid @enderror" name="bill_date"
                id="bill_date">
            @error('bill_date')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>
    </div>

    <center>
        <input class="btn btn-accion" type="submit"
            value="{{ $Modo == 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>

</div>
