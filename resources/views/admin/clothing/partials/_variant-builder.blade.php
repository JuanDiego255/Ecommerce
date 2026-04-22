@php
    $vbAttrData = $attributes->map(function ($a) {
        return [
            'id'     => $a->id,
            'name'   => $a->name,
            'main'   => (int) $a->main,
            'values' => $a->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->values(),
        ];
    })->values();

    $vbExisting = isset($combinations)
        ? $combinations->map(fn($c) => [
            'combination_id' => $c->id,
            'value_ids'      => $c->values->pluck('value_attr')->values(),
            'price'          => $c->price,
            'stock'          => $c->stock,
        ])->values()
        : collect([]);
@endphp

<div id="vb-container"
     data-attributes="{{ $vbAttrData->toJson() }}"
     data-existing="{{ $vbExisting->toJson() }}">

    {{-- Attribute type pills + "Crear tipo" toggle --}}
    <div class="surface-title mb-2">Tipos de atributo</div>
    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
        <div id="vb-attrs" class="d-flex flex-wrap gap-2"></div>
        <button type="button" id="vb-new-attr-toggle"
                style="background:none;border:none;font-size:.75rem;color:var(--blue);
                       cursor:pointer;padding:0;display:inline-flex;align-items:center;gap:3px;">
            <i class="fas fa-plus" style="font-size:.65rem"></i> Crear tipo
        </button>
    </div>

    {{-- Inline new attribute type row --}}
    <div id="vb-new-attr-row" class="d-none d-flex gap-2 mb-3" style="max-width:320px">
        <input type="text" id="vb-new-attr-input" class="filter-input vb-input"
               placeholder="Nombre del tipo (ej: Material)" style="flex:1">
        <button type="button" id="vb-new-attr-btn" class="s-btn-primary"
                style="padding:4px 12px;font-size:.78rem;white-space:nowrap">
            <i class="fas fa-plus me-1"></i>Agregar
        </button>
    </div>

    {{-- Value picker (shown after clicking a pill) --}}
    <div id="vb-picker" class="d-none mb-3 p-3"
         style="background:var(--gray0);border-radius:10px;border:1px solid var(--gray1);">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span id="vb-picker-title" style="font-size:.8rem;font-weight:600;color:var(--gray5)"></span>
            <button type="button" id="vb-select-all"
                    style="font-size:.72rem;padding:3px 10px;border:1px solid var(--gray1);
                           border-radius:6px;background:var(--white);cursor:pointer;color:var(--gray4);">
                Seleccionar todo
            </button>
        </div>

        {{-- Existing value chips --}}
        <div id="vb-values-grid" class="d-flex flex-wrap gap-2 mb-2"></div>

        {{-- Inline new value --}}
        <div class="d-flex gap-2 mt-2" style="max-width:300px">
            <input type="text" id="vb-new-val-input" class="filter-input vb-input"
                   placeholder="Nuevo valor..." style="flex:1">
            <button type="button" id="vb-new-val-btn"
                    style="font-size:.72rem;padding:4px 10px;border:1px solid var(--blue);
                           border-radius:6px;background:var(--blue);color:#fff;cursor:pointer;
                           white-space:nowrap;">
                <i class="fas fa-plus me-1"></i>Agregar
            </button>
        </div>
    </div>

    {{-- Variants table --}}
    <div id="vb-table-wrap" class="d-none">
        <div style="font-size:.71rem;color:var(--gray3);margin-bottom:.5rem;">
            Precio <strong>0</strong> = usa el precio base del producto &nbsp;·&nbsp;
            Stock <strong>−1</strong> = sin control de inventario.
        </div>
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:320px">
            <thead>
                <tr style="border-bottom:1px solid var(--gray1)">
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;font-size:.67rem">Combinación</th>
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;font-size:.67rem">Precio (₡)</th>
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;font-size:.67rem">Stock</th>
                    <th style="width:32px"></th>
                </tr>
            </thead>
            <tbody id="vb-tbody"></tbody>
        </table>
        </div>
    </div>

    <div id="vb-empty-msg" style="font-size:.78rem;color:var(--gray3);text-align:center;padding:1.2rem 0">
        Seleccioná un tipo de atributo para agregar variantes al producto.
    </div>
</div>
