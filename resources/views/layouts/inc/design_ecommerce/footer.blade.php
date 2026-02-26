<!-- footer start -->
{{-- <footer class="footer">
    <div class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-md-6 col-lg-3">
                    <div class="footer_widget">
                        <div class="footer_logo">
                            <a href="#">
                                <img src="{{ route('file', $tenantinfo->logo) }}" alt="" />
                            </a>
                        </div>

                        <div class="socail_links">
                            <ul>
                                @foreach ($social_network as $social)
                                    @php
                                        $social_logo = null;
                                        if (stripos($social->social_network, 'Facebook') !== false) {
                                            $social_logo = 'fab fa-facebook';
                                        } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                            $social_logo = 'fab fa-instagram';
                                        }
                                        if (stripos($social->social_network, 'Twitter') !== false) {
                                            $social_logo = 'fab fa-twitter';
                                        }
                                        if (stripos($social->social_network, 'Linkedin') !== false) {
                                            $social_logo = 'fab fa-linkedin';
                                        }
                                    @endphp
                                    <li>
                                        <a href="{{ url($social->url) }}">
                                            <i class="{{ $social_logo }}"></i>
                                        </a>
                                    </li>
                                @endforeach

                                <li>
                                    <a href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-lg-3">
                    <div class="footer_widget">
                        <h3 class="footer_title">Servicios</h3>
                        <ul>
                            @foreach ($categories as $key => $item)
                                <li><a href="#">{{ $item->name }}</a></li>
                                @if ($key >= 5)
                                    @break
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-lg-2">
                    <div class="footer_widget">
                        <h3 class="footer_title">Atajos</h3>
                        <ul>
                            <li><a href="{{ url('/') }}">Inicio</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#"> Contacto</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 col-lg-4">
                    <div class="footer_widget">
                        <h3 class="footer_title">Subscribirse</h3>
                        <form action="#" class="newsletter_form">
                            <input type="text" placeholder="Ingrese su E-mail" />
                            <button type="submit">Iniciar</button>
                        </form>
                        <p class="newsletter_text">
                            Suscríbete y conoce novedades acerca de AV Electromecánica
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copy-right_text">
        <div class="container">
            <div class="footer_border"></div>
            <div class="row">
                <div class="col-xl-12">
                    <p class="copy_right text-center">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        Todos los derechos reservados | Desarrollado para {{ $tenantinfo->title }}
                        por
                        <a href="https://colorlib.com" target="_blank">Safewor Solutions</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer> --}}
<!-- Footer -->
<footer class="bg3 p-t-75 p-b-32">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Atajos
                </h4>

                <ul>
                    @foreach ($categories_all as $key => $item)
                        <li class="p-b-10">
                            <a href="#" class="stext-107 cl7 hov-cl1 trans-04">
                                {{ $item->name }}
                            </a>
                        </li>
                        @if ($key >= 3)
                            @break
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Ayuda
                </h4>

                <ul>
                    @if (!isset($tenantinfo->tenant) || $tenantinfo->tenant !== 'main')
                        <li class="p-b-10">
                            <a href="{{ url('/buys') }}" class="stext-107 cl7 hov-cl1 trans-04">
                                Mis Compras
                            </a>
                        </li>
                    @endif

                    <li class="p-b-10">
                        <a href="{{ url('/about_us') }}" class="stext-107 cl7 hov-cl1 trans-04">
                            Acerca De
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="{{ url('/privacy-policy') }}" class="stext-107 cl7 hov-cl1 trans-04">
                            Política de Privacidad
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    MAS INFORMACION
                </h4>

                <p class="stext-107 cl7 size-201">                    
                    {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'muebleriasarchi' ? 'Envíos por correos de C.R' : 'Se realizan entregas en todo Costa Rica.' }}<br>
                    <i class="fa fa-whatsapp"></i> {{ isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : '' }}<br>
                    <i class="fa fa-envelope"></i> {{ isset($tenantinfo->email) ? $tenantinfo->email : '' }}
                </p>

                <div class="p-t-27">
                    @foreach ($social_network as $social)
                        @php
                            $social_logo = null;
                            if (stripos($social->social_network, 'Facebook') !== false) {
                                $social_logo = 'fa fa-facebook';
                            } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                $social_logo = 'fa fa-instagram';
                            } elseif (stripos($social->social_network, 'Twitter') !== false) {
                                $social_logo = 'fa fa-twitter';
                            } elseif (stripos($social->social_network, 'LinkedIn') !== false) {
                                $social_logo = 'fa fa-linkedin';
                            }
                            if (stripos($social->social_network, 'You tube') !== false) {
                                $social_logo = 'fa fa-youtube';
                            }
                            if (stripos($social->social_network, 'Wordpress') !== false) {
                                $social_logo = 'fa fa-wordpress';
                            }
                            if (stripos($social->social_network, 'Tik tok') !== false) {
                                $social_logo = 'fa fa-tiktok';
                            }
                        @endphp
                        <a href="{{ url($social->url) }}" target="blank" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                            <i class="{{ $social_logo }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Sobre Nosotros...
                </h4>

                <p class="stext-107-footer cl7 size-201">                    
                    {{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}
                </p>
            </div>
        </div>

        <div class="p-t-40">
            {{-- <div class="flex-c-m flex-w p-b-18">
                <a href="#" class="m-all-1">
                    <img src="design_ecommerce/images/icons/icon-pay-01.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="design_ecommerce/images/icons/icon-pay-02.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="design_ecommerce/images/icons/icon-pay-03.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="design_ecommerce/images/icons/icon-pay-04.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="design_ecommerce/images/icons/icon-pay-05.png" alt="ICON-PAY">
                </a>
            </div> --}}

            <p class="stext-107 cl7 txt-center">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script>,
                <a href="#" class="font-weight-bold text-footer"
                    target="_blank">{{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</a>
                {{ isset($tenantinfo->footer) ? $tenantinfo->footer : '' }}

            </p>
        </div>
    </div>
</footer>
<!--/ footer end  -->
