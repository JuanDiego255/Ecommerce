<script>
// ─── Form Builder State ───────────────────────────────────────────────────────
let builderData = {
    secciones: []
};

// Load existing data if editing
@if(isset($existingCampos) && $existingCampos)
    builderData = @json($existingCampos);
@endif

let activaSeccionIdx = 0;

// ─── Render ───────────────────────────────────────────────────────────────────
function renderBuilder() {
    const canvas = document.getElementById('builderCanvas');
    const empty  = document.getElementById('emptyBuilder');

    if (!builderData.secciones.length) {
        canvas.innerHTML = '';
        empty.style.display = '';
        return;
    }
    empty.style.display = 'none';

    canvas.innerHTML = builderData.secciones.map((sec, sIdx) => `
        <div class="surface p-4 mb-3 seccion-card" data-sidx="${sIdx}"
             style="border:2px solid ${activaSeccionIdx === sIdx ? '#5e72e4' : 'transparent'};transition:border .15s;cursor:pointer;"
             onclick="setActivaSeccion(${sIdx})">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2 flex-grow-1 me-3">
                    <input type="text" class="filter-input" style="max-width:320px;"
                           value="${escHtml(sec.titulo || '')}"
                           placeholder="Nombre de la sección (opcional)"
                           onchange="updateSeccionTitulo(${sIdx}, this.value)"
                           onclick="event.stopPropagation()">
                    ${activaSeccionIdx === sIdx
                        ? '<span style="font-size:.72rem;color:#5e72e4;font-weight:600;"><i class="fas fa-pen me-1"></i>Sección activa</span>'
                        : ''}
                </div>
                <div class="d-flex gap-1">
                    ${sIdx > 0
                        ? `<button type="button" class="act-btn ab-blue" onclick="event.stopPropagation();moveSeccion(${sIdx},-1)" title="Subir"><i class="fas fa-chevron-up"></i></button>`
                        : ''}
                    ${sIdx < builderData.secciones.length - 1
                        ? `<button type="button" class="act-btn ab-blue" onclick="event.stopPropagation();moveSeccion(${sIdx},1)" title="Bajar"><i class="fas fa-chevron-down"></i></button>`
                        : ''}
                    <button type="button" class="act-btn ab-red" onclick="event.stopPropagation();deleteSeccion(${sIdx})" title="Eliminar sección">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            ${sec.campos && sec.campos.length
                ? `<div class="row g-2">${sec.campos.map((c, cIdx) => renderCampoCard(c, sIdx, cIdx)).join('')}</div>`
                : `<div style="border:2px dashed #e2e8f0;border-radius:8px;padding:1.5rem;text-align:center;color:#94a3b8;font-size:.82rem;">
                       Haz clic en un tipo de campo para agregarlo aquí
                   </div>`
            }
        </div>
    `).join('');
}

