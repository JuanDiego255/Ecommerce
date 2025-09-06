<div class="card mt-1">
    <div class="card-body">
        <h4 class="mb-3">Bloques (horarios no disponibles)</h4>

        {{-- Alta de bloque --}}
        <form method="post" action="{{ url('/barberos/' . $barbero->id . '/bloques') }}" class="row g-3 align-items-end">
            {{ csrf_field() }}
            <div class="col-md-2">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Fecha (YYYY-MM-DD)</label>
                    <input type="date" class="form-control" name="date" min="{{ now()->toDateString() }}"
                        onfocus="this.placeholder=''" onblur="this.placeholder='Fecha (YYYY-MM-DD)'" placeholder=""
                        required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Inicio</label>
                    <input type="time" class="form-control" name="start_time" required onfocus="this.placeholder=''"
                        onblur="this.placeholder='Inicio'" placeholder="Inicio">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Fin</label>
                    <input type="time" class="form-control" name="end_time" required onfocus="this.placeholder=''"
                        onblur="this.placeholder='Fin'" placeholder="Fin">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                    <label class="form-label">Motivo</label>
                    <input type="text" class="form-control" name="motivo" maxlength="120"
                        onfocus="this.placeholder=''" onblur="this.placeholder='Motivo (opcional)'" placeholder="">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-accion">
                    Agregar bloque
                </button>
            </div>
        </form>

        @error('date')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
        @error('start_time')
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
        @error('end_time')
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
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Fecha</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Inicio</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Fin</th>
                        <th class="text-secondary font-weight-bolder opacity-7 ps-2">Motivo</th>
                        <th class="text-secondary font-weight-bolder opacity-7">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bloques = $barbero->bloques()->orderBy('date', 'desc')->orderBy('start_time')->get();
                    @endphp
                    @forelse($bloques as $bl)
                        <tr>

                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ \Carbon\Carbon::parse($bl->date)->format('d/m/Y') }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ \Illuminate\Support\Str::of($bl->start_time)->limit(5, '') }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ \Illuminate\Support\Str::of($bl->end_time)->limit(5, '') }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <p class="mb-0">{{ $bl->motivo ?? '—' }}</p>
                            </td>
                            <td class="align-middle">
                                <form method="post"
                                    action="{{ url('/barberos/' . $barbero->id . '/bloques/' . $bl->id) }}"
                                    class="d-inline" onsubmit="return confirm('¿Eliminar este bloque?');">
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
                            <td colspan="5" class="text-center text-muted">Sin bloques</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
