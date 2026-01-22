@php
    $selectedAccountId = $account->id ?? null;
@endphp

<div class="row">
    <div class="col-md-12 mb-2">
        <label>Cuenta Instagram</label>
        <input type="hidden" name="instagram_account_id" value="{{ $selectedAccountId }}">
        <input type="text" class="form-control" value="{{ $account->instagram_username ?? 'No conectada' }}" disabled>
    </div>

    <div class="col-md-6">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-control form-control-lg">
                <option value="feed">Feed</option>
                <option value="story" disabled>Stories (Fase 2)</option>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Programar (Opcional)</label>
            <input type="datetime-local" name="scheduled_at" class="form-control">
        </div>
    </div>

    <div class="col-md-12 mb-2">
        <div class="input-group input-group-lg input-group-outline is-filled my-3">
            <label class="form-label">Producto</label>
            <select name="clothing_id" class="form-control form-control-lg">
                <option value="">-- Seleccionar --</option>
                @foreach ($clothings as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->name }} ({{ $c->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <small class="text-muted">Si seleccionas un producto, puedes auto cargar sus fotos.</small>
    </div>

    <div class="col-md-4 mb-2">
        <label>
            <input type="checkbox" name="auto_fill_images" value="1" checked>
            Auto llenar imágenes desde el producto
        </label>
    </div>

    <div class="col-md-12 mb-2">
        <div class="input-group input-group-static">
            <label>Descripción (opcional)</label>
            <textarea name="caption" rows="1" class="form-control" placeholder="Escribe una descripción..."></textarea>
        </div>
    </div>
</div>
