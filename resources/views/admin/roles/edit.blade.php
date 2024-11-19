<div class="modal fade" id="edit-rol-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar rol</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('role/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->rol) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Rol</label>
                                <input value="{{ isset($item->rol) ? $item->rol : '' }}" required type="text"
                                    class="form-control form-control-lg @error('rol') is-invalid @enderror"
                                    name="rol" id="rol">
                                @error('rol')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Estado') }}</label>
                            <div class="form-check">
                                <input {{ $item->status == 1 ? 'checked' : '' }} class="form-check-input"
                                    type="checkbox" value="1" id="status" name="status">
                                <label class="custom-control-label" for="customCheck1">{{ __('Estado') }}</label>
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-velvet" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
