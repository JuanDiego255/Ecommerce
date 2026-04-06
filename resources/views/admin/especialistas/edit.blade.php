<div class="modal fade" id="edit-especialista-modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Editar especialista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('especialistas/update/' . $item->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Nombre</label>
                            <input type="text" name="nombre" value="{{ $item->nombre ?? '' }}"
                                class="filter-input @error('nombre') is-invalid @enderror" required>
                            @error('nombre')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="filter-label">Salario base (₡)</label>
                                <input type="number" name="salario_base" value="{{ $item->salario_base ?? '' }}"
                                    class="filter-input @error('salario_base') is-invalid @enderror">
                                @error('salario_base')
                                    <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="filter-label">Monto por servicio (₡)</label>
                                <input type="number" name="monto_por_servicio" value="{{ $item->monto_por_servicio ?? '' }}"
                                    class="filter-input @error('monto_por_servicio') is-invalid @enderror">
                                @error('monto_por_servicio')
                                    <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                                @enderror
                            </div>
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
