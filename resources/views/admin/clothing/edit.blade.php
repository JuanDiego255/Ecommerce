@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $stock_array = $stocks;
@endphp
@section('content')
    <form id="updateForm" action="{{ url('update-clothing' . '/' . $clothing->id) }}" method="POST"
        enctype="multipart/form-data">
        <input type="hidden" name="category_id_main" value="{{ $category_id }}">
        <input type="hidden" name="manage_size" id="manage_size" value="{{ $tenantinfo->manage_size }}">
        @csrf
        <div class="row">
            <div @if ($tenantinfo->manage_size != 0 || $tenantinfo->kind_business == 1) class="col-md-6" @else class="col-md-12" @endif>
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">{{ __('Editar producto') }}</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <div class="input-group input-group-static">
                                    <label>{{ __('Categorías (Puede seleccionar múltiples categorías para el producto)') }}</label>
                                    <select id="category_id" name="category_id[]"
                                        class="form-control form-control-lg @error('category_id') is-invalid @enderror"
                                        autocomplete="category_id" autofocus multiple>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                {{ in_array($item->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $item->name }}
                                                {{ $item->department != 'Default' ? '- ' . $item->department : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Producto') }}</label>
                                    <input required value="{{ $clothing->name }}" type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'fragsperfumecr')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Casa') }}</label>
                                        <input type="text" value="{{ $clothing->casa }}"
                                            class="form-control form-control-lg" name="casa">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Código') }}</label>
                                    <input required value="{{ $clothing->code }}" type="text"
                                        class="form-control form-control-lg" name="code">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">

                                <label>{{ __('Descripción') }}</label><br>
                                <textarea id="editor" type="text" class="form-control form-control-lg" name="description">{!! $clothing->description !!}</textarea>
                            </div>

                            <input type="hidden" name="clothing_id" id="clothing_id" value="{{ $clothing->id }}">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Precio') }}</label>
                                    <input required type="number" value="{{ $clothing->price }}"
                                        class="form-control form-control-lg" name="price">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Precio al por mayor') }}</label>
                                        <input required type="number" value="{{ $clothing->mayor_price }}"
                                            class="form-control form-control-lg" name="mayor_price">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Descuento (%)') }}</label>
                                    <input type="number" value="{{ $clothing->discount }}"
                                        class="form-control form-control-lg" name="discount">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch col-md-6">
                                    <input value="1" @if ($clothing->manage_stock == 1) checked @endif
                                        class="form-check-input" type="checkbox" name="manage_stock" id="manage_stock">
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
                                    <input id="stock" @if ($clothing->manage_stock == 1) min="1" @endif  required
                                        value="{{ $clothing->total_stock == 0 ? '1' : $clothing->total_stock }}"
                                        type="number" class="form-control form-control-lg" name="stock">
                                </div>
                            </div>

                            @if (isset($tenantinfo->kind_business) &&
                                    ($tenantinfo->kind_business == 2 ||
                                        $tenantinfo->kind_business == 3 ||
                                        $tenantinfo->tenant === 'muebleriasarchi'))
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Se puede comprar?') }}</label>
                                    <div class="form-check">
                                        <input {{ $clothing->can_buy == 1 ? 'checked' : '' }} class="form-check-input"
                                            type="checkbox" value="1" id="can_buy" name="can_buy">
                                        <label class="custom-control-label" for="customCheck1">Producto de compra</label>
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'solociclismocrc')
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Producto contrapedido') }}</label>
                                    <div class="form-check">
                                        <input {{ $clothing->is_contra_pedido == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1"
                                            id="is_contra_pedido" name="is_contra_pedido"
                                            {{ old('is_contra_pedido') ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="customCheck1">{{ __('¿Es producto contrapedido?') }}</label>
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Es tendencia?') }}</label>
                                    <div class="form-check">
                                        <input {{ $clothing->trending == 1 ? 'checked' : '' }} class="form-check-input"
                                            type="checkbox" value="1" id="trending" name="trending">
                                        <label class="custom-control-label"
                                            for="customCheck1">{{ __('Tendencia') }}</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-3">
                                <label>{{ __('Imagenes (Max 4)') }}</label>
                                <div id="img-current" class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach(\App\Models\ProductImage::where('clothing_id', $clothing->id)->get() as $pi)
                                        <img src="{{ route('file', $pi->image) }}" loading="lazy"
                                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1.5px solid var(--gray1);">
                                    @endforeach
                                </div>
                                <div class="input-group input-group-static mb-2">
                                    <input multiple class="form-control form-control-lg" type="file"
                                        name="images[]" id="img-input" accept="image/*">
                                </div>
                                <div id="img-preview" class="d-flex flex-wrap gap-2 mt-1"></div>
                            </div>
                            @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business == 1)
                                @if ($clothing->horizontal_image)
                                    <img class="img-fluid img-thumbnail"
                                        src="{{ route('file', $clothing->horizontal_image) }}"
                                        style="width: 150px; height:150px;" alt="image">
                                @endif
                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Imagen horizontal') }}</label>
                                    <div class="input-group input-group-static mb-4">
                                        <input class="form-control form-control-lg" type="file"
                                            name="horizontal_image">
                                    </div>
                                </div>
                                @if ($clothing->main_image)
                                    <img class="img-fluid img-thumbnail" src="{{ route('file', $clothing->main_image) }}"
                                        style="width: 150px; height:150px;" alt="image">
                                @endif
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>{{ __('Imagen Principal') }}</label>
                                        <input required class="form-control form-control-lg" type="file"
                                            name="main_image">
                                    </div>
                                </div>
                            @endif
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                                <div class="col-md-12 mb-3">

                                    <label>{{ __('Meta Keywords (Opcional - Presione enter para agregar la palabra clave)') }}</label><br>
                                    <div class="tags-input">
                                        <ul id="tags"></ul>
                                        <input type="text" id="input-tag" placeholder="Escriba la palabra clave.." />
                                    </div>
                                    <input id="meta_keywords" type="hidden" name="meta_keywords"
                                        value="{{ $clothing->meta_keywords }}">

                                </div>
                            @endif

                        </div>

                        <div class="col-md-12 mt-3 text-center">
                            <button type="submit" class="btn btn-accion">{{ __('Guardar cambios') }}</button>
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
                                        value="{{ isset($details->distancia_suelo) ? $details->distancia_suelo : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Peso (Kg)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="peso"
                                        value="{{ isset($details->peso) ? $details->peso : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Color') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="color"
                                        value="{{ isset($details->color) ? $details->color : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Modelo o Año') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="modelo"
                                        value="{{ isset($details->modelo) ? $details->modelo : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Kilometraje MI') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="kilometraje"
                                        value="{{ isset($details->kilometraje) ? $details->kilometraje : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Capacidad del tanque (L)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="capacidad_tanque"
                                        value="{{ isset($details->kilometraje) ? $details->kilometraje : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Tipo combustible') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="combustible"
                                        value="{{ isset($details->combustible) ? $details->combustible : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Motor (CC)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="motor"
                                        value="{{ isset($details->motor) ? $details->motor : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Puertas') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="potencia"
                                        value="{{ isset($details->potencia) ? $details->potencia : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Pasajeros') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="pasajeros"
                                        value="{{ isset($details->pasajeros) ? $details->pasajeros : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Llantas') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="llantas"
                                        value="{{ isset($details->llantas) ? $details->llantas : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Tracción') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="traccion"
                                        value="{{ isset($details->traccion) ? $details->traccion : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Transmisión') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="transmision"
                                        value="{{ isset($details->transmision) ? $details->transmision : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Largo (mm)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="largo"
                                        value="{{ isset($details->largo) ? $details->largo : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>{{ __('Ancho (mm)') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="ancho"
                                        value="{{ isset($details->ancho) ? $details->ancho : '' }}">
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
    <script src="{{ asset('js/edit-tag.js') }}"></script>
    <script src="{{ asset('js/variant-builder.js') }}"></script>
    <script>
        $(document).ready(function () {
            const manageStockCheckbox = document.getElementById('manage_stock');
            const stockQuantityField  = document.getElementById('stock');

            ClassicEditor.create(document.querySelector('#editor'))
                .catch(function (error) { console.error(error); });

            function toggleStockQuantityField() {
                if (manageStockCheckbox.checked) {
                    if (typeof vbRestoreStock === 'function') vbRestoreStock();
                    Swal.fire({
                        title: 'Aviso!',
                        html: 'Se restauraron los valores de stock anteriores.',
                        icon: 'info', showCloseButton: true, focusConfirm: false,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Entendido!'
                    });
                    stockQuantityField.removeAttribute('disabled');
                } else {
                    Swal.fire({
                        title: 'Aviso!',
                        html: 'Se establecerá −1 en el stock de cada variante.',
                        icon: 'info', showCloseButton: true, focusConfirm: false,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Entendido!'
                    });
                    if (typeof vbSetAllStock === 'function') vbSetAllStock(-1);
                    stockQuantityField.setAttribute('disabled', 'disabled');
                }
            }
            manageStockCheckbox.addEventListener('change', toggleStockQuantityField);

            /* Image preview */
            document.getElementById('img-input').addEventListener('change', function () {
                var preview = document.getElementById('img-preview');
                preview.innerHTML = '';
                Array.from(this.files).forEach(function (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--blue);';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

            /* Unsaved changes guard */
            var isDirty = false;
            document.querySelectorAll('#updateForm input, #updateForm textarea, #updateForm select')
                .forEach(function (el) {
                    el.addEventListener('change', function () { isDirty = true; });
                    el.addEventListener('input',  function () { isDirty = true; });
                });
            document.getElementById('updateForm').addEventListener('submit', function () { isDirty = false; });
            window.addEventListener('beforeunload', function (e) {
                if (isDirty) { e.preventDefault(); e.returnValue = ''; }
            });
        });
    </script>
@endsection
