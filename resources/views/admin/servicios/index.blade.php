@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Servicios</li>
@endsection

@section('content')

{{-- Modal crear --}}
@include('admin.servicios.add')

<div class="page-header">
    <div>
        <p class="page-header-title">Servicios</p>
        <p class="page-header-sub">Catálogo de servicios disponibles</p>
    </div>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-servicio-modal" class="s-btn-primary">
        <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">add</span>
        Nuevo servicio
    </button>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card">
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
                        <th>Duración</th>
                        <th>Precio base</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->nombre }}</td>
                            <td>{{ (int) ($item->duration_minutes ?? 0) }} min</td>
                            <td>₡{{ number_format((int) ($item->base_price_cents ?? 0) / 100, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->activo)
                                    <span class="s-pill pill-green">Activo</span>
                                @else
                                    <span class="s-pill pill-gray">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <button type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit-servicio-modal{{ $item->id }}"
                                        class="act-btn ab-neutral" title="Editar">
                                        <span class="material-icons" style="font-size:.9rem;">edit</span>
                                    </button>

                                    <form method="post" action="{{ url('/servicios/destroy/' . $item->id) }}" style="display:inline">
                                        {{ csrf_field() }} {{ method_field('DELETE') }}
                                        <button type="submit"
                                            onclick="return confirm('¿Deseas borrar este servicio?')"
                                            class="act-btn ab-del" title="Eliminar">
                                            <span class="material-icons" style="font-size:.9rem;">delete</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- Modal editar --}}
                                @include('admin.servicios.edit', ['servicio' => $item])
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
