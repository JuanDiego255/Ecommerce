@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>Nómina</strong>
            <small class="d-block" style="font-size: .9rem;">
                {{ \Carbon\Carbon::parse($payroll->week_start)->format('d/m/Y') }}
                —
                {{ \Carbon\Carbon::parse($payroll->week_end)->format('d/m/Y') }}
            </small>
        </h2>
    </center>

    @if (session('ok'))
        <div class="alert alert-success text-white" id="alerta">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-white" id="alerta">{{ session('error') }}</div>
    @endif

    {{-- Estado + acciones --}}
    <div class="card mt-3">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                @php
                    $badge =
                        [
                            'open' => 'bg-info',
                            'closed' => 'bg-warning',
                            'paid' => 'bg-success',
                        ][$payroll->status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }}" style="font-size: .95rem;">
                    @switch($payroll->status)
                        @case('open')
                            Abierta
                        @break

                        @case('closed')
                            Cerrada
                        @break

                        @default
                    @endswitch
                </span>
            </div>
            <div class="d-flex gap-2">
                @if ($payroll->status === 'open')
                    <form method="post" action="{{ route('payroll.close', $payroll) }}">
                        @csrf @method('PUT')
                        <button type="submit" class="icon-btn text-warning" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Cerrar Nómina">
                            <i class="material-icons">lock</i>
                        </button>
                    </form>
                @elseif($payroll->status === 'closed')
                    <form method="post" action="{{ route('payroll.reopen', $payroll) }}">
                        @csrf @method('PUT')
                        <button type="submit" class="icon-btn text-info" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="Reabrir">
                            <i class="material-icons">restore</i>
                        </button>
                    </form>
                @endif
                {{-- <a href="{{ route('payroll.export.csv', $payroll) }}" class="icon-btn text-success" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" title="Exportar Planilla">
                    <i class="material-icons">file_download</i></a> --}}
                <a href="{{ route('payroll.export.pdf', $payroll) }}" class="icon-btn text-danger" data-bs-toggle="tooltip"
                    data-bs-placement="bottom" title="Exportar PDF">
                    <i class="material-icons">file_download</i></a>
                {{-- Pagar todos (confirma antes) --}}
                <form method="post" action="{{ route('payroll.pay_all', $payroll) }}"
                    onsubmit="return confirm('¿Marcar TODOS los ítems como pagados?');">
                    @csrf @method('PUT')
                    <button type="submit" class="icon-btn text-success" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Pagar todos">
                        <i class="material-icons">money</i>
                    </button>
                </form>
                <a href="{{ route('payroll.index') }}" class="icon-btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Volver">
                    <i class="material-icons">arrow_back</i></a>
            </div>
        </div>
    </div>

    {{-- Totales --}}
    <div class="card mt-3">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Servicios</div>
                        <div class="kpi-value">{{ number_format($totals['services']) }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Bruto</div>
                        <div class="kpi-value text-info">₡{{ number_format((int) ($totals['gross'] / 100), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Barberos</div>
                        <div class="kpi-value text-danger">
                            ₡{{ number_format((int) ($totals['barber'] / 100), 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Ajustes</div>
                        <div class="kpi-value text-danger">
                            ₡{{ number_format((int) ($totals['adjust'] / 100), 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Final Barbero</div>
                        <div class="kpi-value text-danger">
                            ₡{{ number_format((int) ($totals['final_barber'] / 100), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="kpi">
                        <div class="kpi-label">Propietario</div>
                        <div class="kpi-value text-success">
                            ₡{{ number_format((int) ($totals['owner'] / 100), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ítems por barbero --}}
    <div class="card mt-3 p-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7">Barbero</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Servicios</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Bruto (₡)</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">% Comisión</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Barbero (₡)</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Ajuste (₡)</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Final barbero (₡)</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Propietario (₡)</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $it)
                        @php
                            $grossCol = (int) ($it->gross_cents / 100);
                            $barbCol = (int) ($it->barber_commission_cents / 100);
                            $adjCol = (int) ($it->adjustment_cents / 100);
                            $finalBarbCol = (int) (($it->barber_commission_cents + $it->adjustment_cents) / 100);
                            $ownerCol = (int) ($it->owner_commission_cents / 100);
                        @endphp
                        <tr>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ $it->barbero->nombre ?? '#' . $it->barbero_id }}</p>
                            </td>
                            <td class="align-middle text-sm">{{ $it->services_count }}</td>
                            <td class="align-middle text-sm">₡{{ number_format($grossCol, 0, ',', '.') }}</td>
                            <td class="align-middle text-sm">{{ number_format($it->commission_rate, 2) }}%</td>
                            <td class="align-middle text-sm">₡{{ number_format($barbCol, 0, ',', '.') }}</td>
                            <td class="align-middle text-sm">
                                @if ($payroll->status === 'open')
                                    <form method="post" action="{{ route('payroll.items.update', $it) }}"
                                        class="d-flex gap-2 align-items-center">
                                        @csrf @method('PUT')
                                        <div class="input-group input-group-sm input-group-outline is-filled"
                                            style="min-width: 140px;">
                                            <label class="form-label">Ajuste (₡)</label>
                                            <input type="number" name="adjustment_cents" class="form-control"
                                                value="{{ old('adjustment_cents', $it->adjustment_cents / 100) }}">
                                        </div>
                                        <button type="submit" class="icon-btn text-info" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Aplicar ajuste">
                                            <i class="material-icons">save</i>
                                        </button>
                                    </form>
                                @else
                                    ₡{{ number_format($adjCol, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="align-middle text-sm">
                                <strong>₡{{ number_format($finalBarbCol, 0, ',', '.') }}</strong>
                            </td>
                            <td class="align-middle text-sm">₡{{ number_format($ownerCol, 0, ',', '.') }}</td>
                            <td class="align-middle text-sm">
                                @if ($it->paid_at)
                                    <span class="badge bg-success">Pagado</span>
                                @else
                                    <form method="post" action="{{ route('payroll.items.paid', $it) }}"
                                        onsubmit="return confirm('¿Marcar como pagado?')">
                                        @csrf @method('PUT')
                                        <button class="btn btn-outline-accion btn-sm">Marcar pagado</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay ítems</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.input-group-outline .form-control').forEach(function(el) {
                if (el.value) el.closest('.input-group-outline')?.classList.add('is-filled');
                el.addEventListener('input', function() {
                    el.closest('.input-group-outline')?.classList.toggle('is-filled', !!el.value);
                });
            });
        });
    </script>
@endsection
