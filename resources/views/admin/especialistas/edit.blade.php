<div class="modal fade" id="edit-especialista-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar especialista</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('especialistas/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->nombre) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nombre</label>
                                <input value="{{ isset($item->nombre) ? $item->nombre : '' }}" required type="text"
                                    class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                    name="nombre" id="nombre">
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
                                <input value="{{ isset($item->salario_base) ? $item->salario_base : '' }}"
                                    type="number"
                                    class="form-control form-control-lg @error('salario_base') is-invalid @enderror"
                                    name="salario_base" id="salario_base">
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
                                <input value="{{ isset($item->monto_por_servicio) ? $item->monto_por_servicio : '' }}"
                                    type="number"
                                    class="form-control form-control-lg @error('monto_por_servicio') is-invalid @enderror"
                                    name="monto_por_servicio" id="monto_por_servicio">
                                @error('monto_por_servicio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-velvet" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
