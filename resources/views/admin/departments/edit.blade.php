<div class="modal fade" id="edit-department-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar Departamento</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('department/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}


                    <div class="col-md-12 mb-3">
                        <div
                            class="input-group input-group-lg input-group-outline {{ isset($item->department) ? 'is-filled' : '' }} my-3">
                            <label class="form-label">Departamento</label>
                            <input value="{{ isset($item->department) ? $item->department : '' }}" type="text"
                                class="form-control form-control-lg @error('department') is-invalid @enderror"
                                name="department" id="department">
                            @error('department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Campo Requerido</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    @if ($item->image)
                        <img class="img-fluid img-thumbnail" src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                            style="width: 150px; height:150px;" alt="image">
                    @endif
                    <div class="col-md-12 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <input class="form-control" type="file" name="image">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>{{ __('Promocionar categoría?') }}</label>
                        <div class="form-check">
                            <input {{ $item->black_friday == 1 ? 'checked' : '' }} class="form-check-input"
                                type="checkbox" value="1" id="black_friday" name="black_friday">
                            <label class="custom-control-label" for="customCheck1">{{ __('Promocionar categoría') }}</label>
                        </div>
                    </div>


                    <input class="btn btn-accion" type="submit" value="Guardar Cambios">


                </form>
            </div>
        </div>
    </div>
</div>
