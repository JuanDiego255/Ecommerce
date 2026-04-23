@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Tokens de app móvil</li>
@endsection
@section('content')

{{-- One-time token reveal --}}
@if (session('generated_token'))
<div class="alert alert-success border-0 mb-4" role="alert">
    <h6 class="fw-bold mb-1">
        <i class="fas fa-key me-1"></i> Token generado para "{{ session('generated_name') }}"
    </h6>
    <p class="mb-2 small text-muted">
        Copiá este token <strong>ahora</strong>. No se volverá a mostrar.
    </p>
    <div class="d-flex align-items-center gap-2">
        <code id="generated-token-value"
              class="p-2 rounded"
              style="background:#1a1a2e;color:#00d4aa;letter-spacing:.05em;word-break:break-all;flex:1">
            {{ session('generated_token') }}
        </code>
        <button onclick="copyToken()" class="btn btn-sm btn-outline-success" title="Copiar">
            <i class="fas fa-copy"></i>
        </button>
    </div>
</div>
@endif

@if (session('status'))
<div class="alert alert-info border-0 mb-4">{{ session('status') }}</div>
@endif

<div class="page-header d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="page-header-title mb-0">Tokens de app móvil</h4>
        <div class="page-header-sub">
            Cada token es un secreto pre-compartido que la app envía en el header
            <code>X-App-Token</code> para autenticarse con la API.
        </div>
    </div>
    <button type="button" class="ph-btn ph-btn-add" data-bs-toggle="modal" data-bs-target="#new-token-modal">
        <i class="fas fa-plus"></i>
    </button>
</div>

<div class="surface p-4">
    @if ($tokens->isEmpty())
        <p class="text-muted small">No hay tokens creados aún.</p>
    @else
    <div class="table-responsive">
        <table class="table align-items-center mb-0 thead-lite">
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Último uso</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tokens as $token)
                <tr>
                    <td class="align-middle">
                        {{-- Toggle active --}}
                        <form method="POST"
                              action="{{ route('mobile-tokens.toggle', $token->id) }}"
                              class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-link p-1"
                                    title="{{ $token->is_active ? 'Desactivar' : 'Activar' }}">
                                <i class="material-icons" style="font-size:1.1rem;color:{{ $token->is_active ? '#28a745' : '#adb5bd' }}">
                                    {{ $token->is_active ? 'toggle_on' : 'toggle_off' }}
                                </i>
                            </button>
                        </form>
                        {{-- Delete --}}
                        <form method="POST"
                              action="{{ route('mobile-tokens.destroy', $token->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar este token? La app que lo usa perderá acceso de inmediato.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger p-1" title="Eliminar">
                                <i class="material-icons" style="font-size:1.1rem">delete</i>
                            </button>
                        </form>
                    </td>
                    <td class="align-middle fw-600">{{ $token->name }}</td>
                    <td class="align-middle">
                        @if ($token->is_active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="align-middle" style="font-size:.82rem;color:var(--gray4)">
                        {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : '—' }}
                    </td>
                    <td class="align-middle" style="font-size:.82rem;color:var(--gray4)">
                        {{ $token->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- New token modal --}}
<div class="modal fade" id="new-token-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('mobile-tokens.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo token de app móvil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nombre descriptivo</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Ej. App Android — Juan"
                           required maxlength="100">
                    <div class="form-text mt-2">
                        Después de crear el token se mostrará <strong>una sola vez</strong>.
                        Copialo inmediatamente y pegalo en el login de la app.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Generar token</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
function copyToken() {
    const val = document.getElementById('generated-token-value').textContent.trim();
    navigator.clipboard.writeText(val).then(() => {
        alert('Token copiado al portapapeles.');
    });
}
</script>
@endsection
