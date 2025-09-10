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
        <div class="card-body">

            @if (session('ok'))
                <div class="alert alert-success text-white">{{ session('ok') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger text-white">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('clientes.update', $client) }}" autocomplete="off">
                @csrf @method('PUT')

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
                    </div>
                
                    <div class="col-md-3">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Hora inicio</label>
                            <input type="time" name="preferred_start"
                                value="{{ old('preferred_start', $client->preferred_start) }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                            <label class="form-label">Hora fin</label>
                            <input type="time" name="preferred_end"
                                value="{{ old('preferred_end', $client->preferred_end) }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="input-group input-group-lg input-group-outline my-3 is-filled">
                            <label class="form-label">Notas</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-accion">Guardar</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-accion">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection
