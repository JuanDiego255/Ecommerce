{{-- HEADER SIMPLE --}}
<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif
        <div>
            <h4 class="mb-1 fw-bold">Servicios de {{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">
                    💰 Servicios Profesionales
                    <strong>Sin monto definido</strong>
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        @can('barberos.manage')
            <a href="{{ url('barberos') }}" class="icon-btn" data-bs-toggle="tooltip" title="Volver a barberos">
                <i class="material-icons">arrow_back</i>
            </a>
            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-barbero-modal"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar barbero">
                <i class="material-icons">edit</i>
            </button>
        @endcan

    </div>
</div>

{{-- ALERTAS --}}
@if (session('ok'))
    <div class="alert alert-success text-white" id="alerta">{{ session('ok') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger text-white" id="alerta">{{ $errors->first() }}</div>
@endif

{{-- FORM: Asignar/actualizar servicio --}}
<div class="surface mb-4">
    <div class="surface-title">Asignar o actualizar un servicio</div>
    <form class="row g-3" action="{{ url('/barberos/service/store') }}" method="post" autocomplete="off">
        @csrf
        <input type="hidden" name="barbero_id" value="{{ $barbero->id }}">

        <div class="col-md-4">
            <label class="form-label fw-semibold">Servicio</label>
            <select name="servicio_id" class="form-control form-control-lg" required>
                <option value="">Seleccione…</option>
                @foreach ($allServicios as $s)
                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Precio (₡)</label>
            <input type="number" name="price_view" id="price_view" class="form-control form-control-lg" min="0"
                step="1" inputmode="numeric" pattern="[0-9]*" placeholder="0">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Duración (min)</label>
            <input type="number" name="duration_minutes" class="form-control form-control-lg" min="5"
                max="480" step="5" placeholder="30">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-semibold">Activo</label>
            @php $activoOld = old('activo', 1); @endphp
            <select name="activo" class="form-control form-control-lg">
                <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="col-12 text-end">
            <button class="btn btn-velvet px-4">
                <i class="material-icons align-middle">save</i> Guardar / Actualizar
            </button>
        </div>
    </form>
</div>

{{-- LISTA: Servicios asignados --}}
<div class="surface">
    <div class="surface-title d-flex align-items-center justify-content-between">
        <span>Servicios asignados</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-lite">
                <tr>
                    <th>Servicio</th>
                    <th class="text-end">Precio (₡)</th>
                    <th class="text-center">Duración (min)</th>
                    <th class="text-center">Activo</th>
                    <th class="text-center">Acciones</th>
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
                        <td class="fw-semibold">{{ $srv->nombre }}</td>
                        <td class="text-end">₡{{ number_format($precioColones, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $dur }}</td>
                        <td class="text-center">
                            {!! $activo ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>' !!}
                        </td>
                        <td class="text-center">
                            <button type="button" class="icon-btn" data-bs-toggle="tooltip" title="Editar"
                                onclick="$('#edit-pivot-{{ $srv->id }}').modal('show')">
                                <i class="material-icons">edit</i>
                            </button>

                            <form method="post"
                                action="{{ url('/barberos/' . $barbero->id . '/services/' . $srv->id) }}"
                                class="d-inline" onsubmit="return confirm('¿Quitar este servicio del barbero?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn text-danger" data-bs-toggle="tooltip"
                                    title="Quitar">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL EDITAR PIVOT --}}
                    <div class="modal fade" id="edit-pivot-{{ $srv->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-velvet text-white">
                                    <h5 class="modal-title">Editar {{ $srv->nombre }}</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal"
                                        action="{{ url('/barberos/' . $barbero->id . '/services/' . $srv->id) }}"
                                        method="post" autocomplete="off">
                                        @csrf @method('PUT')

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Precio (₡)</label>
                                            <input value="{{ old('price_view', $precioColones) }}" type="number"
                                                class="form-control form-control-lg @error('price_view') is-invalid @enderror"
                                                name="price_view" min="0" step="1" inputmode="numeric"
                                                pattern="[0-9]*">
                                            @error('price_view')
                                                <span class="invalid-feedback"><strong>Valor inválido</strong></span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Duración (min)</label>
                                            <input value="{{ old('duration_minutes', $dur) }}" type="number"
                                                class="form-control form-control-lg @error('duration_minutes') is-invalid @enderror"
                                                name="duration_minutes" min="5" max="480" step="5"
                                                inputmode="numeric">
                                            @error('duration_minutes')
                                                <span class="invalid-feedback"><strong>Valor inválido</strong></span>
                                            @enderror
                                        </div>

                                        <div class="mb-1">
                                            @php $activoOld = old('activo', $activo); @endphp
                                            <label class="form-label fw-semibold">Activo</label>
                                            <select name="activo" class="form-control form-control-lg">
                                                <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>
                                                    Sí</option>
                                                <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        </div>

                                        <div class="text-end mt-3">
                                            <button class="btn btn-velvet">
                                                <i class="material-icons align-middle">save</i> Guardar cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Sin servicios asignados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- (opcional) Modal editar barbero que ya usas en Info --}}
@includeWhen(View::exists('admin.barberos.partials._edit'), 'admin.barberos.partials._edit')
