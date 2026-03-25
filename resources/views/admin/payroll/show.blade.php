@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Módulo salarial</a></li>
    <li class="breadcrumb-item active">
        Nómina {{ \Carbon\Carbon::parse($payroll->week_start)->format('d/m/Y') }}
        — {{ \Carbon\Carbon::parse($payroll->week_end)->format('d/m/Y') }}
    </li>
@endsection

@section('content')

{{-- Header --}}
<div class="page-header">
    <div>
        <p class="page-header-title">Nómina</p>
        <p class="page-header-sub">
            {{ \Carbon\Carbon::parse($payroll->week_start)->format('d/m/Y') }}
            &nbsp;—&nbsp;
            {{ \Carbon\Carbon::parse($payroll->week_end)->format('d/m/Y') }}
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        {{-- Status badge --}}
        @switch($payroll->status)
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
                <span class="s-pill pill-gray">{{ $payroll->status }}</span>
        @endswitch

        {{-- Actions --}}
        @if($payroll->status === 'open')
            <form method="post" action="{{ route('payroll.close', $payroll) }}">
                @csrf @method('PUT')
                <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" title="Cerrar nómina" style="color:#ff9500;">
                    <i class="material-icons">lock</i>
                </button>
            </form>
        @elseif($payroll->status === 'closed')
            <form method="post" action="{{ route('payroll.reopen', $payroll) }}">
                @csrf @method('PUT')
                <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" title="Reabrir nómina" style="color:#007aff;">
                    <i class="material-icons">restore</i>
                </button>
            </form>
        @endif

        <a href="{{ route('payroll.export.pdf', $payroll) }}" class="icon-btn" data-bs-toggle="tooltip"
            data-bs-placement="bottom" title="Exportar PDF" style="color:#ff3b30;">
            <i class="material-icons">picture_as_pdf</i>
        </a>

        <form method="post" action="{{ route('payroll.pay_all', $payroll) }}"
            onsubmit="return confirm('¿Marcar TODOS los ítems como pagados?');">
            @csrf @method('PUT')
            <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                data-bs-placement="bottom" title="Pagar todos" style="color:#34c759;">
                <i class="material-icons">payments</i>
            </button>
        </form>

        <a href="{{ route('payroll.index') }}" class="icon-btn" data-bs-toggle="tooltip"
            data-bs-placement="bottom" title="Volver">
            <i class="material-icons">arrow_back</i>
        </a>
    </div>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- KPI Totales --}}
<div class="surface mb-3">
    <p class="surface-title mb-3">Resumen del período</p>
    <div class="row g-3 text-center">
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Servicios</div>
                <div class="kpi-value">{{ number_format($totals['services']) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Bruto</div>
                <div class="kpi-value" style="color:#007aff;">
                    ₡{{ number_format((int) ($totals['gross'] / 100), 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Barberos</div>
                <div class="kpi-value" style="color:#ff3b30;">
                    ₡{{ number_format((int) ($totals['barber'] / 100), 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Ajustes</div>
                <div class="kpi-value" style="color:#ff9500;">
                    ₡{{ number_format((int) ($totals['adjust'] / 100), 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Final Barbero</div>
                <div class="kpi-value" style="color:#ff3b30;">
                    ₡{{ number_format((int) ($totals['final_barber'] / 100), 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="kpi">
                <div class="kpi-label">Propietario</div>
                <div class="kpi-value" style="color:#34c759;">
                    ₡{{ number_format((int) ($totals['owner'] / 100), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Ítems por barbero --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-lite">
                    <tr>
                        <th>Barbero</th>
                        <th class="text-center">Servicios</th>
                        <th class="text-end">Bruto (₡)</th>
                        <th class="text-center">% Com.</th>
                        <th class="text-end">Barbero (₡)</th>
                        <th>Ajuste (₡)</th>
                        <th class="text-end">Final Barbero</th>
                        <th class="text-end">Propietario</th>
                        <th class="text-center">Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $it)
                        @php
                            $grossCol     = (int) ($it->gross_cents / 100);
                            $barbCol      = (int) ($it->barber_commission_cents / 100);
                            $adjCol       = (int) ($it->adjustment_cents / 100);
                            $finalBarbCol = (int) (($it->barber_commission_cents + $it->adjustment_cents) / 100);
                            $ownerCol     = (int) ($it->owner_commission_cents / 100);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $it->barbero->nombre ?? '#' . $it->barbero_id }}</td>
                            <td class="text-center">{{ $it->services_count }}</td>
                            <td class="text-end">₡{{ number_format($grossCol, 0, ',', '.') }}</td>
                            <td class="text-center">{{ number_format($it->commission_rate, 1) }}%</td>
                            <td class="text-end">₡{{ number_format($barbCol, 0, ',', '.') }}</td>
                            <td>
                                @if($payroll->status === 'open')
                                    <form method="post" action="{{ route('payroll.items.update', $it) }}"
                                        class="d-flex gap-2 align-items-center">
                                        @csrf @method('PUT')
                                        <input type="number" name="adjustment_cents" class="filter-input"
                                            style="max-width:120px;"
                                            value="{{ old('adjustment_cents', $it->adjustment_cents / 100) }}">
                                        <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Guardar ajuste"
                                            style="color:#007aff;width:34px;height:34px;">
                                            <i class="material-icons" style="font-size:1rem;">save</i>
                                        </button>
                                    </form>
                                @else
                                    ₡{{ number_format($adjCol, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-end fw-bold">₡{{ number_format($finalBarbCol, 0, ',', '.') }}</td>
                            <td class="text-end">₡{{ number_format($ownerCol, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($it->paid_at)
                                    <span class="s-pill pill-green">Pagado</span>
                                @else
                                    <form method="post" action="{{ route('payroll.items.paid', $it) }}"
                                        onsubmit="return confirm('¿Marcar como pagado?')">
                                        @csrf @method('PUT')
                                        <button class="s-btn-primary w-auto" style="font-size:.72rem;padding:4px 10px;">
                                            Pagar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No hay ítems en esta nómina</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    @parent
@endsection
