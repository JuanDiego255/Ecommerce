@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">

        <h2 class="text-center font-title"><strong>Maneja las meta etiquetas desde acá</strong>
        </h2>

        <hr class="hr-servicios">
        <a href="{{ url('metatag/agregar') }}" class="btn btn-velvet">Nueva Sección</a>

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


        <center>
            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="metatags" class="table align-items-center mb-0" style="width: 95%;">
                        <thead class="">
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Sección</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Title
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Meta
                                    Keywords</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    OG
                                    Title</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    OG
                                    Umage</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    URL
                                    Canonical</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Type
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($metatags as $tag)
                                <tr>
                                    <td class="align-middle text-center">{{ $tag->section }}</td>
                                    <td class="align-middle text-center">{{ $tag->title }}</td>
                                    <td class="align-middle text-center">{{ $tag->meta_keywords }}</td>
                                    <td class="align-middle text-center">{{ $tag->meta_og_title }}</td>
                                    <td class="align-middle text-center">{{ $tag->url_image_og }}</td>
                                    <td class="align-middle text-center">{{ $tag->url_canonical }}</td>
                                    <td class="align-middle text-center">{{ $tag->meta_type }}</td>
                                    <td class="align-middle">
                                        <center>
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"
                                                data-container="body" data-animation="true" class="btn btn-velvet"
                                                style="text-decoration: none;"
                                                href="{{ url('metatag/edit/' . $tag->id) }}"><i
                                                    class="material-icons opacity-10">edit</i></a>
                                            <form method="post" action="{{ url('/delete-metatag/' . $tag->id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button class="btn btn-velvet text-white btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"
                                                    data-container="body" data-animation="true" type="submit"> <i
                                                        class="material-icons opacity-10">
                                                        delete

                                                    </i>
                                                </button>
                                            </form>
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
        var dataTable = $('#metatags').DataTable({
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
    </script>
@endsection
