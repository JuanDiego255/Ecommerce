<div class="modal fade" id="form-fav-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Ingresar código para verificación</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('check/list-fav') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="approve" value="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-static mb-4">
                                                <input required type="text" placeholder="Código"
                                                    class="form-control form-control-lg" name="code">
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-accion">
                                            {{ __('Enviar') }}</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
