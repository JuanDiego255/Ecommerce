@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Testimonios</li>
@endsection
@section('content')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">star</span></div>
        <span class="card-h-title">Testimonios
            <span style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($comments) }})</span>
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
            <a href="{{ url('comments/add') }}" class="act-btn ab-add" title="Agregar nuevo testimonio">
                <span class="material-icons">add</span>
            </a>
        </div>
    </div>
</div>

<div class="card p-2">
    <div class="table-responsive">
        <table class="table align-items-center mb-0" id="table">
            <thead>
                <tr>
                    <th class="text-secondary font-weight-bolder opacity-7">{{ __('Acciones') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Descripción') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Aprobar') }}</th>
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Rating') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comments as $item)
                    <tr>
                        <td class="align-middle">
                            <form name="delete-comment{{ $item->id }}" id="delete-comment{{ $item->id }}"
                                method="post" action="{{ url('/delete-comments/' . $item->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <div class="act-group">
                                <a class="act-btn ab-neutral" href="{{ url('/comments/' . $item->id . '/edit') }}" title="Editar">
                                    <span class="material-icons">edit</span>
                                </a>
                                <button type="button" class="act-btn ab-del" title="Eliminar"
                                    onclick="confirmDelete('delete-comment{{ $item->id }}', '¿Deseas borrar este testimonio?')">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                        </td>
                        <td class="w-50">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    @if ($item->image)
                                        <img src="{{ route('file', $item->image) }}" class="avatar avatar-md me-3" loading="lazy">
                                    @else
                                        <img src="{{ url('images/sin-foto.PNG') }}" class="avatar avatar-md me-3">
                                    @endif
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h4 class="mb-0 text-lg">{!! $item->name !!}</h4>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <p class="font-weight-bold mb-0">{{ $item->description }}</p>
                        </td>
                        <td class="align-middle text-center">
                            <form name="formApprove{{ $item->id }}" id="formApprove{{ $item->id }}" method="post"
                                action="{{ url('approve-comment/' . $item->id) }}" style="display:inline">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input class="form-check-input" type="checkbox"
                                    value="1" name="approve"
                                    onchange="submitForm('formApprove{{ $item->id }}')"
                                    {{ $item->approve == 1 ? 'checked' : '' }}>
                            </form>
                        </td>
                        <td class="text-xxs">
                            <div class="rated">
                                @for ($i = 1; $i <= $item->stars; $i++)
                                    <label class="star-rating-complete" title="text">{{ $i }} stars</label>
                                @endfor
                            </div>
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
    function submitForm(alias) {
        var form = document.querySelector('form[name="' + alias + '"]');
        form.submit();
    }
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
