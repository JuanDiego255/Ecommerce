<div class="modal fade" id="edit-social-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar Imagen</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('social/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}


                    <div class="col-md-12 mb-3">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($item->description) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Descripción</label>
                            <input value="{{ isset($item->description) ? $item->description : '' }}" required
                                type="text"
                                class="form-control form-control-lg @error('description') is-invalid @enderror"
                                name="description" id="description">
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($item->url) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">URL</label>
                            <input value="{{ isset($item->url) ? $item->url : '' }}" required
                                type="text"
                                class="form-control form-control-lg @error('url') is-invalid @enderror"
                                name="url" id="url">
                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @if ($item->image)
                        <img class="img-fluid img-thumbnail w-50" src="{{tenant_asset('/') . '/'. $item->image}}"
                             alt="image">
                    @endif
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>


                    <input class="btn btn-velvet" type="submit" value="Guardar Cambios">


                </form>
            </div>
        </div>
    </div>
</div>
