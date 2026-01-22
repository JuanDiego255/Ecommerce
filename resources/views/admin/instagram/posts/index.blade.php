@extends('layouts.admin') {{-- ajusta a tu layout real --}}

@section('content')
    <div class="container-fluid">

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <center>
            <h2 class="text-center font-title"><strong>{{ __('Gestiona publicaciones en Instagram') }}</strong></h2>
        </center>

       <div class="d-flex gap-2">
            <button class="btn btn-accion" data-bs-toggle="modal" data-bs-target="#modalAddPost">
                Nueva publicación
            </button>
        </div>

        {{-- Estado de cuenta --}}
        <div class="card mt-3">
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
        <div class="card mt-3">
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Filtrar</label>
                            <input value="" placeholder="Escribe para filtrar...." type="text"
                                class="form-control form-control-lg" name="searchfor" id="searchfor">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-static my-3 w-100">
                            <label>Mostrar</label>
                            <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                                autocomplete="recordsPerPage">
                                <option value="5">5 Registros</option>
                                <option value="10">10 Registros</option>
                                <option selected value="15">15 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card mt-3">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm" id="table">
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
                        @foreach ($posts as $post)
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
                                        <button class="btn btn-accion btn-sm"
                                            {{ $account ? '' : 'disabled' }}>Publicar</button>
                                    </form>

                                    {{-- Editar (simple inline) --}}
                                    <button class="btn btn-accion btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditPost{{ $post->id }}">
                                        Editar
                                    </button>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('instagram.posts.destroy', $post->id) }}"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-accion btn-sm"
                                            onclick="return confirm('¿Eliminar publicación?')">Eliminar</button>
                                    </form>

                                    @include('admin.instagram.posts.edit', ['post' => $post])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.instagram.posts.add')
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
