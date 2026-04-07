{{-- Form Builder Canvas --}}
<div class="row g-3">
    {{-- Left: field toolbox --}}
    <div class="col-lg-3">
        <div class="surface p-3" style="position:sticky;top:80px;">
            <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                Tipos de campo
            </div>
            <p style="font-size:.78rem;color:#64748b;margin-bottom:.75rem;">
                Haz clic en un tipo para agregarlo a la sección activa.
            </p>
            <div class="d-flex flex-column gap-2">
                @php
                    $tipos = [
                        ['tipo' => 'texto',    'icon' => 'fa-font',         'label' => 'Texto corto'],
                        ['tipo' => 'area',     'icon' => 'fa-align-left',   'label' => 'Texto largo'],
                        ['tipo' => 'numero',   'icon' => 'fa-hashtag',      'label' => 'Número'],
                        ['tipo' => 'fecha',    'icon' => 'fa-calendar',     'label' => 'Fecha'],
                        ['tipo' => 'select',   'icon' => 'fa-list',         'label' => 'Selección'],
                        ['tipo' => 'booleano', 'icon' => 'fa-toggle-on',    'label' => 'Sí / No'],
                        ['tipo' => 'escala',   'icon' => 'fa-sliders-h',    'label' => 'Escala 1–10'],
                    ];
                @endphp
                @foreach($tipos as $t)
                    <button type="button"
                            class="btn-tipo-campo"
                            data-tipo="{{ $t['tipo'] }}"
                            style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;border:1px solid #e2e8f0;border-radius:8px;background:#fff;cursor:pointer;font-size:.83rem;text-align:left;">
                        <i class="fas {{ $t['icon'] }}" style="width:16px;color:#5e72e4;"></i>
                        {{ $t['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
                <button type="button" id="addSeccionBtn" class="s-btn-sec w-100">
                    <i class="fas fa-plus me-1"></i> Nueva sección
                </button>
            </div>
        </div>
    </div>

    {{-- Right: builder canvas --}}
    <div class="col-lg-9">
        <div id="builderCanvas">
            {{-- Secciones rendered by JS --}}
        </div>
        <div id="emptyBuilder" style="display:none;" class="surface p-5 text-center">
            <i class="fas fa-clipboard" style="font-size:2rem;color:#cbd5e0;"></i>
            <p class="mt-2 text-muted" style="font-size:.88rem;">
                Haz clic en <strong>+ Nueva sección</strong> para comenzar a construir tu plantilla.
            </p>
        </div>
    </div>
</div>

{{-- Campo editor modal --}}
<div class="modal fade" id="campoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="font-size:.95rem;font-weight:700;" id="campoModalTitle">Configurar campo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <input type="hidden" id="editSeccionIdx">
                <input type="hidden" id="editCampoIdx">
                <input type="hidden" id="editCampoTipo">

                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="filter-label">Etiqueta del campo *</label>
                        <input type="text" id="campoEtiqueta" class="filter-input" placeholder="Ej: Área de tratamiento">
                    </div>
                    <div class="col-md-5">
                        <label class="filter-label">Ancho en formulario</label>
                        <select id="campoAncho" class="filter-input">
                            <option value="completo">Completo (100%)</option>
                            <option value="mitad" selected>Mitad (50%)</option>
                            <option value="tercio">Tercio (33%)</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="filter-label">Texto de ayuda (placeholder)</label>
                        <input type="text" id="campoPlaceholder" class="filter-input" placeholder="Texto orientativo para el especialista">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <label class="d-flex align-items-center gap-2" style="cursor:pointer;font-size:.85rem;">
                            <input type="checkbox" id="campoRequerido" style="accent-color:#5e72e4;width:16px;height:16px;">
                            Campo obligatorio
                        </label>
                    </div>

                    {{-- Select options --}}
                    <div class="col-12" id="opcionesContainer" style="display:none;">
                        <label class="filter-label">Opciones (una por línea)</label>
                        <textarea id="campoOpciones" class="filter-input" rows="4" placeholder="Opción 1&#10;Opción 2&#10;Opción 3"></textarea>
                    </div>

                    {{-- Escala config --}}
                    <div class="col-12" id="escalaContainer" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="filter-label">Valor mínimo</label>
                                <input type="number" id="escalaMin" class="filter-input" value="1" min="0" max="10">
                            </div>
                            <div class="col-md-4">
                                <label class="filter-label">Valor máximo</label>
                                <input type="number" id="escalaMax" class="filter-input" value="10" min="1" max="20">
                            </div>
                            <div class="col-md-4">
                                <label class="filter-label">Etiqueta max</label>
                                <input type="text" id="escalaMaxLabel" class="filter-input" placeholder="Ej: Muy intenso">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:1rem 1.5rem;">
                <button type="button" class="s-btn-sec" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="s-btn-primary" id="saveCampoBtn">
                    <i class="fas fa-check me-1"></i> Aplicar
                </button>
            </div>
        </div>
    </div>
</div>
