<div class="modal fade" id="changeArqueoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold">Cambio de arqueo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form id="changeArqueoForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;gap:12px;">
                        <div>
                            <label class="filter-label">Arqueo</label>
                            <select id="arqueoSelect" name="arqueo_id" class="filter-input" required>
                                {{-- Opciones se llenan dinámicamente --}}
                            </select>
                        </div>
                        <div>
                            <label class="filter-label">Nota (Cambio Arqueo)</label>
                            <input type="text" name="justificacion_arqueo" id="justificacionArqueoInput"
                                class="filter-input" required>
                        </div>
                        <div class="d-flex justify-content-end pt-1">
                            <button type="submit" class="s-btn-primary w-auto">Cambiar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
