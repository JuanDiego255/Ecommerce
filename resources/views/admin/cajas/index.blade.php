@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Cajas</li>
@endsection
@section('content')
    @include('admin.cajas.add')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Administrar Cajas</h4>
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-caja-modal" class="s-btn-primary">
            <i class="fas fa-plus me-1"></i> Nueva caja
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
                        <th>Caja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cajas as $item)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <button type="button" class="act-btn ab-neutral"
                                        data-bs-toggle="modal" data-bs-target="#edit-caja-modal{{ $item->id }}"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="post" action="{{ url('/delete/cajas/' . $item->id) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="act-btn ab-del" title="Borrar"
                                            onclick="Swal.fire({title:'¿Borrar caja?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, borrar',cancelButtonText:'Cancelar',confirmButtonColor:'#e53e3e'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    @if (!isset($item->estado) || $item->estado == 0)
                                        <form method="post" action="{{ url('/open/cajas/' . $item->id) }}" style="display:inline">
                                            @csrf
                                            <button type="button" class="act-btn ab-neutral" title="Abrir caja"
                                                onclick="Swal.fire({title:'¿Abrir caja?',icon:'question',showCancelButton:true,confirmButtonText:'Sí, abrir',cancelButtonText:'Cancelar'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                                <i class="fas fa-lock-open"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="post" action="{{ url('/close/cajas/' . $item->id) }}" style="display:inline">
                                            @csrf
                                            <button type="button" class="act-btn ab-neutral" title="Cerrar caja"
                                                onclick="Swal.fire({title:'¿Cerrar caja?',icon:'question',showCancelButton:true,confirmButtonText:'Sí, cerrar',cancelButtonText:'Cancelar'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ url('cajas/arqueos/' . $item->id) }}" class="act-btn ab-neutral" title="Arqueos">
                                        <i class="fas fa-clipboard-list"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="align-middle">{{ $item->nombre }}</td>

                            @include('admin.cajas.edit')
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
