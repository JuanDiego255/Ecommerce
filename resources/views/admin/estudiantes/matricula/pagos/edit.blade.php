<div class="modal fade" id="edit-pago-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Editar pago</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('pago/matricula/update/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group is-filled input-group-lg input-group-outline my-3 me-2 flex-grow-1">
                                <label class="form-label">Tipo venta</label>
                                <select id="tipo_venta_edit" name="tipo_venta"
                                    class="form-control form-control-lg @error('tipo_venta') is-invalid @enderror"
                                    autocomplete="tipo_venta" autofocus>
                                    <option
                                        {{ isset($item->tipo_venta) && $item->tipo_venta == '1' ? 'selected' : '' }}
                                        value="1">
                                        Mensualidad
                                    </option>
                                    <option
                                        {{ isset($item->tipo_venta) && $item->tipo_venta == '2' ? 'selected' : '' }}
                                        value="2">
                                        {{ $info_estudiante->tipo == 'C' ? 'Otro' : 'Sesión' }}
                                    </option>
                                </select>
                                @error('tipo_venta')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div
                                class="input-group input-group-lg input-group-outline {{ isset($item->monto_pago) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Monto pago</label>
                                <input value="{{ isset($item->monto_pago) ? $item->monto_pago : '' }}" required
                                    type="number"
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
                                class="input-group input-group-lg input-group-outline {{ isset($item->descuento) ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Monto descuento</label>
                                <input value="{{ isset($item->descuento) ? $item->descuento : '' }}" type="number"
                                    class="form-control form-control-lg @error('descuento') is-invalid @enderror"
                                    name="descuento" id="descuento">
                                @error('descuento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static">
                                <label>Tipo de pago</label>
                                <select id="tipo_pago" name="tipo_pago"
                                    class="form-control form-control-lg @error('tipo_pago') is-invalid @enderror"
                                    autocomplete="tipo_pago" autofocus>
                                    @foreach ($tipo_pagos as $key => $tipo)
                                        <option value="{{ $tipo->id }}">
                                            {{ $tipo->tipo }}
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
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                <label class="form-label">Fecha de pago {{$item->tipo_venta}}</label>
                                <input
                                    value="{{ $item->fecha_pago }}"
                                    required type="date"
                                    class="form-control form-control-lg @error('fecha_pago') is-invalid @enderror"
                                    name="fecha_pago" id="fecha_pago">


                                @error('edad')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 {{ isset($item->tipo_venta) && $item->tipo_venta == 2 ? 'd-block' : 'd-none' }}">
                            <div class="input-group input-group-lg input-group-outline my-3 {{ isset($item->detalle) ? 'is-filled' : '' }}">
                                <label class="form-label">Detalle</label>
                                <input value="{{ isset($item->detalle) ? $item->detalle : '' }}" type="text"
                                    class="form-control form-control-lg @error('detalle') is-invalid @enderror"
                                    name="detalle" id="detalle_edit">
                                @error('detalle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input class="btn btn-accion" type="submit" value="Guardar Cambios">
                </form>
            </div>
        </div>
    </div>
</div>
