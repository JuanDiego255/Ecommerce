@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item">
        <a href="{{ url('estudiantes/manage/' . ($item->tipo_estudiante == 'C' ? 'clases' : 'yoga')) }}">
            {{ $item->tipo_estudiante == 'C' ? 'Estudiantes' : 'Yoga' }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ $item->nombre }}</li>
@endsection
@section('content')
    @php
        $label = $item->tipo_estudiante == 'Y' ? 'mensualidad' : 'matricula';
    @endphp
    @include('admin.estudiantes.matricula')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">
            {{ $item->tipo_estudiante == 'C' ? 'Matrículas de' : 'Mensualidades de' }} {{ $item->nombre }}
        </h4>
        <div class="d-flex gap-2">
            <button type="button" data-bs-toggle="modal" data-bs-target="#matricula-estudiante-modal{{ $item->id }}"
                class="ph-btn ph-btn-add" title="Nueva {{ $label }}" data-bs-placement="left">
                <i class="fas fa-plus"></i>
            </button>
            <a href="{{ url('estudiantes/manage/' . ($item->tipo_estudiante == 'C' ? 'clases' : 'yoga')) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-placement="left">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
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
                        <th>Curso</th>
                        <th>Monto Pagado</th>
                        <th>Monto del curso</th>
                        <th>Fecha {{ $item->tipo_estudiante == 'C' ? 'Matrícula' : 'Inicio' }}</th>
                        <th>Próxima fecha de pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculas as $matricula)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <button type="button" class="act-btn ab-neutral"
                                        data-bs-toggle="modal" data-bs-target="#edit-matricula-modal{{ $matricula->id }}"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="post" action="{{ url('/delete/matricula/' . $matricula->id) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="act-btn ab-del" title="Borrar"
                                            onclick="Swal.fire({title:'¿Borrar matrícula?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, borrar',cancelButtonText:'Cancelar',confirmButtonColor:'#e53e3e'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ url('/pagos/matricula/' . $matricula->id) }}" class="act-btn ab-neutral" title="Gestión de pagos">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="align-middle">{{ $matricula->curso }}</td>
                            <td class="align-middle">₡{{ number_format($matricula->monto_pago) }}</td>
                            <td class="align-middle">₡{{ number_format($matricula->monto_curso) }}</td>
                            <td class="align-middle">{{ $matricula->fecha_matricula }}</td>
                            <td class="align-middle">
                                @if ($matricula->proxima_fecha_pago <= $fechaCostaRica)
                                    <span class="s-pill pill-red">{{ $matricula->proxima_fecha_pago }}</span>
                                @else
                                    <span class="s-pill pill-green">{{ $matricula->proxima_fecha_pago }}</span>
                                @endif
                            </td>

                            @include('admin.estudiantes.matricula.edit')
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
