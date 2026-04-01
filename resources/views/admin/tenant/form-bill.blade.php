<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Monto (₡)</label>
        <input type="number" name="bill"
            class="filter-input @error('bill') is-invalid @enderror"
            min="1" step="1" placeholder="Ej: 10000" required>
        @error('bill')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Concepto / Detalle</label>
        <input type="text" name="detail"
            class="filter-input @error('detail') is-invalid @enderror"
            placeholder="Ej: Servidor, dominio…" required>
        @error('detail')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Fecha del gasto</label>
        <input type="date" name="bill_date"
            class="filter-input @error('bill_date') is-invalid @enderror"
            value="{{ now()->toDateString() }}" required>
        @error('bill_date')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
