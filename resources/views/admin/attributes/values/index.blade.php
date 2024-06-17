@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Gestiona los valores del atributo ') }} {{ $attr->name }}</strong>
        </h2>
    </center>
    @include('admin.attributes.values.add')
    <div class="row w-50">
        <div class="col-md-6">
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-value-attr-modal"
                class="btn btn-velvet">{{ __('Nuevo valor') }}</button>
        </div>
    </div>
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

                    <table class="table align-items-center mb-0" id="values">
                        <thead>
                            <tr>
                                <th class="text-secondary text-center font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Valor') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($values_attr as $item)
                                <tr>
                                    <td class="align-middle text-center">
                                        <form name="delete-value{{ $item->value_id }}"
                                            id="delete-value{{ $item->value_id }}" method="post"
                                            action="{{ url('/delete-value/' . $item->value_id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-value{{ $item->value_id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este valor?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/value/' . $item->attr_id . '/' . $item->value_id . '/edit') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
                                    <td class="align-middle text-xxs">
                                        <p class=" font-weight-bold mb-0">{{ $item->value }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('attributes/') }}" class="btn btn-velvet w-25">{{ __('Ir a atributos') }}</a>
        </div>
    </center>
@endsection
@section('script')    
    <script>
        $(document).ready(function() {
            var dataTable = $('#values').DataTable({
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
    </script>
@endsection
