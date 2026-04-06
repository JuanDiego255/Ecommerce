<div class="modal fade" id="edit-estudiante-modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Editar estudiante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('estudiantes/update/' . $item->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{ $tipo }}" name="tipo_est">
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Nombre Completo</label>
                            <input type="text" name="nombre" value="{{ $item->nombre ?? '' }}"
                                class="filter-input @error('nombre') is-invalid @enderror" required>
                            @error('nombre')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="filter-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ $item->telefono ?? '' }}"
                                class="filter-input @error('telefono') is-invalid @enderror" required>
                            @error('telefono')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="filter-label">Correo</label>
                            <input type="text" name="email" value="{{ $item->email ?? '' }}"
                                class="filter-input @error('email') is-invalid @enderror" required>
                            @error('email')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="filter-label">Edad</label>
                            <input type="number" name="edad" value="{{ $item->edad ?? '' }}"
                                class="filter-input @error('edad') is-invalid @enderror" required>
                            @error('edad')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="filter-label">Fecha Pago</label>
                            <input type="date" name="fecha_pago" value="{{ $item->fecha_pago ?? '' }}"
                                class="filter-input @error('fecha_pago') is-invalid @enderror" required>
                            @error('fecha_pago')
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
