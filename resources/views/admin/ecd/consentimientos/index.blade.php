@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item active">Consentimientos</li>
@endsection
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Plantillas de consentimiento</h4>
        <a href="{{ route('ecd.consentimientos.create') }}" class="s-btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva plantilla
        </a>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="consentimientosTable">
                <thead class="thead-lite">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Versión</th>
                        <th>Firmados</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plantillas as $c)
                        <tr>
                            <td class="align-middle" style="font-size:.88rem;font-weight:600;">{{ $c->nombre }}</td>
                            <td class="align-middle" style="font-size:.85rem;">{{ $c->tipo }}</td>
                            <td class="align-middle" style="font-size:.85rem;">v{{ $c->version ?? 1 }}</td>
                            <td class="align-middle" style="font-size:.85rem;">{{ $c->firmados_count }}</td>
                            <td class="align-middle">
                                @if($c->activo)
                                    <span class="s-pill pill-green">Activa</span>
                                @else
                                    <span class="s-pill pill-red">Inactiva</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ecd.consentimientos.edit', $c) }}"
                                       class="act-btn ab-yellow" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ecd.consentimientos.toggle', $c) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="act-btn {{ $c->activo ? 'ab-yellow' : 'ab-green' }}"
                                                title="{{ $c->activo ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $c->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    <button class="act-btn ab-red" title="Eliminar"
                                            onclick="confirmDelete({{ $c->id }}, '{{ addslashes($c->nombre) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-cons-{{ $c->id }}"
                                          action="{{ route('ecd.consentimientos.destroy', $c) }}"
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay plantillas de consentimiento creadas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('js')
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: '¿Eliminar plantilla?',
            text: `"${name}" será eliminada permanentemente.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => { if (r.isConfirmed) document.getElementById('del-cons-' + id).submit(); });
    }
</script>
@endpush
