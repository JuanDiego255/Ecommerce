<script>
// ── State ─────────────────────────────────────────────────────────────────────
let materiales = @if($protocolo && $protocolo->materiales_necesarios) @json($protocolo->materiales_necesarios) @else [] @endif;
let pasos      = @if($protocolo && $protocolo->pasos) @json($protocolo->pasos) @else [] @endif;

// ── Materials ─────────────────────────────────────────────────────────────────
function renderMateriales() {
    const list = document.getElementById('materialesList');
    list.innerHTML = materiales.map((m, i) => `
        <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .65rem;background:#eef2ff;border-radius:20px;font-size:.82rem;color:#5e72e4;">
            ${escHtml(m)}
            <button type="button" onclick="deleteMaterial(${i})"
                    style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;line-height:1;">
                <i class="fas fa-times" style="font-size:.65rem;"></i>
            </button>
        </span>
    `).join('');
}

function addMaterial() {
    const input = document.getElementById('materialInput');
    const val = input.value.trim();
    if (!val) return;
    materiales.push(val);
    input.value = '';
    renderMateriales();
}

function deleteMaterial(idx) {
    materiales.splice(idx, 1);
    renderMateriales();
}

document.addEventListener('DOMContentLoaded', function () {

document.getElementById('addMaterialBtn').addEventListener('click', addMaterial);
document.getElementById('addMaterialBtn2').addEventListener('click', addMaterial);
document.getElementById('materialInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); addMaterial(); }
});

// ── Steps ─────────────────────────────────────────────────────────────────────
function renderPasos() {
    const list  = document.getElementById('pasosList');
    const empty = document.getElementById('pasoEmpty');
    empty.style.display = pasos.length ? 'none' : '';

    list.innerHTML = pasos.map((p, i) => `
        <div style="border:1px solid #e2e8f0;border-radius:10px;padding:1rem;margin-bottom:.75rem;background:#fafbfc;">
            <div class="d-flex align-items-start gap-2">
                <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:#5e72e4;color:#fff;font-size:.78rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                    ${i + 1}
                </span>
                <div class="flex-grow-1">
                    <div class="row g-2">
                        <div class="col-md-7">
                            <label class="filter-label">Título del paso</label>
                            <input type="text" class="filter-input paso-titulo" data-idx="${i}"
                                   value="${escHtml(p.titulo || '')}" placeholder="Ej: Limpieza inicial...">
                        </div>
                        <div class="col-md-3">
                            <label class="filter-label">Duración (min)</label>
                            <input type="number" class="filter-input paso-dur" data-idx="${i}" min="1" max="999"
                                   value="${p.duracion_min || ''}" placeholder="—">
                        </div>
                        <div class="col-12">
                            <label class="filter-label">Descripción / instrucciones</label>
                            <textarea class="filter-input paso-desc" data-idx="${i}" rows="2"
                                      placeholder="Describe cómo se realiza este paso...">${escHtml(p.descripcion || '')}</textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1" style="flex-shrink:0;">
                    ${i > 0 ? `<button type="button" class="act-btn ab-blue" onclick="movePaso(${i},-1)" style="width:24px;height:24px;font-size:.6rem;" title="Subir"><i class="fas fa-chevron-up"></i></button>` : ''}
                    ${i < pasos.length - 1 ? `<button type="button" class="act-btn ab-blue" onclick="movePaso(${i},1)" style="width:24px;height:24px;font-size:.6rem;" title="Bajar"><i class="fas fa-chevron-down"></i></button>` : ''}
                    <button type="button" class="act-btn ab-red" onclick="deletePaso(${i})" style="width:24px;height:24px;font-size:.6rem;" title="Eliminar"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
    `).join('');

    // Bind live changes
    document.querySelectorAll('.paso-titulo').forEach(el => {
        el.addEventListener('input', e => { pasos[e.target.dataset.idx].titulo = e.target.value; });
    });
    document.querySelectorAll('.paso-desc').forEach(el => {
        el.addEventListener('input', e => { pasos[e.target.dataset.idx].descripcion = e.target.value; });
    });
    document.querySelectorAll('.paso-dur').forEach(el => {
        el.addEventListener('input', e => { pasos[e.target.dataset.idx].duracion_min = parseInt(e.target.value) || null; });
    });
}

document.getElementById('addPasoBtn').addEventListener('click', () => {
    pasos.push({ titulo: '', descripcion: '', duracion_min: null });
    renderPasos();
});

function deletePaso(idx) {
    pasos.splice(idx, 1);
    renderPasos();
}

function movePaso(idx, dir) {
    const target = idx + dir;
    if (target < 0 || target >= pasos.length) return;
    [pasos[idx], pasos[target]] = [pasos[target], pasos[idx]];
    renderPasos();
}

// ── Serialize on submit ───────────────────────────────────────────────────────
function serializeProtocolo() {
    document.getElementById('materialesJson').value = JSON.stringify(materiales);
    document.getElementById('pasosJson').value      = JSON.stringify(pasos);
}
document.getElementById('protocoloForm').addEventListener('submit', serializeProtocolo);

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Init
renderMateriales();
renderPasos();

}); // DOMContentLoaded
</script>
