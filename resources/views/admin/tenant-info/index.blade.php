@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        @include('admin.tenant-info.social-modal')
        <hr class="hr-servicios">
        @foreach ($tenant_info as $item)
            <form class="form-horizontal" action="{{ url('tenant-info/update/' . $item->id) }}" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-dark">Página Principal, Pie De Página, y Redes Sociales.</h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->title) ? 'is-filled' : '' }}">
                                    <label class="form-label">Empresa</label>
                                    <input value="{{ isset($item->title) ? $item->title : '' }}" required type="text"
                                        class="form-control form-control-lg @error('title') is-invalid @enderror"
                                        name="title" id="title">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Instagram (Esta descripción se mostrará en la sección de
                                    descuentos en la página de inicio)</label>
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->title_discount) ? 'is-filled' : '' }} my-3">

                                    <textarea placeholder="Descripción descuento: Esta descripción se mostrará en la sección de descuentos del cliente"
                                        type="text" class="form-control form-control-lg @error('title_discount') is-invalid @enderror"
                                        name="title_discount" id="title_discount">{{ isset($item->title_discount) ? $item->title_discount : '' }}</textarea>
                                    @error('title_discount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Instagram (Esta descripción se mostrará en la sección de Instagram
                                    en la página de inicio)</label>
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->title_instagram) ? 'is-filled' : '' }} my-3">

                                    <textarea
                                        placeholder="Descripción instagram: Esta descripción se mostrará en la sección de Instagram en la página principal"
                                        type="text" class="form-control form-control-lg @error('title_instagram') is-invalid @enderror"
                                        name="title_instagram" id="title_instagram">{{ isset($item->title_instagram) ? $item->title_instagram : '' }}</textarea>
                                    @error('title_instagram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Instagram (Esta descripción se mostrará en la sección Misión en la
                                    página de inicio)</label>
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->mision) ? 'is-filled' : '' }} my-3">

                                    <textarea placeholder="Misión: Esta misión se mostrará en la sección de Misión en la página principal" required
                                        type="text" class="form-control form-control-lg @error('mision') is-invalid @enderror" name="mision"
                                        id="mision">{{ isset($item->mision) ? $item->mision : '' }}</textarea>
                                    @error('mision')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Instagram (Esta descripción se mostrará en la sección de productos
                                    en tendencia en la página de inicio)</label>
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->title_trend) ? 'is-filled' : '' }} my-3">

                                    <textarea
                                        placeholder="Descripción Tendencia: Esta descripción se mostrará en la sección de artículos en tendencia en la página principal"
                                        type="text" class="form-control form-control-lg @error('title_trend') is-invalid @enderror" name="title_trend"
                                        id="title_trend">{{ isset($item->title_trend) ? $item->title_trend : '' }}</textarea>
                                    @error('title_trend')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->title_suscrib_a) ? 'is-filled' : '' }} my-3">
                                    <label class="form-label">Título suscripción: este título es el enlace directo para ir a
                                        suscribirse</label>
                                    <input value="{{ isset($item->title_suscrib_a) ? $item->title_suscrib_a : '' }}"
                                        type="text"
                                        class="form-control form-control-lg @error('title_suscrib_a') is-invalid @enderror"
                                        name="title_suscrib_a" id="title_suscrib_a">
                                    @error('title_suscrib_a')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->description_suscrib) ? 'is-filled' : '' }} my-3">
                                    <label class="form-label">Descripción suscripción: esta descripción es el cuerpo del
                                        mensaje
                                        que incita al
                                        usuario a suscribirse</label>
                                    <input
                                        value="{{ isset($item->description_suscrib) ? $item->description_suscrib : '' }}"
                                        type="text"
                                        class="form-control form-control-lg @error('description_suscrib') is-invalid @enderror"
                                        name="description_suscrib" id="description_suscrib">
                                    @error('description_suscrib')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->footer) ? 'is-filled' : '' }} my-3">
                                    <label class="form-label">Pie de página: esta descripción es el pie de página,
                                        normalmente
                                        es una frase llamativa</label>
                                    <input value="{{ isset($item->footer) ? $item->footer : '' }}" type="text"
                                        class="form-control form-control-lg @error('footer') is-invalid @enderror"
                                        name="footer" id="footer">
                                    @error('footer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 col-md-12">
                    <div class="card-header">
                        <h4 class="text-dark">Información Del Negocio</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->delivery) ? 'is-filled' : '' }}">
                                    <label class="form-label">Precio de envío</label>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" required
                                        value="{{ isset($item->delivery) ? $item->delivery : '' }}" type="text"
                                        class="form-control form-control-lg @error('delivery')
