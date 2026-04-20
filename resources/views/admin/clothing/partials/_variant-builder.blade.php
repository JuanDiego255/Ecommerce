@php
    $vbAttrData = $attributes->map(function ($a) {
        return [
            'id'     => $a->id,
            'name'   => $a->name,
            'main'   => (int) $a->main,
            'values' => $a->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value])->values(),
        ];
    })->values();

    $vbExisting = isset($stocks)
        ? $stocks->filter(fn($s) => $s->attr_id)->map(fn($s) => [
            'attr_id'    => $s->attr_id,
            'value_attr' => $s->value_attr,
            'price'      => $s->price,
            'stock'      => $s->stock,
        ])->values()
        : collect([]);
@endphp

<div id="vb-container"
     data-attributes="{{ $vbAttrData->toJson() }}"
     data-existing="{{ $vbExisting->toJson() }}">

    {{-- Attribute type pills --}}
    <div class="surface-title mb-2">Tipos de atributo</div>
    <div id="vb-attrs" class="d-flex flex-wrap gap-2 mb-3"></div>

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
        <div id="vb-values-grid" class="d-flex flex-wrap gap-2"></div>
    </div>

    {{-- Variants table --}}
    <div id="vb-table-wrap" class="d-none">
        <div style="font-size:.71rem;color:var(--gray3);margin-bottom:.5rem;">
            Precio <strong>0</strong> = usa precio base del producto &nbsp;·&nbsp;
            Stock <strong>−1</strong> = sin control de inventario.
        </div>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-bottom:1px solid var(--gray1)">
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;font-size:.67rem">Variante</th>
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;width:130px;font-size:.67rem">Precio (₡)</th>
                    <th class="surface-title" style="padding:.4rem .6rem;text-align:left;width:100px;font-size:.67rem">Stock</th>
                    <th style="width:36px"></th>
                </tr>
            </thead>
            <tbody id="vb-tbody"></tbody>
        </table>
    </div>

    <div id="vb-empty-msg" style="font-size:.78rem;color:var(--gray3);text-align:center;padding:1.2rem 0">
        Seleccioná un tipo de atributo para agregar variantes al producto.
    </div>
</div>
