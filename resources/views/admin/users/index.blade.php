@extends('layouts.admin')
@section('content')
    <h2 class="text-center font-title"><strong>Clientes</strong>
    </h2>

    <hr class="hr-servicios">
    <center>
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
                                <option selected value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card w-100 mb-4">
            <div class="table-responsive">
                <table id="table" class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Usuario</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                E-mail</th>
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                Teléfono</th>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Al por mayor</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>

                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $user->name }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $user->email }}</p>
                                </td>
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $user->telephone }}</p>
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

    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
