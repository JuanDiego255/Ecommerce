@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $label = $tipo == 'Y' ? 'mensualidad' : 'matricula'
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">{{ $tipo == 'C' ? 'Estudiantes' : 'Yoga' }}</li>
@endsection
@section('content')
    @include('admin.estudiantes.add')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ $tipo == 'C' ? 'Gestión de estudiantes' : 'Gestión de clases de yoga' }}</h4>
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-estudiante-modal" class="s-btn-primary">
            <i class="fas fa-plus me-1"></i> Nuevo estudiante
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
                        <th>Teléfono</th>
                        <th>Edad</th>
                        <th>Email</th>
                        <th>Fecha Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estudiantes as $item)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <button type="button" class="act-btn ab-neutral"
                                        data-bs-toggle="modal" data-bs-target="#edit-estudiante-modal{{ $item->id }}"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="post" action="{{ url('/delete/estudiantes/' . $item->id) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="act-btn ab-del" title="Borrar"
                                            onclick="Swal.fire({title:'¿Borrar estudiante?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, borrar',cancelButtonText:'Cancelar',confirmButtonColor:'#e53e3e'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="act-btn ab-neutral"
                                        data-bs-toggle="modal" data-bs-target="#matricula-estudiante-modal{{ $item->id }}"
                                        title="Matricular">
                                        <i class="fas fa-graduation-cap"></i>
                                    </button>

                                    <a href="{{ url('list/matricula/' . $item->id) }}" class="act-btn ab-neutral" title="Gestionar">
                                        <i class="fas fa-folder-open"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="align-middle">{{ $item->nombre }}</td>
                            <td class="align-middle">{{ $item->telefono }}</td>
                            <td class="align-middle">{{ $item->edad }}</td>
                            <td class="align-middle">{{ $item->email }}</td>
                            <td class="align-middle">{{ $item->fecha_pago }} de cada mes</td>

                            @include('admin.estudiantes.edit')
                            @include('admin.estudiantes.matricula')
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
