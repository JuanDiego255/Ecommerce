<div class="modal fade" id="changeArqueoModal" tabindex="-1" role="dialog"
    aria-labelledby="changeArqueoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="changeArqueoModalLabel">Ingresar nota de cambio de arqueo</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="changeArqueoForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>Arqueos</label>
                                <select id="arqueoSelect" name="arqueo_id"
                                    class="form-control form-control-lg" required>
                                    {{-- Opciones se llenan dinámicamente --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <label class="form-label">Nota (Cambio Arqueo)</label>
                                <input required type="text"
                                    class="form-control form-control-lg"
                                    name="justificacion_arqueo" id="justificacionArqueoInput">
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-velvet text-center" type="submit" value="Cambiar">
                </form>
            </div>
        </div>
    </div>
</div>
