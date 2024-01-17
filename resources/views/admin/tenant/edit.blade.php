<div class="modal fade" id="edit-tenant-modal{{ $tenant->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar Inquilino</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('tenants/update/' . $tenant) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                   
                    <div class="col-md-12 mb-3">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($tenant->tenant) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Inquilino</label>
                            <input required value="{{ isset($tenant->tenant) ? $tenant->tenant : '' }}" required type="text"
                                class="form-control form-control-lg @error('tenant') is-invalid @enderror"
                                name="tenant" id="tenant">
                            @error('tenant')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    

                    <input class="btn btn-velvet" type="submit"
                        value="Guardar Cambios">


                </form>
            </div>
        </div>
    </div>
</div>
