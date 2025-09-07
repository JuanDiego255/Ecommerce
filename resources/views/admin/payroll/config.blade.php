@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title"><strong>Comisiones por barbero</strong></h2>
        <div class="text-muted">Si dejas vacío, se usa el 50% por defecto.</div>
    </center>

    @if (session('ok'))
        <div class="alert alert-success text-white" id="alerta">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger text-white" id="alerta">{{ $errors->first() }}</div>
    @endif

    <div class="card mt-3 p-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7">Barbero</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">% Comisión</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barberos as $b)
                        <tr>
                            <td class="align-middle text-sm">{{ $b->nombre }}</td>
                            <td class="align-middle text-sm">
                                <form class="d-flex align-items-center gap-2" method="post"
                                    action="{{ route('payroll.barbero.commission', $b) }}">
                                    @csrf @method('PUT')
                                    <div class="input-group input-group-outline is-filled" style="min-width:180px;">
                                        <label class="form-label">% Comisión (0-100)</label>
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="commission_rate" class="form-control"
                                            value="{{ old('commission_rate', $b->commission_rate) }}">
                                    </div>
                                    <button type="submit" class="icon-btn text-info" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="Aplicar comisión">
                                        <i class="material-icons">save</i>
                                    </button>
                                </form>
                            </td>
                            <td class="align-middle text-sm">
                                @if (is_null($b->commission_rate))
                                    <span class="badge bg-secondary">Usa 50%</span>
                                @else
                                    <span class="badge bg-success">Personalizado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay barberos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <center class="mt-3">
        <a href="{{ route('payroll.index') }}" class="btn btn-accion">Volver</a>
    </center>
@endsection

@section('script')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.input-group-outline .form-control').forEach(function(el) {
                if (el.value !== '' && el.value !== null) el.closest('.input-group-outline')?.classList.add(
                    'is-filled');
                el.addEventListener('input', function() {
                    el.closest('.input-group-outline')?.classList.toggle('is-filled', !!el.value);
                });
            });
        });
    </script>
@endsection
