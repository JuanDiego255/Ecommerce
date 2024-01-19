@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @include('admin.tenant-info.add')
    @include('admin.tenant-info.social-modal')
    <div class="container">

        <h2 class="text-center font-title"><strong>Administra la información de tu negocio.</strong>
        </h2>

        <hr class="hr-servicios">

        @if ($tenantinfo->isEmpty())
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-tenant-info-modal" class="btn btn-velvet">Agregar
                Información</button>
        @else
            @foreach ($tenantinfo as $item)
                @include('admin.tenant-info.edit')
                <button type="button" data-bs-toggle="modal" data-bs-target="#edit-tenant-info-modal{{ $item->id }}"
                    class="btn btn-velvet">Editar
                    Información</button>
            @endforeach
        @endif

        <center>


            @foreach ($tenantinfo as $item)
                <hr class="dark horizontal text-danger mb-3">
                <div class="flex3 text-center" id="siteBrand">
                    <img style="width: 100px; height:100px;" class="img-fluid img-thumbnail"
                        src="{{ tenant_asset('/') . '/' . $item->logo }}" alt="image">
                </div>
                <span class="text-s">Sección: Logo</span>
                <hr class="dark horizontal text-danger mb-3">
                <div class="flex3 text-center" id="siteBrand">
                    {{ $item->title }}
                </div>
                <span class="text-s">Sección: Negocio</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <h3 class="text-center text-muted">{{ $item->title_discount }}</h3>
                </div>
                <span class="text-s">Sección: Productos en descuento</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <span class="text-muted text-center"><a href="#">Instagram</a> |
                        {{ $item->title_instagram }}</span>
                </div>
                <span class="text-s">Sección: Instagram</span>
                <hr class="dark horizontal text-danger">
                <div class="bg-footer p-3 text-center">
                    <h3 class="text-center text-title">{{ $item->title }}</h3>
                    <span class="text-center text-muted">{{ $item->mision }}</span>
                </div>
                <span class="text-s">Sección: misión de la empresa</span>
                <hr class="dark horizontal text-danger">
                <div class="text-center">
                    <h3 class="text-center text-muted">{{ $item->title_trend }}</h3>
                </div>
                <span class="text-s">Sección: Productos en tendencia</span>
                <hr class="dark horizontal text-danger">
                <center>
                    <div class="container-fluid bg-footer">

                        <div class="row mt-5 pt-5">

                            <div class="col-md-4">
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
                                                    onclick="confirmAndSubmit({{$social->id}})"
                                                    style="text-decoration: none;"><i class="fa fa-minus-circle me-3"></i>
                                                </a>
                                            </form><br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 mt-5">

                                <h5 class="text-uppercase">Queremos conocerte <i class="fa fa-heart"></i></h5>
                                <div>
                                    <p class="text-muted text-uppercase text-lg">
                                        <a style="text-decoration: none;" class="mr-5 text-muted text-lg"
                                            href="{{ route('register') }}">
                                            <i class="fa fa-envelope"></i>{{ $item->title_suscrib_a }}
                                        </a>
                                    </p>
                                    <span class="text-s">Sección: Título suscripción</span>
                                    <p class="text-muted text-uppercase text-lg">
                                        <i class="fas fa-percentage"></i>{{ $item->description_suscrib }}
                                    </p>
                                    <span class="text-s">Sección: Descripción suscripción</span>


                                </div>


                            </div>
                            <div class="col-md-4">
                                <h5 class="text-uppercase text-muted-title">Más Información!</h5>
                                <div>
                                    <p class="text-muted text-uppercase text-lg">
                                        <a style="text-decoration: none;" class="mr-5 text-muted" href="#">
                                            <i class="fa fa-envelope"></i> Envíos por correos de C.R
                                        </a><br>
                                        <a href="#" class="text-muted">
                                            <i class="fa fa-whatsapp"> {{ $item->whatsapp }}</i>
                                        </a>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <hr class="dark horizontal text-danger my-0 mt-2">
                        <div class="copyright text-center text-lg text-muted pb-4 text-uppercase">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            <a href="#" class="font-weight-bold" target="_blank">{{ $item->title }}</a>
                            {{ $item->footer }}

                        </div>
                        <span class="text-s">Sección: Pie de página</span>
                    </div>


                </center>
            @endforeach

        </center>
    </div>
@endsection
@section('script')
    <script>
        function confirmAndSubmit(id) {
            
            if (confirm('¿Deseas borrar esta red social?')) {
                document.getElementById('deleteForm'+id).submit();
            }
        }
    </script>
@endsection
