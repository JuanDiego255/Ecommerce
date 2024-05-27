@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Gestiona los atributos') }}</strong>
        </h2>
    </center>
    @include('admin.attributes.add')
    <div class="row w-50">
        <div class="col-md-6">
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-attribute-modal"
                class="btn btn-velvet">{{ __('Nuevo atributo') }}</button>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h4 class="text-dark">
                {{ __('Puedes agregar todos los atributos que desees, ten en cuenta que solo existe un atributo principal, que puede variar entre precios, todos los demás son extras adicionales que no modifican el precio, por lo que lo ideal es que se contemplen en el precio total.') }}
            </h4>
        </div>
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
                            <option selected value="10">10 Registros</option>
                            <option value="25">25 Registros</option>
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

                    <table class="table align-items-center mb-0" id="attributes">
                        <thead>
                            <tr>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Estilo') }}</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Atributo principal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $item)
                                <tr>
                                    <td class="align-middle">
                                        <form name="delete-attribute{{ $item->id }}"
                                            id="delete-attribute{{ $item->id }}" method="post"
                                            action="{{ url('/delete-attribute/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-attribute{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este atributo?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/attribute-values/' . $item->id) }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Valores">
                                            <i class="material-icons text-lg">visibility</i>
                                        </a>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/attribute/' . $item->id . '/edit') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
                                    <td class="align-middle text-xxs">
                                        <p class=" font-weight-bold mb-0">{{ $item->name }}</p>
                                    </td>
                                    <td class="align-middle text-xxs">
                                        <p class=" font-weight-bold mb-0">
                                            @switch($item->type)
                                                @case(0)
                                                    {{ __('Botón simple') }}
                                                @break

                                                @case(1)
                                                    {{ __('Seleccionador') }}
                                                @break
                                            @endswitch
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form name="formMain{{ $item->id }}" id="formMain" method="post"
                                            action="{{ url('main-attribute/' . $item->id) }}" style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkMain">
                                                <div class="form-check">
                                                    <input id="checkMain" class="form-check-input" type="checkbox"
                                                        value="1" name="main"
                                                        onchange="submitForm('formMain{{ $item->id }}')"
                                                        {{ $item->main == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>
                                        </form>
                                    </td>
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
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $('#attributes').DataTable({
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

            $('#recordsPerPage').on('change', function() {
                var recordsPerPage = parseInt($(this).val(), 10);
                dataTable.page.len(recordsPerPage).draw();
            });

            // Captura el evento input en el campo de búsqueda
            $('#searchfor').on('input', function() {
                var searchTerm = $(this).val();
                dataTable.search(searchTerm).draw();
            });

        });

        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }
    </script>
@endsection
