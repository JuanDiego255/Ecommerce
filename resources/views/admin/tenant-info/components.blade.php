@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        @include('admin.tenant-info.carousel-modal')
        @include('admin.tenant-info.social-modal')
        <hr class="hr-servicios">
        @foreach ($tenant_info as $item)
            <form class="form-horizontal" action="{{ url('tenant-components/save/') }}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Mostrar u ocultar componentes</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="manage_size"
                                    id="manage_size" @if ($item->manage_size == 1) checked @endif>
                                <label class="form-check-label" for="manage_size">Manejar Tallas</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="manage_department"
                                    id="manage_department" @if ($item->manage_department == 1) checked @endif>
                                <label class="form-check-label" for="manage_department">Manejar Departamentos</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_stock"
                                    id="show_stock" @if ($item->show_stock == 1) checked @endif>
                                <label class="form-check-label" for="show_stock">Mostrar Stock</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_insta"
                                    id="show_insta" @if ($item->show_insta == 1) checked @endif>
                                <label class="form-check-label" for="show_insta">Mostrar Seccion Instagram</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_trending"
                                    id="show_trending" @if ($item->show_trending == 1) checked @endif>
                                <label class="form-check-label" for="show_trending">Mostrar Productos En Tendencia</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_cintillo"
                                    id="show_cintillo" @if ($item->show_cintillo == 1) checked @endif>
                                <label class="form-check-label" for="show_cintillo">Mostrar Cintillo (Cinta arriba del menu
                                    principal)</label>
                            </div>
                            <div class="form-check form-switch col-md-6">
                                <input value="1" class="form-check-input" type="checkbox" name="show_mision"
                                    id="show_mision" @if ($item->show_mision == 1) checked @endif>
                                <label class="form-check-label" for="show_mision">Mostrar Misión</label>
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="text-dark mb-3">Gestión de productos</h4>
                            <div class="form-check form-switch col-md-6">
                                <input @if ($item->manage_size == 0) disabled @endif value="1"
                                    class="form-check-input" type="checkbox" name="custom_size" id="custom_size"
                                    @if ($item->custom_size == 1) checked @endif>
                                <label class="form-check-label" for="custom_size">Gestión de tallas (Permite gestionar la
                                    cantidad y precio de cada talla, debe tener el manejo de talla activo para habilitar
                                    este control)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input class="btn btn-velvet mt-4" type="submit" value="Guardar Cambios">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endforeach

        <div class="card mt-3">
            <div class="card-header">
                <h4 class="text-dark">Personaliza los colores del sitio web (Si conoce el valor hexadecimal del color en
                    específico, puedes ingresarlo en el campo de texto.)</h4>
            </div>
            <form action="{{ url('tenant-components/color-save/') }}" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">

                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Menú</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->navbar }}"
                                    class="form-control form-control-color color-picker" data-color-code="color-code-menu">
                                <input type="text" value="{{ $settings->navbar }}" name="navbar" id="color-code-menu"
                                    class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del menú</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->navbar_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-text-menu">
                                <input type="text" value="{{ $settings->navbar_text }}" name="navbar_text"
                                    id="color-code-text-menu" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del título</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->title_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-text-title">
                                <input type="text" value="{{ $settings->title_text }}" name="title_text"
                                    id="color-code-text-title" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Botón carrito del menú (Móvil)</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->cart_icon }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-btn-cart">
                                <input type="text" name="cart_icon" value="{{ $settings->cart_icon }}"
                                    id="color-code-cart-icon" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Botón carrito del menú</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->btn_cart }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-btn-cart">
                                <input type="text" name="btn_cart" value="{{ $settings->btn_cart }}"
                                    id="color-code-btn-cart" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del botón del carrito del menú</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->btn_cart_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-btn-cart-text">
                                <input type="text" name="btn_cart_text" value="{{ $settings->btn_cart_text }}"
                                    id="color-code-btn-cart-text" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Pie de página</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->footer }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-footer">
                                <input type="text" value="{{ $settings->footer }}" name="footer"
                                    id="color-code-footer" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del pie de página</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->footer_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-text-footer">
                                <input type="text" value="{{ $settings->footer_text }}" name="footer_text"
                                    id="color-code-text-footer" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Menú administrativo</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->sidebar }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-menu-adm">
                                <input type="text" value="{{ $settings->sidebar }}" name="sidebar"
                                    id="color-code-menu-adm" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del menú administrativo</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->sidebar_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-menu-adm-text">
                                <input type="text" value="{{ $settings->sidebar_text }}" name="sidebar_text"
                                    id="color-code-menu-adm-text" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cintillo</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->cintillo }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-cintillo">
                                <input type="text" value="{{ $settings->cintillo }}" name="cintillo"
                                    id="color-code-cintillo" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Texto del cintillo</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->cintillo_text }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-text-cintillo">
                                <input type="text" value="{{ $settings->cintillo_text }}" name="cintillo_text"
                                    id="color-code-text-cintillo" class="form-control color-code">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Efecto de los botones</label>
                            <div class="input-group input-group-lg input-group-outline">
                                <!-- Input para seleccionar el color -->
                                <input type="color" value="{{ $settings->hover }}"
                                    class="form-control form-control-color color-picker"
                                    data-color-code="color-code-hover">
                                <input type="text" value="{{ $settings->hover }}" name="hover"
                                    id="color-code-hover" class="form-control color-code">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input class="btn btn-velvet mt-4" type="submit" value="Guardar Cambios">
                        </div>


                    </div>
                </div>
            </form>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h4 class="text-dark">Gestiona el carrusel de la página principal</h4>
                <a data-bs-toggle="modal"
                    data-bs-target="#add-tenant-carousel-modal">Nueva Imagen<i
                        class="fa fa-plus me-3 text-dark cursor-pointer"></i></a>
            </div>
            <div class="card-body">
                @if (count($tenantcarousel) != 0)
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner mb-4">
                            @foreach ($tenantcarousel as $key => $carousel)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="page-header min-vh-75 m-3"
                                        style="background-image: url('{{ route('file', $carousel->image) }}');">
                                        <span class="mask bg-gradient-dark"></span>
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-6 my-auto">
                                                    <h4 class="text-white mb-0 fadeIn1 fadeInBottom">
                                                        {{ $carousel->text1 }}</h4>
                                                    <h1 class="text-white fadeIn2 fadeInBottom">{{ $carousel->text2 }}
                                                    </h1>
                                                </div>
                                                <div class="text-center">
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#edit-tenant-carousel-modal{{ $carousel->id }}"><i
                                                            class="fa fa-pencil me-3 text-white cursor-pointer"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @include('admin.tenant-info.carousel-modal-edit')
                            @endforeach
                        </div>
                        <div class="min-vh-75 position-absolute w-100 top-0">
                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon position-absolute bottom-50"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon position-absolute bottom-50"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtener todos los controles de color
            var colorPickers = document.querySelectorAll('.color-picker');

            // Iterar sobre cada control de color
            colorPickers.forEach(function(colorPicker) {
                // Obtener el input de texto asociado a este control de color
                var colorCodeInputId = colorPicker.getAttribute('data-color-code');
                var colorCodeInput = document.getElementById(colorCodeInputId);

                // Manejar el evento change del input de color
                colorPicker.addEventListener('change', function() {
                    // Obtener el valor hexadecimal del color seleccionado
                    var selectedColor = colorPicker.value;

                    // Actualizar el valor del input de texto con el código hexadecimal
                    colorCodeInput.value = selectedColor;
                });

                // Manejar el evento input del input de texto
                colorCodeInput.addEventListener('input', function() {
                    // Obtener el valor del input de texto
                    var enteredColor = colorCodeInput.value;

                    // Validar si el valor ingresado es un código hexadecimal válido
                    if (/^#[0-9A-F]{3}([0-9A-F]{3})?$/i.test(enteredColor)) {
                        // Si el código tiene solo tres caracteres, expandirlo a seis
                        if (enteredColor.length === 4) {
                            enteredColor = '#' + enteredColor[1] + enteredColor[1] + enteredColor[
                                    2] +
                                enteredColor[2] + enteredColor[3] + enteredColor[3];
                        }

                        // Actualizar el color seleccionado en el input de color
                        colorPicker.value = enteredColor;
                    }
                });
            });

            var lazyBackgrounds = document.querySelectorAll('.lazy-background');
            lazyBackgrounds.forEach(function(background) {
                var imageUrl = background.getAttribute('data-background');
                background.style.backgroundImage = 'url(' + imageUrl + ')';
            });
        });
    </script>
@endsection
