<div class="modal fade" id="add-products-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">

            {{-- Header --}}
            <div class="modal-header px-4 py-3" style="border-bottom:1px solid #f0f0f0;background:#fff;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:#f5f5f7;display:flex;align-items:center;justify-content:center;">
                        <i class="material-icons" style="font-size:1.1rem;color:#1d1d1f;">inventory_2</i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold" style="color:#1d1d1f;font-size:.95rem;">Catálogo de productos</h6>
                        <p class="mb-0" style="font-size:.75rem;color:#86868b;">Toca un producto para seleccionarlo</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            {{-- Search --}}
            <div class="px-4 py-3" style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                <div class="position-relative">
                    <i class="material-icons position-absolute" style="left:12px;top:50%;transform:translateY(-50%);font-size:1.1rem;color:#86868b;pointer-events:none;">search</i>
                    <input id="icon-search" oninput="filterIcons()" type="text"
                        placeholder="Buscar por nombre o código..."
                        autocomplete="off"
                        style="width:100%;padding:10px 16px 10px 40px;border:1.5px solid #e5e5ea;border-radius:10px;font-size:.875rem;background:#fff;outline:none;transition:border-color .2s;"
                        onfocus="this.style.borderColor='#007aff'" onblur="this.style.borderColor='#e5e5ea'">
                </div>
                <p class="mb-0 mt-2" style="font-size:.72rem;color:#86868b;">
                    <span id="product-count">{{ count($clothings) }}</span> productos disponibles
                </p>
            </div>

            {{-- Grid de productos --}}
            <div class="modal-body p-4" style="background:#fafafa;">
                <div id="icon-list" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;">
                    @foreach ($clothings as $item)
                        <div onclick="selectIcon('{{ $item->code }}')"
                             class="icon-item product-card-modal"
                             data-code="{{ $item->code }}"
                             data-name="{{ $item->name }}"
                             style="background:#fff;border:1.5px solid #e5e5ea;border-radius:12px;padding:12px;cursor:pointer;transition:all .18s;text-align:center;user-select:none;">
                            <img src="{{ isset($item->image) && $item->image ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                 alt="{{ $item->name }}"
                                 style="width:72px;height:72px;object-fit:cover;border-radius:8px;margin-bottom:8px;background:#f5f5f7;">
                            <p class="mb-0 fw-semibold" style="font-size:.78rem;color:#1d1d1f;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $item->name }}</p>
                            <p class="mb-0 mt-1" style="font-size:.7rem;color:#86868b;font-family:monospace;">{{ $item->code }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Estado vacío --}}
                <div id="empty-search" class="text-center py-5 d-none">
                    <i class="material-icons" style="font-size:2.5rem;color:#c7c7cc;">search_off</i>
                    <p class="mt-2 mb-0" style="color:#86868b;font-size:.875rem;">Sin resultados para tu búsqueda</p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.product-card-modal:hover {
    border-color: #007aff !important;
    box-shadow: 0 4px 16px rgba(0,122,255,.12);
    transform: translateY(-2px);
}
.product-card-modal:active {
    transform: translateY(0);
    box-shadow: none;
}
</style>
