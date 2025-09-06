<div class="modal fade" id="anularModal" tabindex="-1" role="dialog"
    aria-labelledby="anularModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="anularModalLabel">Ingresar nota de anulación</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="anularForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <label class="form-label">Nota</label>
                                <input required type="text"
                                    class="form-control form-control-lg" name="nota_anulacion"
                                    id="nota_anulacion_input">
                            </div>       
                        </div>                      
                    </div>
                    <input class="btn btn-accion text-center" type="submit" value="Anular">
                </form>
            </div>
        </div>
    </div>
</div>
