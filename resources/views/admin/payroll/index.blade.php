@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Módulo salarial</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <p class="page-header-title">Módulo salarial</p>
        <p class="page-header-sub">Gestión de nóminas por período</p>
    </div>
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

{{-- Generar / Recalcular nómina --}}
<div class="card mb-3">
    <div class="card-body">
        <p class="surface-title mb-3">Generar o recalcular nómina</p>
        <form class="d-flex flex-wrap gap-3 align-items-end" method="post"
              action="{{ route('payroll.generate') }}" autocomplete="off">
            @csrf
            <div style="flex:1;min-width:160px;">
                <label class="filter-label">Período inicio</label>
                <input type="date" name="week_start" class="filter-input" value="{{ $start }}" required>
            </div>
            <div style="flex:1;min-width:160px;">
                <label class="filter-label">Período fin</label>
                <input type="date" name="week_end" class="filter-input" value="{{ $end }}" required>
            </div>
            <div>
                <button class="s-btn-primary">
                    <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">calculate</span>
                    Generar / Recalcular
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Listado de nóminas --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-lite">
                    <tr>
                        <th>Período</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                        <tr>
                            <td class="fw-semibold">
                                {{ \Carbon\Carbon::parse($p->week_start)->format('d/m/Y') }}
                                <span class="text-muted mx-1">—</span>
                                {{ \Carbon\Carbon::parse($p->week_end)->format('d/m/Y') }}
                            </td>
                            <td>
                                @switch($p->status)
                                    @case('open')
                                        <span class="s-pill pill-blue">Abierta</span>
                                        @break
                                    @case('closed')
                                        <span class="s-pill pill-orange">Cerrada</span>
                                        @break
                                    @case('paid')
                                        <span class="s-pill pill-green">Pagada</span>
                                        @break
                                    @default
                                        <span class="s-pill pill-gray">{{ $p->status }}</span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                <a class="act-btn ab-neutral" href="{{ route('payroll.show', $p) }}">
                                    <span class="material-icons" style="font-size:.9rem;">open_in_new</span>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Aún no hay nóminas generadas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payrolls->hasPages())
            <div class="p-3 border-top">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
    @parent
@endsection
