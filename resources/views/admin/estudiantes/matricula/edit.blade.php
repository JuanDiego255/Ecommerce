<div class="modal fade" id="edit-matricula-modal{{ $matricula->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar {{$label}}</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('matricula/update/' . $matricula->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->nombre) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Nombre Completo</label>
                                <input readonly value="{{ isset($item->nombre) ? $item->nombre : '' }}" required
                                    type="text"
                                    class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                    name="nombre" id="nombre">
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($matricula->curso) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Curso</label>
                                <input value="{{ isset($matricula->curso) ? $matricula->curso : '' }}" required
                                    type="text"
                                    class="form-control form-control-lg @error('curso') is-invalid @enderror"
                                    name="curso" id="curso">
                                @error('curso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($matricula->monto_pago) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Monto {{$label}}</label>
                                <input value="{{ isset($matricula->monto_pago) ? $matricula->monto_pago : '' }}"
                                    required type="number"
                                    class="form-control form-control-lg @error('monto_pago') is-invalid @enderror"
                                    name="monto_pago" id="monto_pago">
                                @error('monto_pago')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->monto_curso) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">{{ isset($item->curso) || $item->tipo_estudiante == 'Y' ? 'Precio por sesión' : 'Precio del curso' }}</label>
                                <input value="{{ isset($item->monto_curso) ? $item->monto_curso : '' }}" required
                                    type="number"
                                    class="form-control form-control-lg @error('monto_curso') is-invalid @enderror"
                                    name="monto_curso" id="monto_curso">
                                @error('monto_curso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>Tipo de pago</label>
                                <select id="tipo_pago" name="tipo_pago"
                                    class="form-control form-control-lg @error('tipo_pago') is-invalid @enderror"
                                    autocomplete="tipo_pago" autofocus>
                                    @foreach ($tipo_pagos as $key => $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo_pago')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-lg input-group-outline is-filled  my-3">
                                <label class="form-label">Fecha Matricula</label>
                                <input value="{{ isset($item->fecha_matricula) ? $item->fecha_matricula : '' }}"
                                    required type="date"
                                    class="form-control form-control-lg @error('fecha_matricula') is-invalid @enderror"
                                    name="fecha_matricula" id="fecha_matricula">
                                @error('fecha_matricula')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <center>
                        <input class="btn btn-velvet" type="submit" value="Guardar Cambios">
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
