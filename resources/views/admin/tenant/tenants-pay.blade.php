@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Contabilidad</li>
@endsection

@section('content')
@php
    $fmt = fn($v) => '₡' . number_format((float)$v, 0, ',', '.');
@endphp

{{-- ── Modales ────────────────────────────────────────────── --}}

{{-- Modal: Registrar pago rápido (compartido, se llena por JS) --}}
<div class="modal fade" id="quick-pay-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Registrar pago</h6>
                    <p class="text-xs text-secondary mb-0" id="qp-tenant-label" style="font-size:.75rem;"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('tenant-payment/store') }}" method="POST">
                @csrf
                <input type="hidden" name="tenant_id" id="qp-tenant-id">
                <div class="modal-body" style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Monto (₡)</label>
                        <input type="number" name="payment" id="qp-payment" class="filter-input"
                            min="1" step="1" placeholder="Ej: 25000" required>
                    </div>
                    <div>
                        <label class="filter-label">Fecha de pago</label>
                        <input type="date" name="payment_date" id="qp-date" class="filter-input" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-2">
                    <button type="button" class="s-btn-sec w-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="s-btn-primary w-auto">
                        <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">payments</span>
                        Guardar pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Nuevo gasto --}}
<div class="modal fade" id="add-bill-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Nuevo gasto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('bill/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Monto (₡)</label>
                        <input type="number" name="bill" class="filter-input"
                            min="1" step="1" placeholder="Ej: 10000" required>
                    </div>
                    <div>
                        <label class="filter-label">Concepto / Detalle</label>
                        <input type="text" name="detail" class="filter-input"
                            placeholder="Ej: Servidor, dominio…" required>
                    </div>
                    <div>
                        <label class="filter-label">Fecha del gasto</label>
                        <input type="date" name="bill_date" class="filter-input" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-2">
                    <button type="button" class="s-btn-sec w-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="s-btn-primary w-auto">
                        <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">add</span>
                        Agregar gasto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Header ─────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <p class="page-header-title">Contabilidad</p>
        <p class="page-header-sub">Ingresos por inquilino · Gastos operativos · Balance</p>
    </div>
</div>

{{-- ── KPIs server-side ───────────────────────────────────── --}}
<div class="tp-kpi-row">
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#007aff;">payments</span>
        <div>
            <div class="tp-kpi-value">{{ $fmt($totalPayments) }}</div>
            <div class="tp-kpi-label">Total ingresos</div>
        </div>
    </div>
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#ff3b30;">receipt</span>
        <div>
            <div class="tp-kpi-value">{{ $fmt($totalBills) }}</div>
            <div class="tp-kpi-label">Total gastos</div>
        </div>
    </div>
    <div class="tp-kpi tp-kpi-fund {{ $totalFund >= 0 ? '' : 'tp-kpi-neg' }}">
        <span class="material-icons tp-kpi-icon" style="color:{{ $totalFund >= 0 ? '#34c759' : '#ff3b30' }};">
            account_balance_wallet
        </span>
        <div>
            <div class="tp-kpi-value" style="color:{{ $totalFund >= 0 ? '#34c759' : '#ff3b30' }};">
                {{ $fmt($totalFund) }}
            </div>
            <div class="tp-kpi-label">Fondo disponible</div>
        </div>
    </div>
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#007aff;">calendar_today</span>
        <div>
            <div class="tp-kpi-value">{{ $fmt($monthPayments) }}</div>
            <div class="tp-kpi-label">Ingresos este mes</div>
        </div>
    </div>
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#ff9500;">trending_down</span>
        <div>
            <div class="tp-kpi-value">{{ $fmt($monthBills) }}</div>
            <div class="tp-kpi-label">Gastos este mes</div>
        </div>
    </div>
    @if($overdueCount > 0)
    <div class="tp-kpi" style="border-color:#ff3b30;background:rgba(255,59,48,.04);">
        <span class="material-icons tp-kpi-icon" style="color:#ff3b30;">warning_amber</span>
        <div>
            <div class="tp-kpi-value" style="color:#ff3b30;">{{ $overdueCount }}</div>
            <div class="tp-kpi-label">Con cobro vencido</div>
        </div>
    </div>
    @endif
