@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Redes Sociales</li>
@endsection
@section('content')
    <div class="d-flex gap-2 mb-0">
        <button type="button" data-bs-toggle="modal" data-bs-target="#add-size-modal"
            class="btn btn-primary btn-sm">
            <span class="material-icons">add</span> {{ __('Nueva imagen') }}
        </button>
    </div>
    @include('admin.social.add')
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

        <div class="card p-2">
            <div class="table-responsive">
                <table id="table" class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Imagen') }}</th>{{-- 
                            <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Descripción') }}</th> --}}
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('URL') }}</th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                {{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($social as $item)
                            <tr>
                                <td class="">
                                    <a target="blank" data-fancybox="gallery"
                                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                        <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                            class="text-center img-fluid shadow border-radius-lg w-25"></a>
                                </td>{{-- 
                                <td class="align-middle text-xxs text-center">
                                    <p class=" font-weight-bold mb-0">{{ $item->description }}</p>
                                </td> --}}
                                <td class=" text-xxs">
                                    <p class=" font-weight-bold mb-0">{{ $item->url }}</p>
                                </td>

                                <td class="align-middle">
                                    <center>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-social-modal{{ $item->id }}"
                                            class="btn btn-outline-accion" style="text-decoration: none;">Editar</button>

                                        <form method="post" action="{{ url('/delete/social/' . $item->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar esta talla?')"
                                                data-bs-target="#edit-social-modal{{ $item->id }}"
                                                class="btn btn-outline-accion"
                                                style="text-decoration: none;">Borrar</button>
                                        </form>
                                    </center>

                                </td>
                                @include('admin.social.edit')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
