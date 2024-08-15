@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mb-4">
        <div class="breadcrumb-nav bc3x mt-4">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a href="#"><i class="fas fa-gift me-1"></i>Mis tarjetas de regalo</a>
            </li>
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
                                    Para</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    De</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    E-mail (Destinatario)</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Monto</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Saldo a favor</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Aprobado</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Código</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Vigente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gifts as $item)
                                <tr>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ $item->for }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ $item->by }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ $item->email }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            ₡{{ number_format($item->mount) }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            ₡{{ number_format($item->credit) }}</p>
                                    </td>                                    
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @if ($item->approve == 0)
                                                Pendiente
                                            @else
                                                Aprobado
                                            @endif
                                        </p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            {{ $item->code }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @if ($item->status == 0)
                                                Cancelado
                                            @else
                                                Vigente
                                            @endif
                                        </p>
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
