@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-nav bc3x mt-4">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('/buys') }}"><i class="fa fa-shopping-cart me-1"></i>Mis Compras</a>
            </li>
            <li class="bread-standard"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Detalles Del Pedido</a></li>
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
                    <table id="buysDetails" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Imagen</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Artículo</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Talla</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Precio + IVA</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    IVA</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Pedido</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Cantidad</th>
                                {{-- <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buysDetails as $buy)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{tenant_asset('/') . '/'. $buy->image}}">
                                                    <img src="{{tenant_asset('/') . '/'. $buy->image}}"
                                                        class="text-center img-fluid shadow border-radius-lg w-25">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $buy->name }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $buy->size }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">₡{{ number_format($buy->total) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">₡{{ number_format($buy->iva) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @switch($buy->cancel_item)
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
                                        <p class=" font-weight-bold mb-0">{{ $buy->quantity }}</p>
                                    </td>

                                    {{-- <td class="align-middle">
                                        <center>

                                            <form method="post"
                                                action="{{ url('/cancel/buy-item/' . $buy->buy_id . '/' . $buy->cancel_item) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}

                                                <input type="hidden" name="buy" value="{{ $buy->buy }}">

                                                <button @if ($buy->cancel_item != 0 || $buy->approved != 0) disabled @endif type="submit"
                                                    data-bs-toggle="modal"
                                                    onclick="return confirm('Deseas cancelar este artículo?')"
                                                    class="btn btn-velvet" style="text-decoration: none;">
                                                    @if ($buy->cancel_item != 0)
                                                        Cancelada
                                                    @else
                                                        Cancelar
                                                    @endif
                                                </button>
                                            </form>
                                        </center>

                                    </td>
 --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </div>
    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    <script>
        var dataTable = $('#buysDetails').DataTable({
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
