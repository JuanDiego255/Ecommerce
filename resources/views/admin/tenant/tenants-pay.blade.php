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

{{-- ── Modal: Registrar pago rápido ──────────────────────────── --}}
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

{{-- ── Modal: Nuevo gasto (company se pasa por hidden input) ──── --}}
<div class="modal fade" id="add-bill-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Nuevo gasto</h6>
                    <p style="font-size:.72rem;color:var(--gray3);margin:2px 0 0;" id="bill-company-label"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('bill/store') }}" method="POST">
                @csrf
                <input type="hidden" name="company" id="bill-company-input" value="safewor">
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

{{-- ── Modal: Nuevo cliente Space 360 ─────────────────────────── --}}
<div class="modal fade" id="add-space-client-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Nuevo cliente — Space 360</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('space/client/store') }}" method="POST">
                @csrf
                <div class="modal-body" style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Nombre del cliente</label>
                        <input type="text" name="name" class="filter-input"
                            placeholder="Ej: Autos Grecia" required>
                    </div>
                    <div>
                        <label class="filter-label">Tipo de pago</label>
                        <select name="payment_type" id="sp-payment-type" class="filter-input" required>
                            <option value="one_time">Pago único</option>
                            <option value="monthly">Mensualidad</option>
                        </select>
                    </div>
                    <div id="sp-time-to-pay-row" style="display:none;">
                        <label class="filter-label">Intervalo (meses)</label>
                        <input type="number" name="time_to_pay" id="sp-time-to-pay"
                            class="filter-input" min="1" max="60" value="1"
                            placeholder="1 = mensual, 3 = trimestral…">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 gap-2">
                    <button type="button" class="s-btn-sec w-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="s-btn-primary w-auto">
                        <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">person_add</span>
                        Crear cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Modal: Registrar pago Space 360 ────────────────────────── --}}
<div class="modal fade" id="space-pay-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Registrar pago</h6>
                    <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;" id="sp-client-label"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('space/payment/store') }}" method="POST">
                @csrf
                <input type="hidden" name="client_id" id="sp-client-id">
                <div class="modal-body" style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Monto (₡)</label>
                        <input type="number" name="amount" id="sp-amount" class="filter-input"
                            min="1" step="1" placeholder="Ej: 370000" required>
                    </div>
                    <div>
                        <label class="filter-label">Fecha del pago</label>
                        <input type="date" name="payment_date" id="sp-date" class="filter-input" required>
                    </div>
                    <div>
                        <label class="filter-label">Descripción (opcional)</label>
                        <input type="text" name="description" class="filter-input"
                            placeholder="Ej: Cuota enero, proyecto X…">
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

