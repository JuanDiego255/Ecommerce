{{-- resources/views/admin/owner/dashboard.blade.php --}}
@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-1 fw-bold">Panel de Supervisión</h4>
                    <div class="chip">Vista global por rango de fechas</div>
                </div>
                <form class="d-flex gap-2" method="get" action="{{ route('owner.dashboard') }}">
                    <input type="date" name="start" class="form-control" value="{{ $start->toDateString() }}">
                    <input type="date" name="end" class="form-control" value="{{ $end->toDateString() }}">
                    <button type="submit" class="icon-btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Aplicar filtro">
                        <i class="material-icons">filter_list</i>
                    </button>
                </form>
            </div>
            <div class="surface mb-3">
                <div class="row g-3">
                    <div class="col-6 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Total</div>
                            <div class="kpi-value">{{ $totalCitas }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Por aprobar</div>
                            <div class="kpi-value">{{ $porAprobar }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Confirmadas</div>
                            <div class="kpi-value">{{ $confirmadas }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Completadas</div>
                            <div class="kpi-value">{{ $completadas }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Canceladas</div>
                            <div class="kpi-value">{{ $canceladas }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-2">
                        <div class="kpi">
                            <div class="kpi-label">Ingresos (₡)</div>
                            <div class="kpi-value text-info">₡{{ number_format((int) $ingresosCents / 100, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="surface mb-5">
                <div class="surface-title mb-2">Ingresos por barbero</div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-lite">
                            <tr>
                                <th>Barbero</th>
                                <th class="text-end">Citas Completadas</th>
                                <th class="text-end">Ingresos (₡)</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingresosPorBarbero as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row->barbero?->nombre ?? '—' }}</td>
                                    <td class="text-end">{{ $row->citas }}</td>
                                    <td class="text-end text-info">
                                        ₡{{ number_format((int) $row->total_cents / 100, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-outline-secondary"
                                            href="{{ route('barberos.show', [$row->barbero_id, 'tab' => 'stats', 'start' => $start->toDateString(), 'end' => $end->toDateString()]) }}">
                                            Ver estadísticas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Sin resultados en el rango</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row g-3">
                <div class="surface-title mb-2">Gráficos por día y por estado</div>
                <div class="col-lg-8">
                    <div class="surface"><canvas id="chartPorDia"></canvas></div>
                </div>
                <div class="col-lg-4">
                    <div class="surface"><canvas id="chartPorStatus" class="chart-sm"></canvas></div>
                </div>
            </div>
            <div class="surface mt-3">
                <div class="surface-title mb-2">Ingresos por barbero</div>
                <canvas id="chartIngresosBarbero" height="110"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @parent
    <script>
        // --- Datos desde PHP al front ---
        const porDia = @json($porDia); // [{d:"2025-09-01", qty:5}, ...]
        const porStatus = @json($porStatus); // {"pending":3,"confirmed":7,...}
        const ingresosPorBarbero = @json($ingresosPorBarbero); // [{barbero_id,x,total_cents,citas,barbero:{nombre}}]

        // Helpers
        const peso = v => Math.max(0, Number(v || 0));
        const money = cents => Math.round(peso(cents) / 100);

        // --------- Línea: Citas por día ---------
        (() => {
            const labels = porDia.map(r => r.d);
            const data = porDia.map(r => Number(r.qty));
            const ctx = document.getElementById('chartPorDia');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Citas',
                        data,
                        tension: .3,
                        fill: false,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.parsed.y} cita(s)`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        })();

        // --------- Doughnut: Por estado ---------
        (() => {
            const estados = ['pending', 'confirmed', 'completed', 'cancelled'];
            const etiquetas = ['Por aprobar', 'Confirmadas', 'Completadas', 'Canceladas'];
            const valores = estados.map(k => Number(porStatus[k] || 0));
            const ctx = document.getElementById('chartPorStatus');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: etiquetas,
                    datasets: [{
                        data: valores
                    }]
                },
                aspectRatio: 1.2,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: ${ctx.parsed} `
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        })();

        // --------- Barras: Ingresos por barbero ---------
        (() => {
            const labels = ingresosPorBarbero.map(r => r.barbero?.nombre ?? `#${r.barbero_id}`);
            const data = ingresosPorBarbero.map(r => money(r.total_cents));
            const ctx = document.getElementById('chartIngresosBarbero');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Ingresos (₡)',
                        data
                    }]
                },
                options: {
                    indexAxis: labels.length > 6 ? 'y' : 'x', // si hay muchos barberos, usa barras horizontales
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ₡${ctx.formattedValue}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endsection
