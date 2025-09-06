<div class="modal fade" id="edit-caja-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar caja</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('cajas/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12 mb-3">
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

                    </div>
                    <input class="btn btn-accion" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
