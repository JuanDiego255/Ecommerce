<div class="row">
    <div class="col-md-12 mb-1">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ isset($item->nombre) ? $item->nombre : '' }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre"
                id="nombre">
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>       
    </div>
    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->salario_base) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Salario base</label>
            <input value="{{ isset($item->salario_base) ? $item->salario_base : '' }}" type="number"
                class="form-control form-control-lg @error('salario_base') is-invalid @enderror" name="salario_base"
                id="salario_base">
            @error('salario_base')
                <span class="invalid-feedback" role="alert">
                    <strong>Campo Requerido</strong>
                </span>
            @enderror
        </div>       
    </div>
    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($item->monto_por_servicio) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Monto por servicio</label>
            <input value="{{ isset($item->monto_por_servicio) ? $item->monto_por_servicio : '' }}" type="number"
                class="form-control form-control-lg @error('monto_por_servicio') is-invalid @enderror" name="monto_por_servicio"
                id="monto_por_servicio">
            @error('monto_por_servicio')
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
