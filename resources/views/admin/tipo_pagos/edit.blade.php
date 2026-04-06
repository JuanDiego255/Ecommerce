<div class="modal fade" id="edit-tipo-modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Editar tipo de pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form action="{{ url('tipo_pago/update/' . $item->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Tipo de pago</label>
                            <input type="text" name="tipo" value="{{ $item->tipo ?? '' }}"
                                class="filter-input @error('tipo') is-invalid @enderror" required>
                            @error('tipo')
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
