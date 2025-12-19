<div class="barbero-header d-flex flex-wrap align-items-center justify-content-between mb-1">
    <div class="d-flex align-items-center gap-3">
        @if ($barbero->photo_path)
            <img src="{{ isset($barbero->photo_path) ? route('file', $barbero->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                alt="Foto {{ $barbero->nombre }}" class="rounded-circle me-3"
                style="width:52px;height:52px;object-fit:cover;">
        @else
            <div class="barbero-avatar">{{ strtoupper(mb_substr($barbero->nombre, 0, 1)) }}</div>
        @endif
        <div>
            <h4 class="mb-1 fw-bold">Agenda de {{ $barbero->nombre }}</h4>
            <div class="d-flex flex-wrap gap-2">
                <span class="chip">
                    💰 Servicios Profesionales
                    <strong>Sin monto definido</strong>
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
<div class="card mt-1">
    <div class="card-body">
        <h4 class="mb-3">Excepciones (día libre / feriado)</h4>

        {{-- Alta de excepción --}}
        <form method="post" action="{{ url('/barberos/' . $barbero->id . '/excepciones') }}"
            class="row g-3 align-items-end">
            {{ csrf_field() }}
            <div class="col-md-4">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Fecha Inicial(YYYY-MM-DD)</label>
                    <input type="date" class="form-control" name="date" min="{{ now()->toDateString() }}"
                        onfocus="this.placeholder=''" onblur="this.placeholder='Fecha (YYYY-MM-DD)'" placeholder=""
                        required>
                </div>
            </div>
             <div class="col-md-4">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Fecha Final (YYYY-MM-DD)</label>
                    <input type="date" class="form-control" name="date_to" min="{{ now()->toDateString() }}"
                        onfocus="this.placeholder=''" onblur="this.placeholder='Fecha (YYYY-MM-DD)'" placeholder=""
                        required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Motivo</label>
                    <input type="text" class="form-control" name="motivo" maxlength="120"
                        onfocus="this.placeholder=''" onblur="this.placeholder='Motivo (opcional)'" placeholder="">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-accion">
                    Agregar excepción
                </button>
            </div>
        </form>

        @error('date')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
        @error('motivo')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

        {{-- Listado --}}
        <div class="table-responsive mt-4">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Fecha Inicial</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Fecha Final</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Motivo</th>
                        <th class="text-secondary font-weight-bolder opacity-7">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barbero->excepciones()->orderBy('date','desc')->get() as $ex)
                        <tr>

                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ \Carbon\Carbon::parse($ex->date)->format('d/m/Y') }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ \Carbon\Carbon::parse($ex->date_to)->format('d/m/Y') }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ $ex->motivo ?? '—' }}</p>
                            </td>
                            <td class="align-middle">
                                <form method="post"
                                    action="{{ url('/barberos/' . $barbero->id . '/excepciones/' . $ex->id) }}"
                                    class="d-inline" onsubmit="return confirm('¿Eliminar esta excepción?');">
                                    {{ csrf_field() }} {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link text-danger border-0"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                                        <i class="material-icons text-lg">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Sin excepciones</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
