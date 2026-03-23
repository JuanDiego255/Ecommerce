@extends('layouts.admin')

@section('content')

@if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ── Header ──────────────────────────────────────────────── --}}
<div class="order-header-strip" style="margin-bottom:12px;">
    <div class="card-h-icon" style="flex-shrink:0;">
        <span class="material-icons">photo_library</span>
    </div>
    <div>
        <p class="order-id" style="font-size:.95rem;">Publicaciones de Instagram</p>
        @if($account)
            <span class="order-meta">{{ $account->instagram_username ?? '' }}</span>
        @else
            <span class="order-meta" style="color:var(--red);">Sin cuenta conectada</span>
        @endif
    </div>
    <div class="oh-actions">
        <a href="{{ url('/instagram') }}" class="act-btn ab-neutral" title="Volver al módulo">
            <span class="material-icons" style="font-size:1rem;">arrow_back</span>
        </a>
        <button class="act-btn ab-view" title="Nueva publicación"
            data-bs-toggle="modal" data-bs-target="#modalAddPost">
            <span class="material-icons">add</span>
        </button>
    </div>
</div>

{{-- ── Account status --}}
@if($account)
<div style="margin-bottom:12px;">
    <span class="ig-account-badge">
        <span class="dot"></span>
        {{ $account->instagram_username ?? 'Cuenta conectada' }}
        @if($account->account_type)
            &nbsp;·&nbsp;<span style="opacity:.7;font-weight:400;">{{ $account->account_type }}</span>
        @endif
    </span>
</div>
@endif

{{-- ── Filters ─────────────────────────────────────────────── --}}
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">filter_list</span></div>
        <span class="card-h-title">Filtros</span>
    </div>
    <div class="s-card-body" style="display:grid;grid-template-columns:1fr 180px;gap:12px;">
        <div>
            <label class="filter-label">Buscar</label>
            <input value="" placeholder="Filtrar publicaciones..." type="text"
                class="filter-input" name="searchfor" id="searchfor">
        </div>
        <div>
            <label class="filter-label">Mostrar</label>
            <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                <option value="5">5 registros</option>
                <option value="10">10 registros</option>
                <option selected value="15">15 registros</option>
                <option value="50">50 registros</option>
            </select>
        </div>
    </div>
</div>

{{-- ── Posts table ─────────────────────────────────────────── --}}
<div class="s-card">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">grid_on</span></div>
        <span class="card-h-title">Publicaciones</span>
    </div>
    <div class="table-responsive">
        <table class="orders-table" id="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Programado</th>
                    <th>Publicado</th>
                    <th>Imágenes</th>
                    <th>Caption</th>
                    <th style="width:200px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    @php
                        $stMap = [
                            'draft'     => ['cls' => 'pill-gray',   'label' => 'Borrador'],
                            'scheduled' => ['cls' => 'pill-orange', 'label' => 'Programado'],
                            'published' => ['cls' => 'pill-green',  'label' => 'Publicado'],
                            'failed'    => ['cls' => 'pill-red',    'label' => 'Fallido'],
                        ];
                        $st = $stMap[$post->status] ?? ['cls' => 'pill-gray', 'label' => $post->status];
                    @endphp
                    <tr>
                        <td><span class="cell-mono">{{ $post->id }}</span></td>
                        <td><span class="cell-main">{{ $post->type }}</span></td>
                        <td>
                            <span class="s-pill {{ $st['cls'] }}">{{ $st['label'] }}</span>
                        </td>
                        <td>
                            <span class="cell-sub">
                                {{ optional($post->scheduled_at)->format('d/m/Y H:i') ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-sub">
                                {{ optional($post->published_at)->format('d/m/Y H:i') ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                @foreach ($post->media as $m)
                                    <img src="{{ $m->media_url }}" class="thumb-img">
                                @endforeach
                            </div>
                        </td>
                        <td style="max-width:260px;">
                            <div class="cell-sub" style="max-height:56px;overflow:hidden;text-overflow:ellipsis;">
                                {{ $post->caption }}
                            </div>
                            @if($post->status === 'failed' && $post->error_message)
                                <div style="color:var(--red);font-size:.7rem;margin-top:3px;">
                                    {{ $post->error_message }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="act-group">
                                {{-- Publish now --}}
                                <form method="POST" action="{{ route('instagram.posts.publishNow', $post->id) }}" style="display:contents;">
                                    @csrf
                                    <button type="submit" class="act-btn ab-ok" title="Publicar ahora"
                                        {{ $account ? '' : 'disabled' }}>
                                        <span class="material-icons">send</span>
                                    </button>
                                </form>

                                {{-- Edit --}}
                                <button class="act-btn ab-view" title="Editar"
                                    data-bs-toggle="modal" data-bs-target="#modalEditPost{{ $post->id }}">
                                    <span class="material-icons">edit</span>
                                </button>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('instagram.posts.destroy', $post->id) }}" style="display:contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn ab-del" title="Eliminar"
                                        onclick="return confirm('¿Eliminar publicación?')">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </form>

                                @include('admin.instagram.posts.edit', ['post' => $post])
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.instagram.posts.add')
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
