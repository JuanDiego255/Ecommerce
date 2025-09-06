<div class="modal fade" id="edit-metrica-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar métrica</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('metrica/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->titulo) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Título</label>
                                <input value="{{ isset($item->titulo) ? $item->titulo : '' }}" required type="text"
                                    class="form-control form-control-lg @error('titulo') is-invalid @enderror"
                                    name="titulo" id="titulo">
                                @error('titulo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->valor) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Valor</label>
                                <input value="{{ isset($item->valor) ? $item->valor : '' }}" required type="text"
                                    class="form-control form-control-lg @error('valor') is-invalid @enderror"
                                    name="valor" id="valor">
                                @error('valor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if ($item->image)
                            <img class="img-fluid img-thumbnail w-50"
                                src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                alt="image">
                        @endif
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                    </div>
                    <input class="btn btn-accion" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
