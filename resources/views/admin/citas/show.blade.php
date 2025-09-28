@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    @php $back = $back ?? route('citas.index'); @endphp
    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Detalle de cita #{{ $cita->id }}</h4>
                <a href="{{ $back }}" class="btn btn-accion">Volver</a>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100 is-filled">
                        <label>Cliente</label>
                        <input class="form-control form-control-lg" value="{{ $cita->cliente_nombre }}" readonly>
                        <small class="text-muted">
                            {{ $cita->cliente_email ?? '' }}
                            {{ $cita->cliente_telefono ? ' · ' . $cita->cliente_telefono : '' }}
                        </small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100 is-filled">
                        <label>Barbero</label>
                        <input class="form-control form-control-lg" value="{{ $cita->barbero->nombre ?? '—' }}" readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group input-group-lg input-group-static my-3 w-100 is-filled">
                        <label>Fecha</label>
                        <input class="form-control form-control-lg" value="{{ $cita->starts_at?->format('d/m/Y') }}"
                            readonly>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group input-group-lg input-group-static my-3 w-100 is-filled">
                        <label>Hora</label>
                        <input class="form-control form-control-lg" value="{{ $cita->starts_at?->format('H:i') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th class="text-end">Precio (₡)</th>
                            <th class="text-center">Duración (min)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalTable = 0;
                            $totalCita = $cita->total_cents / 100;
                        @endphp
                        @foreach ($cita->servicios as $s)
                            @php
                                $totalTable += $s->pivot->price_cents / 100;
                            @endphp
                            <tr>
                                <td>{{ $s->nombre }}</td>
                                <td class="text-end">₡{{ number_format((int) $s->pivot->price_cents / 100, 0, ',', '.') }}
                                </td>
                                <td class="text-center">{{ (int) $s->pivot->duration_minutes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="text-end">₡{{ number_format((int) $totalCita, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                        @if ($totalCita != $totalTable)
                            <tr>
                                <th>El monto de la cita fue modificado (Por algún descuento, u otros)</th>
                                <th></th>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            <div class="d-flex align-items-center gap-2 mt-3">
                {{-- Acciones rápidas --}}
                @if ($cita->status !== 'confirmed')
                    <form method="post" action="{{ url('/citas/' . $cita->id . '/status') }}">
                        {{ csrf_field() }} {{ method_field('PUT') }}
                        <input type="hidden" name="status" value="confirmed">
                        <button class="btn btn-link text-success border-0" data-bs-toggle="tooltip" title="Confirmar">
                            <i class="material-icons">task_alt</i>
                        </button>
                    </form>
                @endif

                @if ($cita->status !== 'completed')
                    <form method="post" action="{{ url('/citas/' . $cita->id . '/status') }}">
                        {{ csrf_field() }} {{ method_field('PUT') }}
                        <input type="hidden" name="status" value="completed">
                        <button class="btn btn-link text-primary border-0" data-bs-toggle="tooltip"
                            title="Marcar como completada">
                            <i class="material-icons">done_all</i>
                        </button>
                    </form>
                @endif

                @if ($cita->status !== 'cancelled')
                    <form method="post" action="{{ url('/citas/' . $cita->id . '/status') }}"
                        onsubmit="return confirm('¿Cancelar la cita?');">
                        {{ csrf_field() }} {{ method_field('PUT') }}
                        <input type="hidden" name="status" value="cancelled">
                        <button class="btn btn-link text-warning border-0" data-bs-toggle="tooltip" title="Cancelar">
                            <i class="material-icons">cancel</i>
                        </button>
                    </form>
                @endif

                <form method="post" action="{{ url('/citas/' . $cita->id) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente?');">
                    {{ csrf_field() }} {{ method_field('DELETE') }}
                    <button class="btn btn-link text-danger border-0" data-bs-toggle="tooltip" title="Eliminar">
                        <i class="material-icons">delete</i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
