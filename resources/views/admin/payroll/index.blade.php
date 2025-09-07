@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title"><strong>Módulo salarial</strong></h2>
    </center>

    @if (session('ok'))
        <div class="alert alert-success text-white" id="alerta">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-white" id="alerta">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger text-white" id="alerta">{{ $errors->first() }}</div>
    @endif

    {{-- Generar / Recalcular nómina --}}
    <div class="card mt-3">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="post" action="{{ route('payroll.generate') }}" autocomplete="off">
                @csrf
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                        <label class="form-label">Semana (inicio)</label>
                        <input type="date" name="week_start" class="form-control" value="{{ $start }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                        <label class="form-label">Semana (fin)</label>
                        <input type="date" name="week_end" class="form-control" value="{{ $end }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-accion w-100">Generar / Recalcular</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado --}}
    <div class="card mt-3 p-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7">Semana</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Estado</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payrolls as $p)
                        <tr>
                            <td class="align-middle text-sm">
                                <p class="mb-0">
                                    {{ \Carbon\Carbon::parse($p->week_start)->format('d/m/Y') }}
                                    —
                                    {{ \Carbon\Carbon::parse($p->week_end)->format('d/m/Y') }}
                                </p>
                            </td>
                            <td class="align-middle text-sm">
                                @php
                                    $badge =
                                        [
                                            'open' => 'bg-info',
                                            'closed' => 'bg-warning',
                                            'paid' => 'bg-success',
                                        ][$p->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badge }}">
                                    @switch($p->status)
                                        @case('open')
                                            Abierta
                                        @break

                                        @case('closed')
                                            Cerrada
                                        @break

                                        @default
                                    @endswitch
                                </span>
                            </td>
                            <td class="align-middle">
                                <a class="btn btn-outline-accion" href="{{ route('payroll.show', $p) }}">Ver</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aún no hay nóminas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2 px-2">
                {{ $payrolls->links() }}
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
