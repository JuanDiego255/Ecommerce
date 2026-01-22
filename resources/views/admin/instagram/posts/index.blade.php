@extends('layouts.admin') {{-- ajusta a tu layout real --}}

@section('content')

    <div class="container-fluid">

        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Instagram - Publicaciones</h4>

            <button class="btn btn-accion" data-bs-toggle="modal" data-bs-target="#modalAddPost">
                Nueva publicación
            </button>
        </div>

        {{-- Estado de cuenta --}}
        <div class="card mb-3">
            <div class="card-body">
                @if ($account)
                    <div><strong>Cuenta conectada:</strong> {{ $account->instagram_username ?? 'N/A' }}</div>
                    <div><strong>Tipo:</strong> {{ $account->account_type ?? 'N/D' }}</div>
                @else
                    <div class="text-danger"><strong>No hay cuenta de Instagram conectada.</strong></div>
                    <div>Conecta una cuenta para poder publicar.</div>
                @endif
            </div>
        </div>

        {{-- Filtros --}}
        <form class="row g-2 mb-3" method="GET" action="{{ route('instagram.posts.index') }}">
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Estado (todos) --</option>
                    @foreach (['draft', 'scheduled', 'publishing', 'published', 'failed', 'cancelled'] as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-control">
                    <option value="">-- Tipo (todos) --</option>
                    <option value="feed" {{ $type == 'feed' ? 'selected' : '' }}>feed</option>
                    <option value="story" {{ $type == 'story' ? 'selected' : '' }}>story</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary">Filtrar</button>
            </div>
        </form>

        {{-- Tabla --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Programado</th>
                            <th>Publicado</th>
                            <th>Imágenes</th>
                            <th>Caption</th>
                            <th style="width: 240px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->type }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $post->status }}</span>
                                </td>
                                <td>{{ optional($post->scheduled_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ optional($post->published_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @foreach ($post->media as $m)
                                            <img src="{{ $m->media_url }}"
                                                style="height:40px;width:40px;object-fit:cover;border-radius:4px;">
                                        @endforeach
                                    </div>
                                </td>
                                <td style="max-width: 280px;">
                                    <div style="max-height: 60px; overflow:auto;">
                                        {{ $post->caption }}
                                    </div>
                                    @if ($post->status === 'failed' && $post->error_message)
                                        <div class="text-danger small mt-1">{{ $post->error_message }}</div>
                                    @endif
                                </td>
                                <td>
                                    {{-- Publicar ahora --}}
                                    <form method="POST" action="{{ route('instagram.posts.publishNow', $post->id) }}"
                                        style="display:inline-block;">
                                        @csrf
                                        <button class="btn btn-success btn-sm"
                                            {{ $account ? '' : 'disabled' }}>Publicar</button>
                                    </form>

                                    {{-- Editar (simple inline) --}}
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditPost{{ $post->id }}">
                                        Editar
                                    </button>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('instagram.posts.destroy', $post->id) }}"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Eliminar publicación?')">Eliminar</button>
                                    </form>

                                    @include('admin.instagram.posts.edit', ['post' => $post])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Sin publicaciones</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $posts->links() }}
            </div>
        </div>

    </div>

    @include('admin.instagram.posts.add')

@endsection
