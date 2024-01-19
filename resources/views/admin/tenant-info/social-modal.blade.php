<div class="modal modal-lg fade" id="add-tenant-social-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nueva Información</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('tenant-social/store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-6">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->social_network) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Red Social</label>
                                <input required value="{{ isset($item->social_network) ? $item->social_network : '' }}"
                                    type="text"
                                    class="form-control form-control-lg @error('social_network') is-invalid @enderror"
                                    name="social_network" id="social_network">
                                @error('social_network')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->url) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">URL</label>
                                <input required value="{{ isset($item->url) ? $item->url : '' }}" type="text"
                                    class="form-control form-control-lg @error('url') is-invalid @enderror"
                                    name="url" id="url">
                                @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <center>
                        <input class="btn btn-velvet" type="submit" value="Guardar">
                    </center>


                </form>
            </div>

        </div>
    </div>
</div>
