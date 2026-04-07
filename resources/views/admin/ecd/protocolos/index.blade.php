@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.dashboard') }}">ECD</a></li>
    <li class="breadcrumb-item active">Protocolos</li>
@endsection
@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Protocolos de tratamiento</h4>
        <a href="{{ route('ecd.protocolos.create') }}" class="ph-btn ph-btn-add" title="Nuevo protocolo" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <div class="surface p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <label class="filter-label">Buscar</label>
                <input type="text" class="filter-input" id="searchInput" placeholder="Nombre, categoría...">
            </div>
            <div class="col-md-4">
                <label class="filter-label">Nivel</label>
                <select class="filter-input" id="filterNivel">
                    <option value="">Todos</option>
                    <option value="basico">Básico</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="avanzado">Avanzado</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="protocolosTable">
                <thead class="thead-lite">
                    <tr>
                        <th>Protocolo</th>
                        <th>Categoría</th>
                        <th>Duración</th>
                        <th>Nivel</th>
                        <th>Pasos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nivelPills = ['basico' => 'pill-green', 'intermedio' => 'pill-yellow', 'avanzado' => 'pill-red'];
                        $nivelLabels = ['basico' => 'Básico', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
                    @endphp
                    @forelse($protocolos as $p)
                        <tr>
                            <td class="align-middle">
                                <div class="fw-semibold" style="font-size:.88rem;">{{ $p->nombre }}</div>
                                @if($p->descripcion)
                                    <div style="font-size:.75rem;color:#94a3b8;">{{ Str::limit($p->descripcion, 55) }}</div>
                                @endif
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">{{ $p->categoria ?: '—' }}</td>
                            <td class="align-middle" style="font-size:.85rem;">
                                {{ $p->duracion_estimada_min ? $p->duracion_estimada_min . ' min' : '—' }}
                            </td>
                            <td class="align-middle">
                                <span class="s-pill {{ $nivelPills[$p->nivel_dificultad] ?? 'pill-blue' }}">
                                    {{ $nivelLabels[$p->nivel_dificultad] ?? $p->nivel_dificultad }}
                                </span>
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">{{ count($p->pasos ?? []) }}</td>
                            <td class="align-middle">
                                @if($p->activo)
                                    <span class="s-pill pill-green">Activo</span>
                                @else
                                    <span class="s-pill pill-red">Inactivo</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ecd.protocolos.show', $p) }}"
                                       class="act-btn ab-blue" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('ecd.protocolos.edit', $p) }}"
                                       class="act-btn ab-yellow" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ecd.protocolos.toggle', $p) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="act-btn {{ $p->activo ? 'ab-yellow' : 'ab-green' }}"
                                                title="{{ $p->activo ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $p->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    <button class="act-btn ab-red" title="Eliminar"
                                            onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nombre) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-prot-{{ $p->id }}"
                                          action="{{ route('ecd.protocolos.destroy', $p) }}"
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No hay protocolos registrados.
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
    const search      = document.getElementById('searchInput');
    const filterNivel = document.getElementById('filterNivel');
    const rows = () => document.querySelectorAll('#protocolosTable tbody tr');

    function applyFilters() {
        const q     = search.value.toLowerCase();
        const nivel = filterNivel.value.toLowerCase();
        rows().forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = (text.includes(q) && (!nivel || text.includes(nivel))) ? '' : 'none';
        });
    }
    search.addEventListener('input', applyFilters);
    filterNivel.addEventListener('change', applyFilters);

    function confirmDelete(id, name) {
        Swal.fire({
            title: '¿Eliminar protocolo?',
            text: `"${name}" será eliminado permanentemente.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => { if (r.isConfirmed) document.getElementById('del-prot-' + id).submit(); });
    }
</script>
@endpush
