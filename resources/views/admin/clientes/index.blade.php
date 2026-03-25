@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <p class="page-header-title">Clientes</p>
        <p class="page-header-sub">Historial y preferencias de clientes</p>
    </div>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3 mb-3">
            <div style="flex:1;min-width:180px;">
                <label class="filter-label">Buscar</label>
                <input type="text" id="searchfor" name="searchfor" class="filter-input"
                    placeholder="Nombre, email, teléfono…">
            </div>
            <div style="width:160px;">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                    <option value="5">5 registros</option>
                    <option value="10">10 registros</option>
                    <option selected value="15">15 registros</option>
                    <option value="50">50 registros</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table">
                <thead class="thead-lite">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="text-center">Auto-agendar</th>
                        <th>Última visita</th>
                        <th>Próxima propuesta</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $c)
                        <tr>
                            <td class="fw-semibold">{{ $c->nombre ?? '—' }}</td>
                            <td style="font-size:.8rem;color:var(--gray3);">{{ $c->email ?? '—' }}</td>
                            <td>{{ $c->telefono ?? '—' }}</td>
                            <td class="text-center">
                                @if($c->auto_book_opt_in)
                                    <span class="s-pill pill-green">Sí</span>
                                @else
                                    <span class="s-pill pill-gray">No</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($c->last_seen_at))
                                    <span style="font-size:.82rem;">{{ $c->last_seen_at }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($c->next_due_at))
                                    <span class="s-pill pill-blue">{{ $c->next_due_at }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('clientes.edit', $c) }}"
                                    class="act-btn ab-neutral" title="Editar">
                                    <span class="material-icons" style="font-size:.9rem;">edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay clientes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
