{{-- resources/views/admin/barberos/partials/_stats.blade.php --}}

{{-- HEADER SIMPLE --}}
<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif
        <div>
            <h4 class="mb-1 fw-bold">Estadísticas de {{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">💰 Salario base:
                    <strong>₡{{ number_format((int) $barbero->salario_base, 0, ',', '.') }}</strong>
                </span>
                <span class="chip">✂️ Por servicio:
                    <strong>₡{{ number_format((int) $barbero->monto_por_servicio, 0, ',', '.') }}</strong>
                </span>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
        @can('barberos.manage')
            <a href="{{ url('barberos') }}" class="icon-btn" data-bs-toggle="tooltip" title="Volver a barberos">
                <i class="material-icons">arrow_back</i>
            </a>
            <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#edit-barbero-modal"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar barbero">
                <i class="material-icons">edit</i>
            </button>
        @endcan

    </div>
</div>
<div class="surface mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="surface-title"></div>
        <form class="d-flex gap-3" method="get" action="{{ route('barberos.show', $barbero) }}">
            <input type="hidden" name="tab" value="stats">
            <input type="date" name="start" class="form-control" value="{{ $start->toDateString() }}">
            <input type="date" name="end" class="form-control" value="{{ $end->toDateString() }}">
            <button type="submit" class="icon-btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Aplicar filtro">
                <i class="material-icons">filter_list</i>
            </button>
        </form>
    </div>

    @if ($stats)
        <div class="row g-3">
            <div class="col-6 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Total</div>
                    <div class="kpi-value">{{ $stats['total'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Por aprobar</div>
                    <div class="kpi-value">{{ $stats['pending'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Confirmadas</div>
                    <div class="kpi-value">{{ $stats['confirmed'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Completadas</div>
                    <div class="kpi-value">{{ $stats['completed'] }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Canceladas</div>
                    <div class="kpi-value">{{ $stats['cancelled'] }}</div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="kpi">
                    <div class="kpi-label">Ingresos (₡)</div>
                    <div class="kpi-value text-info">₡{{ number_format((int) $stats['ingresos'] / 100, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    @else
        <div class="text-muted">Selecciona un rango y aplica.</div>
    @endif
</div>
