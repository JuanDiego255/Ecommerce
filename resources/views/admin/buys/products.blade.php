<div class="modal" id="add-products-modal" tabindex="-1" aria-hidden="true">
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
            <div class="modal-body p-4" id="products-modal-body" style="background:#fafafa;">
                <div id="icon-list" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;">
                    @foreach ($clothings as $item)
                        <div onclick="selectIcon('{{ $item->code }}')"
                             class="icon-item product-card-modal"
                             data-code="{{ $item->code }}"
                             data-name="{{ strtolower($item->name) }}"
                             style="background:#fff;border:1.5px solid #e5e5ea;border-radius:12px;padding:12px;cursor:pointer;text-align:center;user-select:none;">
                            <img data-src="{{ isset($item->image) && $item->image ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                 src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                 alt="{{ $item->name }}"
                                 class="product-img-lazy"
                                 style="width:72px;height:72px;object-fit:cover;border-radius:8px;margin-bottom:8px;background:#ebebeb;">
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
/* Modal: instant open, no Bootstrap fade */
#add-products-modal { display: none; }
#add-products-modal.show { display: block; }

/* Cards: hover via border+shadow only — no transform avoids GPU layer creation */
.product-card-modal {
    border: 1.5px solid #e5e5ea;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    text-align: center;
    user-select: none;
    background: #fff;
    transition: border-color .12s, box-shadow .12s;
}
.product-card-modal:hover {
    border-color: #007aff;
    box-shadow: 0 2px 12px rgba(0,122,255,.14);
}
.product-card-modal:active {
    box-shadow: none;
    border-color: #0051c7;
}

/* Placeholder while image loads */
.product-img-lazy { transition: opacity .15s; }
.product-img-lazy.loaded { background: transparent !important; }
</style>

<script>
// ── Lazy load images with IntersectionObserver ────────────
(function () {
    var modalEl   = document.getElementById('add-products-modal');
    var modalBody = document.getElementById('products-modal-body');
    var observer;

    function buildObserver() {
        if (observer) observer.disconnect();
        observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                var img = e.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.addEventListener('load', function () { img.classList.add('loaded'); }, { once: true });
                }
                observer.unobserve(img);
            });
        }, {
            root: modalBody,
            rootMargin: '80px',  // load images 80px before they enter view
            threshold: 0
        });

        modalEl.querySelectorAll('.product-img-lazy[data-src]').forEach(function (img) {
            observer.observe(img);
        });
    }

    modalEl.addEventListener('show.bs.modal', buildObserver);
}());

// ── Debounced filter ──────────────────────────────────────
var _filterTimer = null;
function filterIcons() {
    clearTimeout(_filterTimer);
    _filterTimer = setTimeout(_doFilter, 150);
}
function _doFilter() {
    var q     = document.getElementById('icon-search').value.toLowerCase().trim();
    var items = document.getElementById('icon-list').children;
    var visible = 0;
    // Batch all DOM changes in one rAF to avoid per-item reflow
    var toShow = [], toHide = [];
    for (var i = 0; i < items.length; i++) {
        var el   = items[i];
        var show = !q
            || el.dataset.code.toLowerCase().includes(q)
            || el.dataset.name.includes(q);
        if (show) { toShow.push(el); visible++; }
        else       { toHide.push(el); }
    }
    requestAnimationFrame(function () {
        toShow.forEach(function (el) { el.hidden = false; });
        toHide.forEach(function (el) { el.hidden = true;  });
        document.getElementById('product-count').textContent = visible;
        document.getElementById('empty-search').classList.toggle('d-none', visible > 0);
    });
}
</script>
