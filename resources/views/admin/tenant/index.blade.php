@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>Administra los inquilinos desde acá</strong>
        </h2>

        <hr class="hr-servicios">


        <div class="row w-75">
            <div class="col-md-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#add-tenant-modal" class="btn btn-velvet">Nuevo
                    Inquilino</button>
            </div>
            <div class="col-md-3">
                <form action="{{ url('generate/sitemap') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger">Generar Sitemaps</button>
                </form>
            </div>
            <div class="col-md-3">
                <form action="{{ url('generate/migrate') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger">Migrar BD</button>
                </form>
            </div>
        </div>
        @include('admin.tenant.add-tenant')
        <center>
            <div class="card mt-3 mb-4">
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
                                    Inquilino</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Dominio</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Licencia</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenants as $tenant)
                                <tr>

                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $tenant->id }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $tenant->domains->first()->domain ?? '' }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form name="formLicense{{ $tenant->id }}" id="formLicense" method="post"
                                            action="{{ url('license/' . $tenant->id) }}" style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkLicense">
                                                <div class="form-check">
                                                    <input type="hidden" name="tenant" value="{{ $tenant->id }}">
                                                    <input id="checkLicense" class="form-check-input" type="checkbox"
                                                        value="1" name="license"
                                                        onchange="submitForm('formLicense{{ $tenant->id }}')"
                                                        {{ $tenant->license == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>

                                        </form>
                                    </td>

                                    <td class="align-middle">
                                        <center>
                                            <a href="{{ url('manage/tenant/' . $tenant->id) }}" class="btn btn-velvet"
                                                style="text-decoration: none;">Gestionar</a>
                                        </center>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </center>
    </div>
@endsection
@section('script')
    <script>
        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
