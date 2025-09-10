@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>Clientes</strong>
        </h2>
    </center>

    <div class="card mt-3">
        <div class="card-body">

            {{-- Filtros --}}
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
                <div class="alert alert-success text-white">{{ session('ok') }}</div>
            @endif

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table align-items-center mb-0" id="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th class="text-center">Auto-agendar</th>
                            <th>Última visita</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $c)
                            <tr>
                                <td class="align-middle">{{ $c->nombre ?? '—' }}</td>
                                <td class="align-middle">{{ $c->email ?? '—' }}</td>
                                <td class="align-middle">{{ $c->telefono ?? '—' }}</td>
                                <td class="align-middle text-center">
                                    {!! $c->auto_book_opt_in
                                        ? '<span class="badge bg-success">Sí</span>'
                                        : '<span class="badge bg-secondary">No</span>' !!}
                                </td>
                                <td class="align-middle">
                                    {{ isset($c->last_seen_at) ? $c->last_seen_at : '—' }}
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('clientes.edit', $c) }}" class="btn btn-link text-velvet border-0"
                                        data-bs-toggle="tooltip" title="Editar">
                                        <i class="material-icons text-lg">edit</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay clientes registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
