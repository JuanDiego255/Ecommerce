@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Anuncios</li>
@endsection
@section('content')
@include('admin.adverts.add')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">campaign</span></div>
        <span class="card-h-title">Anuncios
            <span style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($adverts) }})</span>
        </span>
        <div class="card-h-actions">
            <input value="" placeholder="Buscar..." type="text"
                class="filter-input" name="searchfor" id="searchfor"
                style="width:180px;padding:6px 12px;font-size:.8rem;">
            <select id="recordsPerPage" name="recordsPerPage" class="filter-input" style="width:120px;padding:6px 10px;font-size:.8rem;">
                <option value="5">5 registros</option>
                <option value="10">10 registros</option>
                <option selected value="15">15 registros</option>
                <option value="50">50 registros</option>
            </select>
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-advert-modal"
                class="act-btn ab-add" title="Nuevo anuncio">
                <span class="material-icons">add</span>
            </button>
        </div>
    </div>
</div>

<div class="card p-2">
    <div class="table-responsive">
        <table id="table" class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('Sección') }}</th>
                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('Anuncio') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adverts as $item)
                    <tr>
                        <td class="align-middle text-center">
                            <form method="post" action="{{ url('/delete/advert/' . $item->id) }}"
                                id="delete-advert{{ $item->id }}" style="display:inline">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <div class="act-group" style="justify-content:center;">
                                <button type="button" class="act-btn ab-del" title="Borrar"
                                    onclick="confirmDelete('delete-advert{{ $item->id }}', '¿Deseas borrar este anuncio?')">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                        </td>
                        <td class="align-middle text-xxs text-center">
                            <p class="font-weight-bold mb-0">{{ $item->section }}</p>
                        </td>
                        <td class="align-middle text-xxs text-center">
                            <p class="font-weight-bold mb-0">{{ $item->content }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
    <script>
    function confirmDelete(formId, message) {
        Swal.fire({
            title: 'Confirmación',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#ff3b30',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
