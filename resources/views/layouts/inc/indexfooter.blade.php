<center>
    <div class="container-fluid bg-footer">

        <div class="row mt-5 pt-5">

            <div class="col-md-4">
                <h5 class="text-uppercase text-muted-title">Redes Sociales</h5>
                <div>
                    <p class="text-muted text-uppercase text-lg">
                        @foreach ($social_network as $social)
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
                            <a data-bs-toggle="modal" data-bs-target="#edit-tenant-social-modal{{ $social->id }}"
                                class="mr-5 text-muted" href="{{$social->url}}">
                                <i class="{{ $social_logo }}"> {{ $social->social_network }}</i>
                            </a><br>
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="col-md-4 mt-5">
                @guest
                    <h5 class="text-uppercase">Queremos conocerte <i class="fa fa-heart"></i></h5>
                    <div>
                        <p class="text-muted text-uppercase text-lg">
                            <a style="text-decoration: none;" class="mr-5 text-muted text-lg"
                                href="{{ route('register') }}">
                                <i class="fa fa-envelope"></i> {{isset($tenantinfo->title_suscrib_a) ? $tenantinfo->title_suscrib_a : ''}}
                            </a><br>

                            <i class="fas fa-percentage"></i> {{isset($tenantinfo->description_suscrib) ? $tenantinfo->description_suscrib : ''}}

                        </p>
                    </div>
                @endguest

            </div>
            <div class="col-md-4">
                <h5 class="text-uppercase text-muted-title">Más Información!</h5>
                <div>
                    <p class="text-muted text-uppercase text-lg">
                        <a style="text-decoration: none;" class="mr-5 text-muted" href="#">
                            <i class="fa fa-envelope"></i> Envíos por correos de C.R
                        </a><br>
                        <a href="#" class="text-muted">
                            <i class="fa fa-whatsapp"> {{isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : ''}}</i>
                        </a>
                    </p>
                </div>
            </div>

        </div>
        <hr class="dark horizontal text-danger my-0 mt-2 mb-4">
        <div class="copyright text-center text-lg text-muted mb-4 pb-4 text-uppercase">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>,
            <a href="#" class="font-weight-bold" target="_blank">{{isset($tenantinfo->title) ? $tenantinfo->title : ''}}</a>
            {{isset($tenantinfo->footer) ? $tenantinfo->footer : ''}}

        </div>
    </div>


</center>
