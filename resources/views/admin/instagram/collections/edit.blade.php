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

        .ig-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .ig-pen {
            border: none;
            background: #f1f3f5;
            border-radius: 12px;
            padding: 6px 10px;
            cursor: pointer;
            font-weight: 900;
            line-height: 1;
        }

        .ig-pen:hover {
            background: #e9ecef;
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

                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>Plantilla de caption (Spintax)</label>
                                <select name="caption_template_id" class="form-control">
                                    <option value="">— Sin plantilla —</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->id }}"
                                            {{ old('caption_template_id', $collection->caption_template_id) == $tpl->id ? 'selected' : '' }}>
                                            {{ $tpl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted">
                                Selecciona una plantilla para generar captions variados automáticamente.
                                <a href="{{ url('/instagram/caption-templates') }}" target="_blank">Gestionar plantillas</a>
                            </small>
                        </div>

                        @if ($collection->captionTemplate)
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info mb-0" style="font-size: 13px;">
                                    <strong class="text-white">Plantilla activa:</strong> {{ $collection->captionTemplate->name }}<br>
                                    <small class="text-white">Al publicar con "Usar plantilla", se generará un caption único cada vez.</small>
                                </div>
                            </div>
                        @endif
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
                            $statusText = '';

                            $badgeClass = 'ig-status ig-status--draft';
                            if ($status === 'scheduled') {
                                $badgeClass = 'ig-status ig-status--scheduled';
                                $statusText = 'Programado';
                            }
                            if ($status === 'publishing') {
                                $badgeClass = 'ig-status ig-status--publishing';
                                $statusText = 'Publicando...';
                            }
                            if ($status === 'published') {
                                $badgeClass = 'ig-status ig-status--published';
                                $statusText = 'Publicado';
                            }
                            if ($status === 'failed') {
                                $badgeClass = 'ig-status ig-status--failed';
                                $statusText = 'Fallido';
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
                                            <div class="ig-title-row">
                                                <div class="ig-col-title ig-group-title"
                                                    data-group-id="{{ $group->id }}"
                                                    data-locked="{{ $isLocked ? 1 : 0 }}" title="Click para renombrar">
                                                    {{ $group->name }}
                                                </div>

                                                @if (!$isLocked)
                                                    <button type="button" class="ig-pen" title="Renombrar carrusel"
                                                        data-edit-group="{{ $group->id }}">
                                                        ✏️
                                                    </button>
                                                @endif
                                            </div>


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
                                                <span class="{{ $badgeClass }}">{{ strtoupper($statusText) }}</span>
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

                                        @if ($collection->caption_template_id)
                                            {{-- Opción para usar plantilla spintax --}}
                                            <div class="mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" name="use_template" value="1"
                                                        class="form-check-input use-template-check"
                                                        id="useTemplate{{ $group->id }}"
                                                        data-group-id="{{ $group->id }}">
                                                    <label class="form-check-label" for="useTemplate{{ $group->id }}">
                                                        <strong>Usar plantilla:</strong> {{ $collection->captionTemplate->name ?? 'Sin nombre' }}
                                                    </label>
                                                </div>

                                                {{-- Opción para analizar imágenes --}}
                                                <div class="form-check mt-1">
                                                    <input type="checkbox" name="analyze_images" value="1"
                                                        class="form-check-input analyze-images-check"
                                                        id="analyzeImages{{ $group->id }}"
                                                        data-group-id="{{ $group->id }}">
                                                    <label class="form-check-label" for="analyzeImages{{ $group->id }}">
                                                        <strong>Analizar imágenes</strong>
                                                        {{-- <small class="text-muted d-block">Detecta color, tipo de prenda y estampado</small> --}}
                                                    </label>
                                                </div>

                                                <div class="d-flex gap-1 mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-preview-template"
                                                        data-group-id="{{ $group->id }}"
                                                        data-template-id="{{ $collection->caption_template_id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Ver variación de plantilla">
                                                        <i class="fas fa-magic"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-analyze-preview"
                                                        data-group-id="{{ $group->id }}"
                                                        data-collection-id="{{ $collection->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Analizar y generar descripción Instagram">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success btn-analyze-ecommerce"
                                                        data-group-id="{{ $group->id }}"
                                                        data-collection-id="{{ $collection->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Analizar y generar descripción E-commerce">
                                                        <i class="fas fa-store"></i>
                                                    </button>
                                                </div>

                                                <div class="caption-preview mt-2" id="captionPreview{{ $group->id }}" style="display: none;">
                                                    <small class="text-muted">Vista previa:</small>
                                                    <div class="bg-light p-2 rounded mt-1" style="font-size: 12px; white-space: pre-wrap; text-align: justify;"
                                                        id="captionPreviewText{{ $group->id }}"></div>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                        @endif

                                        {{-- Opción para crear en E-commerce --}}
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" name="create_ecommerce" value="1"
                                                    class="form-check-input create-ecommerce-check"
                                                    id="createEcommerce{{ $group->id }}"
                                                    data-group-id="{{ $group->id }}">
                                                <label class="form-check-label" for="createEcommerce{{ $group->id }}">
                                                    <strong>Crear en E-commerce</strong>
                                                </label>
                                            </div>

                                            <div class="ecommerce-fields mt-2" id="ecommerceFields{{ $group->id }}" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">₡</span>
                                                            <input type="number" name="ecommerce_price" class="form-control"
                                                                id="ecommercePrice{{ $group->id }}"
                                                                placeholder="Precio" min="0" step="0.01">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" name="ecommerce_stock" class="form-control form-control-sm"
                                                            id="ecommerceStock{{ $group->id }}"
                                                            placeholder="Stock (opcional)" min="0">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="ecommerce_analysis_data" id="ecommerceAnalysisData{{ $group->id }}" value="">
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle"></i> Debe analizar las imágenes primero
                                                </small>
                                            </div>
                                        </div>
                                        <hr class="my-2">

                                        <label class="form-label mb-1">{{ __('Descripción') }} (opcional)</label>
                                        <textarea name="caption" class="form-control caption-textarea" rows="2"
                                            id="caption{{ $group->id }}"
                                            placeholder="Si lo dejas vacío, se usa la descripción base{{ $collection->caption_template_id ? ' o la plantilla seleccionada' : '' }}."></textarea>

                                        {{-- inputs ocultos para el backend --}}
                                        <input type="hidden" name="publish_mode" value="now">
                                        <input type="hidden" name="scheduled_at" value="">
                                        <input type="hidden" name="generated_caption" id="generatedCaption{{ $group->id }}" value="">
                                        <input type="hidden" name="caption_type" id="captionType{{ $group->id }}" value="">

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

                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-outline-dark w-100" id="btnQuickToday">Hoy 7:00 PM</button>
                        <button type="button" class="btn btn-outline-dark w-100" id="btnQuickTomorrow">Mañana 7:00
                            PM</button>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Fecha y hora</label>
                            <input type="datetime-local" class="form-control" id="scheduleDatetime" required>
                        </div>
                    </div>

                    <div class="mt-2">
                        <small class="text-muted d-block" id="schedulePreview">Se {{ __('publicará') }}: —</small>
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
                // Endpoint REST para renombrar: /instagram/collections/{collection}/groups/{group}
                updateGroupBase: @json(url('/instagram/collections/' . $collection->id . '/groups')),
            };

            function setStatus(msg) {
                if (!saveStatus) return;
                saveStatus.textContent = msg || '';
            }

            // -----------------------------------------
            // Helpers
            // -----------------------------------------
            function parseNullableInt(v) {
                if (v === null || v === undefined) return null;
                v = String(v).trim();
                if (v === '') return null;
                const n = parseInt(v, 10);
                return Number.isNaN(n) ? null : n;
            }

            function idsFromList(listEl) {
                return Array.from(listEl.querySelectorAll('.ig-item'))
                    .map(el => parseInt(el.getAttribute('data-id'), 10))
                    .filter(n => Number.isInteger(n) && n > 0);
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

            function getBootstrapModalClass() {
                return (window.bootstrap && bootstrap.Modal) ? bootstrap.Modal : null;
            }

            function swalWarn(title) {
                if (window.Swal) {
                    Swal.fire({
                        title,
                        icon: "warning"
                    });
                } else {
                    alert(title);
                }
            }

            function pad2(n) {
                return String(n).padStart(2, '0');
            }

            function toDatetimeLocalValue(d) {
                return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) +
                    'T' + pad2(d.getHours()) + ':' + pad2(d.getMinutes());
            }

            function formatPretty(d) {
                const days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
                let h = d.getHours();
                const m = pad2(d.getMinutes());
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12;
                if (h === 0) h = 12;
                return `${days[d.getDay()]} ${h}:${m} ${ampm}`;
            }

            // -----------------------------------------
            // Drag & Drop (Sortable)
            // -----------------------------------------
            document.querySelectorAll('.ig-list').forEach(listEl => {
                new Sortable(listEl, {
                    group: 'ig-items',
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'ig-ghost',
                    chosenClass: 'ig-chosen',

                    onMove: function(evt) {
                        const locked = evt.to.getAttribute('data-locked');
                        if (locked === '1') return false;
                        return true;
                    },

                    onAdd: async function(evt) {
                        const to = evt.to;
                        const from = evt.from;
                        const itemEl = evt.item;

                        const toGroupId = parseNullableInt(to.getAttribute('data-group-id'));
                        const fromGroupId = parseNullableInt(from.getAttribute('data-group-id'));

                        // Límite 10 por carrusel
                        if (toGroupId) {
                            const count = to.querySelectorAll('.ig-item').length;
                            if (count > 10) {
                                swalWarn('Un carrusel no puede tener más de 10 imágenes.');
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
                            setTimeout(() => setStatus(''), 1200);
                            refreshCounts();
                        } catch (e) {
                            console.error(e);
                            swalWarn(e.message || 'No se pudo mover la imagen.');
                            from.insertBefore(itemEl, from.children[evt.oldIndex] || null);
                            refreshCounts();
                            setStatus('No se pudo guardar ❌');
                        }
                    },

                    onUpdate: async function(evt) {
                        const list = evt.to;
                        const locked = list.getAttribute('data-locked');
                        if (locked === '1') {
                            window.location.reload();
                            return;
                        }

                        const groupId = parseNullableInt(list.getAttribute('data-group-id'));
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
                            setTimeout(() => setStatus(''), 1200);
                            refreshCounts();
                        } catch (e) {
                            console.error(e);
                            setStatus('No se pudo guardar el orden ❌');
                        }
                    }
                });
            });

            // -----------------------------------------
            // Inline Rename + ✏️ button
            // -----------------------------------------
            async function updateGroupName(groupId, newName) {
                const url = routes.updateGroupBase + '/' + groupId;

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

            function enableInlineRename(el) {
                const locked = el.getAttribute('data-locked') === '1';
                if (locked) return;

                el.addEventListener('click', () => {
                    if (el.getAttribute('contenteditable') === 'true') return;

                    el.classList.add('is-editing');
                    el.setAttribute('contenteditable', 'true');
                    el.focus();
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
                        swalWarn(e.message || 'No se pudo renombrar');
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
            }

            document.querySelectorAll('.ig-group-title').forEach(enableInlineRename);

            document.querySelectorAll('[data-edit-group]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const gid = btn.getAttribute('data-edit-group');
                    const titleEl = document.querySelector('.ig-group-title[data-group-id="' + gid +
                        '"]');
                    if (titleEl) titleEl.click();
                });
            });

            // -----------------------------------------
            // Create Group (AJAX)
            // -----------------------------------------
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

            // -----------------------------------------
            // Modal Schedule (Premium)
            // -----------------------------------------
            let scheduleForGroupId = null;
            let scheduleModal = null;

            function setSchedulePreviewFromInput() {
                const el = document.getElementById('scheduleDatetime');
                const preview = document.getElementById('schedulePreview');
                if (!el || !preview) return;

                const v = el.value;
                if (!v) {
                    preview.textContent = 'Se publicará: —';
                    return;
                }

                const d = new Date(v);
                if (isNaN(d.getTime())) {
                    preview.textContent = 'Se publicará: —';
                    return;
                }

                preview.textContent = 'Se publicará: ' + formatPretty(d);
            }

            function suggestedToday7pm() {
                const now = new Date();
                let d = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 19, 0, 0, 0);
                if (d.getTime() <= now.getTime()) {
                    d = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 19, 0, 0, 0);
                }
                return d;
            }

            function tomorrow7pm() {
                const now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 19, 0, 0, 0);
            }

            document.getElementById('scheduleDatetime')?.addEventListener('input', setSchedulePreviewFromInput);

            document.getElementById('btnQuickToday')?.addEventListener('click', () => {
                const d = suggestedToday7pm();
                document.getElementById('scheduleDatetime').value = toDatetimeLocalValue(d);
                setSchedulePreviewFromInput();
            });

            document.getElementById('btnQuickTomorrow')?.addEventListener('click', () => {
                const d = tomorrow7pm();
                document.getElementById('scheduleDatetime').value = toDatetimeLocalValue(d);
                setSchedulePreviewFromInput();
            });

            document.querySelectorAll('.btn-open-schedule').forEach(btn => {
                btn.addEventListener('click', () => {
                    scheduleForGroupId = btn.getAttribute('data-group-id');
                    const name = btn.getAttribute('data-group-name') || 'Carrusel';

                    const titleEl = document.getElementById('scheduleModalTitle');
                    if (titleEl) titleEl.textContent = `Carrusel: ${name}`;

                    // Prefill: hoy 7pm (o mañana si ya pasó)
                    const d = suggestedToday7pm();
                    const dtInput = document.getElementById('scheduleDatetime');
                    if (dtInput) dtInput.value = toDatetimeLocalValue(d);
                    setSchedulePreviewFromInput();

                    const ModalClass = getBootstrapModalClass();
                    if (!ModalClass) {
                        swalWarn('Bootstrap modal no disponible.');
                        return;
                    }

                    scheduleModal = new ModalClass(document.getElementById('scheduleModal'));
                    scheduleModal.show();
                });
            });

            // El handler de btnConfirmSchedule está al final con el AJAX submit

            // -----------------------------------------
            // Template Preview (Spintax)
            // -----------------------------------------
            document.querySelectorAll('.btn-preview-template').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const groupId = btn.getAttribute('data-group-id');
                    const templateId = btn.getAttribute('data-template-id');
                    const previewDiv = document.getElementById('captionPreview' + groupId);
                    const previewText = document.getElementById('captionPreviewText' + groupId);
                    const captionTextarea = document.getElementById('caption' + groupId);
                    const useTemplateCheck = document.getElementById('useTemplate' + groupId);

                    if (!templateId) {
                        swalWarn('No hay plantilla configurada para esta colección.');
                        return;
                    }

                    btn.disabled = true;
                    btn.textContent = 'Generando...';

                    try {
                        const resp = await fetch(@json(route('ig.caption-templates.generate')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                template_id: templateId
                            })
                        });

                        const data = await resp.json();

                        if (!data.ok) {
                            throw new Error(data.message || 'Error al generar caption');
                        }

                        previewText.textContent = data.caption;
                        previewDiv.style.display = 'block';

                        // Auto-seleccionar usar plantilla y limpiar textarea
                        if (useTemplateCheck) {
                            useTemplateCheck.checked = true;
                        }
                        if (captionTextarea) {
                            captionTextarea.value = '';
                            captionTextarea.placeholder = 'Usando plantilla. Deja vacío para usar la variación generada.';
                        }

                    } catch (e) {
                        console.error(e);
                        swalWarn(e.message || 'Error al generar la vista previa');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = '✨ Ver variación';
                    }
                });
            });

            // Toggle template usage
            document.querySelectorAll('.use-template-check').forEach(check => {
                check.addEventListener('change', () => {
                    const groupId = check.getAttribute('data-group-id');
                    const captionTextarea = document.getElementById('caption' + groupId);
                    const previewDiv = document.getElementById('captionPreview' + groupId);

                    if (check.checked) {
                        if (captionTextarea) {
                            captionTextarea.placeholder = 'Usando plantilla. Deja vacío para usar la variación generada.';
                        }
                    } else {
                        if (captionTextarea) {
                            captionTextarea.placeholder = 'Si lo dejas vacío, se usa la descripción base de la colección.';
                        }
                        if (previewDiv) {
                            previewDiv.style.display = 'none';
                        }
                    }
                });
            });

            // -----------------------------------------
            // Image Analysis + Caption Generation (Instagram)
            // -----------------------------------------
            document.querySelectorAll('.btn-analyze-preview').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const groupId = btn.getAttribute('data-group-id');
                    const collectionId = btn.getAttribute('data-collection-id');
                    const previewDiv = document.getElementById('captionPreview' + groupId);
                    const previewText = document.getElementById('captionPreviewText' + groupId);
                    const analyzeCheck = document.getElementById('analyzeImages' + groupId);
                    const useTemplateCheck = document.getElementById('useTemplate' + groupId);
                    const captionTextarea = document.getElementById('caption' + groupId);
                    const ecommerceAnalysisData = document.getElementById('ecommerceAnalysisData' + groupId);
                    const generatedCaption = document.getElementById('generatedCaption' + groupId);
                    const captionType = document.getElementById('captionType' + groupId);

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const resp = await fetch(`/instagram/collections/${collectionId}/groups/${groupId}/analyze-images`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await resp.json();

                        if (!data.ok) {
                            throw new Error(data.message || 'Error al analizar imágenes');
                        }

                        // Mostrar el caption generado en Vista previa
                        previewText.textContent = data.caption;
                        previewDiv.style.display = 'block';

                        // Guardar caption generado para usar al publicar
                        if (generatedCaption) {
                            generatedCaption.value = data.caption;
                        }
                        if (captionType) {
                            captionType.value = 'instagram';
                        }

                        // Guardar datos de análisis para E-commerce
                        if (ecommerceAnalysisData && data.analysis_data) {
                            ecommerceAnalysisData.value = JSON.stringify(data.analysis_data);
                        }

                        // Auto-marcar checkboxes
                        if (analyzeCheck) {
                            analyzeCheck.checked = true;
                        }
                        if (useTemplateCheck) {
                            useTemplateCheck.checked = true;
                        }

                        // Limpiar textarea
                        if (captionTextarea) {
                            captionTextarea.value = '';
                            captionTextarea.placeholder = 'Usando plantilla con análisis. Deja vacío para usar la variación generada.';
                        }

                    } catch (e) {
                        console.error(e);
                        swalWarn(e.message || 'Error al analizar las imágenes');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-search"></i>';
                    }
                });
            });

            // -----------------------------------------
            // Image Analysis + E-commerce Description
            // -----------------------------------------
            document.querySelectorAll('.btn-analyze-ecommerce').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const groupId = btn.getAttribute('data-group-id');
                    const collectionId = btn.getAttribute('data-collection-id');
                    const previewDiv = document.getElementById('captionPreview' + groupId);
                    const previewText = document.getElementById('captionPreviewText' + groupId);
                    const ecommerceAnalysisData = document.getElementById('ecommerceAnalysisData' + groupId);
                    const generatedCaption = document.getElementById('generatedCaption' + groupId);
                    const captionType = document.getElementById('captionType' + groupId);

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const resp = await fetch(`/instagram/collections/${collectionId}/groups/${groupId}/analyze-ecommerce`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await resp.json();

                        if (!data.ok) {
                            throw new Error(data.message || 'Error al analizar imágenes');
                        }

                        // Mostrar la descripción E-commerce en Vista previa
                        previewText.textContent = data.description;
                        previewDiv.style.display = 'block';

                        // Guardar caption generado para usar al publicar
                        if (generatedCaption) {
                            generatedCaption.value = data.description;
                        }
                        if (captionType) {
                            captionType.value = 'ecommerce';
                        }

                        // Guardar datos de análisis para E-commerce
                        if (ecommerceAnalysisData && data.analysis_data) {
                            ecommerceAnalysisData.value = JSON.stringify(data.analysis_data);
                        }

                    } catch (e) {
                        console.error(e);
                        swalWarn(e.message || 'Error al analizar las imágenes');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-store"></i>';
                    }
                });
            });

            // -----------------------------------------
            // Toggle E-commerce Fields
            // -----------------------------------------
            document.querySelectorAll('.create-ecommerce-check').forEach(check => {
                check.addEventListener('change', () => {
                    const groupId = check.getAttribute('data-group-id');
                    const fieldsDiv = document.getElementById('ecommerceFields' + groupId);

                    if (check.checked) {
                        fieldsDiv.style.display = 'block';
                    } else {
                        fieldsDiv.style.display = 'none';
                    }
                });
            });

            // -----------------------------------------
            // Initialize Tooltips
            // -----------------------------------------
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // -----------------------------------------
            // AJAX Form Submission for Publishing
            // -----------------------------------------
            function getStatusBadgeClass(status) {
                const classes = {
                    'scheduled': 'ig-status ig-status--scheduled',
                    'publishing': 'ig-status ig-status--publishing',
                    'published': 'ig-status ig-status--published',
                    'failed': 'ig-status ig-status--failed',
                };
                return classes[status] || 'ig-status ig-status--draft';
            }

            function updateCarouselToLocked(groupId, postData) {
                // Find the carousel column
                const colBody = document.querySelector(`.ig-list[data-group-id="${groupId}"]`);
                if (!colBody) return;

                const column = colBody.closest('.ig-col');
                if (!column) return;

                // Mark the list as locked (disable drag)
                colBody.setAttribute('data-locked', '1');

                // Add LOCK pill to header
                const colSub = column.querySelector('.ig-col-sub');
                if (colSub && !colSub.querySelector('.ig-pill-dark')) {
                    colSub.insertAdjacentHTML('beforeend', '<span class="ig-pill ig-pill-dark ms-2">LOCK</span>');
                }

                // Hide the edit button
                const editBtn = column.querySelector(`[data-edit-group="${groupId}"]`);
                if (editBtn) editBtn.style.display = 'none';

                // Update or create the post box
                const colHeader = column.querySelector('.ig-col-header > div');
                let postBox = colHeader.querySelector('.ig-postbox');

                const statusClass = getStatusBadgeClass(postData.status);
                const statusText = postData.status_text || postData.status.toUpperCase();

                let postBoxHtml = `
                    <div class="ig-postbox mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="ig-postbox-title">Post #${postData.id}</div>
                            <span class="${statusClass}">${statusText.toUpperCase()}</span>
                        </div>
                `;

                if (postData.scheduled_at) {
                    postBoxHtml += `
                        <div class="ig-postbox-line">Programado: <strong>${postData.scheduled_at}</strong> ({{ config('app.timezone') }})</div>
                    `;
                }

                if (postData.published_at) {
                    postBoxHtml += `
                        <div class="ig-postbox-line">Publicado: <strong>${postData.published_at}</strong> ({{ config('app.timezone') }})</div>
                    `;
                }

                if (postData.status === 'failed' && postData.error_message) {
                    postBoxHtml += `
                        <div class="ig-postbox-error">
                            <strong>Error:</strong> ${postData.error_message}
                        </div>
                    `;
                }

                postBoxHtml += '</div>';

                if (postBox) {
                    postBox.outerHTML = postBoxHtml;
                } else {
                    colHeader.insertAdjacentHTML('beforeend', postBoxHtml);
                }

                // Replace footer with locked state
                const footer = column.querySelector('.ig-col-footer');
                if (footer) {
                    footer.innerHTML = `
                        <button class="ig-btn-muted w-100" disabled>Ya generado</button>
                        <div class="ig-hint mt-2">Este carrusel ya generó un post. Crea uno nuevo si necesitas otro.</div>
                    `;
                }

                // Disable drag handles in this carousel
                colBody.querySelectorAll('.ig-handle').forEach(handle => {
                    handle.style.opacity = '0.5';
                    handle.style.cursor = 'not-allowed';
                });
            }

            function showSuccessNotification(message) {
                if (window.Swal) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: message,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    // Create a simple toast notification
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success position-fixed';
                    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    toast.innerHTML = `<strong>¡Éxito!</strong> ${message}`;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                }
            }

            function showErrorNotification(message) {
                if (window.Swal) {
                    Swal.fire({
                        title: 'Error',
                        text: message,
                        icon: 'error'
                    });
                } else {
                    alert(message);
                }
            }

            // Intercept form submissions
            document.querySelectorAll('.ig-generate-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const groupId = form.getAttribute('data-group-id');
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;

                    // Disable submit button and show loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publicando...';

                    try {
                        const formData = new FormData(form);

                        const resp = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': @json(csrf_token()),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await resp.json();

                        if (!resp.ok || !data.ok) {
                            throw new Error(data.message || 'Error al publicar');
                        }

                        // Success - update the UI
                        updateCarouselToLocked(groupId, data.post);
                        showSuccessNotification(data.message);

                    } catch (error) {
                        console.error('Error publishing:', error);
                        showErrorNotification(error.message || 'Error al publicar el carrusel');

                        // Re-enable the button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            });

            // Update schedule confirmation to use AJAX
            document.getElementById('btnConfirmSchedule')?.addEventListener('click', async () => {
                const dtInput = document.getElementById('scheduleDatetime');
                const dt = dtInput ? dtInput.value : '';

                if (!dt) {
                    swalWarn('Selecciona fecha y hora.');
                    return;
                }

                // Validation (keep existing)
                const selected = new Date(dt);
                const now = new Date();
                const minFutureMs = 60 * 1000;

                if (isNaN(selected.getTime())) {
                    swalWarn('Fecha/hora inválida.');
                    return;
                }

                if (selected.getTime() < now.getTime() + minFutureMs) {
                    swalWarn('La fecha/hora debe ser al menos 1 minuto en el futuro.');
                    return;
                }

                const form = document.querySelector(`.ig-generate-form[data-group-id="${scheduleForGroupId}"]`);
                if (!form) return;

                // Set form values
                form.querySelector('input[name="publish_mode"]').value = 'scheduled';
                form.querySelector('input[name="scheduled_at"]').value = dt;

                // Close modal
                if (scheduleModal) scheduleModal.hide();

                // Submit the form (will be intercepted by our AJAX handler)
                const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                form.dispatchEvent(submitEvent);
            });

            // Init
            refreshCounts();
        })();
    </script>
@endsection