is-invalid
@enderror"
                                        name="delivery" id="delivery">
                                    @error('delivery')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->whatsapp) ? 'is-filled' : '' }}">
                                    <label class="form-label">WhatsApp</label>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" required
                                        value="{{ isset($item->whatsapp) ? $item->whatsapp : '' }}" type="text"
                                        class="form-control form-control-lg @error('whatsapp')
is-invalid
@enderror"
                                        name="whatsapp" id="whatsapp">
                                    @error('whatsapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->sinpe) ? 'is-filled' : '' }}">
                                    <label class="form-label">SINPE Móvil</label>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" required
                                        value="{{ isset($item->sinpe) ? $item->sinpe : '' }}" type="text"
                                        class="form-control form-control-lg @error('sinpe')
is-invalid
@enderror"
                                        name="sinpe" id="sinpe">
                                    @error('sinpe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->count) ? 'is-filled' : '' }}">
                                    <label class="form-label">Cuenta bancaria</label>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                        value="{{ isset($item->count) ? $item->count : '' }}" type="text"
                                        class="form-control form-control-lg @error('count')
is-invalid
@enderror"
                                        name="count" id="count">
                                    @error('count')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mt-4">
                                <div
                                    class="input-group input-group-lg input-group-outline {{ isset($item->email) ? 'is-filled' : '' }}">
                                    <label class="form-label">E-mail</label>
                                    <input placeholder="Este E-mail es para recibir correos cuando se realiza una compra"
                                        value="{{ isset($item->email) ? $item->email : '' }}" type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        name="email" id="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card mt-4 col-md-12">
                    <div class="card-header">
                        <h4 class="text-dark">Logo, Imagen de Inicio De Sesión</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                @if ($item->logo)
                                    <img style="width: 100px; height:100px;" class="img-fluid img-thumbnail"
                                        src="{{ route('file', $item->logo) }}" alt="image">
                                @endif
                                <label class="form-label">Logo</label>
                                <div class="input-group input-group-static mb-4">
                                    <input class="form-control" type="file" name="logo">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                @if ($item->login_image)
                                    <img class="img-fluid img-thumbnail" style="width: 100px; height:100px;"
                                        src="{{ route('file', $item->login_image) }}" alt="image">
                                @endif
                                <label class="form-label">Imagen Login</label>
                                <div class="input-group input-group-static mb-4">
                                    <input class="form-control" type="file" name="login_image">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <input class="btn btn-velvet mt-4" type="submit" value="Guardar Cambios">
                <hr class="dark horizontal text-danger">
                <div class="card w-50">
                    <div class="card-body text-center">
                        <h5 class="text-uppercase text-muted-title">Redes Sociales <a href="#"
                                data-bs-toggle="modal" data-bs-target="#add-tenant-social-modal"><i
                                    class="fa fa-plus me-3"></i></a></h5>
                        <div>
                            <p class="text-muted text-uppercase text-lg">
                                @foreach ($tenantsocial as $social)
                                    @include('admin.tenant-info.social-modal-edit')
                                    @php
                                        $social_logo = null;
                                        if (stripos($social->social_network, 'Facebook') !== false) {
                                            $social_logo = 'fa fa-facebook';
                                        } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                            $social_logo = 'fa fa-instagram';
                                        }
                                        if (stripos($social->social_network, 'Twitter') !== false) {
                                            $social_logo = 'fa fa-twitter';
                                        }
                                    @endphp
                                    <a data-bs-toggle="modal"
                                        data-bs-target="#edit-tenant-social-modal{{ $social->id }}"
                                        class="mr-5 text-muted" href="#">
                                        <i class="{{ $social_logo }}"> {{ $social->social_network }} <i
                                                class="fa fa-edit"></i></i>
                                    </a>
                                    <form id="deleteForm{{ $social->id }}" method="post"
                                        action="{{ url('/delete/tenant-social/' . $social->id) }}"
                                        style="display:inline">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <a href="#" class="mr-5 text-muted"
                                            onclick="confirmAndSubmit({{ $social->id }})"
                                            style="text-decoration: none;"><i class="fa fa-minus-circle me-3"></i>
                                        </a>
                                    </form><br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        @endforeach


    </div>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        function confirmAndSubmit(id) {
            if (confirm('¿Deseas borrar esta red social?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }

        function confirmAndSubmitCar(id) {
            if (confirm('¿Deseas borrar esta imagen del carousel?')) {
                document.getElementById('deleteFormCarousel' + id).submit();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var lazyBackgrounds = document.querySelectorAll('.lazy-background');
            lazyBackgrounds.forEach(function(background) {
                var imageUrl = background.getAttribute('data-background');
                background.style.backgroundImage = 'url(' + imageUrl + ')';
            });
        });
    </script>
@endsection
