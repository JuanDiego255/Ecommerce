@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('insert-clothing') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div @if ($tenantinfo->manage_size != 0 || $tenantinfo->kind_business == 1) class="col-md-6" @else class="col-md-12" @endif>
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark"> {{ __('Agregar nuevo ') }}
                            {{ $tenantinfo->kind_business == 1 ? 'vehículo' : 'producto' }}</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label> {{ __('Categoría') }}</label>
                                    <input readonly value="{{ $category_name }}" type="text"
                                        class="form-control form-control-lg" name="category">
                                    <input type="hidden" value="{{ $id }}" name="category_id" id="category_id">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label> {{ $tenantinfo->kind_business == 1 ? 'Vehículo' : 'Producto' }}</label>
                                    <input required type="text" class="form-control form-control-lg" name="name"
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'fragsperfumecr')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label> {{ __('Casa') }}</label>
                                        <input type="text" class="form-control form-control-lg" name="casa"
                                            value="{{ old('casa') }}">
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch col-md-6">
                                    <input value="1" checked class="form-check-input" type="checkbox"
                                        name="manage_stock" id="manage_stock">
                                    <label class="form-check-label" for="manage_stock">{{ __('Maneja Stock') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>
                                        @if ($tenantinfo->manage_size != 0)
                                            {{ __('Stock (Inventario, los atributos alimentan el stock)') }}
                                        @else
                                            {{ __('Stock (Inventario)') }}
                                        @endif
                                    </label>
                                    <input required type="number" class="form-control form-control-lg" name="stock"
                                        id="stock" value="{{ old('stock') }}">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>
                                            {{ __('SKU o Código (Si dejas el campo en blanco o el código digitado ya existe, el sistema sugerirá un código random, compuesto por la letra P indicando que es un producto, y una secuencia de 13 números aleatorios)') }}</label>
                                        <input type="text" placeholder="Puedes digitar el código deseado..."
                                            class="form-control form-control-lg" name="code"
                                            value="{{ old('code') }}">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <textarea id="editor" type="text" class="form-control form-control-lg" name="description"
                                        placeholder="Descripción del {{ $tenantinfo->kind_business == 1 ? 'vehículo' : 'producto' }}">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label> {{ __('Precio') }}</label>
                                    <input required type="number" class="form-control form-control-lg" name="price"
                                        value="{{ old('price') }}">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Precio al por mayor') }}</label>
                                        <input type="number" class="form-control form-control-lg" name="mayor_price"
                                            value="{{ old('mayor_price') }}">
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Descuento (%) Opcional') }}</label>
                                    <input type="number" class="form-control form-control-lg" name="discount"
                                        value="{{ old('discount') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Imagenes') }}
                                        {{ isset($tenantinfo->tenant) && $tenantinfo->tenant === 'marylu' ? '(Selecciona gran cantidad de imagenes, y crea productos masívamente si así lo deseas.)' : '(Máximo 4)' }}</label>
                                    <input multiple required class="form-control form-control-lg" type="file"
                                        name="images[]">
                                </div>
                            </div>
                            @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business == 1)
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Imagen horizontal') }}</label>
                                        <input required class="form-control form-control-lg" type="file"
                                            name="horizontal_image">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Imagen Principal') }}</label>
                                        <input required class="form-control form-control-lg" type="file"
                                            name="main_image">
                                    </div>
                                </div>
                            @endif

                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'marylu')
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Crear productos masivamente?') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="masive"
                                            name="masive" {{ old('masive') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Crear</label>
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->kind_business) &&
                                    ($tenantinfo->kind_business == 2 ||
                                        $tenantinfo->kind_business == 3 ||
                                        $tenantinfo->tenant === 'muebleriasarchi'))
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Se puede comprar?') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="can_buy"
                                            name="can_buy" {{ old('can_buy') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customCheck1">Producto de compra</label>
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'solociclismocrc')
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Producto contrapedido') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_contra_pedido"
                                            name="is_contra_pedido" {{ old('is_contra_pedido') ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="customCheck1">{{ __('¿Es producto contrapedido?') }}</label>
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Es tendencia?') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="trending"
                                            name="trending" {{ old('trending') ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="customCheck1">{{ __('Tendencia') }}</label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label
                                        class="form-label">{{ __('Meta Keywords (Opcional - Presione enter para agregar la palabra clave)') }}</label><br>
                                    <div class="tags-input">
                                        <ul id="tags">
                                            @if (old('meta_keywords'))
                                                @foreach (explode(',', old('meta_keywords')) as $keyword)
                                                    <li>{{ $keyword }}<span class="tag-remove"
                                                            onclick="removeTag(this)">&times;</span></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                        <input type="text" id="input-tag" placeholder="Escriba la palabra clave.." />
                                        <input type="hidden" value="{{ old('meta_keywords') }}" id="meta_keywords"
                                            name="meta_keywords">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-accion">{{ __('Agregar producto') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div @if ($tenantinfo->manage_size != 0) class="col-md-6" @else class="d-none" @endif>
                @if (count($attributes) > 0)
                    <div class="surface p-3 mt-3">
                        @include('admin.clothing.partials._variant-builder')
                    </div>
                @endif
            </div>
            <div @if ($tenantinfo->kind_business == 1) class="col-md-5" @else class="d-none" @endif>
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Especificaciones') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('distancia al suelo (mm)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="distancia_suelo"
                                        value="{{ old('distancia_suelo') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Peso (Kg)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="peso"
                                        value="{{ old('peso') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Color') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="color"
                                        value="{{ old('color') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Modelo o Año') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="modelo"
                                        value="{{ old('modelo') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Kilometraje MI') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="kilometraje"
                                        value="{{ old('kilometraje') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Capacidad del tanque (L)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="capacidad_tanque"
                                        value="{{ old('capacidad_tanque') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Tipo combustible') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="combustible"
                                        value="{{ old('combustible') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Motor (CC)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="motor"
                                        value="{{ old('motor') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Puertas') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="potencia"
                                        value="{{ old('potencia') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Pasajeros') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="pasajeros"
                                        value="{{ old('pasajeros') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Llantas') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="llantas"
                                        value="{{ old('llantas') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Tracción') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="traccion"
                                        value="{{ old('traccion') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Transmisión') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="transmision"
                                        value="{{ old('transmision') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Largo (mm)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="largo"
                                        value="{{ old('largo') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Ancho (mm)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="ancho"
                                        value="{{ old('ancho') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('add-item/' . $category_id) }}" class="btn btn-accion w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/variant-builder.js') }}"></script>
    <script src="{{ asset('js/add-tag.js') }}"></script>
    <script>
        $(document).ready(function () {
            const manageStockCheckbox = document.getElementById('manage_stock');
            const stockQuantityField  = document.getElementById('stock');

            ClassicEditor.create(document.querySelector('#editor'))
                .catch(function (error) { console.error(error); });

            function toggleStockQuantityField() {
                if (manageStockCheckbox.checked) {
                    if (typeof vbSetAllStock === 'function') {
                        document.querySelectorAll('input[name^="cantidades_attr["]').forEach(function (inp) {
                            if (parseInt(inp.value) < 0) inp.value = 0;
                        });
                    }
                    stockQuantityField.removeAttribute('disabled');
                } else {
                    Swal.fire({
                        title: 'Aviso!',
                        html: 'Si deshabilitas el stock se colocará −1 en la cantidad de cada variante.',
                        icon: 'info', showCloseButton: true, focusConfirm: false,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Entendido!'
                    });
                    if (typeof vbSetAllStock === 'function') vbSetAllStock(-1);
                    stockQuantityField.setAttribute('disabled', 'disabled');
                }
            }
            manageStockCheckbox.addEventListener('change', toggleStockQuantityField);
        });
    </script>
@endsection
