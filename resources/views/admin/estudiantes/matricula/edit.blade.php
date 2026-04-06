<div class="modal fade" id="edit-matricula-modal{{ $matricula->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Editar {{ $label }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('matricula/update/' . $matricula->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Nombre Completo</label>
                            <input type="text" name="nombre" value="{{ $item->nombre ?? '' }}"
                                class="filter-input" readonly>
                        </div>
                        <div>
                            <label class="filter-label">Curso</label>
                            <input type="text" name="curso" value="{{ $matricula->curso ?? '' }}"
                                class="filter-input @error('curso') is-invalid @enderror" required>
                            @error('curso')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="filter-label">Monto {{ $label }} (₡)</label>
                                <input type="number" name="monto_pago" value="{{ $matricula->monto_pago ?? '' }}"
                                    class="filter-input @error('monto_pago') is-invalid @enderror" required>
                                @error('monto_pago')
                                    <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="filter-label">{{ (isset($item->curso) || $item->tipo_estudiante == 'Y') ? 'Precio por sesión (₡)' : 'Precio del curso (₡)' }}</label>
                                <input type="number" name="monto_curso" value="{{ $item->monto_curso ?? '' }}"
                                    class="filter-input @error('monto_curso') is-invalid @enderror" required>
                                @error('monto_curso')
                                    <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="filter-label">Tipo de pago</label>
                            <select name="tipo_pago" class="filter-input @error('tipo_pago') is-invalid @enderror">
                                @foreach ($tipo_pagos as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->tipo }}</option>
                                @endforeach
                            </select>
                            @error('tipo_pago')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="filter-label">Fecha Matrícula</label>
                            <input type="date" name="fecha_matricula" value="{{ $item->fecha_matricula ?? '' }}"
                                class="filter-input @error('fecha_matricula') is-invalid @enderror" required>
                            @error('fecha_matricula')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end pt-1">
                            <button type="submit" class="s-btn-primary w-auto">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
