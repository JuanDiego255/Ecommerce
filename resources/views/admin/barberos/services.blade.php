@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Servicios de ') }} {{ $barbero->nombre }}</strong>
        </h2>
    </center>

    <div class="card mt-3">
        <div class="card-body">
            @if (session('ok'))
                <div class="alert alert-success text-white" id="alerta">
                    {{ session('ok') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger text-white" id="alerta">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Asignar / actualizar un servicio --}}
            <form class="row" action="{{ url('/barberos/service/store') }}" method="post" autocomplete="off">
                {{ csrf_field() }}
                <input type="hidden" name="barbero_id" value="{{ $barbero->id }}">

                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                        <label class="form-label">Servicio</label>
                        <select name="servicio_id" class="form-control form-control-lg" required>
                            <option value="">Seleccione…</option>
                            @foreach ($allServicios as $s)
                                <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group input-group-lg input-group-outline my-3">
                        <label class="form-label">Precio (₡)</label>
                        <input type="number" name="price_view" id="price_view" class="form-control form-control-lg"
                            min="0" step="1" inputmode="numeric" pattern="[0-9]*" placeholder="">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group input-group-lg input-group-outline my-3">
                        <label class="form-label">Duración (min)</label>
                        <input type="number" name="duration_minutes" class="form-control form-control-lg" min="5"
                            max="480" step="5" placeholder="">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                        <label class="form-label">Activo</label>
                        @php $activoOld = old('activo', 1); @endphp
                        <select name="activo" class="form-control form-control-lg">
                            <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-accion">Guardar / Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista de servicios asignados --}}
    <div class="card mt-3 p-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7">{{ __('Servicio') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Precio (₡)') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Duración (min)') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Activo') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barbero->servicios as $srv)
                        @php
                            $precioCents = (int) ($srv->pivot->price_cents ?? $srv->base_price_cents);
                            $precioColones = (int) ($precioCents / 100);
                            $dur = (int) ($srv->pivot->duration_minutes ?? $srv->duration_minutes);
                            $activo = (int) $srv->pivot->activo;
                        @endphp
                        <tr>
                            <td class="align-middle text-sm">
                                <p class="text-success mb-0">{{ $srv->nombre }}</p>
                            </td>

                            <td class="align-middle text-sm">
                                <p class="font-weight-bold mb-0">₡{{ number_format($precioColones, 0, ',', '.') }}</p>
                            </td>

                            <td class="align-middle text-sm">
                                <p class="font-weight-bold mb-0">{{ $dur }}</p>
                            </td>

                            <td class="align-middle text-sm">
                                {!! $activo ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' !!}
                            </td>

                            <td class="align-middle">
                                {{-- Editar (modal) --}}
                                <button type="button" class="btn btn-accion" data-bs-toggle="modal"
                                    data-bs-target="#edit-pivot-{{ $srv->id }}">
                                    Editar
                                </button>

                                {{-- Quitar --}}
                                <form method="post"
                                    action="{{ url('/barberos/' . $barbero->id . '/services/' . $srv->id) }}"
                                    style="display:inline" onsubmit="return confirm('¿Quitar este servicio del barbero?');">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-admin-delete"
                                        style="text-decoration: none;">Quitar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- MODAL EDITAR PIVOT --}}
                        <div class="modal fade" id="edit-pivot-{{ $srv->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="editPivotLabel{{ $srv->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title font-weight-normal" id="editPivotLabel{{ $srv->id }}">
                                            Editar {{ $srv->nombre }}
                                        </h5>
                                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form class="form-horizontal"
                                            action="{{ url('/barberos/' . $barbero->id . '/services/' . $srv->id) }}"
                                            method="post" autocomplete="off">
                                            {{ csrf_field() }} {{ method_field('PUT') }}

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div
                                                        class="input-group input-group-lg input-group-outline is-filled my-3">
                                                        <label class="form-label">Precio (₡)</label>
                                                        <input value="{{ old('price_view', $precioColones) }}"
                                                            type="number"
                                                            class="form-control form-control-lg @error('price_view') is-invalid @enderror"
                                                            name="price_view" id="price_view_{{ $srv->id }}"
                                                            min="0" step="1" inputmode="numeric"
                                                            pattern="[0-9]*">
                                                        @error('price_view')
                                                            <span class="invalid-feedback" role="alert"><strong>Valor
                                                                    inválido</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div
                                                        class="input-group input-group-lg input-group-outline is-filled my-3">
                                                        <label class="form-label">Duración (min)</label>
                                                        <input value="{{ old('duration_minutes', $dur) }}" type="number"
                                                            class="form-control form-control-lg @error('duration_minutes') is-invalid @enderror"
                                                            name="duration_minutes"
                                                            id="duration_minutes_{{ $srv->id }}" min="5"
                                                            max="480" step="5" inputmode="numeric"
                                                            pattern="[0-9]*">
                                                        @error('duration_minutes')
                                                            <span class="invalid-feedback" role="alert"><strong>Valor
                                                                    inválido</strong></span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    @php $activoOld = old('activo', $activo); @endphp
                                                    <div
                                                        class="input-group input-group-lg input-group-outline is-filled my-3">
                                                        <label class="form-label">Activo</label>
                                                        <select name="activo" class="form-control form-control-lg"
                                                            id="activo_{{ $srv->id }}">
                                                            <option value="1"
                                                                {{ (int) $activoOld == 1 ? 'selected' : '' }}>Sí</option>
                                                            <option value="0"
                                                                {{ (int) $activoOld == 0 ? 'selected' : '' }}>No</option>
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
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Sin servicios asignados</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('barberos') }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection

@section('script')
    <script>
        // Marca is-filled en inputs con valor
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.input-group-outline .form-control').forEach(function(el) {
                if (el.value) el.closest('.input-group-outline')?.classList.add('is-filled');
                el.addEventListener('input', function() {
                    el.closest('.input-group-outline')?.classList.toggle('is-filled', !!el.value);
                });
            });
        });
    </script>
@endsection
