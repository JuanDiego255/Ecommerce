@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <form action="{{ url('update-clothing' . '/' . $clothing->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Editar Producto</h4>
                    </div>
                    <div class="card-body">

                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>Producto</label>
                                    <input required value="{{ $clothing->name }}" type="text"
                                        class="form-control form-control-lg" name="name">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'fragsperfumecr')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Casa</label>
                                        <input type="text" value="{{ $clothing->casa }}"
                                            class="form-control form-control-lg" name="casa">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>Código</label>
                                    <input required value="{{ $clothing->code }}" type="text"
                                        class="form-control form-control-lg" name="code">
                                </div>
                            </div>
                            <input type="hidden" name="category_id" value="{{ $clothing->category_id }}">
                            <div class="col-md-12 mb-3">

                                <label>Descripción</label><br>
                                <textarea id="editor" type="text" class="form-control form-control-lg" name="description">{!! $clothing->description !!}</textarea>
                            </div>

                            <input type="hidden" name="clothing_id" id="clothing_id" value="{{ $clothing->id }}">
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>Precio</label>
                                    <input required type="number" value="{{ $clothing->price }}"
                                        class="form-control form-control-lg" name="price">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'torres')
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Precio al por mayor</label>
                                        <input required type="number" value="{{ $clothing->mayor_price }}"
                                            class="form-control form-control-lg" name="mayor_price">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>Descuento (%)</label>
                                    <input type="number" value="{{ $clothing->discount }}"
                                        class="form-control form-control-lg" name="discount">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group input-group-static mb-4">
                                    <label>Stock (El dato que se ingresa aumenta el stock ya existente en las tallas
                                        seleccionadas,
                                        siempre y cuando este sea 0)</label>
                                    <input min="1" required
                                        value="{{ $clothing->total_stock == 0 ? '1' : $clothing->total_stock }}"
                                        type="number" class="form-control form-control-lg" name="stock">
                                </div>
                            </div>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->manage_size == 1)
                                <div class="col-md-12 mb-3">
                                    <label
                                        class="control-label control-label text-formulario {{ $errors->has('sizes_id[]') ? 'is-invalid' : '' }}"
                                        for="sizes_id[]">Tallas</label><br>
                                    @foreach ($sizes as $size)
                                        <div class="form-check form-check-inline">
                                            <input name="sizes_id[]"
                                                class="form-check-input mb-2 {{ isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1 ? 'size-checkbox' : '' }}"
                                                {{ $size_active->contains('size_id', $size->id) ? 'checked' : '' }}
                                                type="checkbox" value="{{ $size->id }}" id="sizes_id[]">
                                            <label class="form-check-label table-text mb-2" for="size_{{ $size->id }}">
                                                {{ $size->size }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business == 2)
                                <div class="col-md-12 mb-3">
                                    <label>Se puede comprar?</label>
                                    <div class="form-check">
                                        <input {{ $clothing->can_buy == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="can_buy"
                                            name="can_buy">
                                        <label class="custom-control-label" for="customCheck1">Producto de compra</label>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 mb-3">
                                <label>Es Tendencia?</label>
                                <div class="form-check">
                                    <input {{ $clothing->trending == 1 ? 'checked' : '' }} class="form-check-input"
                                        type="checkbox" value="1" id="trending" name="trending">
                                    <label class="custom-control-label" for="customCheck1">Trending</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                @if ($clothing->image)
                                    <img class="img-fluid img-thumbnail" src="{{ route('file', $clothing->image) }}"
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


                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Gestionar
                            {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'tallas' : 'tamaños' }}
                            (Opcional)
                            {{ isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 0 ? 'Para gestionar las tallas debes activar este modo en la sección componentes' : '' }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('custom/size') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div id="tallasSeleccionadas" class="row">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('add-item/' . $category_id) }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            createHtml();
            $('.size-checkbox').change(function(e) {
                createHtml();
            });

            function createHtml() {
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
                    var result = obtenerStockYPrecioParaTalla(talla);
                    var stock = result.stock;
                    var price = result.price;
                    html += `                    
                        <label for="precio_${talla}">{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'Talla' : 'Tamaño' }} ${sizeName}:</label><br>                       
                        <div class="col-md-6">
                            <div class="input-group input-group-static">                           
                                <input required type="text" value="${price}" class="form-control form-control-lg" id="precio_${talla}" name="precios[${talla}]" placeholder="Precio">                            
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <div class="input-group input-group-static">                           
                                <input required type="text" class="form-control form-control-lg" id="cantidad_${talla}" name="cantidades[${talla}]" placeholder="Cantidad" value="${stock}">
                            </div>
                        </div>
                        
                    `;
                });
                document.getElementById("tallasSeleccionadas").innerHTML = html;
            }

            function obtenerStockYPrecioParaTalla(tallaId) {
                var cloth_id = document.getElementById("clothing_id").value;
                var stocks = <?php echo json_encode($stocks); ?>;
                var stockParaTalla = 0;
                var precioParaTalla = 0;

                stocks.forEach(function(stock) {
                    if (stock.clothing_id == cloth_id && stock.size_id == tallaId) {
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
                                <input required type="text" value="" class="form-control form-control-lg" id="precio_${talla}" name="precios[${talla}]" placeholder="Precio">                            
                            </div>
                        </div>
                       
                        <div class="col-md-6">
                            <div class="input-group input-group-static">                           
                                <input required type="text" value="" class="form-control form-control-lg" id="cantidad_${talla}" name="cantidades[${talla}]" placeholder="Cantidad">
                            </div>
                        </div>
                        
                    `;
                    });
                    document.getElementById("tallasSeleccionadas").innerHTML = html;
                });
                ClassicEditor
                    .create(document.querySelector('#editor'))
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection
