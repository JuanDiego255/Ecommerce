@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Inscripciones') }}</strong>
        </h2>
    </center>

    <div class="card mt-3">
        <div class="card-body">
            <form class="row w-100 g-2" method="get">
                {{-- <div class="col-md-4">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Estado</label>
                        <select name="estado" class="form-control form-control-lg" onchange="this.form.submit()">
                            @php $estado = request('estado'); @endphp
                            <option value="">Todos</option>
                            <option value="pending" {{ $estado === 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="approved" {{ $estado === 'approved' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rejected" {{ $estado === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="{{ request('q') }}" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor"
                            oninput="filterTable(this.value)">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Mostrar</label>
                        <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                            onchange="window.location='?per_page='+this.value">
                            @foreach ([5, 10, 15, 50] as $n)
                                <option value="{{ $n }}"
                                    {{ (int) request('per_page', 15) === $n ? 'selected' : '' }}>
                                    {{ $n }} Registros
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="alert alert-success mb-0 mt-2 text-white">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mb-0 mt-2 text-white">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>

    <div class="card p-2 mt-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table">
                <thead>
                    <tr>
                        <th>{{ __('Acciones') }}</th>
                        <th>{{ __('Evento') }}</th>
                        <th>{{ __('Categoría') }}</th>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Teléfono') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Estado') }}</th>
                        <th>{{ __('Comprobante') }}</th>
                        <th>{{ __('Fecha') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($regs as $reg)
                        <tr>
                            <td class="align-middle">
                                <form method="post" action="{{ route('registrations.updateEstado', $reg->id) }}"
                                    style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado"
                                        value="{{ $reg->estado === 'approved' ? 'pending' : 'approved' }}">
                                    <button type="submit" class="btn btn-outline-accion">
                                        {{ $reg->estado === 'approved' ? 'Marcar pendiente' : 'Aprobar' }}
                                    </button>
                                </form>

                                <form method="post" action="{{ route('registrations.updateEstado', $reg->id) }}"
                                    style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="estado" value="rejected">
                                    <button type="submit" class="btn btn-outline-accion">Rechazar</button>
                                </form>
                            </td>

                            <td class="align-middle">{{ $reg->event->nombre }}</td>
                            <td class="align-middle">{{ $reg->category->nombre }}</td>
                            <td class="align-middle">{{ $reg->nombre }} {{ $reg->apellidos }}</td>
                            <td class="align-middle">{{ $reg->telefono }}</td>
                            <td class="align-middle">{{ $reg->email }}</td>
                            <td class="align-middle">
                                @php $badge = ['pending'=>'secondary','approved'=>'success','rejected'=>'danger'][$reg->estado] ?? 'secondary'; @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst(__($reg->estado)) }}</span>
                            </td>
                            <td class="align-middle">
                                <a class="btn btn-outline-accion" href="{{ route('registrations.download', $reg->id) }}">
                                    Descargar
                                </a>
                            </td>
                            <td class="align-middle">
                                {{ $reg->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-2">
            {{ $regs->withQueryString()->links() }}
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
