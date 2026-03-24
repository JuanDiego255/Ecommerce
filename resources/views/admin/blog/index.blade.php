@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Blog</li>
@endsection
@section('content')
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">article</span></div>
        <span class="card-h-title">Blog
            <span id="blog-count" style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($blogs) }})</span>
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
            <a href="{{ url('blog/agregar') }}" class="act-btn ab-add" title="Agregar nuevo blog">
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
                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Blog') }}</th>
                    <th class="text-center text-secondary font-weight-bolder opacity-7">{{ __('Fecha Post') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blogs as $item)
                    <tr>
                        <td class="align-middle">
                            <form name="delete-blog{{ $item->id }}" id="delete-blog{{ $item->id }}"
                                method="post" action="{{ url('/blog/' . $item->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <div class="act-group">
                                <a class="act-btn ab-neutral" href="{{ url('/blog-edit/' . $item->id . '/edit') }}" title="Editar">
                                    <span class="material-icons">edit</span>
                                </a>
                                <a class="act-btn ab-neutral" href="{{ url('/blog-show/' . $item->id . '/show') }}" title="Ver artículos">
                                    <span class="material-icons">visibility</span>
                                </a>
                                <a class="act-btn ab-neutral" href="{{ url('/blog-cards/' . $item->id . '/view-cards') }}" title="Ver tarjetas">
                                    <span class="material-icons">book</span>
                                </a>
                                @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                    <a class="act-btn ab-neutral" href="{{ url('/results/' . $item->id) }}" title="Ver resultados">
                                        <span class="material-icons">medical_services</span>
                                    </a>
                                @endif
                                <button type="button" class="act-btn ab-del" title="Eliminar"
                                    onclick="confirmDelete('delete-blog{{ $item->id }}', '¿Deseas borrar este blog?')">
                                    <span class="material-icons">delete</span>
                                </button>
                            </div>
                        </td>
                        <td class="w-50">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <a target="blank" data-fancybox="gallery"
                                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                        <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                            class="avatar avatar-md me-3" loading="lazy">
                                    </a>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h4 class="mb-0 text-lg">{!! $item->title !!}</h4>
                                    <p>{!! $item->autor !!}</p>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <p class="text-success mb-0">{{ $item->fecha_post }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
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
@endsection
