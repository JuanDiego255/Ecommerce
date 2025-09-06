<div class="modal fade" id="edit-size-modal{{ $size->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('sizes/update/' . $size->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                   
                    <div class="col-md-12 mb-3">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($size->size) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Escriba aqui...</label>
                            <input value="{{ isset($size->size) ? $size->size : '' }}" required type="text"
                                class="form-control form-control-lg @error('size') is-invalid @enderror"
                                name="size" id="size">
                            @error('size')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    

                    <input class="btn btn-accion" type="submit"
                        value="Guardar Cambios">


                </form>
            </div>
        </div>
    </div>
</div>
