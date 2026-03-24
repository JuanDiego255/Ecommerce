@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Redes Sociales</li>
@endsection
@section('content')
@include('admin.social.add')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">share</span></div>
        <span class="card-h-title">Redes Sociales
            <span style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($social) }})</span>
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
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-size-modal"
                class="act-btn ab-add" title="Nueva imagen">
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
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('Imagen') }}</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('URL') }}</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($social as $item)
                    <tr>
                        <td>
                            <a target="blank" data-fancybox="gallery"
                                href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                    class="img-fluid shadow border-radius-lg" style="width:60px;height:60px;object-fit:cover;border-radius:10px;" loading="lazy">
                            </a>
                        </td>
                        <td class="text-xxs">
                            <p class="font-weight-bold mb-0">{{ $item->url }}</p>
                        </td>
                        <td class="align-middle">
                            <div class="act-group">
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#edit-social-modal{{ $item->id }}"
                                    class="act-btn ab-neutral" title="Editar">
                                    <span class="material-icons">edit</span>
                                </button>
                                <form method="post" action="{{ url('/delete/social/' . $item->id) }}"
                                    id="delete-social{{ $item->id }}" style="display:inline">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button type="button" class="act-btn ab-del" title="Borrar"
                                    onclick="confirmDelete('delete-social{{ $item->id }}', '¿Deseas borrar esta red social?')">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                            @include('admin.social.edit')
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
