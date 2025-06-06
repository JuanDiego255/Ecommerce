@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('insert-clothing') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div @if ($tenantinfo->manage_size != 0 || $tenantinfo->kind_business == 1) class="col-md-7" @else class="col-md-12" @endif>
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
                            <button type="submit" class="btn btn-velvet">{{ __('Agregar producto') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div @if ($tenantinfo->manage_size != 0) class="col-md-4" @else class="d-none" @endif>
                @if (count($attributes) > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4 class="text-dark">{{ __('Atributos') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="input-group input-group-static">
                                        <label>{{ __('Seleccionar atributo (Los atributos se mostrarán en el orden que los agregues en esta sección)') }}</label>
                                        <select id="attr_id" name="attr_id"
                                            class="form-control form-control-lg @error('attr_id') is-invalid @enderror"
                                            autocomplete="attr_id" autofocus>
                                            <option selected value="0">{{ __('Sin atributos') }}</option>
                                            @foreach ($attributes as $key => $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('attr_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('attr_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="hidden_id"></div>
                            <div id="attr" class="row d-none">
                                <div class="col-md-12 mb-2">
                                    <div class="input-group input-group-static">
                                        <label>{{ __('Seleccionar valor') }}</label>
                                        <select id="value" name="value"
                                            class="form-control form-control-lg @error('value') is-invalid @enderror"
                                            autocomplete="value" autofocus>

                                        </select>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="divValue" class="row">

                            </div>
                        </div>
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
            <a href="{{ url('add-item/' . $category_id) }}" class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var defaultSelectedValue = $('#attr_id').val();
            var htmlHidden = "";
            const manageStockCheckbox = document.getElementById('manage_stock');
            const stockQuantityField = document.getElementById('stock');
            $('.size-checkbox').change(function(e) {
                var tallasSeleccionadas = [];
                var checkboxes = document.getElementsByClassName("size-checkbox");

                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        tallasSeleccionadas.push(checkboxes[i].value);
                    }
                }

                var html = "";
                tallasSeleccionadas.forEach(function(talla) {
                    var sizeName = $("label[for='size_" + talla + "']").text();
                    html += `
                        <label for="precio_${talla}">{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'Tallas' : 'Tamaños' }} ${sizeName}:</label><br>                       
                        <div class="col-md-6">
                            <div class="input-group input-group-static">                           
                                <input required type="text" value="0" class="form-control form-control-lg" id="precio_${talla}" name="precios[${talla}]" placeholder="Precio">                            
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <div class="input-group input-group-static">                           
                                <input required type="text" value="0" class="form-control form-control-lg" id="cantidad_${talla}" name="cantidades[${talla}]" placeholder="Cantidad">
                            </div>
                        </div>
                        
                    `;
                });
                document.getElementById("tallasSeleccionadas").innerHTML = html;
            });

            function getValues(id) {
                var select = document.getElementById("value");
                var element = document.getElementById("attr");
                var divValue = document.getElementById("divValue");
                var element_hidden = document.getElementById("hidden_id");

                if (id != "0") {
                    element_hidden.innerHTML = '';
                    var htmlHidden =
                        `<input required type="hidden" value="${id}" id="attr_id_hidden" name="attr_id_hidden">`;

                    select.innerHTML = '';
                    $.ajax({
                        method: "GET",
                        url: "/get-values/" +
                            id, // Cambia esto por la ruta que devuelve los elementos del carrito
                        success: function(values) {
                            // Recorrer los elementos


                            var option = document.createElement("option");
                            option.value = 0;
                            option.text = "Sin valor";
                            option.selected = true;

                            select.appendChild(option);
                            values.forEach(function(item, index) {
                                var option1 = document.createElement("option");
                                option1.value = item.id + "-" + item.main;
                                option1.text = item.value;
                                select.appendChild(option1);
                            });

                            element.classList.remove("d-none");
                            element_hidden.innerHTML += htmlHidden;
                            element.classList.add("d-block");


                        }
                    });
                } else {
                    select.innerHTML = '';
                    element_hidden.innerHTML = '';
                    divValue.innerHTML = '';
                    element.classList.remove("d-block");
                    element.classList.add("d-none");
                }

            }

            $('#attr_id').change(function(e) {
                var selectedValue = $(this).val();
                getValues(selectedValue);
            });

            $('#value').change(function(e) {
                var selectedValueComplete = $(this).val();
                var partes = selectedValueComplete.split("-");
                var selectedValue = partes[0];
                var main_attr = partes[1];
                var selectedText = $(this).find("option:selected").text();
                var attr_id_h = $('#attr_id_hidden').val();
                var attr_main_h = $('#attr_main_hidden').val();
                var precioId = "precio_attr" + selectedValue;
                var attr_id = "attr_id" + selectedValue;
                var value_id = "value_id" + selectedValue;
                var cantidadId = "cantidad_attr" + selectedValue;
                var containerId = "container_attr" + selectedValue; // Nuevo ID para el contenedor
                var no_main_text = " (No es un valor de un atributo principal, no permite el precio)"

                // Verificar si los elementos ya existen
                if (!document.getElementById(precioId) && !document.getElementById(cantidadId) && !document
                    .getElementById(attr_id) &&
                    selectedValue != "0") {

                    // Crear un contenedor para agrupar todos los elementos
                    var container = document.createElement("div");
                    container.id = containerId;
                    container.className = "attr-container row"; // Añadir clase "row" para estilo de filas

                    var hiddenInput = document.createElement("input");
                    hiddenInput.required = true;
                    hiddenInput.type = "hidden";
                    hiddenInput.value = attr_id_h;
                    hiddenInput.id = attr_id;
                    hiddenInput.name = `attr_id[${selectedValue}]`;
                    hiddenInput.placeholder = "Precio";

                    // Crear el elemento label
                    var label = document.createElement("label");
                    label.setAttribute("for", precioId);
                    label.textContent =
                        `${selectedText}: Al dejar el precio o el stock en 0, se tomará en cuenta el precio y el stock del producto. Si el producto no maneja stock, guardará un -1`;
                    if (main_attr == 0) {
                        label.textContent = `${selectedText}` + no_main_text;
                    }

                    // Crear el primer div con clase col-md-4 para el precio
                    var divCol1 = document.createElement("div");
                    divCol1.className = "col-md-4";

                    // Crear el primer grupo de entrada
                    var inputGroup1 = document.createElement("div");
                    inputGroup1.className = "input-group input-group-static";

                    // Crear el primer elemento de entrada
                    var inputPrecio = document.createElement("input");
                    inputPrecio.required = true;
                    inputPrecio.type = "text";
                    inputPrecio.value = "0";
                    if (main_attr == 0) {
                        inputPrecio.value = "0";
                        inputPrecio.readOnly = true;
                    }
                    inputPrecio.className = "form-control form-control-lg";
                    inputPrecio.id = precioId;
                    inputPrecio.name = `precios_attr[${selectedValue}]`;
                    inputPrecio.placeholder = "Precio";

                    // Añadir el primer input a su grupo de entrada
                    inputGroup1.appendChild(inputPrecio);

                    // Añadir el grupo de entrada al primer div
                    divCol1.appendChild(inputGroup1);

                    // Crear el segundo div con clase col-md-4 para la cantidad
                    var divCol2 = document.createElement("div");
                    divCol2.className = "col-md-4";

                    // Crear el segundo grupo de entrada
                    var inputGroup2 = document.createElement("div");
                    inputGroup2.className = "input-group input-group-static";

                    // Crear el segundo elemento de entrada
                    var inputCantidad = document.createElement("input");
                    inputCantidad.required = true;
                    inputCantidad.type = "text";
                    inputCantidad.value = "0";
                    if (!manageStockCheckbox.checked) {
                        inputCantidad.value = "-1";
                        inputCantidad.readOnly = true;
                    }
                    inputCantidad.className = "form-control form-control-lg";
                    inputCantidad.id = cantidadId;
                    inputCantidad.name = `cantidades_attr[${selectedValue}]`;
                    inputCantidad.placeholder = "Cantidad";

                    // Añadir el segundo input a su grupo de entrada
                    inputGroup2.appendChild(inputCantidad);

                    // Añadir el grupo de entrada al segundo div
                    divCol2.appendChild(inputGroup2);

                    // Crear el tercer div con clase col-md-4 para el botón de eliminación
                    var divCol3 = document.createElement("div");
                    divCol3.className = "col-md-4 d-flex align-items-center";

                    // Crear el botón de eliminación (X)
                    var deleteButton = document.createElement("button");
                    deleteButton.type = "button";
                    deleteButton.textContent = "X";
                    deleteButton.className = "delete-btn"; // Clase para estilos
                    deleteButton.onclick = function() {
                        container.remove();
                    };

                    // Añadir el botón de eliminación al tercer div
                    divCol3.appendChild(deleteButton);

                    // Añadir todos los elementos al contenedor
                    container.appendChild(hiddenInput);
                    container.appendChild(label);
                    container.appendChild(divCol1);
                    container.appendChild(divCol2);
                    container.appendChild(divCol3);

                    // Añadir el contenedor al div principal
                    var mainContainer = document.getElementById("divValue");
                    mainContainer.appendChild(container);
                }
            });

            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });



            function toggleStockQuantityField() {
                if (manageStockCheckbox.checked) {
                    stockQuantityField.removeAttribute('disabled');
                } else {
                    var advertContent =
                        "Si deshabilitas el stock, se colocará un -1 (Indica que no maneja stock) en la cantidad de cada uno de los atributos, si deseas deshacer esta opción debes habilitar el switch de Maneja Stock, eliminar y volver a agregar cada atributo.";
                    Swal.fire({
                        title: 'Aviso!',
                        html: advertContent,
                        icon: "info",
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: `
                        <i class="fa fa-thumbs-up"></i> Entendido!
                    `,
                        confirmButtonAriaLabel: "Thumbs up, great!"
                    });

                    stockQuantityField.setAttribute('disabled', 'disabled');
                }
            }
            manageStockCheckbox.addEventListener('change', toggleStockQuantityField);
        });
    </script>
    <script src="{{ asset('js/add-tag.js') }}"></script>
@endsection
