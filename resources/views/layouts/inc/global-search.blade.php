{{-- Global search palette (Cmd/Ctrl + K) --}}
<div class="gs-backdrop" id="gs-backdrop"></div>
<div class="gs-palette" id="gs-palette" role="dialog" aria-label="Búsqueda global" style="display:none;">
    <div class="gs-input-wrap">
        <span class="material-icons gs-search-icon">search</span>
        <input type="text" id="gs-input" class="gs-input"
               placeholder="Buscar productos, pedidos, categorías…"
               autocomplete="off" spellcheck="false">
        <kbd class="gs-esc-hint">Esc</kbd>
    </div>
    <div id="gs-results" class="gs-results">
        <div class="gs-hint">Escribe al menos 2 caracteres…</div>
    </div>
</div>
