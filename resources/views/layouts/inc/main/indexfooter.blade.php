<center>
    <div class="container-fluid bg-footer">
        <div class="row pt-5">

            <div class="col-md-4">
                <ul class="ul-icon">
                    @foreach ($social_network as $social)
                        @php
                            $social_logo = null;
                            if (stripos($social->social_network, 'Facebook') !== false) {
                                $social_logo = 'fab fa-facebook';
                            } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                $social_logo = 'fab fa-instagram';
                            } elseif (stripos($social->social_network, 'Twitter') !== false) {
                                $social_logo = 'fab fa-twitter';
                            } elseif (stripos($social->social_network, 'LinkedIn') !== false) {
                                $social_logo = 'fab fa-linkedin';
                            }
                        @endphp
                        <li><a target="blank" href="{{ url($social->url) }}"><i class="{{ $social_logo }}"
                                    aria-hidden="true"></i></a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4 mt-5">
                @guest
                    <h5 class="text-uppercase">Queremos conocerte <i class="fa fa-heart"></i></h5>
                    <div>
                        <p class="text-footer text-uppercase text-lg">
                            <a style="text-decoration: none;" class="mr-5 text-footer text-lg"
                                href="{{ route('register') }}">
                                <i class="fa fa-envelope"></i>
                                {{ isset($tenantinfo->title_suscrib_a) ? $tenantinfo->title_suscrib_a : '' }}
                            </a><br>

                            <i class="fas fa-percentage"></i>
                            {{ isset($tenantinfo->description_suscrib) ? $tenantinfo->description_suscrib : '' }}

                        </p>
                    </div>
                @endguest

            </div>
            <div class="col-md-4">
                <h5 class="text-uppercase text-footer-title">Más Información!</h5>
                <div>
                    <p class="text-footer text-uppercase text-lg">
                        <a style="text-decoration: none;" class="mr-5 text-footer" href="{{ isset($tenantinfo->email) ? 'mailto:'.$tenantinfo->email : '#' }}">
                            <i class="fa fa-envelope"></i> {{ isset($tenantinfo->email) ? $tenantinfo->email : '' }}
                        </a><br>
                        <a target="blank" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                            class="text-footer">
                            <i class="fab fa-whatsapp"></i>
                            {{ isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : '' }}
                        </a>
                    </p>
                </div>
            </div>

        </div>
        <hr class="dark horizontal text-danger my-0 mt-2 mb-4">
        <div class="copyright text-center text-lg text-footer pb-4 text-uppercase">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script>,
            <a href="#" class="font-weight-bold text-footer"
                target="_blank">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
            {{ isset($tenantinfo->footer) ? $tenantinfo->footer : '' }}

        </div>

    </div>


</center>
