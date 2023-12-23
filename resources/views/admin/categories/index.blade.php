@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title"><strong>Administra las categorías desde acá</strong></h2>
    </center>

    <div class="col-md-12">
        <a href="{{ url('add-category') }}" class="btn btn-velvet">Nueva Categoría</a>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
        @foreach ($categories as $category)
            <div class="col bg-transparent mb-5">
                <div class="card mb-4" data-animation="true">

                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <a class="d-block blur-shadow-image">
                            <img src="{{ asset('storage') . '/' . $category->image }}" alt="img-blur-shadow"
                                class="img-fluid shadow border-radius-lg w-100" style="height:300px;">
                        </a>
                        <div class="colored-shadow"
                            style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <center>
                            <div class="d-flex mt-n6 mx-auto">
                                <form name="delete-category{{ $category->id }}" id="delete-category{{ $category->id }}"
                                    method="post" action="{{ url('/delete-category/' . $category->id) }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button form="delete-category{{ $category->id }}" type="submit"
                                    onclick="return confirm('Deseas borrar esta categoría?')"
                                    class="btn btn-link text-velvet me-auto border-0" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Delete">
                                    <i class="material-icons text-lg">delete</i>
                                </button>
                                <a class="btn btn-link text-velvet me-auto border-0"
                                    href="{{ url('/edit-category') . '/' . $category->id }}" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Edit">
                                    <i class="material-icons text-lg">edit</i>
                                </a>
                                <a class="btn btn-link text-velvet me-auto border-0"
                                    href="{{ url('/add-item') . '/' . $category->id }}" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" title="Lista De Prendas">
                                    <i class="material-icons text-lg">format_list_bulleted</i>
                                </a>

                            </div>
                        </center>

                        <h5 class="font-weight-normal mt-3">
                            <a href="javascript:;">{{ $category->name }}</a>
                        </h5>
                        <p class="mb-0">
                            {{ $category->description }}
                        </p>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer d-flex">
                        <p class="font-weight-normal my-auto">Velvet Boutique</p>
                        <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">place</i>
                        <p class="text-sm my-auto"> Grecia, Alajuela, Costa Rica</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <center>
        <div class="container">
            {{ $categories ?? ('')->links('pagination::simple-bootstrap-4') }}
        </div>
    </center>
@endsection
