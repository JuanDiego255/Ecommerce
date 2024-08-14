@extends('layouts.frontrent')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- <div class="container mt-4">
        <div class="breadcrumb-nav bc3x">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->services }} me-1"></i>{{$title_service}}</a>
                </li>
            @else
                <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                            class="fas fa-shapes me-1"></i>Departamentos</a></li>
                <li class="bread-standard"><a href="#"><i
                            class="fas fa-{{ $icon->categories }} me-1"></i>{{ $department_name }}</a>
                </li>
            @endif

        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
            @foreach ($category as $item)
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                            <ul class="product-links">
                                <li><a target="blank" href="{{ tenant_asset('/') . '/' . $item->image }}"><i
                                            class="fas fa-eye"></i></a></li>
                            </ul>
                            <a href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}"
                                class="add-to-cart">{{$btn}}</a>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a
                                    href="{{ url('clothes-category/' . $item->id . '/' . $department_id) }}">{{ $item->name }}</a>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <center>
            <div class="container">
                {{ $category ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div> --}}
    {{-- Trending --}}
    {{-- Main Banner --}}
    @include('frontend.carsale.products')
    <div class="hero-wrap ftco-degree-bg" style="background-image: url('{{ url('images/bg_1.jpg') }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
                <div class="col-lg-8 ftco-animate">
                    <div class="text w-100 text-center mb-md-5 pb-md-5">
                        <h1 class="mb-4">Compara vehículos</h1>
                        <p style="font-size: 18px;">En busca de la mejor decisión</p>
                    </div>
                </div>
                <input type="hidden" name="code" id="code">
            </div>
        </div>
    </div>
    <section class="ftco-section ftco-no-pt bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-12 heading-section text-center ftco-animate mb-2">
                    <span class="subheading">Selecciona varios vehículos para realizar una comparación</span>
                    <h2 class="mb-2">Hasta 3 vehículos</h2>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button type="button" id="vehicle-button" data-toggle="modal" data-target="#add-products-modal"
                    data-name="products" class="btn btn-secondary py-2 ml-1 icon-button w-50 text-lg">
                    Primer Vehículo
                </button>
            </div>
        </div>
    </section>
    <div id="container" class="d-none"></div>

    @include('layouts.inc.carsale.footer')
