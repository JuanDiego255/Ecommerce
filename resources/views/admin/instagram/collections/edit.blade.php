@extends('layouts.admin')

@section('content')
    <style>
        /* Base */
        .ig-top-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media(max-width: 992px) {
            .ig-top-grid {
                grid-template-columns: 1fr;
            }
        }

        .ig-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .ig-card-head {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ig-card-head-board {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #fff;
        }

        .ig-card-title {
            font-weight: 800;
            font-size: 14px;
        }

        .ig-card-sub {
            color: #6c757d;
            font-size: 12px;
        }

        /* Steps */
        .ig-steps {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ig-step {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 999px;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .03);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ig-step-dot {
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
        }

        .ig-step-hint {
            font-weight: 600;
            color: #495057;
        }

        /* Board */
        .ig-board-wrap {
            padding: 14px 16px 4px;
        }

        .ig-board {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .ig-col {
            min-width: 320px;
            max-width: 320px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .ig-col-available {
            border: 1px dashed #ced4da;
            background: linear-gradient(#fbfbfc, #fff);
        }

        .ig-col-header {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .ig-col-title {
            font-weight: 900;
            font-size: 14px;
        }

        .ig-col-sub {
            color: #6c757d;
            font-size: 12px;
        }

        .ig-pill {
            background: #f1f3f5;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 12px;
        }

        .ig-pill-dark {
            background: #111;
            color: #fff;
        }

        .ig-progress {
            height: 6px;
            background: #f1f3f5;
            border-radius: 999px;
            overflow: hidden;
        }

        .ig-progress>div {
            height: 6px;
            background: #111;
            width: 0%;
        }

        .ig-col-body {
            padding: 12px;
            min-height: 160px;
            max-height: 48vh;
            overflow: auto;
            background: linear-gradient(#fafafa, #fff);
        }

        .ig-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        /* Item */
        .ig-item-inner {
            border: 1px solid #e9ecef;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .03);
        }

        .ig-item-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 8px 6px;
            gap: 8px;
            background: #fff;
        }

        .ig-handle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: grab;
            user-select: none;
            font-weight: 900;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f1f3f5;
            color: #111;
        }

        .ig-x {
            border: none;
            background: #ffe3e3;
            color: #c92a2a;
            font-weight: 900;
            border-radius: 10px;
            padding: 4px 10px;
            cursor: pointer;
        }

        .ig-item-inner img {
            width: 100%;
            height: 115px;
            object-fit: cover;
            display: block;
        }

        .ig-filename {
            padding: 8px 10px 10px;
            font-size: 11px;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Footer */
        .ig-col-footer {
            padding: 12px;
            border-top: 1px solid #f1f3f5;
            background: #fff;
        }

        .ig-btn-danger {
            border: none;
            background: #ffe3e3;
            color: #c92a2a;
            font-weight: 900;
            border-radius: 12px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 12px;
        }

        .ig-btn-muted {
            border: none;
            background: #f1f3f5;
            color: #495057;
            font-weight: 900;
            border-radius: 14px;
            padding: 10px 12px;
        }

        .ig-hint {
            color: #6c757d;
            font-size: 12px;
        }

        /* Post box */
        .ig-postbox {
            border: 1px solid #f1f3f5;
            border-radius: 14px;
            padding: 10px 10px;
            background: #fff;
        }

        .ig-postbox-title {
            font-weight: 900;
            font-size: 12px;
        }

        .ig-postbox-line {
            color: #495057;
            font-size: 12px;
            margin-top: 6px;
        }

        .ig-postbox-error {
            margin-top: 8px;
            background: #fff5f5;
            border: 1px solid #ffe3e3;
            color: #c92a2a;
            border-radius: 12px;
            padding: 8px;
            font-size: 12px;
        }

        /* Status pills */
        .ig-status {
            font-weight: 900;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid #e9ecef;
            background: #f1f3f5;
            color: #495057;
        }

        .ig-status--scheduled {
            background: #e7f5ff;
            border-color: #d0ebff;
            color: #1971c2;
        }

        .ig-status--publishing {
            background: #fff3bf;
            border-color: #ffec99;
            color: #a07900;
        }

        .ig-status--published {
            background: #d3f9d8;
            border-color: #b2f2bb;
            color: #2b8a3e;
        }

        .ig-status--failed {
            background: #ffe3e3;
            border-color: #ffc9c9;
            color: #c92a2a;
        }

        .ig-savebar {
            padding: 10px 16px 16px;
        }

        .ig-group-title {
            cursor: text;
            padding: 2px 0;
        }

        .ig-group-title-hint {
            font-size: 11px;
            color: #868e96;
            margin-top: 2px;
        }

        .ig-group-title.is-editing {
            outline: 2px solid #111;
            border-radius: 10px;
            padding: 2px 8px;
        }
    </style>
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="text-center font-title"><strong>{{ __('Editar colección') }} {{ $collection->name }}</strong></h2>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url()->current() }}" class="btn btn-accion">Actualizar estados</a>
                <a href="{{ url('/instagram/collections') }}" class="btn btn-accion">Volver</a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        {{-- Steps --}}
        <div class="ig-steps mb-3">
            <div class="ig-step">Paso 1 - Subir fotos</div>
            <div class="ig-step">Paso 2 - Crear carruseles</div>
            <div class="ig-step">Paso 3 - Arrastrar fotos</div>
            <div class="ig-step">Paso 4 - Publicar / Programar</div>
            <div class="ig-step ig-step-hint">Tip: {{ __('máximo') }} <strong>10</strong> {{ __('imágenes') }} por carrusel
            </div>
        </div>

        {{-- FORM UPDATE --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ url('/instagram/collections/update/' . $collection->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $collection->name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>Notas</label>
                                <input type="text" name="notes" class="form-control"
                                    value="{{ old('notes', $collection->notes) }}">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>{{ __('Descripción') }} base (opcional)</label>
                                <textarea name="default_caption" class="form-control" rows="1">{{ old('default_caption', $collection->default_caption) }}</textarea>
                            </div>
                            <small class="text-muted">
                                Se {{ __('usará') }} como {{ __('descripción') }} por defecto si un carrusel no define
                                {{ __('descripción') }} propio.
                            </small>
                        </div>
                    </div>

                    <button class="btn btn-accion" type="submit">Guardar</button>
                </form>
            </div>
        </div>

        {{-- UPLOAD IMAGES --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Subir {{ __('Imágenes') }}</h5>
                <form method="POST" action="{{ route('ig.collections.items.upload', $collection) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-2">
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                            <small class="text-muted">
                                Consejo: sube todas las fotos de la nueva {{ __('colección') }} y luego arma carruseles con
                                drag & drop.
                            </small>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button class="btn btn-accion w-100" type="submit">Subir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- KANBAN --}}
        <div class="ig-card mb-4">
            <div class="ig-card-head ig-card-head-board">
                <div>
                    <div class="ig-card-title">Armar carruseles</div>
                    <div class="ig-card-sub">Arrastra las {{ __('imágenes') }} a cada carrusel. Cada carrusel genera 1
                        post.</div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <button class="btn btn-accion" id="btn-create-group" type="button">+ Nuevo carrusel</button>
                </div>
            </div>

            @php
                $unassigned = $collection->items->whereNull('group_id');
            @endphp

            <div class="ig-board-wrap">
                <div class="ig-board">

                    {{-- Columna disponibles --}}
                    <div class="ig-col ig-col-available">
                        <div class="ig-col-header">
                            <div>
                                <div class="ig-col-title">Disponibles</div>
                                <div class="ig-col-sub">Sin asignar</div>
                            </div>
                            <span class="ig-pill" id="count-unassigned">{{ $unassigned->count() }}</span>
                        </div>

                        <div class="ig-col-body ig-list" data-group-id="">
                            @foreach ($unassigned as $item)
                                <div class="ig-item" data-id="{{ $item->id }}">
                                    <div class="ig-item-inner">
                                        <div class="ig-item-top">
                                            <span class="handle ig-handle">↕ Arrastrar</span>

                                            <form method="POST"
                                                action="{{ route('ig.collections.items.delete', [$collection, $item]) }}"
                                                onsubmit="return confirm('¿Eliminar esta imagen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ig-x">✕</button>
                                            </form>
                                        </div>

                                        <img src="{{ route('file', $item->image_path) }}" alt="img">
                                        <div class="ig-filename" title="{{ $item->original_name }}">
                                            {{ $item->original_name }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Carruseles --}}
                    @foreach ($collection->groups as $group)
                        @php
                            $post = $group->post;
                            $status = $post->status ?? null;

                            $badgeClass = 'ig-status ig-status--draft';
                            if ($status === 'scheduled') {
                                $badgeClass = 'ig-status ig-status--scheduled';
                            }
                            if ($status === 'publishing') {
                                $badgeClass = 'ig-status ig-status--publishing';
                            }
                            if ($status === 'published') {
                                $badgeClass = 'ig-status ig-status--published';
                            }
                            if ($status === 'failed') {
                                $badgeClass = 'ig-status ig-status--failed';
                            }

                            $scheduledLocal = null;
                            if ($post && $post->scheduled_at) {
                                $scheduledLocal = \Carbon\Carbon::parse($post->scheduled_at)
                                    ->timezone(config('app.timezone'))
                                    ->format('Y-m-d H:i');
                            }
                            $publishedLocal = null;
                            if ($post && $post->published_at) {
                                $publishedLocal = \Carbon\Carbon::parse($post->published_at)
                                    ->timezone(config('app.timezone'))
                                    ->format('Y-m-d H:i');
                            }

                            $itemsCount = $group->items->count();
                            $isLocked = !empty($group->instagram_post_id);
                            $pct = min(100, (int) round(($itemsCount / 10) * 100));
                        @endphp

                        <div class="ig-col">
                            <div class="ig-col-header">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="ig-col-title ig-group-title" data-group-id="{{ $group->id }}"
                                                data-locked="{{ $isLocked ? 1 : 0 }}" title="Click para renombrar">
                                                {{ $group->name }}
                                            </div>
                                            <div class="ig-group-title-hint">Click para editar</div>

                                            <div class="ig-col-sub">
                                                <span class="ig-pill ig-count"
                                                    data-count-for="{{ $group->id }}">{{ $itemsCount }}</span>
                                                <span class="text-muted"> / 10</span>
                                                @if ($isLocked)
                                                    <span class="ig-pill ig-pill-dark ms-2">LOCK</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            @if (!$isLocked)
                                                <form method="POST"
                                                    action="{{ route('ig.collections.groups.delete', [$collection, $group]) }}"
                                                    onsubmit="return confirm('¿Eliminar este carrusel? Sus imágenes volverán a Sin asignar.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ig-btn-danger">Eliminar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="ig-progress mt-2">
                                        <div style="width: {{ $pct }}%"></div>
                                    </div>

                                    @if ($group->instagram_post_id && $post)
                                        <div class="ig-postbox mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="ig-postbox-title">Post #{{ $post->id }}</div>
                                                <span class="{{ $badgeClass }}">{{ strtoupper($post->status) }}</span>
                                            </div>

                                            @if ($scheduledLocal)
                                                <div class="ig-postbox-line">Programado:
                                                    <strong>{{ $scheduledLocal }}</strong> ({{ config('app.timezone') }})
                                                </div>
                                            @endif

                                            @if ($publishedLocal)
                                                <div class="ig-postbox-line">Publicado:
                                                    <strong>{{ $publishedLocal }}</strong> ({{ config('app.timezone') }})
                                                </div>
                                            @endif

                                            @if (($post->status ?? null) === 'failed' && !empty($post->error_message))
                                                <div class="ig-postbox-error">
                                                    <strong>Error:</strong> {{ $post->error_message }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="ig-col-body ig-list" data-group-id="{{ $group->id }}"
                                data-locked="{{ $isLocked ? 1 : 0 }}">
                                @foreach ($group->items as $item)
                                    <div class="ig-item" data-id="{{ $item->id }}">
                                        <div class="ig-item-inner">
                                            <div class="ig-item-top">
                                                <span class="handle ig-handle">↕ Arrastrar</span>
                                                <form method="POST"
                                                    action="{{ route('ig.collections.items.delete', [$collection, $item]) }}"
                                                    onsubmit="return confirm('¿Eliminar esta imagen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ig-x">✕</button>
                                                </form>
                                            </div>

                                            <img src="{{ route('file', $item->image_path) }}" alt="img">
                                            <div class="ig-filename" title="{{ $item->original_name }}">
                                                {{ $item->original_name }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="ig-col-footer">
                                @if ($isLocked)
                                    <button class="ig-btn-muted w-100" disabled>Ya generado</button>
                                    <div class="ig-hint mt-2">Este carrusel ya generó un post. Crea uno nuevo si necesitas
                                        otro.</div>
                                @else
                                    <form method="POST"
                                        action="{{ route('ig.collections.groups.generatePost', [$collection, $group]) }}"
                                        class="ig-generate-form" data-group-id="{{ $group->id }}">
                                        @csrf

                                        <label class="form-label mb-1">{{ __('Descripción') }} (opcional)</label>
                                        <textarea name="caption" class="form-control" rows="2"
                                            placeholder="Si lo dejas vacío, se usa la descripción base de la colección."></textarea>

                                        {{-- inputs ocultos para el backend --}}
                                        <input type="hidden" name="publish_mode" value="now">
                                        <input type="hidden" name="scheduled_at" value="">

                                        <div class="d-flex gap-2 mt-2">
                                            <button type="submit" class="btn btn-accion w-100">Publicar ahora</button>

                                            <button type="button" class="btn btn-outline-dark w-100 btn-open-schedule"
                                                data-group-id="{{ $group->id }}"
                                                data-group-name="{{ $group->name }}">Programar…
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="ig-savebar">
                <small class="text-muted" id="save-status"></small>
            </div>
        </div>

    </div>
    {{-- Modal Programar --}}
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:18px;">
                <div class="modal-header">
                    <h5 class="modal-title">Programar {{ __('publicación') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="text-muted mb-2" id="scheduleModalTitle"></div>
                    <div class="col-md-12">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Fecha y hora</label>
                            <input type="datetime-local" class="form-control" id="scheduleDatetime" required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-accion" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-accion" id="btnConfirmSchedule">Programar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        (function() {
            const saveStatus = document.getElementById('save-status');
            const btnCreateGroup = document.getElementById('btn-create-group');

            const routes = {
                createGroup: @json(route('ig.collections.groups.create', $collection)),
                moveItem: @json(route('ig.collections.items.move', $collection)),
            };

            function setStatus(msg) {
                if (!saveStatus) return;
                saveStatus.textContent = msg || '';
            }

            // Toggle datetime-local in each group form
            document.querySelectorAll('.ig-generate-form').forEach(form => {
                const mode = form.querySelector('.publish-mode');
                const dt = form.querySelector('.scheduled-at');
                if (!mode || !dt) return;
                const toggle = () => dt.style.display = (mode.value === 'scheduled') ? 'block' : 'none';
                mode.addEventListener('change', toggle);
                toggle();
            });

            // Create group (AJAX)
            if (btnCreateGroup) {
                btnCreateGroup.addEventListener('click', async () => {
                    try {
                        btnCreateGroup.disabled = true;
                        setStatus('Creando carrusel...');

                        const resp = await fetch(routes.createGroup, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            }
                        });

                        if (!resp.ok) {
                            const txt = await resp.text();
                            throw new Error(txt);
                        }

                        setStatus('Carrusel creado ✅. Recargando...');
                        window.location.reload();
                    } catch (e) {
                        console.error(e);
                        setStatus('No se pudo crear el carrusel ❌');
                        btnCreateGroup.disabled = false;
                    }
                });
            }

            function refreshCounts() {
                const unassignedList = document.querySelector('.ig-list[data-group-id=""]');
                const countUn = document.getElementById('count-unassigned');
                if (unassignedList && countUn) {
                    countUn.textContent = unassignedList.querySelectorAll('.ig-item').length;
                }

                document.querySelectorAll('.ig-list[data-group-id]').forEach(list => {
                    const gid = list.getAttribute('data-group-id');
                    if (!gid) return;
                    const badge = document.querySelector('.ig-count[data-count-for="' + gid + '"]');
                    if (!badge) return;
                    badge.textContent = list.querySelectorAll('.ig-item').length;
                });
            }

            function idsFromList(listEl) {
                return Array.from(listEl.querySelectorAll('.ig-item'))
                    .map(el => parseInt(el.getAttribute('data-id'), 10))
                    .filter(n => Number.isInteger(n) && n > 0);
            }

            function parseNullableInt(v) {
                if (v === null || v === undefined) return null;
                v = String(v).trim();
                if (v === '') return null;
                const n = parseInt(v, 10);
                return Number.isNaN(n) ? null : n;
            }

            const lists = document.querySelectorAll('.ig-list');
            lists.forEach(listEl => {

                new Sortable(listEl, {
                    group: 'ig-items',
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'ig-ghost',
                    chosenClass: 'ig-chosen',

                    onMove: function(evt) {
                        const to = evt.to;
                        const locked = to.getAttribute('data-locked');
                        if (locked === '1') return false;
                        return true;
                    },

                    onAdd: async function(evt) {
                        const to = evt.to;
                        const from = evt.from;
                        const itemEl = evt.item;

                        const toGroupId = parseNullableInt(to.getAttribute('data-group-id'));
                        const fromGroupId = parseNullableInt(from.getAttribute('data-group-id'));

                        if (toGroupId) {
                            const count = to.querySelectorAll('.ig-item').length;
                            if (count > 10) {
                                alert('Un carrusel no puede tener más de 10 imágenes.');
                                from.insertBefore(itemEl, from.children[evt.oldIndex] || null);
                                refreshCounts();
                                return;
                            }
                        }

                        const itemId = parseInt(itemEl.getAttribute('data-id'), 10);
                        const toOrder = idsFromList(to);
                        const fromOrder = idsFromList(from);

                        try {
                            setStatus('Guardando cambios...');
                            const resp = await fetch(routes.moveItem, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': @json(csrf_token()),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    item_id: itemId,
                                    to_group_id: toGroupId,
                                    from_group_id: fromGroupId,
                                    to_order: toOrder,
                                    from_order: fromOrder
                                })
                            });

                            if (!resp.ok) {
                                const data = await resp.json().catch(() => null);
                                const msg = (data && data.message) ? data.message :
                                    'Error al mover imagen';
                                throw new Error(msg);
                            }

                            setStatus('Cambios guardados ✅');
                            setTimeout(() => setStatus(''), 1500);
                            refreshCounts();
                        } catch (e) {
                            console.error(e);
                            alert(e.message || 'No se pudo mover la imagen.');
                            from.insertBefore(itemEl, from.children[evt.oldIndex] || null);
                            refreshCounts();
                            setStatus('No se pudo guardar ❌');
                        }
                    },

                    onUpdate: async function(evt) {
                        const list = evt.to;
                        const groupId = parseNullableInt(list.getAttribute('data-group-id'));

                        const locked = list.getAttribute('data-locked');
                        if (locked === '1') {
                            window.location.reload();
                            return;
                        }

                        const itemId = parseInt(evt.item.getAttribute('data-id'), 10);
                        const order = idsFromList(list);

                        try {
                            setStatus('Guardando orden...');
                            const resp = await fetch(routes.moveItem, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': @json(csrf_token()),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    item_id: itemId,
                                    to_group_id: groupId,
                                    from_group_id: groupId,
                                    to_order: order,
                                    from_order: order
                                })
                            });

                            if (!resp.ok) throw new Error('No se pudo guardar el orden.');
                            setStatus('Orden guardado ✅');
                            setTimeout(() => setStatus(''), 1500);
                            refreshCounts();
                        } catch (e) {
                            console.error(e);
                            setStatus('No se pudo guardar el orden ❌');
                        }
                    }
                });
            });
            // ---- Inline rename (Group title) ----
            async function updateGroupName(groupId, newName) {
                const url = @json(url('/instagram/collections/' . $collection->id . '/groups')) + '/' + groupId;

                const resp = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: newName
                    })
                });

                if (!resp.ok) {
                    const data = await resp.json().catch(() => null);
                    throw new Error((data && data.message) ? data.message : 'No se pudo renombrar');
                }
                return resp.json();
            }

            document.querySelectorAll('.ig-group-title').forEach(el => {
                const locked = el.getAttribute('data-locked') === '1';
                if (locked) return;

                el.addEventListener('click', () => {
                    if (el.getAttribute('contenteditable') === 'true') return;

                    el.classList.add('is-editing');
                    el.setAttribute('contenteditable', 'true');
                    el.focus();

                    // selecciona todo
                    document.getSelection().selectAllChildren(el);
                });

                const commit = async () => {
                    if (el.getAttribute('contenteditable') !== 'true') return;

                    el.classList.remove('is-editing');
                    el.setAttribute('contenteditable', 'false');

                    const groupId = el.getAttribute('data-group-id');
                    const newName = (el.textContent || '').trim();

                    if (!newName) {
                        window.location.reload();
                        return;
                    }

                    try {
                        setStatus('Guardando nombre...');
                        await updateGroupName(groupId, newName);
                        setStatus('Nombre guardado ✅');
                        setTimeout(() => setStatus(''), 1200);
                    } catch (e) {
                        alert(e.message || 'No se pudo renombrar');
                        window.location.reload();
                    }
                };

                el.addEventListener('blur', commit);
                el.addEventListener('keydown', (ev) => {
                    if (ev.key === 'Enter') {
                        ev.preventDefault();
                        el.blur();
                    }
                    if (ev.key === 'Escape') {
                        ev.preventDefault();
                        window.location.reload();
                    }
                });
            });


            // ---- Modal Schedule ----
            let scheduleForGroupId = null;
            let scheduleModal = null;

            function getBootstrapModal() {
                // Bootstrap 5: window.bootstrap
                if (window.bootstrap && bootstrap.Modal) {
                    return bootstrap.Modal;
                }
                return null;
            }

            document.querySelectorAll('.btn-open-schedule').forEach(btn => {
                btn.addEventListener('click', () => {
                    scheduleForGroupId = btn.getAttribute('data-group-id');
                    const name = btn.getAttribute('data-group-name') || 'Carrusel';

                    document.getElementById('scheduleModalTitle').textContent = `Carrusel: ${name}`;
                    document.getElementById('scheduleDatetime').value = '';

                    const ModalClass = getBootstrapModal();
                    if (!ModalClass) {
                        alert('Bootstrap modal no disponible. (Asegura Bootstrap 5 en layouts.admin)');
                        return;
                    }
                    scheduleModal = new ModalClass(document.getElementById('scheduleModal'));
                    scheduleModal.show();
                });
            });

            document.getElementById('btnConfirmSchedule')?.addEventListener('click', () => {
                const dt = document.getElementById('scheduleDatetime').value;
                if (!dt) {
                    alert('Selecciona fecha y hora.');
                    return;
                }

                const form = document.querySelector(`.ig-generate-form[data-group-id="${scheduleForGroupId}"]`);
                if (!form) return;

                // setea hidden inputs
                form.querySelector('input[name="publish_mode"]').value = 'scheduled';
                form.querySelector('input[name="scheduled_at"]').value = dt;

                if (scheduleModal) scheduleModal.hide();

                // submit
                form.submit();
            });

            refreshCounts();
        })();
    </script>
@endsection
