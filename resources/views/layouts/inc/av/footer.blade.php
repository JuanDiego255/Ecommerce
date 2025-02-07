<!-- footer start -->
<footer class="footer">
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
                                <li><a href="#">{{$item->name}}</a></li>
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
</footer>
<!--/ footer end  -->
