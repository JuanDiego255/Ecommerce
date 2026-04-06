<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Nombre</label>
        <input type="text" name="nombre" value="{{ isset($item->nombre) ? $item->nombre : '' }}"
            class="filter-input @error('nombre') is-invalid @enderror" required>
        @error('nombre')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
