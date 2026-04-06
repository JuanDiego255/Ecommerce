<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Tipo de pago</label>
        <input type="text" name="tipo" value="{{ isset($item->tipo) ? $item->tipo : '' }}"
            class="filter-input @error('tipo') is-invalid @enderror" required>
        @error('tipo')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
