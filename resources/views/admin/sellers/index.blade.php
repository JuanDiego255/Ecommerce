@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Vendedores') }}</strong>
        </h2>
    </center>
    <button type="button" data-bs-toggle="modal" data-bs-target="#add-seller-modal" class="btn btn-velvet">
        {{ __('Nuevo vendedor') }}</button>
        @include('admin.sellers.add')
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

                    <table class="table align-items-center mb-0" id="sellers">
                        <thead>
                            <tr>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Vendedor') }}
                                </th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Posición') }}</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sellers as $item)
                                <tr>

                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ route('file', $item->image) }}">
                                                    <img src="{{ route('file', $item->image) }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0 text-lg">{{ $item->name }}</h4>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-success mb-0">{{ $item->position }}
                                        </p>
                                    </td>                                    
                                    <td class="align-middle">
                                        <center>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#edit-seller-modal{{ $item->id }}"
                                                class="btn btn-velvet" style="text-decoration: none;">Editar</button>

                                            <form method="post" action="{{ url('/delete/seller/' . $item->id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" data-bs-toggle="modal"
                                                    onclick="return confirm('Deseas borrar este vendedor?')"
                                                    class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                            </form>
                                        </center>

                                    </td>
                                    @include('admin.sellers.edit')
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
            <a href="{{ url('categories') }}" class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $('#sellers').DataTable({
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
