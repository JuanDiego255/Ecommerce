<footer id="contact">
    <!--? Footer Start-->
    <div class="footer-area section-bg" data-background="{{ asset('/barber/img/gallery/footer_bg.png') }}">
        <div class="container">
            <div class="footer-top footer-padding">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <!-- logo -->
                            <div class="footer-logo">
                                <a href="index.html"><img src="assets/img/logo/logo2_footer.png" alt=""></a>
                            </div>
                            <div class="footer-tittle">
                                <div class="footer-pera">
                                    <p class="info1">Si buscas información aquí tienen nuestro distintos contactos.</p>
                                </div>
                            </div>
                            <div class="footer-number">
                                <a href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}">
                                    <h4><span>+506
                                        </span>{{ isset($tenantinfo->whatsapp) ? $tenantinfo->whatsapp : '' }}
                                    </h4>
                                </a>
                                <a href="{{ isset($tenantinfo->email) ? 'mailto:' . $tenantinfo->email : '#' }}">
                                    <p>{{ isset($tenantinfo->email) ? $tenantinfo->email : '' }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Servicios </h4>
                                <ul>
                                    @foreach ($barber_services as $key => $item)
                                        <li><a href="#">{{ $item->nombre }}</a></li>
                                        @if ($key >= 5)
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-3 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Barberos</h4>
                                <ul>
                                    @foreach ($barbers as $key => $item)
                                        <li><a href="{{ url('/barberos/' . $item->id . '/agendar/') }}">{{ $item->nombre }}</a></li>
                                        @if ($key >= 5)
                                            @break
                                        @endif
                                    @endforeach                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>¡HAZ PARTE DE NUESTRO EQUIPO!</h4>
                                <div class="footer-pera">
                                    <p class="info1">Suscríbete y obtén boletín informativo</p>
                                </div>
                            </div>
                            <!-- Form -->
                            <div class="footer-form">
                                <div id="mc_embed_signup">
                                    <form target="_blank"
                                        action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                        method="get" class="subscribe_form relative mail_part" novalidate="true">
                                        <input type="email" name="EMAIL" id="newsletter-form-email"
                                            placeholder=" Email Address " class="placeholder hide-on-focus"
                                            onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Your email address'">
                                        <div class="form-icon">
                                            <button type="submit" name="submit" id="newsletter-submit"
                                                class="email_icon newsletter-submit button-contactForm">Enviar</button>
                                        </div>
                                        <div class="mt-10 info"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-xl-9 col-lg-8">
                        <div class="footer-copy-right">
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                Copyright &copy;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> Todos los derechos reservados | Desarrollado para
                                {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }} por <a
                                    href="https://colorlib.com" target="_blank">Safewor Solutions</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <!-- Footer Social -->
                        <div class="footer-social f-right">

                            <a href="https://www.facebook.com/sai4ull"><i class="fab fa-facebook-f"></i></a>

                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>
