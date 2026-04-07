@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/cajas/') }}">Cajas</a></li>
    <li class="breadcrumb-item active">Arqueos</li>
@endsection
@section('content')
    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Administrar Arqueos</h4>
        <a href="{{ url('/cajas/') }}" class="ph-btn ph-btn-back" title="Volver" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
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
                        <th>Fecha Apertura</th>
                        <th>Fecha Final</th>
                        <th>Estado</th>
                        <th>Abierta Por</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($arqueos as $item)
                        <tr>
                            <td class="align-middle">
                                @if (!$item->estado == 0)
                                    <form method="post" action="{{ url('/close/cajas/' . $item->caja_id) }}" style="display:inline">
                                        @csrf
                                        <button type="button" class="act-btn ab-neutral" title="Cerrar caja"
                                            onclick="Swal.fire({title:'¿Cerrar caja?',icon:'question',showCancelButton:true,confirmButtonText:'Sí, cerrar',cancelButtonText:'Cancelar'}).then(r=>{if(r.isConfirmed)this.closest('form').submit()})">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="align-middle">{{ $item->fecha_ini }}</td>
                            <td class="align-middle">{{ $item->fecha_fin }}</td>
                            <td class="align-middle">
                                @if ($item->estado == 1)
                                    <span class="s-pill pill-green">Abierta</span>
                                @else
                                    <span class="s-pill pill-red">Cerrada</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $item->name }}</td>
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
