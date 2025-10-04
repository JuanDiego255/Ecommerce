@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>Mis citas – {{ $barbero->nombre }}</strong>
        </h2>
    </center>

    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Mostrar</label>
                        <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                            autocomplete="recordsPerPage">
                            <option value="5">5 Registros</option>
                            <option value="10">10 Registros</option>
                            <option selected value="15">15 Registros</option>
                            <option value="50">50 Registros</option>
                        </select>

                    </div>
                </div>

            </div>
            @if (session('ok'))
                <div class="alert alert-success text-white mt-3 mb-0">{{ session('ok') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-white mt-3 mb-0">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger text-white mt-3 mb-0">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>

    <div class="card p-2 mt-3">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Cliente') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Fecha') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Hora') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Total') }}</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Estado') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="align-middle text-center">
                                {{-- Ver --}}
                                @php $current = request()->fullUrl(); @endphp
                                <a href="{{ route('citas.show', ['id' => $item->id, 'back' => $current]) }}"
                                    class="btn btn-link text-velvet border-0" data-bs-toggle="tooltip" title="Ver detalle">
                                    <i class="material-icons text-lg">visibility</i>
                                </a>
                                @if ($item->status !== 'confirmed')
                                    <form method="post" action="{{ url('/citas/' . $item->id . '/status') }}"
                                        class="d-inline">
                                        {{ csrf_field() }} {{ method_field('PUT') }}
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-link text-success border-0"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirmar">
                                            <i class="material-icons text-lg">task_alt</i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Marcar completada (rápido) --}}
                                @if ($item->status !== 'completed')
                                    <form method="post" action="{{ url('/citas/' . $item->id . '/status') }}"
                                        class="d-inline">
                                        {{ csrf_field() }} {{ method_field('PUT') }}
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-link text-success border-0"
                                            data-bs-toggle="tooltip" title="Marcar como completada">
                                            <i class="material-icons text-lg">done_all</i>
                                        </button>
                                    </form>
                                @endif
                                {{-- Ausentar --}}
                                @if ($item->status !== 'not_arrive')
                                    <form method="post" action="{{ url('/citas/' . $item->id . '/status') }}"
                                        class="d-inline" onsubmit="return confirm('¿Desea ausentar al cliente?');">
                                        {{ csrf_field() }} {{ method_field('PUT') }}
                                        <input type="hidden" name="status" value="not_arrive">
                                        <button type="submit" class="btn btn-link text-warning border-0"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ausentar">
                                            <i class="material-icons text-lg">event_busy</i>
                                        </button>
                                    </form>
                                @endif

                                <form method="post" action="{{ url('/citas/' . $item->id) }}" class="d-inline"
                                    onsubmit="return confirm('¿Eliminar definitivamente?');">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link text-danger border-0"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </form>
                            </td>

                            <td class="align-middle text-sm">
                                <p class="text-success mb-0">{{ $item->cliente_nombre }}</p>
                                <small class="text-muted">
                                    {{ $item->cliente_email ?? '' }}
                                    {{ $item->cliente_telefono ? ' · ' . $item->cliente_telefono : '' }}
                                </small>
                            </td>

                            <td class="align-middle text-sm">{{ optional($item->starts_at)->format('d/m/Y') }}</td>
                            <td class="align-middle text-sm">{{ optional($item->starts_at)->format('H:i') }}</td>
                            <td class="align-middle text-sm">
                                <form method="post" action="{{ url('/update/cita/total/' . $item->id) }}"
                                    class="d-flex align-items-center gap-2">
                                    {{ csrf_field() }} {{ method_field('PUT') }}
                                    <div class="input-group input-group-sm input-group-outline is-filled">
                                        <label class="form-label">Total</label>
                                        <input value="{{ $item->total_cents / 100 }}" name="total" id="total"
                                            type="number" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-link text-success border-0"
                                        data-bs-toggle="tooltip" title="Guardar total">
                                        <i class="material-icons text-lg">save</i>
                                    </button>
                                </form>
                            </td>
                            <td class="align-middle text-sm">
                                @switch($item->status)
                                    @case('pending')
                                        <span class="badge bg-secondary">Pendiente</span>
                                    @break

                                    @case('confirmed')
                                        <span class="badge bg-info">Confirmada</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-success">Completada</span>
                                    @break

                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @break

                                    @case('not_arrive')
                                        <span class="badge bg-danger">Ausente</span>
                                    @break
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-2">
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
