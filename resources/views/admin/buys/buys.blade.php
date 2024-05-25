@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="font-title text-center">Ventas</h1>
    <div class="container">

        <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
            <div class="row w-100">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-static my-3 w-100">
                                    <label>Mostrar</label>
                                    <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                                        autocomplete="recordsPerPage">
                                        <option value="5">5 Registros</option>
                                        <option selected value="10">10 Registros</option>
                                        <option value="25">25 Registros</option>
                                        <option value="50">50 Registros</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-lg input-group-static my-3 w-100">
                                    <label>Código</label>
                                    <input value="" placeholder="Código del producto...." type="text"
                                        class="form-control form-control-lg" name="code" id="code">
                                </div>
                            </div>
                            <input type="hidden" value="{{ $tenantinfo->manage_size }}" id="manage_size">

                        </div>
                        <div class="row" id="container" class="d-none">
                            <div id="select-container" class="d-none">

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card p-2">
                    <div class="table-responsive">
                        <input type="hidden" name="iva_tenant" id="iva_tenant" value="{{ $iva_tenant }}">
                        <table class="table align-items-center mb-0" id="cartTable">
                            <thead>
                                <tr>

                                    <th class="text-secondary font-weight-bolder opacity-7 ps-2">Producto
                                    </th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">
                                        Precio</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">
                                        Atributos</th>
                                    <th class="text-center text-secondary font-weight-bolder opacity-7">
                                        Cant</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart_items as $item)
                                    <tr>
                                        @php
                                            $precio = $item->price;
                                            $descuentoPorcentaje = $item->discount;
                                            // Calcular el descuento
                                            $descuento = ($precio * $descuentoPorcentaje) / 100;
                                            // Calcular el precio con el descuento aplicado
                                            $precioConDescuento = $precio - $descuento;
                                            $attributesValues = explode(', ', $item->attributes_values);
                                        @endphp
                                        <input type="hidden" name="prod_id" value="{{ $item->id }}" class="prod_id">
                                        <input type="hidden" class="price"
                                            value="{{ $item->discount > 0 ? $precioConDescuento : $item->price }}">
                                        <input type="hidden" value="{{ $descuento }}" class="discount" name="discount">
                                        <td class="w-50">
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <a target="blank" data-fancybox="gallery"
                                                        href="{{ route('file', $item->image) }}">
                                                        <img src="{{ route('file', $item->image) }}"
                                                            class="avatar avatar-md me-3">
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h4 class="mb-0 text-lg">{{ $item->name }}</h4>
                                                    <p class="text-xs text-secondary mb-0">Código: {{ $item->code }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">

                                            <p class="text-success mb-0">₡
                                                {{ $item->discount > 0 ? $precioConDescuento : $item->price }}
                                                @if ($item->discount > 0)
                                                    <s class="text-danger">{{ $item->price }}</s>
                                                @endif
                                            </p>

                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            @foreach ($attributesValues as $attributeValue)
                                                @php
                                                    // Separa el atributo del valor por ": "
                                                    [$attribute, $value] = explode(': ', $attributeValue);
                                                @endphp

                                                {{ $attribute }}: {{ $value }}<br>
                                            @endforeach
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <div class="input-group text-center input-group-static w-100">

                                                <input min="1" max="{{ $item->stock }}"
                                                    data-cart-id="{{ $item->cart_id }}" value="{{ $item->quantity }}"
                                                    type="number" name="quantity" id="quantity{{ $item->quantity }}"
                                                    class="form-control btnQuantity text-center w-100 quantity">
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <form name="delete-item-cart" id="delete-item-cart" class="delete-form">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button data-item-id="{{ $item->cart_id }}"
                                                    class="btn btn-icon btn-3 btn-danger btnDeleteCart">
                                                    <span class="btn-inner--icon"><i
                                                            class="material-icons">delete</i></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 bg-transparent">
                <div class="card mb-4">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Productos
                                <span><strong id="totalCloth">₡{{ number_format($cloth_price) }}</strong</span>
                            </li>
                            @if ($iva > 0)
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    I.V.A
                                    <span id="totalIvaElement">₡{{ number_format($iva) }}</span>
                                </li>
                            @endif

                            @if ($you_save > 0)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Descuento
                                    <span id="totalDiscountElement">₡{{ number_format($you_save) }}</span>
                                </li>
                            @endif

                            <li class="list-group-item d-flex justify-content-between border-0 ">

                                <strong>Total</strong>
                                <span><strong id="totalPriceElement">₡{{ number_format($total_price) }}</strong></span>
                            </li>
                        </ul>
                        <center>
                            <form action="{{ url('payment') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="F" name="kind_of" id="kind_of">
                                <div class="row checkout-form">
                                    <div class="col-md-12 text-center w-100 mb-3 px-5">
                                        <div class="input-group input-group-lg input-group-outline my-3">
                                            <label class="form-label">Detalle (Opcional)</label>
                                            <input type="text" class="form-control form-control-lg" name="detail">
                                        </div>
                                    </div>
                                </div>
                                <button @if ($total_price == 0) disabled @endif id="btnSinpe" type="submit"
                                    class="btn btn-add_to_cart w-75 d-block h8">Realizar
                                    Venta
                                    ₡<span id="btnPay">{{ number_format($total_price) }}</span></button>
                            </form>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var $container = $(
                '#container'
            ); // Suponiendo que hay un contenedor con id "container"
            var dataTable = $('#cartTable').DataTable({
                searching: true,
                lengthChange: false,

                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "<<",
                        "sLast": "Último",
                        "sNext": ">>",
                        "sPrevious": "<<"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });

            $('#code').keypress(function(event) {
                // Comprueba si la tecla presionada es Enter (código 13)
                if (event.keyCode === 13) {
                    event.preventDefault();

                    var code = $(this).val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        method: "POST",
                        url: "/size-by-cloth",
                        data: {
                            'code': code,
                        },
                        success: function(response) {
                            if (response.status != "success") {
                                swal({
                                    title: response.status,
                                    icon: response.icon,
                                });
                                $container.removeClass('d-block').addClass('d-none');
                                $('#select-container').removeClass('d-block').addClass(
                                    'd-none');
                                $container.empty();
                            } else {
                                var results = response.results;
                                var $currentRow;
                                $.each(results, function(index, attribute) {
                                    if (index % 2 === 0) {
                                        // Crear una nueva fila cada dos columnas
                                        $currentRow = $('<div>', {
                                            class: 'row'
                                        });
                                        $container.append($currentRow);
                                    }

                                    var $col = $('<div>', {
                                        class: 'col-md-6'
                                    });
                                    var $label = $('<label>', {
                                        text: attribute.columna_atributo
                                    });
                                    var values = attribute.valores.split('-');
                                    var ids = attribute.ids.split('-');

                                    var $select = $('<select>', {
                                        required: true,
                                        name: 'size_id',
                                        class: 'size_id form-control form-control-lg mb-2'
                                    });

                                    $.each(values, function(key, value) {
                                        if (ids[key] !== undefined) {
                                            var $option = $('<option>', {
                                                value: ids[key] + '-' +
                                                    attribute.attr_id +
                                                    '-' + attribute
                                                    .clothing_id,
                                                id: 'size_' + ids[key],
                                                text: value
                                            });
                                            if (key === 0) {
                                                $option.attr('selected',
                                                    'selected');
                                            }
                                            $select.append($option);
                                        }
                                    });

                                    var $inputGroup = $('<div>', {
                                        class: 'input-group input-group-static'
                                    }).append($select);

                                    $col.append($label).append('<br>').append(
                                        $inputGroup).append('<br>');

                                    $currentRow.append($col);

                                    // Add the button after every two columns (end of the row)
                                    if (index % 2 === 1 || index === results.length -
                                        1) {
                                        var $buttonCol = $('<div>', {
                                            class: 'col-md-12'
                                        });
                                        var $button = $('<button>', {
                                            class: 'btn btn-add_to_cart shadow-0 btnAdd'
                                        }).append(
                                            '<i class="me-1 fa fa-shopping-basket"></i> Agregar'
                                        );
                                        $buttonCol.append($button);
                                        $currentRow.append($buttonCol);
                                    }
                                });

                                $container.removeClass('d-none').addClass('d-block');
                                $('#select-container').removeClass('d-none').addClass(
                                    'd-block');
                            }

                        }
                    });
                }
            });

            $container.on('click', '.btnAdd', function(e) {
                e.preventDefault();
                var input_code = document.getElementById('code');
                var code = input_code.value;
                var selected_sizes = [];

                // Recorrer todos los <select> con la clase .size_id y obtener sus valores
                $('.size_id').each(function() {
                    var selected_value = $(this).val();
                    selected_sizes.push(selected_value);
                });

                // Convertir el array a una cadena JSON
                var cleaned_sizes = selected_sizes.filter(function(size) {
                    return size !== typeof(undefined) && size.trim() !== "";
                });

                // Convertir el array filtrado a una cadena JSON
                var attributes = JSON.stringify(cleaned_sizes);
                console.log(attributes)

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/add-to-cart",
                    data: {
                        'code': code,
                        'attributes': attributes,
                    },
                    success: function(response) {
                        //swal(response.status);
                        if (response.icon === "success") {
                            location.reload();
                        } else {
                            swal({
                                title: response.status,
                                icon: response.icon,
                            });
                        }

                    }
                });
            });

            $('.btnQuantity').click(function(e) {
                e.preventDefault();

                var quantity = $(this).val();
                var itemId = $(this).data('cart-id');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "POST",
                    url: "/edit-quantity",
                    data: {
                        'cart_id': itemId,
                        'quantity': quantity,
                    },
                    success: function(response) {
                        calcularTotal();
                    }
                });
            });

            $('.btnDeleteCart').click(function(e) {
                e.preventDefault();

                var itemId = $(this).data('item-id');
                // Confirmar la eliminación
                var confirmDelete = confirm('¿Deseas borrar este artículo?');

                if (confirmDelete) {
                    $.ajax({
                        method: "POST",
                        url: "/delete-item-cart/" + itemId,
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE',
                        },
                        success: function(response) {
                            if (response.refresh == true) {
                                window.location.href = "{{ url('/') }}";
                            } else {
                                location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejar errores si es necesario
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        function calcularTotal() {
            let total = 0;
            let total_cloth = 0;
            let iva = parseFloat(document.getElementById("iva_tenant").value);
            let total_iva = 0;
            let you_save = 0;
            // Obtener todas las filas de la tabla
            const filas = document.querySelectorAll('#cartTable tbody tr');

            filas.forEach((fila) => {
                const precio = parseFloat(fila.querySelector('.price').value);
                const discount = parseFloat(fila.querySelector('.discount').value);
                const cantidad = parseInt(fila.querySelector('.quantity').value);

                const subtotal = precio * cantidad;
                const subtotal_discount = discount * cantidad;
                you_save += subtotal_discount;
                total += subtotal;
            });

            total_iva = total * iva;
            total_cloth = total;
            total = total + total_iva;

            // Mostrar el total actualizado en el elemento correspondiente
            const totalElement = document.getElementById('totalPriceElement');
            const totalIvaElement = document.getElementById('totalIvaElement');
            const totalDiscountElement = document.getElementById('totalDiscountElement');
            const totalCloth = document.getElementById('totalCloth');
            const btnPay = document.getElementById('btnPay');

            totalElement.textContent = `₡${total.toLocaleString()}`;
            if (total_iva > 0) {
                totalIvaElement.textContent = `₡${total_iva.toLocaleString()}`;
            }
            if (you_save > 0) {
                totalDiscountElement.textContent = `₡${you_save.toLocaleString()}`;
            }
            totalCloth.textContent = `₡${total_cloth.toLocaleString()}`;
        }
    </script>
@endsection
