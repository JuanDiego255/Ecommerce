@switch($tenantinfo->tenant)
    @case('sakura318')
        <footer>
            <!-- Footer Start-->
            <div class="footer-area footer-padding">
                <div class="container-fluid ">
                    <div class="row d-flex justify-content-between">
                        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-8">
                            <div class="single-footer-caption mb-50">
                                <div class="single-footer-caption mb-30">
                                    <!-- logo -->
                                    <div class="footer-logo mb-35">
                                        <a href="index.html"><img src="{{ route('file', $tenantinfo->logo) }}" alt=""></a>
                                    </div>
                                    <div class="footer-tittle">
                                        <div class="footer-pera">
                                            <p>{{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}</p>
                                        </div>
                                    </div>
                                    <!-- social -->
                                    <div class="footer-social">
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
                                                if (stripos($social->social_network, 'You tube') !== false) {
                                                    $social_logo = 'fab fa-youtube';
                                                }
                                                if (stripos($social->social_network, 'Wordpress') !== false) {
                                                    $social_logo = 'fab fa-wordpress';
                                                }
                                                if (stripos($social->social_network, 'Tik tok') !== false) {
                                                    $social_logo = 'fab fa-tiktok';
                                                }
                                            @endphp

                                            <a href="{{ url($social->url) }}"><i class="{{ $social_logo }}"></i></a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Enlaces directos</h4>
                                    <ul>
                                        @foreach ($categories as $key => $item)
                                            <li><a
                                                    href="{{ url('clothes-category/' . $item->category_id . '/' . $item->department_id) }}">{{ $item->name }}</a>
                                            </li>
                                            @if ($key == 4)
                                                @break
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Políticas</h4>
                                    <ul>
                                        <li><a href="#">Desarollado por Safewor Solutions</a></li>
                                        <li><a href="#">Sakura 318 | © copyright
                                                <script>
                                                    document.write(new Date().getFullYear())
                                                </script>
                                            </a></li>
                                        <li><a href="#">Términos y condiciones</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-tittle">
                                    <h4>Contacto</h4>
                                    <ul>
                                        <li><a href="#"><i class="fa fa-truck"></i>
                                                {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'muebleriasarchi' ? 'Envíos por correos de C.R' : 'Se realizan entregas en todo Costa Rica.' }}</a>
                                        </li>
                                        <li><a target="blank" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"><i
                                                    class="fab fa-whatsapp"></i>
                                                {{ isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : '' }}</a>
                                        </li>
                                        <li><a href="#"><i class="fa fa-envelope"></i>
                                                {{ isset($tenantinfo->email) ? $tenantinfo->email : '' }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @break
    @default
        <div class="container-fluid bg-footer">
            <div class="row pt-5">
                <div class="col-md-3">
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
                                if (stripos($social->social_network, 'You tube') !== false) {
                                    $social_logo = 'fab fa-youtube';
                                }
                                if (stripos($social->social_network, 'Wordpress') !== false) {
                                    $social_logo = 'fab fa-wordpress';
                                }
                                if (stripos($social->social_network, 'Tik tok') !== false) {
                                    $social_logo = 'fab fa-tiktok';
                                }
                            @endphp
                            <li><a target="blank" href="{{ url($social->url) }}"><i class="{{ $social_logo }}"
                                        aria-hidden="true"></i></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @guest
                    <div class="col-md-3">

                        <h5 class="text-footer-col text-uppercase font-weight-bold">Queremos conocerte <i class="fa fa-heart"></i>
                        </h5>
                        <div>
                            <p class="text-footer-col">
                                <a style="text-decoration: none;" class="text-footer-col" href="{{ route('register') }}">
                                    <i class="fa fa-envelope"></i>
                                    {{ isset($tenantinfo->title_suscrib_a) ? $tenantinfo->title_suscrib_a : '' }}
                                </a><br>

                                <i class="fas fa-percentage"></i>
                                {{ isset($tenantinfo->description_suscrib) ? $tenantinfo->description_suscrib : '' }}

                            </p>
                        </div>

                    </div>
                @endguest

                <div class="col-md-3">
                    <h5 class="text-uppercase text-footer-col font-weight-bold">Más Información!</h5>
                    <div>
                        <p class="text-footer-col">
                            <a style="text-decoration: none;" class="text-footer-col" href="#">
                                <i
                                    class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'muebleriasarchi' ? 'fa fa-envelope' : 'fa fa-truck' }}"></i>
                                {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'muebleriasarchi' ? 'Envíos por correos de C.R' : 'Se realizan entregas en todo Costa Rica.' }}
                            </a><br>
                            <a target="blank" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                                class="text-footer">
                                <i class="fab fa-whatsapp"></i>
                                {{ isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : '' }}
                            </a>
                            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant == 'muebleriasarchi')
                                <br>
                                <a target="blank" href="{{ url('https://wa.me/50689420339') }}" class="text-footer">
                                    <i class="fab fa-whatsapp"></i>
                                    89420339
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
                @if (isset($tenantinfo->mision))
                    <div class="col-md-3">
                        <h5 class="text-uppercase text-footer-col font-weight-bold">Sobre nosotros...</h5>
                        <div>
                            <p class="text-footer-col">
                                {{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}
                            </p>
                        </div>
                    </div>
                @endif

            </div>
            <hr class="dark horizontal text-danger my-0 mt-2 mb-4">
            <div class="copyright text-center text-lg text-footer-col pb-4 text-uppercase">
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script>,
                <a href="#" class="font-weight-bold text-footer"
                    target="_blank">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
                {{ isset($tenantinfo->footer) ? $tenantinfo->footer : '' }}

            </div>

        </div>
    @break
@endswitch
