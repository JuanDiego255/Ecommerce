<div style="display:grid;gap:14px;">

    {{-- Nombre --}}
    <div>
        <label class="filter-label">Nombre del especialista</label>
        <input type="text" name="nombre" value="{{ $item->nombre ?? '' }}"
            class="filter-input @error('nombre') is-invalid @enderror"
            placeholder="Ej: Dra. Ana Mora" required>
        @error('nombre')
            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
        @enderror
    </div>

    {{-- Salario base + Monto por servicio --}}
    <div class="row g-2">
        <div class="col-6">
            <label class="filter-label">
                Salario base (₡)
                <span class="ms-1" title="Monto fijo que el especialista recibe por sesión independientemente del precio del servicio."
                    style="cursor:help;color:#a0aec0;font-size:.8rem;">&#9432;</span>
            </label>
            <input type="number" name="salario_base" value="{{ $item->salario_base ?? '' }}"
                class="filter-input @error('salario_base') is-invalid @enderror"
                placeholder="0">
            @error('salario_base')
                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-6">
            <label class="filter-label">
                Monto por servicio (₡)
                <span class="ms-1" title="Monto fijo alternativo por servicio prestado. Se usa si el salario base es 0."
                    style="cursor:help;color:#a0aec0;font-size:.8rem;">&#9432;</span>
            </label>
            <input type="number" name="monto_por_servicio" value="{{ $item->monto_por_servicio ?? '' }}"
                class="filter-input @error('monto_por_servicio') is-invalid @enderror"
                placeholder="0">
            @error('monto_por_servicio')
                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
            @enderror
        </div>
    </div>

    {{-- Separador de configuración de cálculo --}}
    <div style="margin-top:4px;">
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#a0aec0;margin-bottom:.6rem;">
            <i class="fas fa-sliders-h me-1"></i> Configuración de cálculos
        </p>
        <div style="background:#f7f8fc;border:1px solid #e8eaf0;border-radius:10px;padding:12px 14px;display:grid;gap:8px;">

            {{-- aplica_calc --}}
            <label class="esp-toggle-row" title="Activa la distribución por porcentaje entre clínica y especialista al calcular una venta.">
                <div class="esp-toggle-info">
                    <span class="esp-toggle-title">Aplica cálculo por porcentaje</span>
                    <span class="esp-toggle-desc">La clínica recibe el % configurado por servicio; el especialista recibe el resto.</span>
                </div>
                <div class="esp-toggle-wrap">
                    <input type="hidden" name="aplica_calc" value="0">
                    <input type="checkbox" name="aplica_calc" value="1" class="esp-toggle-chk"
                        {{ ($item->aplica_calc ?? true) ? 'checked' : '' }}>
                    <span class="esp-toggle-slider"></span>
                </div>
            </label>

            {{-- aplica_porc_tarjeta --}}
            <label class="esp-toggle-row" title="Al pagar con tarjeta se multiplica el monto por 1.13 (suma el 13% de comisión) antes de distribuir.">
                <div class="esp-toggle-info">
                    <span class="esp-toggle-title">Aplica recargo de tarjeta <span class="esp-badge badge-orange">×1.13</span></span>
                    <span class="esp-toggle-desc">Suma el 13% al monto cuando el tipo de pago es "Tarjeta" antes de calcular.</span>
                </div>
                <div class="esp-toggle-wrap">
                    <input type="hidden" name="aplica_porc_tarjeta" value="0">
                    <input type="checkbox" name="aplica_porc_tarjeta" value="1" class="esp-toggle-chk"
                        {{ ($item->aplica_porc_tarjeta ?? false) ? 'checked' : '' }}>
                    <span class="esp-toggle-slider"></span>
                </div>
            </label>

            {{-- aplica_porc_113 --}}
            <label class="esp-toggle-row" title="Al pagar con tarjeta se divide el monto entre 1.13 (extrae el IVA) antes de distribuir.">
                <div class="esp-toggle-info">
                    <span class="esp-toggle-title">Extraer IVA en tarjeta <span class="esp-badge badge-blue">÷1.13</span></span>
                    <span class="esp-toggle-desc">Divide el monto entre 1.13 para quitar el IVA incluido en pagos con tarjeta.</span>
                </div>
                <div class="esp-toggle-wrap">
                    <input type="hidden" name="aplica_porc_113" value="0">
                    <input type="checkbox" name="aplica_porc_113" value="1" class="esp-toggle-chk"
                        {{ ($item->aplica_porc_113 ?? false) ? 'checked' : '' }}>
                    <span class="esp-toggle-slider"></span>
                </div>
            </label>

            {{-- aplica_porc_prod --}}
            <label class="esp-toggle-row" title="Si hay monto de producto, aplica una comisión del 10% al especialista sobre el monto del producto (ya sin IVA).">
                <div class="esp-toggle-info">
                    <span class="esp-toggle-title">Comisión del 10% sobre producto <span class="esp-badge badge-green">10%</span></span>
                    <span class="esp-toggle-desc">El especialista recibe el 10% del monto de producto (dividido entre 1.13). La clínica recibe el 90%.</span>
                </div>
                <div class="esp-toggle-wrap">
                    <input type="hidden" name="aplica_porc_prod" value="0">
                    <input type="checkbox" name="aplica_porc_prod" value="1" class="esp-toggle-chk"
                        {{ ($item->aplica_porc_prod ?? false) ? 'checked' : '' }}>
                    <span class="esp-toggle-slider"></span>
                </div>
            </label>

            {{-- set_campo_esp --}}
            <label class="esp-toggle-row" title="El monto calculado como 'total especialista' se escribe directamente en el campo del especialista, sin ajustes adicionales.">
                <div class="esp-toggle-info">
                    <span class="esp-toggle-title">Asignar monto calculado directo al especialista</span>
                    <span class="esp-toggle-desc">El total del especialista se registra exactamente como lo calcula la fórmula, sin redistribuir el producto.</span>
                </div>
                <div class="esp-toggle-wrap">
                    <input type="hidden" name="set_campo_esp" value="0">
                    <input type="checkbox" name="set_campo_esp" value="1" class="esp-toggle-chk"
                        {{ ($item->set_campo_esp ?? false) ? 'checked' : '' }}>
                    <span class="esp-toggle-slider"></span>
                </div>
            </label>

        </div>
        <p style="font-size:.72rem;color:#a0aec0;margin-top:6px;margin-bottom:0;">
            <i class="fas fa-info-circle me-1"></i>
            Pasa el cursor sobre cada opción para ver una explicación detallada.
        </p>
    </div>

    <div class="d-flex justify-content-end pt-1">
        <button type="submit" class="s-btn-primary w-auto">
            {{ ($Modo ?? 'crear') === 'crear' ? 'Agregar especialista' : 'Guardar cambios' }}
        </button>
    </div>