@endsection
@section('scripts')
    <script>
        var $container = $('#container');
        let currentButtonName = null;
        let vehicleCount = 0; // Contador de vehículos seleccionados
        let selectedVehicles = {}; // Objeto para almacenar vehículos seleccionados

        // Añadir event listener al botón
        document.getElementById('vehicle-button').addEventListener('click', function() {
            // Actualizar la variable global con el nombre del botón actual
            currentButtonName = this.getAttribute('data-name');
        });

        function selectIcon(icon) {
            if (currentButtonName) {
                let input = document.getElementById("code");
                input.value = icon;
                $('#add-products-modal').modal('hide');
                var code = icon;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    method: "GET",
                    url: "/get-cart-details/"+code,
                    success: function(response) {
                        if (response.status != "success") {
                            Swal.fire({
                                title: response.status,
                                icon: response.icon,
                            });
                            $container.removeClass('d-block').addClass('d-none');
                            $container.empty();
                        } else {
                            var results = response.results;

                            // Verificar si la tabla ya existe, si no, crearla
                            if ($container.find('table').length === 0) {
                                var tableHtml = `
                        <section class="ftco-section ftco-cart">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="car-list">
                                            <table class="table">
                                                <thead class="thead-primary">
                                                    <tr class="text-center">
                                                        <th>&nbsp;</th>
                                                        <th class="bg-primary heading" id="header-vehicle1">Vehículo 1</th>
                                                        <th class="bg-dark heading" id="header-vehicle2">Vehículo 2</th>
                                                        <th class="bg-black heading" id="header-vehicle3">Vehículo 3</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="vehicle-rows">
                                                    <tr>
                                                        <td>Precio</td>
                                                        <td class="price" id="price1"></td>
                                                        <td class="price" id="price2"></td>
                                                        <td class="price" id="price3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Distancia al suelo (mm)</td>
                                                        <td class="price" id="distance1"></td>
                                                        <td class="price" id="distance2"></td>
                                                        <td class="price" id="distance3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Peso (KG)</td>
                                                        <td class="price" id="weight1"></td>
                                                        <td class="price" id="weight2"></td>
                                                        <td class="price" id="weight3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Color</td>
                                                        <td class="price" id="color1"></td>
                                                        <td class="price" id="color2"></td>
                                                        <td class="price" id="color3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Modelo</td>
                                                        <td class="price" id="model1"></td>
                                                        <td class="price" id="model2"></td>
                                                        <td class="price" id="model3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kilometraje</td>
                                                        <td class="price" id="mileage1"></td>
                                                        <td class="price" id="mileage2"></td>
                                                        <td class="price" id="mileage3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Capacidad del tanque</td>
                                                        <td class="price" id="tank_capacity1"></td>
                                                        <td class="price" id="tank_capacity2"></td>
                                                        <td class="price" id="tank_capacity3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tipo combustible</td>
                                                        <td class="price" id="fuel_type1"></td>
                                                        <td class="price" id="fuel_type2"></td>
                                                        <td class="price" id="fuel_type3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Motor (CC)</td>
                                                        <td class="price" id="engine1"></td>
                                                        <td class="price" id="engine2"></td>
                                                        <td class="price" id="engine3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Potencia</td>
                                                        <td class="price" id="power1"></td>
                                                        <td class="price" id="power2"></td>
                                                        <td class="price" id="power3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pasajeros</td>
                                                        <td class="price" id="passengers1"></td>
                                                        <td class="price" id="passengers2"></td>
                                                        <td class="price" id="passengers3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Llantas</td>
                                                        <td class="price" id="tires1"></td>
                                                        <td class="price" id="tires2"></td>
                                                        <td class="price" id="tires3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tracción</td>
                                                        <td class="price" id="traction1"></td>
                                                        <td class="price" id="traction2"></td>
                                                        <td class="price" id="traction3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transmisión</td>
                                                        <td class="price" id="transmission1"></td>
                                                        <td class="price" id="transmission2"></td>
                                                        <td class="price" id="transmission3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Largo (mm)</td>
                                                        <td class="price" id="length1"></td>
                                                        <td class="price" id="length2"></td>
                                                        <td class="price" id="length3"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ancho (mm)</td>
                                                        <td class="price" id="width1"></td>
                                                        <td class="price" id="width2"></td>
                                                        <td class="price" id="width3"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>`;
                                $container.html(tableHtml);
                            }

                            // Verificar si el vehículo ya ha sido cargado
                            if (selectedVehicles[icon]) {
                                Swal.fire({
                                    title: 'Advertencia!',
                                    text: 'Este vehículo ya está en la tabla.',
                                    icon: 'warning'
                                });
                                return;
                            }

                            // Añadir el vehículo al objeto de vehículos seleccionados
                            selectedVehicles[icon] = results;

                            // Actualizar el nombre del vehículo en el encabezado y llenar la tabla
                            if (vehicleCount === 0) {
                                $('#header-vehicle1').text(results[0].name); // Nombre del vehículo 1
                                $('#price1').text('₡ '+results[0].price.toLocaleString()); // Mostrar precio en la primera fila
                                $('#distance1').text(results[0].distance + " mm");
                                $('#weight1').text(results[0].weight);
                                $('#color1').text(results[0].color);
                                $('#model1').text(results[0].model);
                                $('#mileage1').text(number_format(results[0].mileage.toLocaleString()));
                                $('#tank_capacity1').text(results[0].tank_capacity);
                                $('#fuel_type1').text(results[0].fuel_type);
                                $('#engine1').text(results[0].engine + " CC");
                                $('#power1').text(results[0].power);
                                $('#passengers1').text(results[0].passengers);
                                $('#tires1').text(results[0].tires);
                                $('#traction1').text(results[0].traction);
                                $('#transmission1').text(results[0].transmission);
                                $('#length1').text(results[0].length + " mm");
                                $('#width1').text(results[0].width + " mm");
                            } else if (vehicleCount === 1) {
                                $('#header-vehicle2').text(results[0].name); // Nombre del vehículo 2
                                $('#price2').text('₡ '+results[0].price.toLocaleString()); // Mostrar precio en la primera fila
                                $('#distance2').text(results[0].distance + " mm");
                                $('#weight2').text(results[0].weight);
                                $('#color2').text(results[0].color);
                                $('#model2').text(results[0].model);
                                $('#mileage2').text(number_format(results[0].mileage.toLocaleString()));
                                $('#tank_capacity2').text(results[0].tank_capacity);
                                $('#fuel_type2').text(results[0].fuel_type);
                                $('#engine2').text(results[0].engine + " CC");
                                $('#power2').text(results[0].power);
                                $('#passengers2').text(results[0].passengers);
                                $('#tires2').text(results[0].tires);
                                $('#traction2').text(results[0].traction);
                                $('#transmission2').text(results[0].transmission);
                                $('#length2').text(results[0].length + " mm");
                                $('#width2').text(results[0].width + " mm");
                            } else if (vehicleCount === 2) {
                                $('#header-vehicle3').text(results[0].name); // Nombre del vehículo 3
                                $('#price3').text('₡ '+results[0].price.toLocaleString()); // Mostrar precio en la primera fila
                                $('#distance3').text(results[0].distance + " mm");
                                $('#weight3').text(results[0].weight);
                                $('#color3').text(results[0].color);
                                $('#model3').text(results[0].model);
                                $('#mileage3').text(number_format(results[0].mileage.toLocaleString()));
                                $('#tank_capacity3').text(results[0].tank_capacity);
                                $('#fuel_type3').text(results[0].fuel_type);
                                $('#engine3').text(results[0].engine + " CC");
                                $('#power3').text(results[0].power);
                                $('#passengers3').text(results[0].passengers);
                                $('#tires3').text(results[0].tires);
                                $('#traction3').text(results[0].traction);
                                $('#transmission3').text(results[0].transmission);
                                $('#length3').text(results[0].length + " mm");
                                $('#width3').text(results[0].width + " mm");
                            }

                            $container.removeClass('d-none').addClass('d-block');

                            // Actualizar el botón para la siguiente selección
                            vehicleCount++;
                            if (vehicleCount === 1) {
                                $('#vehicle-button').text('Segundo Vehículo');
                            } else if (vehicleCount === 2) {
                                $('#vehicle-button').text('Tercer Vehículo');
                            } else if (vehicleCount === 3) {
                                $('#vehicle-button').text('Restablecer');
                            } else {
                                $('#vehicle-button')
                            .hide(); // Ocultar el botón si ya se seleccionaron tres vehículos
                            }
                        }
                    }
                });
            }
        }

        function number_format(number) {
            return number.toLocaleString('en-US'); // Formatear número con comas
        }

        function resetComparison() {
            // Restablecer la tabla y el botón
            $('#container').empty().removeClass('d-block').addClass('d-none');
            $('#vehicle-button').text('Vehículo 1');
            vehicleCount = 0;
            selectedVehicles = {};
        }

        // Evento para restablecer todo cuando el botón tenga el texto 'Restablecer'
        document.getElementById('vehicle-button').addEventListener('click', function() {
            if (vehicleCount === 3) {
                resetComparison();
            }
        });

        function filterIcons() {
            var input, filter, iconList, icons, i;
            input = document.getElementById('icon-search');
            filter = input.value.toLowerCase();
            iconList = document.getElementById('icon-list');
            icons = iconList.getElementsByClassName('icon-item');

            for (i = 0; i < icons.length; i++) {
                var iconCode = icons[i].getAttribute('data-code');
                var iconName = icons[i].getAttribute('data-name');
                if (iconCode.toLowerCase().indexOf(filter) > -1 || iconName.toLowerCase().indexOf(filter) > -1) {
                    icons[i].style.display = "";
                } else {
                    icons[i].style.display = "none";
                }
            }
        }
    </script>
@endsection
