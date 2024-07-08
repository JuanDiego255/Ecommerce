@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="font-title text-center">{{ __('Adminsitra las compras desde acá') }}</h1>
    <div class="container">
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
                                <option selected value="10">10 Registros</option>
                                <option value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

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
                                    {{ __('Acciones') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Comporbante') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Nombre') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Teléfono') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('E-mail') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Precio') }} @if ($iva > 0)
                                        {{ __('I.V.A') }}
                                    @endif {{ __('+ Envío') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Envío') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Entregado') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Aprobado') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Compra') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Fecha') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buys as $buy)
                                <tr>
                                    <td class="align-middle">
                                        <center>

                                            <form style="display:inline" action="{{ url('delete-buy/' . $buy->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-velvet text-white btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar pedido"
                                                    data-container="body" data-animation="true" type="submit"><i
                                                        class="material-icons opacity-10">
                                                        delete
                                                    </i>
                                                </button>
                                            </form>

                                            @if ($buy->cancel_buy == 0)
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalle"
                                                    data-container="body" data-animation="true" class="btn btn-velvet"
                                                    style="text-decoration: none;"
                                                    href="{{ url('buy/details/admin/' . $buy->id) }}"><i
                                                        class="material-icons opacity-10">visibility</i></a>
                                            @endif

                                            @if ($buy->cancel_buy == 0)
                                                <form style="display:inline"
                                                    action="{{ url('approve/' . $buy->id . '/' . $buy->approved) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <button class="btn btn-velvet text-white btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $buy->approved == 1 ? 'Desaprobar Compra' : 'Aprobar Compra' }}"
                                                        data-container="body" data-animation="true" type="submit"> <i
                                                            class="material-icons opacity-10">
                                                            @if ($buy->approved == 1)
                                                                cancel
                                                            @else
                                                                check_circle
                                                            @endif
                                                        </i>
                                                    </button>
                                                </form>

                                                <form style="display:inline"
                                                    action="{{ url('delivery/' . $buy->id . '/' . $buy->delivered) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <button class="btn btn-velvet text-white btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $buy->delivered == 1 ? 'No Entregado' : 'Entregado' }}"
                                                        data-container="body" data-animation="true" type="submit"> <i
                                                            class="material-icons opacity-10">
                                                            @if ($buy->delivered == 1)
                                                                close
                                                            @else
                                                                check
                                                            @endif
                                                        </i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($buy->cancel_buy == 1)
                                                <form style="display:inline"
                                                    action="{{ url('cancel/buy/' . $buy->id . '/' . $buy->cancel_buy) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf

                                                    <input type="hidden" name="action" value="1">
                                                    <button class="btn btn-velvet text-white btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Aprobar Cancelación" data-container="body"
                                                        data-animation="true" type="submit"> <i
                                                            class="material-icons opacity-10">
                                                            check
                                                        </i>
                                                    </button>
                                                </form>
                                                <form style="display:inline"
                                                    action="{{ url('cancel/buy/' . $buy->id . '/' . $buy->cancel_buy) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf

                                                    <input type="hidden" name="action" value="0">
                                                    <button class="btn btn-velvet text-white btn-tooltip"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Desaprobar Cancelación" data-container="body"
                                                        data-animation="true" type="submit"> <i
                                                            class="material-icons opacity-10">
                                                            cancel
                                                        </i>
                                                    </button>
                                                </form>
                                            @endif
                                        </center>

                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if ($buy->image)
                                                    <a target="blank" data-fancybox="gallery"
                                                        href="{{ route('file', $buy->image) }}">
                                                        <img src="{{ route('file', $buy->image) }}"
                                                            class="img-fluid shadow border-radius-lg">
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ isset($buy->name) ? $buy->name : $buy->name_b }}
                                            {{ isset($buy->last_name) ? $buy->last_name : $buy->last_name_b }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ isset($buy->telephone) ? $buy->telephone : $buy->telephone_b }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ isset($buy->email) ? $buy->email : $buy->email_b }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            ₡{{ number_format($buy->total_buy + $buy->total_delivery) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">₡{{ number_format($buy->total_delivery) }}</p>
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
                                            @if ($buy->approved == 0)
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