</div>

{{-- ── Tabla de inquilinos ─────────────────────────────────── --}}
<div class="card mb-3">
    <div class="card-body">
        {{-- Header de sección --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <p class="surface-title mb-0">Inquilinos activos</p>
                <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;">
                    {{ $tenants->count() }} inquilino{{ $tenants->count() !== 1 ? 's' : '' }}
                </p>
            </div>
            <div style="max-width:220px;width:100%;">
                <label class="filter-label">Buscar</label>
                <input type="text" id="searchfor" class="filter-input" placeholder="Filtrar inquilinos…">
            </div>
        </div>

        <div class="table-responsive">
            {{-- col 0 oculta: clave de ordenamiento 0=activo-con-pagos 1=congelado 2=sin-pagos --}}
            <table class="table table-hover align-middle mb-0" id="tenants-pay">
                <thead class="thead-lite">
                    <tr>
                        <th style="display:none;"></th>{{-- sort key (hidden) --}}
                        <th>Inquilino</th>
                        <th>Estado</th>
                        <th>Último pago</th>
                        <th>Próx. cobro</th>
                        <th class="text-end">Total pagado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                    @php
                        date_default_timezone_set('America/Costa_Rica');
                        $today      = now()->toDateString();
                        $timePay    = max(1, (int) $tenant->time_to_pay);
                        $frozen     = (int) $tenant->cool_pay === 1;
                        $hasPayment = (bool) $tenant->last_payment_date;
                        $nextDate   = $hasPayment
                            ? \Carbon\Carbon::parse($tenant->payment_date)
                                ->addMonths($timePay - 1)->format('Y-m-d')
                            : null;
                        $isOverdue  = !$frozen && $nextDate && $today >= $nextDate;
                        // orden: 0 = activo con pagos, 1 = congelado, 2 = sin pagos
                        $sortKey    = $frozen ? 1 : ($hasPayment ? 0 : 2);
                    @endphp
                    <tr>
                        {{-- sort key oculta --}}
                        <td style="display:none;">{{ $sortKey }}</td>
                        <td class="fw-semibold" style="{{ $frozen ? 'opacity:.6;' : '' }}">{{ $tenant->id }}</td>
                        <td>
                            @if($frozen)
                                <span class="s-pill pill-ice">
                                    <span class="material-icons" style="font-size:.75rem;">ac_unit</span>
                                    Congelado
                                </span>
                            @else
                                <span class="s-pill pill-blue">{{ $tenant->plan }}</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem;color:var(--gray3);">
                            {{ $hasPayment
                                ? \Carbon\Carbon::parse($tenant->last_payment_date)->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            @if($frozen)
                                <span class="s-pill pill-gray" style="opacity:.7;">—</span>
                            @elseif($nextDate)
                                @if($isOverdue)
                                    <span class="s-pill pill-red">
                                        <span class="material-icons" style="font-size:.75rem;">warning</span>
                                        {{ \Carbon\Carbon::parse($nextDate)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="s-pill pill-green">{{ \Carbon\Carbon::parse($nextDate)->format('d/m/Y') }}</span>
                                @endif
                            @else
                                <span class="s-pill pill-gray">Sin pagos</span>
                            @endif
                        </td>
                        <td class="text-end fw-semibold" style="{{ $frozen ? 'opacity:.6;' : '' }}">
                            {{ $fmt($tenant->total_payment ?? 0) }}
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-2">
                                @if(!$frozen)
                                <button type="button"
                                    class="act-btn ab-ok"
                                    title="Registrar pago"
                                    onclick="openPayModal('{{ $tenant->id }}', '{{ $tenant->plan }}')">
                                    <span class="material-icons" style="font-size:.9rem;">payments</span>
                                </button>
                                @endif
                                <a href="{{ url('tenant/manage-pay/' . $tenant->id) }}"
                                    class="act-btn ab-neutral" title="Ver historial">
                                    <span class="material-icons" style="font-size:.9rem;">history</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Tabla de gastos ─────────────────────────────────────── --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <p class="surface-title mb-0">Gastos operativos</p>
                <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;">
                    {{ $bills->count() }} registro{{ $bills->count() !== 1 ? 's' : '' }}
                </p>
            </div>
            <div class="d-flex gap-2 align-items-end">
                <div>
                    <label class="filter-label">Buscar</label>
                    <input type="text" id="searchfor_bill" class="filter-input" placeholder="Filtrar gastos…">
                </div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#add-bill-modal"
                    class="s-btn-primary w-auto">
                    <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">add</span>
                    Nuevo gasto
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="bills">
                <thead class="thead-lite">
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th class="text-end">Monto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                    <tr>
                        <td style="font-size:.82rem;">
                            {{ \Carbon\Carbon::parse($bill->bill_date)->format('d/m/Y') }}
                        </td>
                        <td>{{ $bill->detail }}</td>
                        <td class="text-end fw-semibold">{{ $fmt($bill->bill) }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ url('/delete/bill/' . $bill->id) }}"
                                style="display:inline"
                                onsubmit="return confirm('¿Eliminar este gasto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn ab-del" title="Eliminar">
                                    <span class="material-icons" style="font-size:.9rem;">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No hay gastos registrados</td>
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
<style>
/* ── KPI strip ─────────────────────────────────────────── */
.tp-kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px; margin-bottom: 16px;
}
.tp-kpi {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 16px;
    background: var(--white); border: 1.5px solid var(--gray1);
    border-radius: var(--radius);
}
.tp-kpi-icon { font-size: 1.5rem; }
.tp-kpi-value { font-size: 1.05rem; font-weight: 700; color: var(--black); line-height: 1.2; }
.tp-kpi-label { font-size: .7rem; color: var(--gray3); font-weight: 500; }

/* ── act-btn ok variant ───────────────────────────────── */
.act-btn.ab-ok {
    background: rgba(52,199,89,.1); color: #1a7f3c;
    border-color: rgba(52,199,89,.25);
}
.act-btn.ab-ok:hover { background: rgba(52,199,89,.2); }
</style>

<script>
/* ── Abrir modal de pago rápido ─────────────────────── */
function openPayModal(tenantId, plan) {
    document.getElementById('qp-tenant-id').value = tenantId;
    document.getElementById('qp-tenant-label').textContent = tenantId + ' — Plan: ' + plan;
    // Fecha por defecto: hoy
    document.getElementById('qp-date').valueAsDate = new Date();
    document.getElementById('qp-payment').value = '';
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('quick-pay-modal')
    ).show();
}

/* ── DataTable: inquilinos ──────────────────────────── */
// Orden: col0 (sortKey: 0=activo, 1=congelado, 2=sin pagos) ASC
//        luego col4 (próx. cobro) ASC dentro de activos
var dtTenants = $('#tenants-pay').DataTable({
    searching: true, lengthChange: false, pageLength: 50,
    order: [[0, 'asc'], [4, 'asc']],
    columnDefs: [
        { targets: 0, visible: false },          // oculta col sort key
        { targets: [3, 6], orderable: false }    // últ. pago y acciones no ordenan
    ],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin inquilinos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor').on('input', function() {
    dtTenants.search(this.value).draw();
});

/* ── DataTable: gastos ──────────────────────────────── */
var dtBills = $('#bills').DataTable({
    searching: true, lengthChange: false, pageLength: 15,
    order: [[0, 'desc']],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin gastos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor_bill').on('input', function() {
    dtBills.search(this.value).draw();
});
</script>
@endsection
