@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="alert alert-secondary alert-dismissible text-white fade show mt-4" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    payments
                </span>
            </span>
            <span class="alert-text"><strong>Mis Compras</strong></span><br>
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    cancel
                </span>
            </span>
            <span class="alert-text"><strong>Una vez que la compra esté aprobada, no se puede cancelar la compra, ni artículos de ella</strong></span>
        </div>
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
                    <table id="buys" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Precio + IVA</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    IVA</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Entregado</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Aprobado</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Compra</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Fecha</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buys as $buy)
                                <tr>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">₡{{ number_format($buy->total_buy) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">₡{{ number_format($buy->total_iva) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @if ($buy->delivered == 0)
                                                Pendiente
                                            @else
                                                Entregado
                                            @endif
                                        </p>
                                    </td>

                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @if ($buy->aprroved == 0)
                                                Pendiente
                                            @else
                                                Aprobado
                                            @endif
                                        </p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @switch($buy->cancel_buy)
                                                @case(0)
                                                    Vigente
                                                @break

                                                @case(1)
                                                    En proceso cancelación
                                                @break

                                                @default
                                                    Cancelada
                                            @endswitch
                                        </p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $buy->created_at }}</p>
                                    </td>

                                    <td class="align-middle">
                                        <center>
                                            @if ($buy->cancel_buy == 0)
                                                <a class="btn btn-velvet" style="text-decoration: none;"
                                                    href="{{ url('buy/details/' . $buy->id) }}">Ver
                                                    Detalle</a>
                                            @endif

                                            <form method="post"
                                                action="{{ url('/cancel/buy/' . $buy->id . '/' . $buy->cancel_buy) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                <button @if ($buy->cancel_buy != 0 || $buy->approved != 0) disabled @endif type="submit"
                                                    data-bs-toggle="modal"
                                                    onclick="return confirm('Deseas cancelar este pedido?')"
                                                    class="btn btn-velvet" style="text-decoration: none;">
                                                    @if ($buy->cancel_buy != 0)
                                                        Cancelado
                                                    @else
                                                        Cancelar Compra
                                                    @endif
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
    <center>

        <div class="col-md-12">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </center>
    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    <script>
        var dataTable = $('#buys').DataTable({
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