{{-- ── Header ──────────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <p class="page-header-title">Contabilidad</p>
        <p class="page-header-sub">Ingresos · Gastos · Balance por empresa</p>
    </div>
</div>

{{-- ── Company tabs ────────────────────────────────────────────── --}}
<ul class="nav tp-company-tabs mb-4" id="companyTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="tp-tab active" id="tab-safewor" data-bs-toggle="tab"
                data-bs-target="#pane-safewor" type="button" role="tab">
            <span class="material-icons tp-tab-icon">store</span>
            Safewor Solutions
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="tp-tab" id="tab-space" data-bs-toggle="tab"
                data-bs-target="#pane-space" type="button" role="tab">
            <span class="material-icons tp-tab-icon">360</span>
            Space 360
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- ════════════════════════════════════════════════════════════
         TAB 1: SAFEWOR SOLUTIONS
    ════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="pane-safewor" role="tabpanel">

        {{-- KPIs Safewor --}}
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
                    <div class="tp-kpi-value">{{ $fmt($totalBillsSafewor) }}</div>
                    <div class="tp-kpi-label">Total gastos</div>
                </div>
            </div>
            <div class="tp-kpi {{ $totalFundSafewor >= 0 ? '' : 'tp-kpi-neg' }}">
                <span class="material-icons tp-kpi-icon"
                      style="color:{{ $totalFundSafewor >= 0 ? '#34c759' : '#ff3b30' }};">
                    account_balance_wallet
                </span>
                <div>
                    <div class="tp-kpi-value"
                         style="color:{{ $totalFundSafewor >= 0 ? '#34c759' : '#ff3b30' }};">
                        {{ $fmt($totalFundSafewor) }}
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
                    <div class="tp-kpi-value">{{ $fmt($monthBillsSafewor) }}</div>
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

        {{-- Tabla de inquilinos --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div>
                        <p class="surface-title mb-0">Inquilinos</p>
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
                    <table class="table table-hover align-middle mb-0" id="tenants-pay">
                        <thead class="thead-lite">
                            <tr>
                                <th style="display:none;"></th>{{-- sort key --}}
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
                                $sortKey    = $frozen ? 1 : ($hasPayment ? 0 : 2);
                            @endphp
                            <tr>
                                <td style="display:none;">{{ $sortKey }}</td>
                                <td class="fw-semibold" style="{{ $frozen ? 'opacity:.55;' : '' }}">
                                    {{ $tenant->id }}
                                </td>
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
                                            <span class="s-pill pill-green">
                                                {{ \Carbon\Carbon::parse($nextDate)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="s-pill pill-gray">Sin pagos</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold" style="{{ $frozen ? 'opacity:.55;' : '' }}">
                                    {{ $fmt($tenant->total_payment ?? 0) }}
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2 align-items-center">
                                        {{-- Pago rápido (solo si no está congelado) --}}
                                        @if(!$frozen)
                                        <button type="button" class="act-btn ab-ok" title="Registrar pago"
                                            onclick="openPayModal('{{ $tenant->id }}', '{{ $tenant->plan }}')">
                                            <span class="material-icons" style="font-size:.9rem;">payments</span>
                                        </button>
                                        @endif

                                        {{-- Historial --}}
                                        <a href="{{ url('tenant/manage-pay/' . $tenant->id) }}"
                                            class="act-btn ab-neutral" title="Ver historial">
                                            <span class="material-icons" style="font-size:.9rem;">history</span>
                                        </a>

                                        {{-- Congelar / Activar --}}
                                        <form method="POST"
                                              action="{{ url('tenant/toggle-freeze/' . $tenant->id) }}"
                                              style="display:inline;"
                                              onsubmit="return confirm('{{ $frozen ? '¿Activar al inquilino ' . $tenant->id . '?' : '¿Congelar al inquilino ' . $tenant->id . '? No se registrarán cobros.' }}')">
                                            @csrf
                                            <button type="submit"
                                                class="act-btn {{ $frozen ? 'ab-ok' : 'ab-ice' }}"
                                                title="{{ $frozen ? 'Activar inquilino' : 'Congelar inquilino' }}">
                                                <span class="material-icons" style="font-size:.9rem;">
                                                    {{ $frozen ? 'play_circle' : 'ac_unit' }}
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Gastos Safewor --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div>
                        <p class="surface-title mb-0">Gastos operativos — Safewor</p>
                        <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;">
                            {{ $billsSafewor->count() }} registro{{ $billsSafewor->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="d-flex gap-2 align-items-end">
                        <div>
                            <label class="filter-label">Buscar</label>
                            <input type="text" id="searchfor_bill_sw" class="filter-input" placeholder="Filtrar gastos…">
                        </div>
                        <button type="button" class="s-btn-primary w-auto"
                                onclick="openBillModal('safewor')">
                            <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">add</span>
                            Nuevo gasto
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="bills-sw">
                        <thead class="thead-lite">
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th class="text-end">Monto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($billsSafewor as $bill)
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

    </div>{{-- /pane-safewor --}}

    {{-- ════════════════════════════════════════════════════════════
         TAB 2: SPACE 360
    ════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="pane-space" role="tabpanel">

        {{-- KPIs Space 360 --}}
        <div class="tp-kpi-row">
            <div class="tp-kpi">
                <span class="material-icons tp-kpi-icon" style="color:#007aff;">payments</span>
                <div>
                    <div class="tp-kpi-value">{{ $fmt($totalSpaceIncome) }}</div>
                    <div class="tp-kpi-label">Total ingresos</div>
                </div>
            </div>
            <div class="tp-kpi">
                <span class="material-icons tp-kpi-icon" style="color:#ff3b30;">receipt</span>
                <div>
                    <div class="tp-kpi-value">{{ $fmt($totalBillsSpace) }}</div>
                    <div class="tp-kpi-label">Total gastos</div>
                </div>
            </div>
            <div class="tp-kpi {{ $totalFundSpace >= 0 ? '' : 'tp-kpi-neg' }}">
                <span class="material-icons tp-kpi-icon"
                      style="color:{{ $totalFundSpace >= 0 ? '#34c759' : '#ff3b30' }};">
                    account_balance_wallet
                </span>
                <div>
                    <div class="tp-kpi-value"
                         style="color:{{ $totalFundSpace >= 0 ? '#34c759' : '#ff3b30' }};">
                        {{ $fmt($totalFundSpace) }}
                    </div>
                    <div class="tp-kpi-label">Fondo disponible</div>
                </div>
            </div>
            <div class="tp-kpi">
                <span class="material-icons tp-kpi-icon" style="color:#007aff;">calendar_today</span>
                <div>
                    <div class="tp-kpi-value">{{ $fmt($monthSpaceIncome) }}</div>
                    <div class="tp-kpi-label">Ingresos este mes</div>
                </div>
            </div>
            <div class="tp-kpi">
                <span class="material-icons tp-kpi-icon" style="color:#ff9500;">trending_down</span>
                <div>
                    <div class="tp-kpi-value">{{ $fmt($monthBillsSpace) }}</div>
                    <div class="tp-kpi-label">Gastos este mes</div>
                </div>
            </div>
            @if($spaceOverdueCount > 0)
            <div class="tp-kpi" style="border-color:#ff3b30;background:rgba(255,59,48,.04);">
                <span class="material-icons tp-kpi-icon" style="color:#ff3b30;">warning_amber</span>
                <div>
                    <div class="tp-kpi-value" style="color:#ff3b30;">{{ $spaceOverdueCount }}</div>
                    <div class="tp-kpi-label">Cobros vencidos</div>
                </div>
            </div>
            @endif
        </div>

        {{-- Tabla de clientes Space 360 --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div>
                        <p class="surface-title mb-0">Clientes — Space 360</p>
                        <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;">
                            {{ $spaceClients->count() }} cliente{{ $spaceClients->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="d-flex gap-2 align-items-end">
                        <div>
                            <label class="filter-label">Buscar</label>
                            <input type="text" id="searchfor_sp" class="filter-input" placeholder="Filtrar clientes…">
                        </div>
                        <button type="button" class="s-btn-primary w-auto"
                                data-bs-toggle="modal" data-bs-target="#add-space-client-modal">
                            <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">person_add</span>
                            Nuevo cliente
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="space-clients">
                        <thead class="thead-lite">
                            <tr>
                                <th style="display:none;"></th>{{-- sort key --}}
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Último pago</th>
                                <th>Próx. cobro</th>
                                <th class="text-end">Total pagado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spaceClients as $sc)
                            @php
                                $scIsMonthly  = $sc->payment_type === 'monthly';
                                $scTimePay    = max(1, (int) $sc->time_to_pay);
                                $scHasPay     = (bool) $sc->last_payment_date;
                                $scNextDate   = ($scIsMonthly && $scHasPay)
                                    ? \Carbon\Carbon::parse($sc->next_payment_base)
                                        ->addMonths($scTimePay - 1)->format('Y-m-d')
                                    : null;
                                $scOverdue    = $scNextDate && now()->toDateString() >= $scNextDate;
                                $scSortKey    = $scIsMonthly ? ($scHasPay ? 0 : 2) : 3;
                            @endphp
                            <tr>
                                <td style="display:none;">{{ $scSortKey }}</td>
                                <td class="fw-semibold">{{ $sc->name }}</td>
                                <td>
                                    @if($scIsMonthly)
                                        <span class="s-pill pill-blue">
                                            Mensual{{ $scTimePay > 1 ? ' /' . $scTimePay . 'm' : '' }}
                                        </span>
                                    @else
                                        <span class="s-pill pill-gray">Único</span>
                                    @endif
                                </td>
                                <td style="font-size:.8rem;color:var(--gray3);">
                                    {{ $scHasPay
                                        ? \Carbon\Carbon::parse($sc->last_payment_date)->format('d/m/Y')
                                        : '—' }}
                                </td>
                                <td>
                                    @if(!$scIsMonthly)
                                        <span class="s-pill pill-gray">—</span>
                                    @elseif($scNextDate)
                                        @if($scOverdue)
                                            <span class="s-pill pill-red">
                                                <span class="material-icons" style="font-size:.75rem;">warning</span>
                                                {{ \Carbon\Carbon::parse($scNextDate)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="s-pill pill-green">
                                                {{ \Carbon\Carbon::parse($scNextDate)->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="s-pill pill-gray">Sin pagos</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ $fmt($sc->total_payment ?? 0) }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2 align-items-center">
                                        <button type="button" class="act-btn ab-ok"
                                            title="Registrar pago"
                                            onclick="openSpacePayModal({{ $sc->id }}, '{{ addslashes($sc->name) }}')">
                                            <span class="material-icons" style="font-size:.9rem;">payments</span>
                                        </button>
                                        <a href="{{ url('space/client/' . $sc->id . '/payments') }}"
                                           class="act-btn ab-neutral" title="Ver historial">
                                            <span class="material-icons" style="font-size:.9rem;">history</span>
                                        </a>
                                        <form method="POST"
                                              action="{{ url('space/client/' . $sc->id) }}"
                                              style="display:inline;"
                                              onsubmit="return confirm('¿Eliminar a {{ addslashes($sc->name) }} y todos sus pagos?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="act-btn ab-del" title="Eliminar cliente">
                                                <span class="material-icons" style="font-size:.9rem;">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Gastos Space 360 --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div>
                        <p class="surface-title mb-0">Gastos operativos — Space 360</p>
                        <p style="font-size:.75rem;color:var(--gray3);margin:2px 0 0;">
                            {{ $billsSpace->count() }} registro{{ $billsSpace->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="d-flex gap-2 align-items-end">
                        <div>
                            <label class="filter-label">Buscar</label>
                            <input type="text" id="searchfor_bill_sp" class="filter-input" placeholder="Filtrar gastos…">
                        </div>
                        <button type="button" class="s-btn-primary w-auto"
                                onclick="openBillModal('space360')">
                            <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">add</span>
                            Nuevo gasto
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="bills-sp">
                        <thead class="thead-lite">
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th class="text-end">Monto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($billsSpace as $bill)
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

    </div>{{-- /pane-space --}}

</div>{{-- /tab-content --}}

@endsection

@section('script')
@parent
<style>
/* ── KPI strip ────────────────────────────────────────────── */
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

/* ── Company tabs ─────────────────────────────────────────── */
.tp-company-tabs {
    display: flex; gap: 6px; border-bottom: 2px solid var(--gray1);
    padding-bottom: 0; list-style: none; padding-left: 0; margin-bottom: 0 !important;
}
.tp-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border: none; background: none;
    font-size: .82rem; font-weight: 600; color: var(--gray3);
    border-bottom: 2px solid transparent; margin-bottom: -2px;
    cursor: pointer; border-radius: 8px 8px 0 0; transition: color .15s;
}
.tp-tab:hover { color: var(--blue); background: var(--gray0); }
.tp-tab.active { color: var(--blue); border-bottom-color: var(--blue); background: none; }
.tp-tab-icon { font-size: 1rem; }

/* ── act-btn variants ─────────────────────────────────────── */
.act-btn.ab-ok {
    background: rgba(52,199,89,.1); color: #1a7f3c;
    border-color: rgba(52,199,89,.25);
}
.act-btn.ab-ok:hover { background: rgba(52,199,89,.2); }
.act-btn.ab-ice {
    background: rgba(0,122,255,.07); color: #005ec4;
    border-color: rgba(0,122,255,.2);
}
.act-btn.ab-ice:hover { background: rgba(0,122,255,.15); }
</style>

<script>
/* ── Modal: pago rápido ─────────────────────────────────── */
function openPayModal(tenantId, plan) {
    document.getElementById('qp-tenant-id').value = tenantId;
    document.getElementById('qp-tenant-label').textContent = tenantId + ' — Plan: ' + plan;
    document.getElementById('qp-date').valueAsDate = new Date();
    document.getElementById('qp-payment').value = '';
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('quick-pay-modal')
    ).show();
}

/* ── Modal: nuevo gasto (con empresa) ──────────────────── */
function openBillModal(company) {
    document.getElementById('bill-company-input').value = company;
    document.getElementById('bill-company-label').textContent =
        company === 'space360' ? 'Space 360' : 'Safewor Solutions';
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('add-bill-modal')
    ).show();
}

/* ── Persistir tab activo en localStorage ──────────────── */
(function () {
    var saved = localStorage.getItem('tp-active-tab');
    if (saved) {
        var btn = document.querySelector('[data-bs-target="' + saved + '"]');
        if (btn) bootstrap.Tab.getOrCreateInstance(btn).show();
    }
    document.querySelectorAll('.tp-tab').forEach(function (btn) {
        btn.addEventListener('shown.bs.tab', function () {
            localStorage.setItem('tp-active-tab', btn.getAttribute('data-bs-target'));
        });
    });
})();

/* ── DataTable: inquilinos ──────────────────────────────── */
var dtTenants = $('#tenants-pay').DataTable({
    searching: true, lengthChange: false, pageLength: 50,
    order: [[0, 'asc'], [4, 'asc']],
    columnDefs: [
        { targets: 0, visible: false },
        { targets: [3, 6], orderable: false }
    ],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin inquilinos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor').on('input', function () { dtTenants.search(this.value).draw(); });

/* ── DataTable: gastos Safewor ──────────────────────────── */
var dtBillsSw = $('#bills-sw').DataTable({
    searching: true, lengthChange: false, pageLength: 15,
    order: [[0, 'desc']],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin gastos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor_bill_sw').on('input', function () { dtBillsSw.search(this.value).draw(); });

/* ── DataTable: gastos Space 360 ────────────────────────── */
var dtBillsSp = $('#bills-sp').DataTable({
    searching: true, lengthChange: false, pageLength: 15,
    order: [[0, 'desc']],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin gastos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor_bill_sp').on('input', function () { dtBillsSp.search(this.value).draw(); });

/* ── DataTable: clientes Space 360 ─────────────────────────── */
var dtSpaceClients = $('#space-clients').DataTable({
    searching: true, lengthChange: false, pageLength: 50,
    order: [[0, 'asc'], [4, 'asc']],
    columnDefs: [
        { targets: 0, visible: false },
        { targets: [3, 6], orderable: false }
    ],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin clientes',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor_sp').on('input', function () { dtSpaceClients.search(this.value).draw(); });

/* ── Modal: pago Space 360 ─────────────────────────────────── */
function openSpacePayModal(clientId, clientName) {
    document.getElementById('sp-client-id').value = clientId;
    document.getElementById('sp-client-label').textContent = clientName;
    document.getElementById('sp-date').valueAsDate = new Date();
    document.getElementById('sp-amount').value = '';
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('space-pay-modal')
    ).show();
}

/* ── Toggle intervalo en modal nuevo cliente ───────────────── */
document.getElementById('sp-payment-type').addEventListener('change', function () {
    var row = document.getElementById('sp-time-to-pay-row');
    row.style.display = this.value === 'monthly' ? '' : 'none';
});
</script>
@endsection
