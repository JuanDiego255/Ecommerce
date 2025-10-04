@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>Editar Cliente</strong>
        </h2>
    </center>
    <div class="card mt-3">
        <form method="post" action="{{ route('clientes.update', $client) }}" autocomplete="off">
            @csrf @method('PUT')
            <div class="card-body">
                @if (session('ok'))
                    <div class="alert alert-success text-white">{{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger text-white">{{ $errors->first() }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $client->nombre) }}"
                                class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $client->email) }}"
                                class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $client->telefono) }}"
                                class="form-control form-control-lg">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Auto-agendar</label>
                            <select name="auto_book_opt_in" class="form-control form-control-lg">
                                <option value="0" {{ !$client->auto_book_opt_in ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $client->auto_book_opt_in ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>
                    </div>
                    {{-- ▼▼ Config avanzada de auto-agendado ▼▼ --}}
                    <div class="col-md-12" id="autoBookConfig"
                        style="{{ $client->auto_book_opt_in ? '' : 'display:none' }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Frecuencia</label>
                                    <select name="auto_book_frequency" id="auto_book_frequency"
                                        class="form-control form-control-lg">
                                        <option value="">— Selecciona —</option>
                                        <option value="weekly"
                                            {{ old('auto_book_frequency', $client->auto_book_frequency) === 'weekly' ? 'selected' : '' }}>
                                            Semanal</option>
                                        <option value="biweekly"
                                            {{ old('auto_book_frequency', $client->auto_book_frequency) === 'biweekly' ? 'selected' : '' }}>
                                            Quincenal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Cadencia (días)</label>
                                    <input type="number" min="1" step="1" name="cadence_days"
                                        id="cadence_days" value="{{ old('cadence_days', $client->cadence_days) }}"
                                        class="form-control form-control-lg" placeholder="7 u 14">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Anticipación de búsqueda (días)</label>
                                    <input type="number" min="7" max="60" step="1"
                                        name="auto_book_lookahead_days"
                                        value="{{ old('auto_book_lookahead_days', $client->auto_book_lookahead_days ?? ($client->auto_book_frequency === 'weekly' ? 14 : ($client->auto_book_frequency === 'biweekly' ? 28 : 21))) }}"
                                        class="form-control form-control-lg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Próximo disparo (next_due_at)</label>
                                    <input type="text" class="form-control form-control-lg"
                                        value="{{ optional(optional($client->next_due_at)->timezone(config('app.timezone', 'America/Costa_Rica')))->format('Y-m-d H:i') ?? '—' }}""
                                        disabled>
                                </div>
                                {{--  <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" id="resetDue" name="next_due_at_reset"
                                        value="1">
                                    <label class="form-check-label" for="resetDue">Forzar re-cálculo (ponerlo para el
                                        próximo ciclo)</label>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    {{-- ▲▲ Fin config avanzada ▲▲ --}}
                    <div class="col-md-6">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Barbero preferido</label>
                            <select name="preferred_barbero_id" class="form-control form-control-lg">
                                <option value="">— Ninguno —</option>
                                @foreach ($barberos as $b)
                                    <option value="{{ $b->id }}"
                                        {{ $client->preferred_barbero_id == $b->id ? 'selected' : '' }}>
                                        {{ $b->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Días preferidos</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php $days = [1=>'Lun',2=>'Mar',3=>'Mié',4=>'Jue',5=>'Vie',6=>'Sáb',0=>'Dom']; @endphp
                            @foreach ($days as $idx => $label)
                                <div class="form-check">
                                    <input type="checkbox" name="preferred_days[]" value="{{ $idx }}"
                                        class="form-check-input" id="day{{ $idx }}"
                                        {{ in_array($idx, old('preferred_days', $client->preferred_days ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="day{{ $idx }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Hora inicio</label>
                            <input type="time" name="preferred_start"
                                value="{{ old('preferred_start', \Carbon\Carbon::parse($client->preferred_start)->format('H:i')) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Hora fin</label>
                            <input type="time" name="preferred_end"
                                value="{{ old('preferred_end', \Carbon\Carbon::parse($client->preferred_end)->format('H:i')) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Precio con descuento</label>
                            <input type="number" name="discount" value="{{ old('discount', $client->discount) }}"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                            <label class="form-label">Notas</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-accion">Guardar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-accion">Volver</a>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const optIn = document.getElementById('auto_book_opt_in');
            const cfg = document.getElementById('autoBookConfig');
            const freq = document.getElementById('auto_book_frequency');
            const cadence = document.getElementById('cadence_days');

            function toggleCfg() {
                cfg.style.display = (optIn && optIn.value === '1') ? '' : 'none';
            }

            function syncCadence() {
                if (!freq) return;
                if (freq.value === 'weekly' && (!cadence.value || cadence.value === '' || cadence.value === '14')) {
                    cadence.value = 7;
                } else if (freq.value === 'biweekly' && (!cadence.value || cadence.value === '' || cadence.value ===
                        '7')) {
                    cadence.value = 14;
                }
            }

            if (optIn) {
                optIn.addEventListener('change', toggleCfg);
                toggleCfg();
            }
            if (freq) {
                freq.addEventListener('change', syncCadence);
                syncCadence();
            }
        });
    </script>
@endsection
