@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Tarjetas de regalo</li>
@endsection
@section('content')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">filter_list</span></div>
        <span class="card-h-title">Filtros</span>
    </div>
    <div class="s-card-body" style="display:grid;grid-template-columns:1fr 180px;gap:12px;">
        <div>
            <label class="filter-label">Filtrar</label>
            <input value="" placeholder="Escribe para filtrar...." type="text"
                class="filter-input" name="searchfor" id="searchfor">
        </div>
        <div>
            <label class="filter-label">Mostrar</label>
            <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                <option value="5">5 Registros</option>
                <option value="10">10 Registros</option>
                <option selected value="15">15 Registros</option>
                <option value="50">50 Registros</option>
            </select>
        </div>
    </div>
</div>
    <div class="container">
        <div class="card p-2">
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

                                            <button class="btn btn-accion text-white btn-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar tarjeta" data-container="body"
                                                data-animation="true" type="submit"><i class="material-icons opacity-10">
                                                    delete
                                                </i>
                                            </button>
                                        </form>
                                        <form style="display:inline"
                                            action="{{ url('approve-gift/' . $item->id . '/' . $item->approve) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <button class="btn btn-accion text-white btn-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
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
                                                        style="width:60px;height:90px;object-fit:cover;border-radius:8px;border:1px solid var(--gray1);">
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
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
