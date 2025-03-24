<div class="modal fade" id="anular-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Ingresar nota de anulación</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('anular/venta/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->nota_anulacion) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nota</label>
                                <input value="{{ isset($item->nota_anulacion) ? $item->nota_anulacion : '' }}" required type="text"
                                    class="form-control form-control-lg @error('nota_anulacion') is-invalid @enderror" name="nota_anulacion"
                                    id="nota_anulacion">
                                @error('nota_anulacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>       
                        </div>                      

                    </div>
                    <input class="btn btn-velvet text-center" type="submit" value="Anular">
                </form>
            </div>
        </div>
    </div>
</div>
