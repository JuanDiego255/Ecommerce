@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Barberos</li>
@endsection

@section('content')

{{-- Modal crear --}}
@include('admin.barberos.add')

<div class="page-header">
    <div>
        <p class="page-header-title">Barberos</p>
        <p class="page-header-sub">Gestión del equipo</p>
    </div>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-barbero-modal" class="s-btn-primary">
        <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">person_add</span>
        Nuevo barbero
    </button>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="surface">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-3 mb-3">
            <div style="flex:1;min-width:180px;">
                <label class="filter-label">Buscar</label>
                <input type="text" id="searchfor" name="searchfor" class="filter-input" placeholder="Filtrar por nombre…">
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
                        <th>Salario base</th>
                        <th>Monto por servicio</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barberos as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->nombre }}</td>
                            <td>₡{{ number_format($item->salario_base ?? 0) }}</td>
                            <td>₡{{ number_format($item->monto_por_servicio ?? 0) }}</td>
                            <td class="text-center">
                                <a href="{{ route('barberos.show', ['barbero' => $item->id, 'tab' => 'info', 'back' => url()->current()]) }}"
                                   class="act-btn ab-neutral">
                                    <span class="material-icons" style="font-size:.9rem;">person</span>
                                    Perfil
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
