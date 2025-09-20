@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar eventos') }}</strong>
        </h2>
    </center>

    <button type="button" data-bs-toggle="modal" data-bs-target="#add-evento-modal" class="btn btn-accion">
        {{ __('Nuevo evento') }}
    </button>

    {{-- Modal crear --}}
    @include('admin.events.add')

    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
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
                                <option value="{{ $n }}" {{ (int) request('per_page', 15) === $n ? 'selected' : '' }}>
                                    {{ $n }} Registros
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-0 mt-2 text-white">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mb-0 mt-2 text-white">{{ $errors->first() }}</div>
            @endif
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Fecha inscripción') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Costo (₡)') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Ubicación') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Estado') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $item)
                                <tr class="data-row">
                                    <td class="align-middle">
                                        <a href="{{ route('events.edit', $item) }}" data-bs-toggle="modal"
                                            data-bs-target="#edit-evento-modal{{ $item->id }}"
                                            class="btn btn-outline-accion">{{ __('Editar') }}</a>

                                        <form method="post" action="{{ route('events.destroy', $item) }}"
                                            style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Deseas borrar este evento?')"
                                                class="btn btn-outline-accion">
                                                {{ __('Borrar') }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->nombre }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">
                                            {{ \Illuminate\Support\Carbon::parse($item->fecha_inscripcion)->format('d/m/Y H:i') }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="font-weight-bold mb-0">
                                            ₡{{ number_format((int) ($item->costo_crc ?? 0), 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $item->ubicacion }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        {!! $item->activo
                                            ? '<span class="badge bg-success">Activo</span>'
                                            : '<span class="badge bg-secondary">Inactivo</span>' !!}
                                    </td>

                                    {{-- Modal editar --}}
                                    @include('admin.events.edit', ['event' => $item])
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-2">
                    {{ $events->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        function filterTable(q) {
            q = (q || '').toLowerCase();
            document.querySelectorAll('#table tbody tr.data-row').forEach(tr => {
                const text = tr.innerText.toLowerCase();
                tr.style.display = text.includes(q) ? '' : 'none';
            });
        }
    </script>
@endsection
