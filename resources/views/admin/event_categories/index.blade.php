@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Administrar categorías de eventos') }}</strong>
        </h2>
    </center>

    <button type="button" data-bs-toggle="modal" data-bs-target="#add-cat-modal" class="btn btn-accion">
        {{ __('Nueva categoría') }}
    </button>

    {{-- Modal crear --}}
    @include('admin.event_categories.add', [
        'events' => $events ?? \App\Models\Event::orderBy('nombre')->get(),
    ])

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
                                <option value="{{ $n }}"
                                    {{ (int) request('per_page', 15) === $n ? 'selected' : '' }}>
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

    <div class="card p-2 mt-2">
        <div class="table-responsive">
            <table class="table align-items-center mb-0" id="table">
                <thead>
                    <tr>
                        <th>{{ __('Acciones') }}</th>
                        <th>{{ __('Evento') }}</th>
                        <th>{{ __('Nombre') }}</th>
                        <th>{{ __('Edad min') }}</th>
                        <th>{{ __('Edad max') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cats as $cat)
                        <tr>
                            <td class="align-middle">
                                <a href="#" class="btn btn-outline-accion" data-bs-toggle="modal"
                                    data-bs-target="#edit-cat-modal{{ $cat->id }}">{{ __('Editar') }}</a>
                                <form method="post" action="{{ url('event-categories/destroy/' . $cat->id) }}"
                                    style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Borrar categoría?')"
                                        class="btn btn-outline-accion">{{ __('Borrar') }}</button>
                                </form>
                            </td>
                            <td class="align-middle">{{ $cat->event->nombre }}</td>
                            <td class="align-middle">{{ $cat->nombre }}</td>
                            <td class="align-middle">{{ $cat->edad_min ?? '-' }}</td>
                            <td class="align-middle">{{ $cat->edad_max ?? '-' }}</td>

                            {{-- Modal editar --}}
                            @include('admin.event_categories.edit', [
                                'category' => $cat,
                                'events' => $events ?? \App\Models\Event::orderBy('nombre')->get(),
                            ])
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-2">
            {{ $cats->withQueryString()->links() }}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