function renderCampoCard(c, sIdx, cIdx) {
    const tipoLabels = {
        texto:'Texto corto',area:'Texto largo',numero:'Número',
        fecha:'Fecha',select:'Selección',booleano:'Sí/No',escala:'Escala'
    };
    const tipoColors = {
        texto:'#5e72e4',area:'#6366f1',numero:'#0ea5e9',
        fecha:'#10b981',select:'#f59e0b',booleano:'#8b5cf6',escala:'#ef4444'
    };
    const col = c.ancho === 'completo' ? 'col-12' : (c.ancho === 'tercio' ? 'col-md-4' : 'col-md-6');
    return `
        <div class="${col}">
            <div style="border:1px solid #e2e8f0;border-radius:8px;padding:.6rem .75rem;background:#fafbfc;position:relative;" onclick="event.stopPropagation()">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;color:${tipoColors[c.tipo]||'#5e72e4'};letter-spacing:.05em;">
                            ${tipoLabels[c.tipo] || c.tipo}
                        </span>
                        <div style="font-size:.82rem;font-weight:600;color:#1e293b;">${escHtml(c.etiqueta || 'Sin etiqueta')}</div>
                        ${c.requerido ? '<span style="font-size:.65rem;color:#ef4444;">* Obligatorio</span>' : ''}
                    </div>
                    <div class="d-flex gap-1" style="flex-shrink:0;margin-left:.5rem;">
                        ${cIdx > 0
                            ? `<button type="button" class="act-btn ab-blue" onclick="moveCampo(${sIdx},${cIdx},-1)" title="Mover izq" style="width:22px;height:22px;font-size:.6rem;"><i class="fas fa-chevron-left"></i></button>`
                            : ''}
                        ${cIdx < builderData.secciones[sIdx].campos.length - 1
                            ? `<button type="button" class="act-btn ab-blue" onclick="moveCampo(${sIdx},${cIdx},1)" title="Mover der" style="width:22px;height:22px;font-size:.6rem;"><i class="fas fa-chevron-right"></i></button>`
                            : ''}
                        <button type="button" class="act-btn ab-yellow" onclick="editCampo(${sIdx},${cIdx})" title="Editar" style="width:22px;height:22px;font-size:.6rem;"><i class="fas fa-edit"></i></button>
                        <button type="button" class="act-btn ab-red" onclick="deleteCampo(${sIdx},${cIdx})" title="Eliminar" style="width:22px;height:22px;font-size:.6rem;"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        </div>`;
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Init (deferred until DOM + Bootstrap are ready) ─────────────────────────
document.addEventListener('DOMContentLoaded', function () {

// ─── Section actions ─────────────────────────────────────────────────────────
document.getElementById('addSeccionBtn').addEventListener('click', () => {
    builderData.secciones.push({ titulo: '', campos: [] });
    activaSeccionIdx = builderData.secciones.length - 1;
    renderBuilder();
});

function setActivaSeccion(idx) {
    activaSeccionIdx = idx;
    renderBuilder();
}

function updateSeccionTitulo(idx, val) {
    builderData.secciones[idx].titulo = val;
}

function deleteSeccion(idx) {
    if (!confirm('¿Eliminar esta sección y todos sus campos?')) return;
    builderData.secciones.splice(idx, 1);
    if (activaSeccionIdx >= builderData.secciones.length) {
        activaSeccionIdx = Math.max(0, builderData.secciones.length - 1);
    }
    renderBuilder();
}

function moveSeccion(idx, dir) {
    const target = idx + dir;
    if (target < 0 || target >= builderData.secciones.length) return;
    [builderData.secciones[idx], builderData.secciones[target]] = [builderData.secciones[target], builderData.secciones[idx]];
    activaSeccionIdx = target;
    renderBuilder();
}

// ─── Campo actions ────────────────────────────────────────────────────────────
document.querySelectorAll('.btn-tipo-campo').forEach(btn => {
    btn.addEventListener('click', () => {
        if (!builderData.secciones.length) {
            builderData.secciones.push({ titulo: '', campos: [] });
            activaSeccionIdx = 0;
        }
        const tipo = btn.dataset.tipo;
        openCampoModal(activaSeccionIdx, null, tipo);
    });
});

function deleteCampo(sIdx, cIdx) {
    builderData.secciones[sIdx].campos.splice(cIdx, 1);
    renderBuilder();
}

function moveCampo(sIdx, cIdx, dir) {
    const campos = builderData.secciones[sIdx].campos;
    const target = cIdx + dir;
    if (target < 0 || target >= campos.length) return;
    [campos[cIdx], campos[target]] = [campos[target], campos[cIdx]];
    renderBuilder();
}

function editCampo(sIdx, cIdx) {
    const campo = builderData.secciones[sIdx].campos[cIdx];
    openCampoModal(sIdx, cIdx, campo.tipo, campo);
}

// ─── Campo modal ──────────────────────────────────────────────────────────────
const campoModal = new bootstrap.Modal(document.getElementById('campoModal'));

function openCampoModal(sIdx, cIdx, tipo, existing = null) {
    document.getElementById('editSeccionIdx').value = sIdx;
    document.getElementById('editCampoIdx').value   = cIdx !== null ? cIdx : '';
    document.getElementById('editCampoTipo').value  = tipo;

    const tipoLabels = { texto:'Texto corto',area:'Texto largo',numero:'Número',fecha:'Fecha',select:'Selección',booleano:'Sí / No',escala:'Escala 1–10' };
    document.getElementById('campoModalTitle').textContent = 'Campo: ' + (tipoLabels[tipo] || tipo);

    document.getElementById('campoEtiqueta').value    = existing?.etiqueta   || '';
    document.getElementById('campoPlaceholder').value = existing?.placeholder || '';
    document.getElementById('campoAncho').value       = existing?.ancho       || 'mitad';
    document.getElementById('campoRequerido').checked = existing?.requerido   || false;

    // Type-specific
    document.getElementById('opcionesContainer').style.display = tipo === 'select' ? '' : 'none';
    document.getElementById('escalaContainer').style.display   = tipo === 'escala' ? '' : 'none';

    if (tipo === 'select') {
        document.getElementById('campoOpciones').value = (existing?.opciones || []).join('\n');
    }
    if (tipo === 'escala') {
        document.getElementById('escalaMin').value      = existing?.escala_min       ?? 1;
        document.getElementById('escalaMax').value      = existing?.escala_max       ?? 10;
        document.getElementById('escalaMaxLabel').value = existing?.escala_max_label ?? '';
    }

    campoModal.show();
}

document.getElementById('saveCampoBtn').addEventListener('click', () => {
    const etiqueta = document.getElementById('campoEtiqueta').value.trim();
    if (!etiqueta) {
        alert('La etiqueta del campo es obligatoria.');
        return;
    }

    const sIdx = parseInt(document.getElementById('editSeccionIdx').value);
    const cIdxRaw = document.getElementById('editCampoIdx').value;
    const cIdx = cIdxRaw !== '' ? parseInt(cIdxRaw) : null;
    const tipo = document.getElementById('editCampoTipo').value;

    const campo = {
        key:         (cIdx !== null ? builderData.secciones[sIdx].campos[cIdx].key : null) || crypto.randomUUID(),
        tipo,
        etiqueta,
        placeholder: document.getElementById('campoPlaceholder').value.trim(),
        ancho:       document.getElementById('campoAncho').value,
        requerido:   document.getElementById('campoRequerido').checked,
    };

    if (tipo === 'select') {
        campo.opciones = document.getElementById('campoOpciones').value
            .split('\n').map(o => o.trim()).filter(o => o);
    }
    if (tipo === 'escala') {
        campo.escala_min       = parseInt(document.getElementById('escalaMin').value);
        campo.escala_max       = parseInt(document.getElementById('escalaMax').value);
        campo.escala_max_label = document.getElementById('escalaMaxLabel').value.trim();
    }

    if (cIdx !== null) {
        builderData.secciones[sIdx].campos[cIdx] = campo;
    } else {
        builderData.secciones[sIdx].campos.push(campo);
    }

    campoModal.hide();
    renderBuilder();
});

// ─── Serialize on submit ──────────────────────────────────────────────────────
function serializeCampos() {
    // Sync any open section title inputs
    document.querySelectorAll('.seccion-card').forEach((card, idx) => {
        const input = card.querySelector('input[type=text]');
        if (input && builderData.secciones[idx]) {
            builderData.secciones[idx].titulo = input.value;
        }
    });
    document.getElementById('camposJson').value = JSON.stringify(builderData);
}

// Pre-serialize also on native form submit (fallback)
document.getElementById('plantillaForm').addEventListener('submit', serializeCampos);

// ─── Init ─────────────────────────────────────────────────────────────────────
renderBuilder();

}); // DOMContentLoaded
</script>
