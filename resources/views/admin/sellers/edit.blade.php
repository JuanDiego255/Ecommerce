<div class="modal fade" id="edit-seller-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar Vendedor</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('seller/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->name) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nombre Completo</label>
                                <input value="{{ isset($item->name) ? $item->name : '' }}" required type="text"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    name="name" id="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->position) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Posición</label>
                                <input value="{{ isset($item->position) ? $item->position : '' }}" required
                                    type="text"
                                    class="form-control form-control-lg @error('position') is-invalid @enderror"
                                    name="position" id="position">
                                @error('position')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->url_face) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">URL Facebook</label>
                                <input value="{{ isset($item->url_face) ? $item->url_face : '' }}" type="text"
                                    class="form-control form-control-lg @error('url_face') is-invalid @enderror"
                                    name="url_face" id="url_face">
                                @error('url_face')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->url_insta) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">URL Insta</label>
                                <input value="{{ isset($item->url_insta) ? $item->url_insta : '' }}" type="text"
                                    class="form-control form-control-lg @error('url_insta') is-invalid @enderror"
                                    name="url_insta" id="url_insta">
                                @error('url_insta')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->url_linkedin) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">URL LinkedIn</label>
                                <input value="{{ isset($item->url_linkedin) ? $item->url_linkedin : '' }}"
                                    type="text"
                                    class="form-control form-control-lg @error('url_linkedin') is-invalid @enderror"
                                    name="url_linkedin" id="url_linkedin">
                                @error('url_linkedin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            @if ($item->image)
                                <img class="img-fluid img-thumbnail w-50" src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="image">
                            @endif
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>
                    </div>


                    <input class="btn btn-velvet" type="submit" value="Guardar Cambios">


                </form>
            </div>
        </div>
    </div>
</div>
