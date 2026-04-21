@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('tenants/payments') }}">Contabilidad</a></li>
    <li class="breadcrumb-item active">{{ $client->name }}</li>
@endsection

@section('content')
@php
    $fmt = fn($v) => '₡' . number_format((float)$v, 0, ',', '.');
    $isMonthly = $client->payment_type === 'monthly';
    $timePay   = max(1, (int) $client->time_to_pay);
@endphp

{{-- Modal: Registrar pago --}}
<div class="modal fade" id="add-pay-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h6 class="modal-title fw-bold mb-0">Registrar pago</h6>
                    <p class="mb-0" style="font-size:.75rem;color:var(--gray3);">{{ $client->name }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('space/payment/store') }}" method="POST">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="modal-body" style="display:grid;gap:12px;">
                    <div>
                        <label class="filter-label">Monto (₡)</label>
                        <input type="number" name="amount" class="filter-input"
                            min="1" step="1" placeholder="Ej: 370000" required>
                    </div>
                    <div>
                        <label class="filter-label">Fecha del pago</label>
                        <input type="date" name="payment_date" class="filter-input"
                            value="{{ now()->toDateString() }}" required>
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

{{-- Header --}}
<div class="page-header">
    <div>
        <p class="page-header-title">{{ $client->name }}</p>
        <p class="page-header-sub">
            Space 360 ·
            @if($isMonthly)
                Mensualidad{{ $timePay > 1 ? ' cada ' . $timePay . ' meses' : '' }}
            @else
                Pago único
            @endif
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-pay-modal"
            class="s-btn-primary w-auto">
            <span class="material-icons" style="font-size:.9rem;vertical-align:middle;">payments</span>
            Registrar pago
        </button>
        <a href="{{ url('tenants/payments') }}#pane-space" class="act-btn ab-neutral" title="Volver">
            <span class="material-icons" style="font-size:.9rem;">arrow_back</span>
        </a>
    </div>
</div>

{{-- KPIs --}}
<div class="tp-kpi-row mb-3">
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#007aff;">payments</span>
        <div>
            <div class="tp-kpi-value">{{ $fmt($totalPaid) }}</div>
            <div class="tp-kpi-label">Total pagado</div>
        </div>
    </div>
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#34c759;">event_available</span>
        <div>
            <div class="tp-kpi-value">
                {{ $lastPayment
                    ? \Carbon\Carbon::parse($lastPayment->payment_date)->format('d/m/Y')
                    : '—' }}
            </div>
            <div class="tp-kpi-label">Último pago</div>
        </div>
    </div>
    <div class="tp-kpi">
        <span class="material-icons tp-kpi-icon" style="color:#ff9500;">receipt_long</span>
        <div>
            <div class="tp-kpi-value">{{ $payments->count() }}</div>
            <div class="tp-kpi-label">Pagos registrados</div>
        </div>
    </div>
    @if($isMonthly && $lastPayment)
    @php
        $nextDue = \Carbon\Carbon::parse($lastPayment->payment_date)->addMonths($timePay);
        $isOverdue = now() >= $nextDue;
    @endphp
    <div class="tp-kpi" style="{{ $isOverdue ? 'border-color:#ff3b30;background:rgba(255,59,48,.04);' : '' }}">
        <span class="material-icons tp-kpi-icon"
              style="color:{{ $isOverdue ? '#ff3b30' : '#007aff' }};">
            {{ $isOverdue ? 'warning_amber' : 'calendar_today' }}
        </span>
        <div>
            <div class="tp-kpi-value" style="color:{{ $isOverdue ? '#ff3b30' : 'var(--black)' }};">
                {{ $nextDue->format('d/m/Y') }}
            </div>
            <div class="tp-kpi-label">
                {{ $isOverdue ? 'Cobro vencido' : 'Próx. cobro estimado' }}
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Tabla de pagos --}}
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <p class="surface-title mb-0">Historial de pagos</p>
            <div style="max-width:220px;width:100%;">
                <label class="filter-label">Buscar</label>
                <input type="text" id="searchfor" class="filter-input" placeholder="Filtrar…">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="sp-payments">
                <thead class="thead-lite">
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th class="text-end">Monto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                        <td style="font-size:.82rem;color:var(--gray3);">
                            {{ $p->description ?: '—' }}
                        </td>
                        <td class="text-end fw-semibold">{{ $fmt($p->amount) }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ url('space/payment/' . $p->id) }}"
                                style="display:inline"
                                onsubmit="return confirm('¿Eliminar este pago?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn ab-del" title="Eliminar">
                                    <span class="material-icons" style="font-size:.9rem;">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Sin pagos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($payments->count() > 0)
                <tfoot>
                    <tr style="border-top:2px solid var(--gray1);">
                        <td class="fw-bold" style="font-size:.82rem;" colspan="2">Total</td>
                        <td class="text-end fw-bold" style="color:var(--blue);">{{ $fmt($totalPaid) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
@parent
<style>
.tp-kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
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
</style>
<script>
var dtPay = $('#sp-payments').DataTable({
    searching: true, lengthChange: false, pageLength: 25,
    order: [[0, 'desc']],
    language: {
        sZeroRecords: 'Sin resultados', sEmptyTable: 'Sin pagos',
        sInfo: '_START_–_END_ de _TOTAL_', sInfoEmpty: '0 registros',
        sInfoFiltered: '(filtrado de _MAX_)',
        oPaginate: { sNext: '›', sPrevious: '‹', sFirst: '«', sLast: '»' }
    }
});
$('#searchfor').on('input', function () { dtPay.search(this.value).draw(); });
</script>
@endsection
