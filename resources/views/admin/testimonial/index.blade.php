@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Testimonios</li>
@endsection
@section('content')
    <div class="d-flex gap-2 mb-0">
        <a href="{{ url('comments/add') }}" class="btn btn-primary btn-sm">
            <span class="material-icons">add</span> {{ __('Agregar nuevo testimonio') }}
        </a>
    </div>
    <div class="s-card">
        <div class="s-card-header">
            <div class="card-h-icon"><span class="material-icons">tune</span></div>
            <span class="card-h-title">Filtros</span>
        </div>
        <div class="s-card-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
            <div>
                <label class="filter-label">Buscar</label>
                <input value="" placeholder="Escribe para filtrar..." type="text"
                    class="filter-input" name="searchfor" id="searchfor">
            </div>
            <div>
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                    <option value="5">5 registros</option>
                    <option value="10">10 registros</option>
                    <option selected value="15">15 registros</option>
                    <option value="50">50 registros</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Nombre') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Descripción') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Aprobar') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Rating') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $item)
                                <tr>
                                    <td class="align-middle text-center">
                                        <form name="delete-comment{{ $item->id }}"
                                            id="delete-comment{{ $item->id }}" method="post"
                                            action="{{ url('/delete-comments/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-comment{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este testimonio?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/comments/' . $item->id . '/edit') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery" href="#">
                                                    @if ($item->image)
                                                        <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                            class="avatar avatar-md me-3">
                                                    @else
                                                        <img src="{{ url('images/sin-foto.PNG') }}"
                                                            class="avatar avatar-md me-3">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0 text-lg">{!! $item->name !!}</h4>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->description }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <form name="formApprove{{ $item->id }}" id="formApprove" method="post"
                                            action="{{ url('approve-comment/' . $item->id) }}" style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkApprove">
                                                <div class="form-check">
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input id="checkApprove" class="form-check-input" type="checkbox"
                                                        value="1" name="approve"
                                                        onchange="submitForm('formApprove{{ $item->id }}')"
                                                        {{ $item->approve == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>

                                        </form>
                                    </td>
                                    <td class="text-xxs">
                                        <div class="col">
                                            <div class="rated">
                                                @for ($i = 1; $i <= $item->stars; $i++)
                                                    {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                    <label class="star-rating-complete" title="text">{{ $i }}
                                                        stars</label>
                                                @endfor
                                            </div>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')   
    <script>
        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }       
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
