@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Seguridad</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <p class="page-header-title">Seguridad</p>
        <p class="page-header-sub">Roles de usuario y vinculación con barberos</p>
    </div>
</div>

@if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row g-3 align-items-start">
    {{-- Users table --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-lite">
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Barbero vinculado</th>
                                <th class="text-center">Ver agenda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td class="text-muted" style="font-size:.8rem;">{{ $u->email }}</td>

                                    {{-- Rol --}}
                                    <td style="min-width:200px;">
                                        <form method="post" action="{{ route('security.updateRole', $u->id) }}"
                                            class="d-flex align-items-center gap-2">
                                            {{ csrf_field() }} {{ method_field('PUT') }}
                                            @php $roleOld = old('role', $u->role); @endphp
                                            <select name="role" class="filter-input" style="max-width:140px;">
                                                <option value="owner"   {{ $roleOld === 'owner'   ? 'selected' : '' }}>Owner</option>
                                                <option value="manager" {{ $roleOld === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="barber"  {{ $roleOld === 'barber'  ? 'selected' : '' }}>Barber</option>
                                            </select>
                                            <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                                                title="Guardar rol" style="width:32px;height:32px;color:#34c759;">
                                                <i class="material-icons" style="font-size:.95rem;">save</i>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Barbero vinculado --}}
                                    <td style="min-width:240px;">
                                        <form method="post" action="{{ route('security.attachBarbero', $u->id) }}"
                                            class="d-flex align-items-center gap-2">
                                            {{ csrf_field() }} {{ method_field('PUT') }}
                                            @php $currentId = $u->barbero?->id; @endphp
                                            <select name="barbero_id" class="filter-input">
                                                <option value="">(sin vincular)</option>
                                                @foreach($barberos as $b)
                                                    <option value="{{ $b->id }}"
                                                        {{ (int) $currentId === (int) $b->id ? 'selected' : '' }}>
                                                        {{ $b->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="icon-btn" data-bs-toggle="tooltip"
                                                title="Guardar vínculo" style="width:32px;height:32px;color:#34c759;">
                                                <i class="material-icons" style="font-size:.95rem;">save</i>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Ver agenda --}}
                                    <td class="text-center">
                                        @if($u->barbero)
                                            <a href="{{ route('citas.mine') }}?user={{ $u->id }}"
                                                class="icon-btn" data-bs-toggle="tooltip" title="Ver agenda"
                                                style="width:32px;height:32px;color:#007aff;text-decoration:none;">
                                                <i class="material-icons" style="font-size:.95rem;">event</i>
                                            </a>
                                        @else
                                            <span class="text-muted" style="font-size:.75rem;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="p-3 border-top">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Configuración de nómina --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <p class="surface-title mb-3">Configuración de pago</p>
                <form action="{{ route('settings.payroll.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="settings-field mb-4">
                        <label class="filter-label">Frecuencia de pago</label>
                        <select name="payroll_time" class="filter-input">
                            <option {{ $settings_barber->payroll_time == 1  ? 'selected' : '' }} value="1">Diario</option>
                            <option {{ $settings_barber->payroll_time == 7  ? 'selected' : '' }} value="7">Semanal</option>
                            <option {{ $settings_barber->payroll_time == 15 ? 'selected' : '' }} value="15">Quincenal</option>
                            <option {{ $settings_barber->payroll_time == 30 ? 'selected' : '' }} value="30">Mensual</option>
                        </select>
                    </div>
                    <button type="submit" class="s-btn-primary w-100">Guardar configuración</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
