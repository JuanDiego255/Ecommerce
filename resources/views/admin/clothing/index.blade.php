@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ $category_name }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('new-item/' . $category_id) }}" class="btn btn-velvet w-100">Agregar Nuevo Producto</a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
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
        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="clothings">
                        <thead>
                            <tr>

                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                </th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    Precio</th>
                                <th
                                    class="text-center text-secondary font-weight-bolder opacity-7 {{ isset($tenantinfo->manage_size) && $tenantinfo->manage_size == 0 ? 'd-none' : '' }}">
                                    Tallas</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    Stock</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clothings as $item)
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
                                        <p class="text-success mb-0">₡{{ $item->price }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm {{ isset($tenantinfo->manage_size) && $tenantinfo->manage_size == 0 ? 'd-none' : '' }}">
                                        @if (isset($tenantinfo->tenant) && $tenantinfo->manage_size == 1)
                                            @php
                                                $sizes = explode(',', $item->available_sizes);
                                                $stockPerSize = explode(',', $item->stock_per_size);
                                            @endphp
                                            @for ($i = 0; $i < count($sizes); $i++)
                                                <p class="mb-0">Talla {{ $sizes[$i] }}: {{ $stockPerSize[$i] }}</p>
                                            @endfor
                                        @endif
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-success mb-0">{{ $item->total_stock }}
                                        </p>
                                    </td>

                                    <td class="align-middle text-center">
                                        <form name="delete-clothing{{ $item->id }}"
                                            id="delete-clothing{{ $item->id }}" method="post"
                                            action="{{ url('/delete-clothing/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-clothing{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este producto?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/edit-clothing') . '/' . $item->id . '/' . $category_id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
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
            <a href="{{ url('categories') }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        $(document).ready(function() {
            var dataTable = $('#clothings').DataTable({
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
