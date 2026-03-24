@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Roles</li>
@endsection
@section('content')
@include('admin.roles.add')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">manage_accounts</span></div>
        <span class="card-h-title">Roles
            <span style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($roles) }})</span>
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
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-role-modal"
                class="act-btn ab-add" title="Nuevo rol">
                <span class="material-icons">add</span>
            </button>
        </div>
    </div>
</div>

<div class="card p-2">
    <div class="table-responsive">
        <table class="table align-items-center mb-0" id="table">
            <thead>
                <tr>
                    <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Rol') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7">{{ __('Estado') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $item)
                    <tr>
                        <td class="align-middle">
                            <form method="post" action="{{ url('/delete/role/' . $item->id) }}"
                                id="delete-role{{ $item->id }}" style="display:inline">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <div class="act-group">
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#edit-rol-modal{{ $item->id }}"
                                    class="act-btn ab-neutral" title="Editar">
                                    <span class="material-icons">edit</span>
                                </button>
                                <button type="button" class="act-btn ab-del" title="Borrar"
                                    onclick="confirmDelete('delete-role{{ $item->id }}', '¿Deseas borrar este rol?')">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                        </td>
                        <td class="align-middle text-sm">
                            <p class="text-success mb-0">{{ $item->rol }}</p>
                        </td>
                        <td class="align-middle text-sm">
                            <span class="s-pill {{ $item->status == 1 ? 'pill-green' : 'pill-gray' }}">
                                {{ $item->status == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        @include('admin.roles.edit')
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
