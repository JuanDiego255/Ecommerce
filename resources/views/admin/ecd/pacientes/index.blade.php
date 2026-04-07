@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Expedientes</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Pacientes</h4>
        <a href="{{ route('ecd.pacientes.create') }}" class="ph-btn ph-btn-add" title="Nuevo paciente" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    {{-- Session alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="surface p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <label class="filter-label">Buscar</label>
                <input type="text" class="filter-input" id="searchInput" placeholder="Nombre, cédula, teléfono...">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Estado</label>
                <select class="filter-input" id="filterActivo">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label">Mostrar</label>
                <select class="filter-input" id="recordsPerPage">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="pacientesTable">
                <thead class="thead-lite">
                    <tr>
                        <th>Paciente</th>
                        <th>Contacto</th>
                        <th>Sesiones</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $p)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $p->foto_url }}"
                                         alt="{{ $p->nombre_completo }}"
                                         style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold" style="font-size:.9rem;">{{ $p->nombre_completo }}</div>
                                        @if($p->cedula)
                                            <div style="font-size:.78rem;color:#94a3b8;">{{ $p->cedula }}</div>
                                        @endif
                                        @if($p->edad)
                                            <div style="font-size:.78rem;color:#94a3b8;">{{ $p->edad }} años</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                @if($p->telefono)
                                    <div style="font-size:.85rem;"><i class="fas fa-phone me-1 text-muted"></i>{{ $p->telefono }}</div>
                                @endif
                                @if($p->email)
                                    <div style="font-size:.85rem;"><i class="fas fa-envelope me-1 text-muted"></i>{{ $p->email }}</div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span style="font-size:.88rem;">{{ $p->sesiones_count }}</span>
                                @if($p->alertas->count())
                                    <span class="s-pill pill-red ms-1">{{ $p->alertas->count() }} alerta{{ $p->alertas->count() > 1 ? 's' : '' }}</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($p->activo)
                                    <span class="s-pill pill-green">Activo</span>
                                @else
                                    <span class="s-pill pill-red">Inactivo</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('ecd.pacientes.show', $p) }}"
                                       class="act-btn ab-blue" title="Ver expediente">
                                        <i class="fas fa-folder-open"></i>
                                    </a>
                                    <a href="{{ route('ecd.pacientes.edit', $p) }}"
                                       class="act-btn ab-yellow" title="Editar datos">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="act-btn ab-red" title="Eliminar"
                                            onclick="confirmDelete({{ $p->id }}, '{{ addslashes($p->nombre_completo) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="del-{{ $p->id }}"
                                          action="{{ route('ecd.pacientes.destroy', $p) }}"
                                          method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No hay pacientes registrados aún.
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
    // Client-side filter (simple)
    const search = document.getElementById('searchInput');
    const filterActivo = document.getElementById('filterActivo');
    const rows = () => document.querySelectorAll('#pacientesTable tbody tr');

    function applyFilters() {
        const q = search.value.toLowerCase();
        const activo = filterActivo.value;
        rows().forEach(row => {
            const text  = row.textContent.toLowerCase();
            const pills = row.querySelectorAll('.s-pill');
            let estadoMatch = true;
            if (activo !== '') {
                estadoMatch = activo === '1'
                    ? [...pills].some(p => p.textContent.trim() === 'Activo')
                    : [...pills].some(p => p.textContent.trim() === 'Inactivo');
            }
            row.style.display = (text.includes(q) && estadoMatch) ? '' : 'none';
        });
    }

    search.addEventListener('input', applyFilters);
    filterActivo.addEventListener('change', applyFilters);

    function confirmDelete(id, name) {
        Swal.fire({
            title: '¿Eliminar paciente?',
            text: `Se eliminará el registro de "${name}" y todos sus datos.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar',
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('del-' + id).submit();
            }
        });
    }
</script>
@endpush
