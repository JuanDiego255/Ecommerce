<div class="modal fade" id="edit-logo-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar logo</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('logos/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->name) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nombre</label>
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
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Es un proveedor? (Si es proveedor se mostrará como un logo utilizado por la empresa)') }}</label>
                            <div class="form-check">
                                <input {{ $item->is_supplier == 1 ? 'checked' : '' }} class="form-check-input"
                                    type="checkbox" value="1" id="is_supplier" name="is_supplier">
                                <label class="custom-control-label" for="customCheck1">Es proveedor?</label>
                            </div>
                        </div>

                    </div>
                    <input class="btn btn-velvet" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
