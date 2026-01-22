@php
    $selectedAccountId = $account->id ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-2">
        <label>Cuenta Instagram</label>
        <input type="hidden" name="instagram_account_id" value="{{ $selectedAccountId }}">
        <input type="text" class="form-control" value="{{ $account->instagram_username ?? 'No conectada' }}" disabled>
    </div>

    <div class="col-md-3 mb-2">
        <label>Tipo</label>
        <select name="type" class="form-control">
            <option value="feed">Feed</option>
            <option value="story" disabled>Stories (Fase 2)</option>
        </select>
        <small class="text-muted">Stories se habilita solo si es Business + integración fase 2.</small>
    </div>

    <div class="col-md-3 mb-2">
        <label>Programar (opcional)</label>
        <input type="datetime-local" name="scheduled_at" class="form-control">
        <small class="text-muted">Si lo dejas vacío queda como borrador.</small>
    </div>

    <div class="col-md-12 mb-2">
        <label>Producto (opcional)</label>
        <select name="clothing_id" class="form-control">
            <option value="">-- Seleccionar --</option>
            @foreach ($clothings as $c)
                <option value="{{ $c->id }}">
                    {{ $c->name }} ({{ $c->code }})
                </option>
            @endforeach
        </select>
        <small class="text-muted">Si seleccionas un producto, puedes auto cargar sus fotos.</small>
    </div>

    <div class="col-md-4 mb-2">
        <label>
            <input type="checkbox" name="auto_fill_images" value="1" checked>
            Auto llenar imágenes desde el producto
        </label>
        <div class="small text-muted">Toma imágenes de product_images por clothing_id.</div>
    </div>

    <div class="col-md-12 mb-2">
        <label>Caption (opcional)</label>
        <textarea name="caption" rows="4" class="form-control" placeholder="Escribe una descripción..."></textarea>
    </div>

    <div class="col-md-12 mb-2">
        <div class="alert alert-info mb-0">
            Para el MVP, las imágenes se cargan automáticamente desde <strong>product_images</strong> cuando marcas
            “Auto llenar”.
            En el siguiente paso podemos agregar selección manual de fotos si lo necesitas.
        </div>
    </div>
</div>
