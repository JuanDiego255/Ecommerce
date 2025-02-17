@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mb-4">
        <div class="breadcrumb-nav bc3x mt-4">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Historial De pedidos</a></li>
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
                        <option value="10">10 Registros</option>
                        <option selected value="15">15 Registros</option>
                        <option value="50">50 Registros</option>
                    </select>

                </div>
            </div>
        </div>
        <center>
            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="table" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Precio @if ($iva > 0)
                                        (IVA)
                                    @endif + Envío</th>
                                @if ($iva)
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                        IVA</th>
                                @endif

                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Envío</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Entregado</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Aprobado</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Compra</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Número de guía</th>
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
                                        <p class=" font-weight-bold mb-0">
                                            ₡{{ number_format($buy->total_buy + $buy->total_delivery) }}</p>
                                    </td>
                                    @if ($iva)
                                        <td class="align-middle text-xxs text-center">
                                            <p class=" font-weight-bold mb-0">₡{{ number_format($buy->total_iva) }}</p>
                                        </td>
                                    @endif

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
                                        <a class="text-info" href="https://correos.go.cr/rastreo/" target="blank"><p class=" font-weight-bold mb-0">{{ $buy->guide_number }}</p></a>
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

                                            {{-- <form method="post"
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
                                            </form> --}}
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
    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
