{{-- Header info rápida --}}
{{-- HEADER SIMPLE (sin card anidado) --}}
<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    {{-- Izquierda: avatar + datos --}}
    <div class="d-flex align-items-center gap-3">
        {{-- Avatar con inicial (cámbialo por <img> si luego tienes foto) --}}
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif

        <div>
            <h4 class="mb-1 fw-bold">{{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">
                    💰 Servicios Profesionales
                    <strong>Sin monto definido</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- Derecha: acciones --}}
    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        @can('barberos.manage')
            <a href="{{ url('barberos') }}" class="icon-btn" data-bs-toggle="tooltip" title="Volver a barberos">
                <i class="material-icons">arrow_back</i>
            </a>
            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-barbero-modal"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar barbero">
                <i class="material-icons">edit</i>
            </button>

            <form method="post" action="{{ url('/barberos/destroy/' . $barbero->id) }}"
                onsubmit="return confirm('¿Deseas borrar este barbero?')" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Eliminar barbero">
                    <i class="material-icons">delete</i>
                </button>
            </form>
        @endcan

    </div>
</div>
