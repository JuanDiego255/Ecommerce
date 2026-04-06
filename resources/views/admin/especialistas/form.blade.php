<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Nombre</label>
        <input type="text" name="nombre" value="{{ isset($item->nombre) ? $item->nombre : '' }}"
            class="filter-input @error('nombre') is-invalid @enderror" required>
        @error('nombre')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="row g-2">
        <div class="col-6">
            <label class="filter-label">Salario base (₡)</label>
            <input type="number" name="salario_base" value="{{ isset($item->salario_base) ? $item->salario_base : '' }}"
                class="filter-input @error('salario_base') is-invalid @enderror">
            @error('salario_base')
                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-6">
            <label class="filter-label">Monto por servicio (₡)</label>
            <input type="number" name="monto_por_servicio" value="{{ isset($item->monto_por_servicio) ? $item->monto_por_servicio : '' }}"
                class="filter-input @error('monto_por_servicio') is-invalid @enderror">
            @error('monto_por_servicio')
                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
