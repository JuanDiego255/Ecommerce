@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Roles') }}</strong>
        </h2>
    </center>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-role-modal" class="btn btn-accion">
        {{ __('Nuevo rol') }}</button>
    @include('admin.roles.add')
    <div class="card mt-3">
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
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Rol') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Estado') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                                <tr>
                                    <td class="align-middle">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-rol-modal{{ $item->id }}" class="btn btn-accion"
                                            style="text-decoration: none;">Editar</button>

                                        <form method="post" action="{{ url('/delete/role/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar este rol?')"
                                                class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                        </form>

                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->rol }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success mb-0">{{ $item->status == 1 ? 'Activo' : 'Inactivo' }}
                                        </p>
                                    </td>
                                    @include('admin.roles.edit')
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
