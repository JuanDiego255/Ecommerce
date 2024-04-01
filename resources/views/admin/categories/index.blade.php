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
        @foreach ($categories as $item)
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="product-grid product_data">
                    <div class="product-image">
                    
                        <img src="{{ route('file',$item->image) }}">
                        <ul class="product-links">
                            <li><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar" target="blank"
                                    href="{{ url('/edit-category') . '/' . $item->id }}"><i class="fa fa-pencil"></i></a>
                            </li>

                            <form name="delete-category{{ $item->id }}" id="delete-category{{ $item->id }}"
                                method="post" action="{{ url('/delete-category/' . $item->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"
                                    href="javascript:void(0);" onclick="submitForm({{ $item->id }})">
                                    <i class="fa fa-times"></i>
                                </a>
                            </li>
                        </ul>
                        <a href="{{ url('/add-item') . '/' . $item->id }}" class="add-to-cart">Ver Colección</a>
                    </div>
                    <div class="product-content">
                        <h3 class="text-muted"><a href="{{ url('/add-item') . '/' . $item->id }}">{{ $item->name }}</a>
                        </h3>
                        <h5 class="text-muted-normal">{{ $item->description }}
                        </h5>
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
@section('script')
    <script>
        function submitForm(itemId) {
            var form = document.getElementById('delete-category' + itemId);
            var confirmDelete = confirm('Deseas borrar esta categoría?');

            if (confirmDelete) {
                form.submit();
            }
        }
    </script>
@endsection
