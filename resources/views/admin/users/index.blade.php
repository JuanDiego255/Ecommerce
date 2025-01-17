@extends('layouts.admin')
@section('content')
    <h2 class="text-center font-title"><strong>Clientes</strong>
    </h2>

    <hr class="hr-servicios">
    <div class="card mt-3 mb-3">
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
        </div>
    </div>
    <div class="card p-2">
        <div class="table-responsive">
            <table id="table" class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Acciones</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Usuario</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            E-mail</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Teléfono</th>
                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                            Rol</th>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Al por mayor</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="align-middle">
                                <form name="delete-user{{ $user->id }}" id="delete-user{{ $user->id }}"
                                    method="post" action="{{ url('/delete-user/' . $user->id) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button form="delete-user{{ $user->id }}" type="submit"
                                    onclick="return confirm('Deseas borrar este usuario?')"
                                    class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Eliminar">
                                    <i class="material-icons text-lg">delete</i>
                                </button>
                                <a class="btn btn-link text-velvet me-auto border-0"
                                    href="{{ url('/user/' . $user->id . '/edit') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Editar">
                                    <i class="material-icons text-lg">edit</i>
                                </a>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $user->name }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $user->email }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $user->telephone }}</p>
                            </td>
                            <td class="align-middle text-xxs text-center">
                                <p class=" font-weight-bold mb-0">{{ $user->role_as == 1 || $user->role_as == 2 ? $user->rol : 'Usuario' }}</p>
                            </td>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                                <form id="myForm" action="{{ url('user/mayor/' . $user->id) }}" method="POST">
                                    @csrf
                                    <!-- Otros campos del formulario -->
                                    <td class="align-middle text-center">
                                        <label for="checkboxSubmit">
                                            <div class="form-check">
                                                <input id="checkboxSubmit" class="form-check-input" type="checkbox"
                                                    value="1" name="status" onchange="this.form.submit()"
                                                    {{ $user->mayor == 1 ? 'checked' : '' }}>
                                            </div>
                                        </label>
                                    </td>
                                    <!-- Otros campos del formulario -->
                                </form>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
