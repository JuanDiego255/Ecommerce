<div class="row">
    {{-- Identidad --}}
    <div class="col-md-12 mb-1">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($barbero->nombre) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Nombre</label>
            <input value="{{ old('nombre', $barbero->nombre ?? '') }}" required type="text"
                class="form-control form-control-lg @error('nombre') is-invalid @enderror" name="nombre" id="nombre"
                maxlength="120" autocomplete="name">
            @error('nombre')
                <span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($barbero->telefono) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Teléfono</label>
            <input value="{{ old('telefono', $barbero->telefono ?? '') }}" type="text"
                class="form-control form-control-lg @error('telefono') is-invalid @enderror" name="telefono"
                id="telefono" autocomplete="tel">
            @error('telefono')
                <span class="invalid-feedback" role="alert"><strong>Campo inválido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($barbero->email) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Email</label>
            <input value="{{ old('email', $barbero->email ?? '') }}" type="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email"
                autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>Correo inválido</strong></span>
            @enderror
        </div>
    </div>

    {{-- Economía --}}
    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($barbero->salario_base) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Salario base (₡)</label>
            <input value="{{ old('salario_base', $barbero->salario_base ?? '') }}" type="number"
                class="form-control form-control-lg @error('salario_base') is-invalid @enderror" name="salario_base"
                id="salario_base" min="0" step="1" inputmode="numeric" pattern="[0-9]*">
            @error('salario_base')
                <span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline {{ isset($barbero->monto_por_servicio) ? 'is-filled' : '' }} my-3">
            <label class="form-label">Monto por servicio (₡)</label>
            <input value="{{ old('monto_por_servicio', $barbero->monto_por_servicio ?? '') }}" type="number"
                class="form-control form-control-lg @error('monto_por_servicio') is-invalid @enderror"
                name="monto_por_servicio" id="monto_por_servicio" min="0" step="1" inputmode="numeric"
                pattern="[0-9]*">
            @error('monto_por_servicio')
                <span class="invalid-feedback" role="alert"><strong>Campo Requerido</strong></span>
            @enderror
        </div>
    </div>

    {{-- Agenda --}}
    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Intervalo (min)</label>
            @php
                $slotOld = old('slot_minutes', isset($barbero) ? $barbero->slot_minutes : 30);
            @endphp
            <select name="slot_minutes" class="form-control form-control-lg" required>
                @foreach ([15, 20, 30, 45, 60] as $m)
                    <option value="{{ $m }}" {{ (int) $slotOld == (int) $m ? 'selected' : '' }}>
                        {{ $m }}</option>
                @endforeach
            </select>
            @error('slot_minutes')
                <span class="invalid-feedback" role="alert"><strong>Seleccione un intervalo</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Inicio</label>
            <input
                value="{{ old('work_start', isset($barbero->work_start) ? substr($barbero->work_start, 0, 5) : '09:00') }}"
                required type="time" class="form-control form-control-lg @error('work_start') is-invalid @enderror"
                name="work_start" id="work_start">
            @error('work_start')
                <span class="invalid-feedback" role="alert"><strong>Hora inválida</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Fin</label>
            <input
                value="{{ old('work_end', isset($barbero->work_end) ? substr($barbero->work_end, 0, 5) : '18:00') }}"
                required type="time" class="form-control form-control-lg @error('work_end') is-invalid @enderror"
                name="work_end" id="work_end">
            @error('work_end')
                <span class="invalid-feedback" role="alert"><strong>Hora inválida</strong></span>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div
            class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Estado</label>
            @php
                $activoOld = old('activo', isset($barbero) ? (int) $barbero->activo : 1);
            @endphp
            <select name="activo" id="activo" class="form-control form-control-lg">
                <option value="1" {{ (int) $activoOld == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ (int) $activoOld == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>

        </div>
    </div>

    <div class="col-12">
        <label class="form-label d-block">Días laborables</label>
        @php
            $days = old(
                'work_days',
                isset($barbero) && $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5],
            );
        @endphp
        <div class="d-flex flex-wrap gap-3">
            @php
                $days = old(
                    'work_days',
                    isset($barbero) && $barbero->work_days ? json_decode($barbero->work_days, true) : [1, 2, 3, 4, 5],
                );
                $days = is_array($days) ? $days : [];
            @endphp
            @foreach ([0 => 'Dom', 1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb'] as $idx => $lbl)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="work_days[]" value="{{ $idx }}"
                        id="day{{ $idx }}" {{ in_array($idx, $days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="day{{ $idx }}">{{ $lbl }}</label>
                </div>
            @endforeach
        </div>
        @error('work_days')
            <div class="text-danger small mt-1"><strong>Selecciona al menos un día</strong></div>
        @enderror
    </div>

    <center class="mt-3">
        <input class="btn btn-accion" type="submit"
            value="{{ ($Modo ?? '') === 'crear' ? 'Agregar' : 'Guardar Cambios' }}">
    </center>
</div>
