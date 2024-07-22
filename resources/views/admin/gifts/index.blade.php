@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="font-title text-center">{{ __('Adminsitra las tarjetas de regalo desde acá') }}</h1>
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
                    <table id="table" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Comporbante') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Para') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('De') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('E-mail') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Monto') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Saldo a favor') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Aprobado') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Código') }}</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    {{ __('Vigente') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gifts as $item)
                                <tr>
                                    <td class="align-middle">
                                        <center>

                                            <form style="display:inline" action="{{ url('delete-gift/' . $item->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-velvet text-white btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Eliminar tarjeta" data-container="body" data-animation="true"
                                                    type="submit"><i class="material-icons opacity-10">
                                                        delete
                                                    </i>
                                                </button>
                                            </form>
                                            <form style="display:inline"
                                                action="{{ url('approve-gift/' . $item->id . '/' . $item->approve) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <button class="btn btn-velvet text-white btn-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $item->approve == 1 ? 'Desaprobar tarjeta' : 'Aprobar tarjeta' }}"
                                                    data-container="body" data-animation="true" type="submit"> <i
                                                        class="material-icons opacity-10">
                                                        @if ($item->approve == 1)
                                                            cancel
                                                        @else
                                                            check_circle
                                                        @endif
                                                    </i>
                                                </button>
                                            </form>
                                        </center>

                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if ($item->image)
                                                    <a target="blank" data-fancybox="gallery"
                                                        href="{{ route('file', $item->image) }}">
                                                        <img src="{{ route('file', $item->image) }}"
                                                            class="img-fluid shadow border-radius-lg">
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
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
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
