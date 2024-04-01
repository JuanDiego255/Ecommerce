@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="text-dark">Editar Producto</h4>
        </div>
        <div class="card-body">
            <form action="{{ url('update-clothing' . '/' . $clothing->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Producto</label>
                            <input required value="{{ $clothing->name }}" type="text"
                                class="form-control form-control-lg" name="name">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Código</label>
                            <input required value="{{ $clothing->code }}" type="text" class="form-control form-control-lg" name="code">
                        </div>
                    </div>
                    <input type="hidden" name="category_id" value="{{ $clothing->category_id }}">
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Descripción</label><br>
                            <input required value="{{ $clothing->description }}" type="text"
                                class="form-control form-control-lg" name="description">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Price</label>
                            <input required type="text" value="{{ $clothing->price }}"
                                class="form-control form-control-lg" name="price">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Descuento (%)</label>
                            <input type="number" value="{{ $clothing->discount }}" class="form-control form-control-lg"
                                name="discount">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-static mb-4">
                            <label>Stock (El dato que se ingresa aumenta el stock ya existente en las tallas seleccionadas,
                                siempre y cuando este sea 0)</label>
                            <input min="1" required
                                value="{{ $clothing->total_stock == 0 ? '1' : $clothing->total_stock }}" type="number"
                                class="form-control form-control-lg" name="stock">
                        </div>
                    </div>
                                     
                    <div class="col-md-12 mb-3">
                        <label
                            class="control-label control-label text-formulario {{ $errors->has('sizes_id[]') ? 'is-invalid' : '' }}"
                            for="sizes_id[]">Tallas</label><br>
                        @foreach ($sizes as $size)
                            <div class="form-check form-check-inline">
                                <input name="sizes_id[]" class="form-check-input mb-2"
                                    {{ $size_active->contains('size_id', $size->id) ? 'checked' : '' }} type="checkbox"
                                    value="{{ $size->id }}" id="size_{{ $size->id }}">
                                <label class="form-check-label table-text mb-2" for="size_{{ $size->id }}">
                                    {{ $size->size }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Es Tendencia?</label>
                        <div class="form-check">
                            <input {{ $clothing->trending == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox"
                                value="1" id="trending" name="trending">
                            <label class="custom-control-label" for="customCheck1">Trending</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        @if ($clothing->image)
                        <img class="img-fluid img-thumbnail" src="{{ route('file',$clothing->image) }}"
                            style="width: 150px; height:150px;" alt="image">
                    @endif
                        <label>Imágenes (Máximo 4)</label>
                        <div class="input-group input-group-static mb-4">
                            <input multiple class="form-control form-control-lg" type="file" name="images[]">
                        </div>
                    </div>

                </div>

                <div class="col-md-12 mt-3 text-center">
                    <button type="submit" class="btn btn-velvet">Editar Producto</button>
                </div>

            </form>
        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('add-item/' . $category_id) }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
