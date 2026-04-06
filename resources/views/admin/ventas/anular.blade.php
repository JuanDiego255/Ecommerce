<div class="modal fade" id="anularModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Nota de anulación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form id="anularForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Nota</label>
                            <input type="text" name="nota_anulacion" id="nota_anulacion_input"
                                class="filter-input" required>
                        </div>
                        <div class="d-flex justify-content-end pt-1">
                            <button type="submit" class="s-btn-primary w-auto">Anular</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
