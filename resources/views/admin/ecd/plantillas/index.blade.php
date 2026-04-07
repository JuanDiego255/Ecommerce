@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item active">Plantillas de ficha</li>
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
        <h4 class="mb-0">Plantillas de ficha clínica</h4>
        <a href="{{ route('ecd.plantillas.create') }}" class="ph-btn ph-btn-add" title="Nueva plantilla" data-bs-toggle="tooltip" data-bs-placement="left">
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
                <label class="filter-label">Estado</label>
                <select class="filter-input" id="filterEstado">
                    <option value="">Todas</option>
                    <option value="activa">Activas</option>
                    <option value="inactiva">Inactivas</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="plantillasTable">
                <thead class="thead-lite">
                    <tr>
                        <th>Plantilla</th>
                        <th>Categoría</th>
                        <th>Campos</th>
                        <th>Usos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plantillas as $p)
                        @php
                            $numCampos = collect($p->campos['secciones'] ?? [])->sum(fn($s) => count($s['campos'] ?? []));
                        @endphp
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $p->color_etiqueta ?? '#5e72e4' }};flex-shrink:0;"></span>
                                    <div>
                                        <div class="fw-semibold" style="font-size:.88rem;">{{ $p->nombre }}</div>
                                        @if($p->descripcion)
                                            <div style="font-size:.75rem;color:#94a3b8;">{{ Str::limit($p->descripcion, 60) }}</div>
                                        @endif
                                        @if($p->es_sistema)
                                            <span class="s-pill pill-blue" style="font-size:.65rem;">Sistema</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">{{ $p->categoria ?: '—' }}</td>
                            <td class="align-middle" style="font-size:.85rem;">
                                {{ $numCampos }} campo{{ $numCampos !== 1 ? 's' : '' }}
                                <div style="font-size:.75rem;color:#94a3b8;">
                                    {{ count($p->campos['secciones'] ?? []) }} sección{{ count($p->campos['secciones'] ?? []) !== 1 ? 'es' : '' }}
                                </div>
                            </td>
                            <td class="align-middle" style="font-size:.85rem;">{{ $p->sesiones_count }}</td>
                            <td class="align-middle">
                                @if($p->activa)
                                    <span class="s-pill pill-green">Activa</span>
                                @else
                                    <span class="s-pill pill-red">Inactiva</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ecd.plantillas.edit', $p) }}"
                                       class="act-btn ab-yellow" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ecd.plantillas.duplicate', $p) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="act-btn ab-blue" title="Duplicar">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('ecd.plantillas.toggle', $p) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="act-btn {{ $p->activa ? 'ab-yellow' : 'ab-green' }}"
                                                title="{{ $p->activa ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas {{ $p->activa ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    @if(!$p->es_sistema)
                                        <button class="act-btn ab-red" title="Eliminar"
                                                onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nombre) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="del-plt-{{ $p->id }}"
                                              action="{{ route('ecd.plantillas.destroy', $p) }}"
                                              method="POST" class="d-none">
                                            @csrf @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No hay plantillas creadas. <a href="{{ route('ecd.plantillas.create') }}">Crear la primera</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('script')
<script>
    const search = document.getElementById('searchInput');
    const filterEstado = document.getElementById('filterEstado');
    const rows = () => document.querySelectorAll('#plantillasTable tbody tr');

    function applyFilters() {
        const q = search.value.toLowerCase();
        const estado = filterEstado.value;
        rows().forEach(row => {
            const text = row.textContent.toLowerCase();
            let estadoOk = true;
            if (estado) {
                const pills = row.querySelectorAll('.s-pill');
                estadoOk = estado === 'activa'
                    ? [...pills].some(p => p.textContent.trim() === 'Activa')
                    : [...pills].some(p => p.textContent.trim() === 'Inactiva');
            }
            row.style.display = (text.includes(q) && estadoOk) ? '' : 'none';
        });
    }

    search.addEventListener('input', applyFilters);
    filterEstado.addEventListener('change', applyFilters);

    function confirmDelete(id, name) {
        Swal.fire({
            title: '¿Eliminar plantilla?',
            text: `Se eliminará "${name}". Las sesiones que la usaron conservarán sus datos.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(r => { if (r.isConfirmed) document.getElementById('del-plt-' + id).submit(); });
    }
</script>
@endsection
