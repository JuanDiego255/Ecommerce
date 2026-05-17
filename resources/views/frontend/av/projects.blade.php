@extends('layouts.frontmainav')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    {{-- ── Breadcrumb ─────────────────────────────────────────────────────────── --}}
    <div class="bradcam_area bradcam_bg_services">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Proyectos Ejecutados</h3>
                        <p><a href="{{ url('/') }}">Inicio</a> &rsaquo; Proyectos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Projects area ──────────────────────────────────────────────────────── --}}
    <div class="case_study_area case_page mt-40 pb-60">
        <div class="container">

            {{-- Search ---------------------------------------------------------------- --}}
            <div class="row justify-content-center mb-40">
                <div class="col-xl-5 col-lg-6 col-md-8 col-12">
                    <div style="position:relative;">
                        <input
                            id="projectSearch"
                            type="text"
                            placeholder="Buscar proyecto..."
                            autocomplete="off"
                            style="width:100%;padding:12px 44px 12px 18px;border:2px solid #ddd;border-radius:4px;
                                   font-size:.95rem;outline:none;transition:border-color .2s;"
                            onfocus="this.style.borderColor='#444'"
                            onblur="this.style.borderColor='#ddd'"
                        >
                        <i class="fa fa-search"
                           style="position:absolute;right:16px;top:50%;transform:translateY(-50%);color:#aaa;pointer-events:none;"></i>
                    </div>
                </div>
            </div>

            {{-- Empty state ----------------------------------------------------------- --}}
            <div id="emptyState" class="row" style="display:none!important;">
                <div class="col-12 text-center py-60">
                    <i class="fa fa-folder-open" style="font-size:3rem;color:#ccc;"></i>
                    <p style="color:#888;margin-top:16px;font-size:1rem;">No se encontraron proyectos.</p>
                </div>
            </div>

            {{-- Cards grid ------------------------------------------------------------ --}}
            <div class="row grid" id="projectsGrid">
                @forelse ($projects as $item)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 project-card mb-30"
                         data-title="{{ strtolower($item->title) }}">
                        <div class="single_case">
                            <div class="case_thumb">
                                <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">
                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                         alt="{{ $item->title }}"
                                         style="width:100%;height:220px;object-fit:cover;" />
                                </a>
                            </div>
                            <div class="case_heading">
                                <span>Finalizado</span>
                                <h3>
                                    <a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-60">
                        <i class="fa fa-folder-open" style="font-size:3rem;color:#ccc;"></i>
                        <p style="color:#888;margin-top:16px;">Aún no hay proyectos publicados.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination ------------------------------------------------------------ --}}
            <div class="row mt-20">
                <div class="col-12">
                    <div id="pagination" class="text-center" style="display:flex;justify-content:center;align-items:center;gap:6px;flex-wrap:wrap;"></div>
                    <p id="paginationInfo" class="text-center mt-10" style="color:#888;font-size:.85rem;"></p>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
(function () {
    var PER_PAGE   = 9;
    var currentPage = 1;
    var allCards    = Array.from(document.querySelectorAll('.project-card'));
    var filtered    = allCards.slice(); // starts as all cards

    // ── Search ─────────────────────────────────────────────────────────────────
    document.getElementById('projectSearch').addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        filtered = q
            ? allCards.filter(function (c) { return c.dataset.title.includes(q); })
            : allCards.slice();
        currentPage = 1;
        render();
    });

    // ── Render ─────────────────────────────────────────────────────────────────
    function render() {
        var totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
        currentPage    = Math.min(currentPage, totalPages);
        var start      = (currentPage - 1) * PER_PAGE;
        var end        = start + PER_PAGE;

        // Show / hide cards
        allCards.forEach(function (c) { c.style.display = 'none'; });
        var pageItems = filtered.slice(start, end);
        pageItems.forEach(function (c) { c.style.display = ''; });

        // Empty state
        document.getElementById('emptyState').style.display =
            filtered.length === 0 ? 'flex' : 'none';

        // Pagination info
        var info = document.getElementById('paginationInfo');
        if (filtered.length > 0) {
            info.textContent = 'Mostrando ' + (start + 1) + '–' + Math.min(end, filtered.length) +
                               ' de ' + filtered.length + ' proyecto' + (filtered.length !== 1 ? 's' : '');
        } else {
            info.textContent = '';
        }

        renderPagination(totalPages);
    }

    // ── Pagination buttons ─────────────────────────────────────────────────────
    function renderPagination(totalPages) {
        var container = document.getElementById('pagination');
        container.innerHTML = '';

        if (totalPages <= 1) return;

        var btnStyle = 'display:inline-flex;align-items:center;justify-content:center;' +
                       'width:36px;height:36px;border-radius:4px;border:1px solid #ddd;' +
                       'background:#fff;cursor:pointer;font-size:.85rem;font-weight:600;' +
                       'transition:background .15s,color .15s;text-decoration:none;color:#444;';
        var activeStyle = 'background:#2c3e50;color:#fff;border-color:#2c3e50;';

        // Prev
        var prev = makeBtn('&lsaquo;', btnStyle + (currentPage === 1 ? 'opacity:.35;cursor:default;' : ''));
        if (currentPage > 1) {
            prev.addEventListener('click', function () { currentPage--; render(); window.scrollTo(0,0); });
        }
        container.appendChild(prev);

        // Pages — show max 7 buttons with ellipsis
        var pages = pageRange(currentPage, totalPages);
        pages.forEach(function (p) {
            if (p === '…') {
                var dots = document.createElement('span');
                dots.innerHTML = '…';
                dots.style.cssText = 'display:inline-flex;align-items:center;padding:0 4px;color:#888;';
                container.appendChild(dots);
                return;
            }
            var btn = makeBtn(p, btnStyle + (p === currentPage ? activeStyle : ''));
            btn.addEventListener('click', (function (pg) {
                return function () { currentPage = pg; render(); window.scrollTo(0,0); };
            })(p));
            container.appendChild(btn);
        });

        // Next
        var next = makeBtn('&rsaquo;', btnStyle + (currentPage === totalPages ? 'opacity:.35;cursor:default;' : ''));
        if (currentPage < totalPages) {
            next.addEventListener('click', function () { currentPage++; render(); window.scrollTo(0,0); });
        }
        container.appendChild(next);
    }

    function makeBtn(html, style) {
        var el = document.createElement('button');
        el.type = 'button';
        el.innerHTML = html;
        el.style.cssText = style;
        el.addEventListener('mouseover', function () {
            if (!this.style.cssText.includes('2c3e50')) this.style.background = '#f5f5f5';
        });
        el.addEventListener('mouseout', function () {
            if (!this.style.cssText.includes('2c3e50')) this.style.background = '#fff';
        });
        return el;
    }

    function pageRange(current, total) {
        if (total <= 7) return Array.from({length: total}, function (_, i) { return i + 1; });
        var pages = [];
        if (current <= 4) {
            pages = [1,2,3,4,5,'…',total];
        } else if (current >= total - 3) {
            pages = [1,'…',total-4,total-3,total-2,total-1,total];
        } else {
            pages = [1,'…',current-1,current,current+1,'…',total];
        }
        return pages;
    }

    // ── Init ───────────────────────────────────────────────────────────────────
    render();
})();
</script>
@endsection
