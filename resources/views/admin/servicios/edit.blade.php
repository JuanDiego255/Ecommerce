<div class="modal fade" id="edit-servicio-modal{{ $servicio->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editServicioLabel{{ $servicio->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="editServicioLabel{{ $servicio->id }}">Editar servicio
                </h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('servicios/update/' . $servicio->id) }}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($servicio->nombre) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nombre</label>
                                <input value="{{ old('nombre', $servicio->nombre ?? '') }}" required type="text"
                                    class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                    name="nombre" id="nombre_{{ $servicio->id }}" maxlength="120" autocomplete="off">
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($servicio->descripcion) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion_{{ $servicio->id }}"
                                    class="form-control form-control-lg @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback" role="alert"><strong>Campo inválido</strong></span>
                                @enderror
                            </div>
                        </div>
                        @if ($servicio->image)
                            <img class="img-fluid img-thumbnail" src="{{ route('file', $servicio->image) }}"
                                style="width: 150px; height:150px;" alt="image">
                        @endif
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <input class="form-control" type="file" name="image">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($servicio->duration_minutes) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Duración (min)</label>
                                <input value="{{ old('duration_minutes', $servicio->duration_minutes ?? 30) }}"
                                    type="number"
                                    class="form-control form-control-lg @error('duration_minutes') is-invalid @enderror"
                                    name="duration_minutes" id="duration_minutes_{{ $servicio->id }}" min="5"
                                    max="480" step="5" inputmode="numeric" pattern="[0-9]*">
                                @error('duration_minutes')
                                    <span class="invalid-feedback" role="alert"><strong>Valor inválido</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($servicio->base_price_cents) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Precio base (₡)</label>
                                <input
                                    value="{{ old('base_price_view', isset($servicio->base_price_cents) ? (int) $servicio->base_price_cents / 100 : 0) }}"
                                    type="number"
                                    class="form-control form-control-lg @error('base_price_view') is-invalid @enderror"
                                    name="base_price_view" id="base_price_view_{{ $servicio->id }}" min="0"
                                    step="1" inputmode="numeric" pattern="[0-9]*">
                                @error('base_price_view')
                                    <span class="invalid-feedback" role="alert"><strong>Valor inválido</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            @php $activoOld = old('activo', isset($servicio) ? (int)$servicio->activo : 1); @endphp
                            <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                <label class="form-label">Estado</label>
                                <select name="activo" id="activo_{{ $servicio->id }}"
                                    class="form-control form-control-lg">
                                    <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>Inactivo
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <input class="btn btn-accion" type="submit" value="Guardar Cambios">
                </form>
            </div>

        </div>
    </div>
</div>