</div>

{{-- Estilos para los toggles (se aplican solo a esta vista) --}}
<style>
.esp-toggle-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 7px 4px;
    border-radius: 6px;
    cursor: pointer;
    transition: background .12s;
    border-bottom: 1px solid #eef0f5;
}
.esp-toggle-row:last-child { border-bottom: none; }
.esp-toggle-row:hover { background: #eef1fd; }
.esp-toggle-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex: 1;
}
.esp-toggle-title {
    font-size: .82rem;
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.esp-toggle-desc {
    font-size: .72rem;
    color: #718096;
    line-height: 1.35;
}
.esp-badge {
    font-size: .65rem;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 20px;
    letter-spacing: .03em;
}
.badge-orange { background: #fff3cd; color: #856404; }
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-green  { background: #d1fae5; color: #065f46; }

/* Toggle switch */
.esp-toggle-wrap {
    position: relative;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.esp-toggle-chk { display: none; }
.esp-toggle-slider {
    display: inline-block;
    width: 36px;
    height: 20px;
    background: #cbd5e0;
    border-radius: 20px;
    cursor: pointer;
    transition: background .2s;
    position: relative;
}
.esp-toggle-slider::after {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 14px; height: 14px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.15);
}
.esp-toggle-chk:checked + .esp-toggle-slider { background: #5e72e4; }
.esp-toggle-chk:checked + .esp-toggle-slider::after { transform: translateX(16px); }
</style>
