@extends('layouts.admin')
@section('metatag')
{!! SEOMeta::generate() !!}
{!! OpenGraph::generate() !!}
@endsection
@php
$stock_array = $stocks;
@endphp
@section('content')
<form id="updateForm" action="{{ url('update-clothing' . '/' . $clothing->id) }}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="category_id_main" value="{{$category_id}}">
    @csrf
    <div class="row">
        <div @if ($tenantinfo->manage_size != 0) class="col-md-8" @else class="col-md-12" @endif>
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
                                <select id="category_id" name="category_id[]" class="form-control form-control-lg @error('category_id') is-invalid @enderror" autocomplete="category_id" autofocus multiple>
                                    @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ in_array($item->id, $selectedCategories) ? 'selected' : '' }}>
                                        {{ $item->name }} {{$item->department != "Default" ? '- '.$item->department : ''}}
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
                                <input required value="{{ $clothing->name }}" type="text" class="form-control form-control-lg" name="name">
                            </div>
                        </div>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'fragsperfumecr')
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Casa') }}</label>
                                <input type="text" value="{{ $clothing->casa }}" class="form-control form-control-lg" name="casa">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Código') }}</label>
                                <input required value="{{ $clothing->code }}" type="text" class="form-control form-control-lg" name="code">
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
                                <input required type="number" value="{{ $clothing->price }}" class="form-control form-control-lg" name="price">
                            </div>
                        </div>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Precio al por mayor') }}</label>
                                <input required type="number" value="{{ $clothing->mayor_price }}" class="form-control form-control-lg" name="mayor_price">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static mb-4">
                                <label>{{ __('Descuento (%)') }}</label>
                                <input type="number" value="{{ $clothing->discount }}" class="form-control form-control-lg" name="discount">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch col-md-6">
                                <input value="1" @if ($clothing->manage_stock == 1) checked @endif class="form-check-input" type="checkbox" name="manage_stock" id="manage_stock">
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
                                <input id="stock" min="1" required @if ($tenantinfo->manage_size != 0) readonly @endif
                                value="{{ $clothing->total_stock == 0 ? '1' : $clothing->total_stock }}"
                                type="number" class="form-control form-control-lg" name="stock">
                            </div>
                        </div>

                        @if (isset($tenantinfo->kind_business) && ($tenantinfo->kind_business == 2 || $tenantinfo->kind_business == 3))
                        <div class="col-md-12 mb-3">
                            <label>{{ __('Se puede comprar?') }}</label>
                            <div class="form-check">
                                <input {{ $clothing->can_buy == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="can_buy" name="can_buy">
                                <label class="custom-control-label" for="customCheck1">Producto de compra</label>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-12 mb-3">
                            <label>{{ __('Es tendencia?') }}</label>
                            <div class="form-check">
                                <input {{ $clothing->trending == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="trending" name="trending">
                                <label class="custom-control-label" for="customCheck1">{{ __('Tendencia') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            @if ($clothing->image)
                            <img class="img-fluid img-thumbnail" src="{{ route('file', $clothing->image) }}" style="width: 150px; height:150px;" alt="image">
                            @endif
                            <label>{{ __('Imagenes (Max 4)') }}</label>
                            <div class="input-group input-group-static mb-4">
                                <input multiple class="form-control form-control-lg" type="file" name="images[]">
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">

                            <label>{{ __('Meta Keywords (Opcional - Presione enter para agregar la palabra clave)') }}</label><br>
                            <div class="tags-input">
                                <ul id="tags"></ul>
                                <input type="text" id="input-tag" placeholder="Escriba la palabra clave.." />
                            </div>
                            <input id="meta_keywords" type="hidden" name="meta_keywords" value="{{ $clothing->meta_keywords }}">

                        </div>

                    </div>

                    <div class="col-md-12 mt-3 text-center">
                        <button type="submit" class="btn btn-velvet">{{ __('Guardar cambios') }}</button>
                    </div>


                </div>
            </div>
        </div>
        <div @if ($tenantinfo->manage_size != 0) class="col-md-4" @else class="d-none" @endif>
            @if (count($attributes) > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="text-dark">{{ __('Atributos') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="input-group input-group-static">
                                <label>{{ __('Seleccionar atributo') }}</label>
                                <select id="attr_id" name="attr_id" class="form-control form-control-lg @error('attr_id') is-invalid @enderror" autocomplete="attr_id" autofocus>
                                    @if (isset($stock_active->attr_id))
                                    <option selected value="{{ $stock_active->attr_id }}">
                                        {{ $stock_active->name }}
                                    </option>
                                    @else
                                    <option value="0">{{ __('Sin atributos') }}</option>
                                    @endif

                                    <option value="0">{{ __('Sin atributos') }}</option>
                                    @foreach ($attributes as $key => $item)
                                    <option value="{{ $item->id }}">
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
                                <select id="value" name="value" class="form-control form-control-lg @error('value') is-invalid @enderror" autocomplete="value" autofocus>

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
    </div>

</form>
<center>
    <div class="col-md-12 mt-3">
        <a href="{{ url('add-item/' . $category_id) }}" class="btn btn-velvet w-25">{{ __('Volver') }}</a>
    </div>
</center>
@endsection
@section('script')
<script src="{{ asset('js/edit-tag.js') }}"></script>

<script>
    $(document).ready(function() {
        const manageStockCheckbox = document.getElementById('manage_stock');
        const stockQuantityField = document.getElementById('stock');
        createHtml(true,false);

        $('.size-checkbox').change(function(e) {
            createHtml(true,false);
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
        var deletedValues = [];

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
                label.textContent = `${selectedText}: Al dejar el precio o el stock en 0, se tomará en cuenta el precio y el stock del producto. Si el producto no maneja stock, guardará un -1`;
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
                    deletedValues.push({
                        attr_id: attr_id_h,
                        precio: inputPrecio.value,
                        cantidad: inputCantidad.value
                    });
                    console.log("Deleted Values:", deletedValues);
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

        function createHtml(startup,manage) {
            var attributes = <?php echo json_encode($stock_array); ?>;

            attributes.forEach(function(item, index) {
                if (item.attr_id != null && item.attr_id != "") {
                    var selectedValueComplete = item.value_attr + "-" + item.main;
                    var partes = selectedValueComplete.split("-");
                    var selectedValue = partes[0];
                    var main_attr = partes[1];
                    var selectedText = item.value;
                    var attr_id_h = item.attr_id;
                    var precioId = "precio_attr" + selectedValue;
                    var attr_id = "attr_id" + selectedValue;
                    var value_id = "value_id" + selectedValue;
                    var cantidadId = "cantidad_attr" + selectedValue;
                    var containerId = "container_attr" + selectedValue; // Nuevo ID para el contenedor
                    var no_main_text =
                        " (No es un valor de un atributo principal, no permite el precio)"

                    // Verificar si los elementos ya existen
                    if (!document.getElementById(precioId) && !document.getElementById(cantidadId) && !
                        document.getElementById(attr_id) &&
                        selectedValue != "0") {

                        // Crear un contenedor para agrupar todos los elementos
                        var container = document.createElement("div");
                        container.id = containerId;
                        container.className =
                            "attr-container row"; // Añadir clase "row" para estilo de filas

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
                        label.textContent = `${selectedText}: Al dejar el precio o el stock en 0, se tomará en cuenta el precio y el stock del producto. Si el producto no maneja stock, guardará un -1`;
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
                        inputPrecio.value = item.price;
                        if (main_attr == 0) {
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
                        inputCantidad.value = item.stock;
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
                    }else{
                        if(!startup){
                            if(manage){
                                document.getElementById(cantidadId).value = item.stock > 0 ? item.stock : 1;
                            }else{
                                document.getElementById(cantidadId).value = -1;
                            }
                        }
                    }
                }
            });
        }

        function obtenerStockYPrecioParaTalla(tallaId) {
            var cloth_id = document.getElementById("clothing_id").value;
            var stocks = <?php echo json_encode($stocks); ?>;
            var stockParaTalla = 0;
            var precioParaTalla = 0;

            stocks.forEach(function(stock) {
                if (stock.clothing_id == cloth_id && stock.size_id == tallaId && stock.attr_id ==
                    null && stock.value_attr == null) {
                    stockParaTalla = stock.stock;
                    precioParaTalla = stock.price;
                    if (typeof precioParaTalla === 'undefined') {
                        precioParaTalla = 0;
                    }
                    return; // Salir del bucle forEach una vez que se encuentre el stock y precio para la talla
                }
            });

            return {
                stock: stockParaTalla,
                price: precioParaTalla
            };
        }
        $(document).ready(function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        });

        function toggleStockQuantityField() {
            if (manageStockCheckbox.checked) {
                createHtml(false,true);
                var advertContent = "Al habilitar el stock, tomará por defecto los valores que ya existían en el producto. Si el valor es negativo el sistema sugerirá un valor mayor a 0";
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
                stockQuantityField.removeAttribute('disabled');
            } else {
                var advertContent = "Al deshabilitar el stock, tomará por defecto los valores que ya existían en el producto, pero les establecerá un -1";
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
                createHtml(false,false);
            }
        }
        manageStockCheckbox.addEventListener('change', toggleStockQuantityField);
    });
</script>
@endsection