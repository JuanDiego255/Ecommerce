{{-- resources/views/admin/barberos/partials/_trabajos.blade.php --}}

<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif
        <div>
            <h4 class="mb-1 fw-bold">Galería de trabajos de {{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">💰 Salario base:
                    <strong>₡{{ number_format((int) $barbero->salario_base, 0, ',', '.') }}</strong>
                </span>
                <span class="chip">✂️ Por servicio:
                    <strong>₡{{ number_format((int) $barbero->monto_por_servicio, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        @can('barberos.manage')
            <a href="{{ url('barberos') }}" class="icon-btn" data-bs-toggle="tooltip" title="Volver a barberos">
                <i class="material-icons">arrow_back</i>
            </a>
            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-barbero-modal"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar barbero">
                <i class="material-icons">edit</i>
            </button>
        @endcan

    </div>
</div>

<div class="surface mb-4">
    <div class="surface-title">Subir nuevas fotos</div>
    <form action="{{ route('barberos.trabajos.store', $barbero) }}" method="post" enctype="multipart/form-data"
        class="row g-3">
        @csrf
        <div class="col-md-8">
            <input type="file" name="photos[]" accept="image/*" class="form-control" multiple required>
            <small class="text-muted">Puedes seleccionar varias imágenes</small>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-velvet px-4">
                <i class="material-icons align-middle">upload</i> Subir
            </button>
        </div>
    </form>
</div>

<div class="surface">
    <div class="surface-title">Galería</div>
    @if ($fotos->isEmpty())
        <div class="text-center text-muted py-4">Aún no hay trabajos subidos</div>
    @else
        <div class="row g-3">
            @foreach ($fotos as $photo)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="position-relative border rounded-3 overflow-hidden">
                        <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                            class="w-100" style="aspect-ratio: 1/1; object-fit: cover;">
                        @if ($photo->is_featured)
                            <span class="badge bg-velvet position-absolute m-2">Destacado</span>
                        @endif
                        <div class="p-2 d-flex align-items-center justify-content-between">
                            <form method="post" action="{{ route('barberos.trabajos.feature', [$barbero, $photo]) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                    title="Destacar">⭐</button>
                            </form>
                            <form method="post" action="{{ route('barberos.trabajos.destroy', [$barbero, $photo]) }}"
                                onsubmit="return confirm('¿Eliminar esta foto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-admin-delete" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="material-icons" style="font-size:18px">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
