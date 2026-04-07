@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Especialistas</li>
@endsection
@section('content')
    @include('admin.especialistas.add')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Administrar Especialistas</h4>
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-especialista-modal" class="ph-btn ph-btn-add" title="Nuevo especialista" data-bs-placement="left">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="surface p-3 mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <label class="filter-label">Filtrar</label>
                <input type="text" class="filter-input" id="searchfor" placeholder="Escribe para filtrar...">
            </div>
            <div class="col-md-6">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" class="filter-input">
                    <option value="5">5 Registros</option>
                    <option value="10">10 Registros</option>
                    <option selected value="15">15 Registros</option>
                    <option value="50">50 Registros</option>
                </select>
            </div>
        </div>
    </div>

    <div class="surface">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table">
                <thead class="thead-lite">
                    <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Salario base</th>
                        <th>Monto por servicio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($especialistas as $item)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <button type="button" class="act-btn ab-neutral"
                                        data-bs-toggle="modal" data-bs-target="#edit-especialista-modal{{ $item->id }}"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="post" action="{{ url('/especialistas/destroy/' . $item->id) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="act-btn ab-del" title="Borrar"
                                            onclick="Swal.fire({title:'¿Borrar especialista?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, borrar',cancelButtonText:'Cancelar',confirmButtonColor:'#e53e3e'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ url('/services/specialists/' . $item->id) }}" class="act-btn ab-neutral" title="Servicios">
                                        <i class="fas fa-cogs"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="align-middle">{{ $item->nombre }}</td>
                            <td class="align-middle">₡{{ number_format($item->salario_base) }}</td>
                            <td class="align-middle">₡{{ number_format($item->monto_por_servicio) }}</td>

                            @include('admin.especialistas.edit')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
