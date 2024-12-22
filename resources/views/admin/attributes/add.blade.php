<div class="modal fade" id="add-attribute-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Nuevo</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('attribute/store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Atributo') }}</label>
                                <input required type="text" class="form-control form-control-lg" name="name">
                            </div>
                        </div>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static">
                                    <label>{{ __('Estilo del atributo') }}</label>
                                    <select id="type" name="type"
                                        class="form-control form-control-lg @error('type') is-invalid @enderror"
                                        autocomplete="type" autofocus>
                                        <option selected value="0">
                                            {{ __('Botón simple') }}
                                        </option>
                                        <option value="1">
                                            {{ __('Seleccionador') }}
                                        </option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-velvet"> {{ __('Agregar atributo') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
