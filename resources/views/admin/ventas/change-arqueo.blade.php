<div class="modal fade" id="change-arqueo-modal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Ingresar nota de cambio de arqueo</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ url('cambiar/venta/' . $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}


                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>Arqueos</label>
                                <select id="arqueo_id" name="arqueo_id"
                                    class="form-control form-control-lg @error('arqueo_id') is-invalid @enderror"
                                    autocomplete="arqueo_id" autofocus>
                                    @php
                                        $arqueos_filter = $arqueos
                                            ->where('estado', 0)
                                            ->whereDate('fecha_ini', '<', $item->created_at)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                                    @endphp
                                    @foreach ($arqueos_filter as $key => $arqueo)
                                        <option value="{{ $arqueo->id }}"
                                            {{ $key == 0 ? 'selected' : '' }}>
                                            Inicio: {{ $arqueo->fecha_ini }} - Cierre: {{ $arqueo->fecha_fin }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('arqueo_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-lg input-group-outline my-3">
                                <label class="form-label">Nota (Cambio Arqueo)</label>
                                <input value="" required type="text"
                                    class="form-control form-control-lg @error('justificacion_arqueo') is-invalid @enderror"
                                    name="justificacion_arqueo" id="justificacion_arqueo">
                                @error('justificacion_arqueo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>Campo Requerido</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <input class="btn btn-velvet text-center" type="submit" value="Cambiar">
                </form>
            </div>
        </div>
    </div>
</div>
