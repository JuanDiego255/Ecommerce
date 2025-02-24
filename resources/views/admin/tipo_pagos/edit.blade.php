<div class="modal fade" id="edit-tipo-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar tipo de pago</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('tipo_pago/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->tipo) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Tipo de pago</label>
                                <input value="{{ isset($item->tipo) ? $item->tipo : '' }}" required type="text"
                                    class="form-control form-control-lg @error('tipo') is-invalid @enderror"
                                    name="tipo" id="tipo">
                                @error('tipo')
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
