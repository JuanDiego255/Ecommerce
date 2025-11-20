@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Seguridad: Roles y Vinculación') }}</strong>
        </h2>
    </center>

    <div class="card mt-3">
        <div class="card-body">
            @if (session('ok'))
                <div class="alert alert-success text-white mb-0">{{ session('ok') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger text-white mb-0">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-1 g-4 align-content-center card-group mt-1">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Usuario') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Email') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Rol') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Barbero vinculado') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr>
                                    {{-- Acciones íconos --}}
                                    <td class="align-middle text-center">
                                        {{-- “Mis citas” rápido si es barbero vinculado --}}
                                        @if ($u->barbero)
                                            <a href="{{ route('citas.mine') }}?user={{ $u->id }}"
                                                class="btn btn-link text-velvet border-0" data-bs-toggle="tooltip"
                                                title="Ver mis citas como barbero">
                                                <i class="material-icons text-lg">event</i>
                                            </a>
                                        @endif
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $u->name }}</p>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $u->email }}</p>
                                    </td>

                                    {{-- Rol (select) --}}
                                    <td class="align-middle" style="width: 220px;">
                                        <form method="post" action="{{ route('security.updateRole', $u->id) }}"
                                            class="d-flex align-items-center gap-2">
                                            {{ csrf_field() }} {{ method_field('PUT') }}

                                            @php $roleOld = old('role', $u->role); @endphp
                                            <div class="input-group input-group-sm input-group-outline is-filled"
                                                style="min-width: 150px;">
                                                <label class="form-label">Rol</label>
                                                <select name="role" class="form-control">
                                                    <option value="owner" {{ $roleOld === 'owner' ? 'selected' : '' }}>
                                                        owner</option>
                                                    <option value="manager" {{ $roleOld === 'manager' ? 'selected' : '' }}>
                                                        manager</option>
                                                    <option value="barber" {{ $roleOld === 'barber' ? 'selected' : '' }}>
                                                        barber</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-link text-success border-0"
                                                data-bs-toggle="tooltip" title="Guardar rol">
                                                <i class="material-icons text-lg">save</i>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Vincular barbero --}}
                                    <td class="align-middle" style="width: 340px;">
                                        <form method="post" action="{{ route('security.attachBarbero', $u->id) }}"
                                            class="d-flex align-items-center gap-2">
                                            {{ csrf_field() }} {{ method_field('PUT') }}

                                            @php $currentId = $u->barbero?->id; @endphp
                                            <div class="input-group input-group-sm input-group-outline is-filled"
                                                style="min-width: 260px;">
                                                <label class="form-label">Barbero</label>
                                                <select name="barbero_id" class="form-control">
                                                    <option value="">(sin vincular)</option>
                                                    @foreach ($barberos as $b)
                                                        <option value="{{ $b->id }}"
                                                            {{ (int) $currentId === (int) $b->id ? 'selected' : '' }}>
                                                            {{ $b->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-link text-success border-0"
                                                data-bs-toggle="tooltip" title="Guardar vínculo">
                                                <i class="material-icons text-lg">save</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-2">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mt-3">
                <div class="card-body">
                    <form action="{{ route('settings.payroll.update') }}" method="POST" class="form-horizontal">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-sm input-group-outline is-filled"
                                    style="min-width: 150px;">
                                    <label class="form-label">Pago Nómina</label>
                                    <select name="payroll_time" class="form-control">
                                        <option {{ $settings_barber->payroll_time == 1 ? 'selected' : '' }} value="1">
                                            Diario</option>
                                        <option value="7" {{ $settings_barber->payroll_time == 7 ? 'selected' : '' }}>
                                            Semanal</option>
                                        <option value="15"
                                            {{ $settings_barber->payroll_time == 15 ? 'selected' : '' }}>
                                            Quincenal</option>
                                        <option value="30"
                                            {{ $settings_barber->payroll_time == 30 ? 'selected' : '' }}>
                                            Mensual</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <center>
                            <button type="submit" class="btn btn-accion">Guardar</button>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
