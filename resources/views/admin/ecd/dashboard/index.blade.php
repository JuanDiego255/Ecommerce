@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Dashboard Expedientes</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Dashboard — Expediente Clínico</h4>
        <a href="{{ route('ecd.pacientes.create') }}" class="ph-btn ph-btn-add" title="Nuevo paciente" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center">
                <div style="font-size:1.6rem;font-weight:700;color:#5e72e4;">{{ $totalPacientes }}</div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:.2rem;">Pacientes activos</div>
                @if($nuevosEsteMes)
                    <div style="font-size:.72rem;color:#10b981;margin-top:.2rem;">+{{ $nuevosEsteMes }} este mes</div>
                @endif
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center">
                <div style="font-size:1.6rem;font-weight:700;color:#10b981;">{{ $sesionesEsteMes }}</div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:.2rem;">Sesiones este mes</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center">
                <div style="font-size:1.6rem;font-weight:700;color:#0ea5e9;">{{ $sesionesHoy }}</div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:.2rem;">Sesiones hoy</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center">
                <div style="font-size:1.6rem;font-weight:700;color:#f59e0b;">{{ $proximasCitas }}</div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:.2rem;">Próximas citas (14d)</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center">
                <div style="font-size:1.6rem;font-weight:700;color:#ef4444;">{{ $alertasActivas }}</div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin-top:.2rem;">Alertas activas</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="surface p-3 text-center d-flex flex-column gap-1">
                <a href="{{ route('ecd.pacientes.index') }}" class="s-btn-sec" style="font-size:.75rem;padding:.3rem .6rem;">
                    <i class="fas fa-users me-1"></i> Ver pacientes
                </a>
                <a href="{{ route('ecd.plantillas.index') }}" class="s-btn-sec" style="font-size:.75rem;padding:.3rem .6rem;">
                    <i class="fas fa-clipboard me-1"></i> Plantillas
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Sessions chart --}}
        <div class="col-lg-7">
            <div class="surface p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;">
                        Sesiones — últimos 6 meses
                    </div>
                </div>
                @if($sesionesXMes->isEmpty())
                    <p class="text-muted text-center py-4" style="font-size:.85rem;">Sin sesiones registradas aún.</p>
                @else
                    <canvas id="sesionesChart" height="110"></canvas>
                @endif
            </div>
        </div>

        {{-- Top treatments --}}
        <div class="col-lg-5">
            <div class="surface p-4 h-100">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                    Tratamientos más frecuentes
                </div>
                @forelse($topTratamientos as $t)
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span style="font-size:.84rem;color:#1e293b;">{{ Str::limit($t->titulo, 35) }}</span>
                        <span class="s-pill pill-blue">{{ $t->total }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center py-3" style="font-size:.85rem;">Sin datos.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming appointments --}}
        @if($proxCitas->isNotEmpty())
            <div class="col-lg-5">
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Próximas citas agendadas
                    </div>
                    @foreach($proxCitas as $cita)
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2" style="border-bottom:1px solid #f1f5f9;">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $cita->paciente->foto_url }}"
                                     style="width:30px;height:30px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                <div>
                                    <div style="font-size:.84rem;font-weight:600;">{{ $cita->paciente->nombre_completo }}</div>
                                    <div style="font-size:.72rem;color:#94a3b8;">{{ $cita->titulo }}</div>
                                </div>
                            </div>
                            <div class="text-end" style="flex-shrink:0;">
                                <div style="font-size:.8rem;font-weight:600;color:#5e72e4;">
                                    {{ $cita->proxima_cita->format('d/m/Y') }}
                                </div>
                                <div style="font-size:.72rem;color:#94a3b8;">
                                    {{ $cita->proxima_cita->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent sessions --}}
        <div class="{{ $proxCitas->isNotEmpty() ? 'col-lg-7' : 'col-12' }}">
            <div class="surface p-4">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                    Sesiones recientes
                </div>
                @forelse($sesionesRecientes as $s)
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2" style="border-bottom:1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $s->paciente->foto_url }}"
                                 style="width:30px;height:30px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                            <div>
                                <a href="{{ route('ecd.sesiones.show', [$s->paciente, $s]) }}"
                                   style="font-size:.84rem;font-weight:600;color:#1e293b;text-decoration:none;">
                                    {{ $s->titulo }}
                                </a>
                                <div style="font-size:.72rem;color:#94a3b8;">
                                    {{ $s->paciente->nombre_completo }}
                                    @if($s->especialista) · {{ $s->especialista->nombre ?? '' }} @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <span class="s-pill {{ $s->estado_badge }}">{{ ucfirst($s->estado) }}</span>
                            <span style="font-size:.78rem;color:#94a3b8;">{{ $s->fecha_sesion->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3" style="font-size:.85rem;">Sin sesiones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($sesionesXMes->isNotEmpty())
    const ctx = document.getElementById('sesionesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($sesionesXMes->pluck('mes')),
            datasets: [{
                label: 'Sesiones',
                data: @json($sesionesXMes->pluck('total')),
                backgroundColor: 'rgba(94,114,228,.18)',
                borderColor: '#5e72e4',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: '#f1f5f9' },
                },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
@endif
</script>
@endpush
