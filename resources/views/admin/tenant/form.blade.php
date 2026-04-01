<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Monto (₡)</label>
        <input type="number" name="payment"
            class="filter-input @error('payment') is-invalid @enderror"
            min="1" step="1" placeholder="Ej: 25000" required>
        @error('payment')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Fecha de pago</label>
        <input type="date" name="payment_date"
            class="filter-input @error('payment_date') is-invalid @enderror"
            value="{{ now()->toDateString() }}" required>
        @error('payment_date')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
