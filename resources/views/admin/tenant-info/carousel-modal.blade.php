<div class="modal modal-lg fade" id="add-tenant-carousel-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nueva Imagen</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('tenant-carousel/store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="col-md-12">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($carousel->text1) ? 'is-filled' : '' }} my-3">

                            <textarea id="editor_add" placeholder="Título de la imagen" value="{{ isset($carousel->text1) ? $carousel->text1 : '' }}" type="text"
                                class="form-control form-control-lg @error('text1') is-invalid @enderror" name="text1" id="text1"></textarea>
                            @error('text1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                   {{--  <div class="col-md-12">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($carousel->text2) ? 'is-filled' : '' }} my-3">

                            <textarea placeholder="Descripción de la imagen" value="{{ isset($carousel->text2) ? $carousel->text2 : '' }}"
                                type="text" class="form-control form-control-lg @error('text2') is-invalid @enderror" name="text2"
                                id="text2"></textarea>
                            @error('text2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-lg input-group-outline is-filled }} my-3">
                                <label class="form-label">URL (Opcional)</label>
                                <input required value="{{ isset($item->url) ? $item->url : '' }}" type="text"
                                    placeholder="URL de redirección"
                                    class="form-control form-control-lg @error('url') is-invalid @enderror"
                                    name="url" id="url">
                                @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-lg input-group-outline is-filled my-3">
                                <label class="form-label">Texto del botón de enlace (Opcional)</label>
                                <input required value="{{ isset($item->link_text) ? $item->link_text : '' }}"
                                    type="text" placeholder="URL de redirección"
                                    class="form-control form-control-lg @error('link_text') is-invalid @enderror"
                                    name="link_text" id="link_text">
                                @error('link_text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mb-3">
                        <label class="form-label">Imagen</label>
                        <div class="input-group input-group-static mb-4">
                            <input required class="form-control" type="file" name="image">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Imagen (Visible en smartphones)</label>
                        <div class="input-group input-group-static mb-4">
                            <input required class="form-control" type="file" name="mobile_image">
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
