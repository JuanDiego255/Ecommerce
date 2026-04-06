<div style="display:grid;gap:12px;">
    <div>
        <label class="filter-label">Nombre Completo</label>
        <input type="text" name="nombre" value="{{ isset($item->nombre) ? $item->nombre : '' }}"
            class="filter-input @error('nombre') is-invalid @enderror" required>
        @error('nombre')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Teléfono</label>
        <input type="text" name="telefono" value="{{ isset($item->telefono) ? $item->telefono : '' }}"
            class="filter-input @error('telefono') is-invalid @enderror">
        @error('telefono')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Correo</label>
        <input type="text" name="email" value="{{ isset($item->email) ? $item->email : '' }}"
            class="filter-input @error('email') is-invalid @enderror">
        @error('email')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Edad</label>
        <input type="number" name="edad" value="{{ isset($item->edad) ? $item->edad : '' }}"
            class="filter-input @error('edad') is-invalid @enderror">
        @error('edad')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label class="filter-label">Día de pago</label>
        <input type="number" name="fecha_pago" value="{{ isset($item->fecha_pago) ? $item->fecha_pago : '' }}"
            class="filter-input @error('fecha_pago') is-invalid @enderror" required>
        @error('fecha_pago')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ $Modo === 'crear' ? 'Agregar' : 'Guardar cambios' }}
        </button>
    </div>
</div>
