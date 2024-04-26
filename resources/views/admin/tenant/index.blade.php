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

        <button type="button" data-bs-toggle="modal" data-bs-target="#add-tenant-modal" class="btn btn-velvet">Nuevo
            Inquilino</button>

        <center>
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
                            <option selected value="10">10 Registros</option>
                            <option value="25">25 Registros</option>
                            <option value="50">50 Registros</option>
                        </select>

                    </div>
                </div>
            </div>

            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="tenants" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Inquilino</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Dominio</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Licencia</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Maneja Tallas</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Maneja Departamentos</th>
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
                                        <form name="formLicense" id="formLicense" method="post" action="{{ url('license/' . $tenant->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkLicense">
                                                <div class="form-check">
                                                    <input id="checkLicense" class="form-check-input" type="checkbox"
                                                        value="1" name="license" onchange="submitForm('formLicense')"
                                                        {{ $tenant->license == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>

                                        </form>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form name="formSize" id="formSize" method="post" action="{{ url('manage/size/' . $tenant->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkboxSize">
                                                <div class="form-check">
                                                    <input id="checkboxSize" class="form-check-input" type="checkbox"
                                                        value="1" name="manage_size" onchange="submitForm('formSize')"
                                                        {{ $tenant->manage_size == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>

                                        </form>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form name="formDepartment" id="formDepartment" method="post" action="{{ url('manage/department/' . $tenant->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkboxDepartment">
                                                <div class="form-check">
                                                    <input id="checkboxDepartment" class="form-check-input" type="checkbox"
                                                        value="1" name="manage_department"
                                                        onchange="submitForm('formDepartment')"
                                                        {{ $tenant->manage_department == 1 ? 'checked' : '' }}>
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
        var dataTable = $('#tenants').DataTable({
            searching: true,
            lengthChange: false,

            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "<<",
                    "sLast": "Último",
                    "sNext": ">>",
                    "sPrevious": "<<"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }

        $('#recordsPerPage').on('change', function() {
            var recordsPerPage = parseInt($(this).val(), 10);
            dataTable.page.len(recordsPerPage).draw();
        });

        // Captura el evento input en el campo de búsqueda
        $('#searchfor').on('input', function() {
            var searchTerm = $(this).val();
            dataTable.search(searchTerm).draw();
        });
    </script>
@endsection
