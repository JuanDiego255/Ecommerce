<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2"><a href="{{ url('/') }}" class="logo">AUTOS<span> GRECIA SRL</span></a>
                    </h2>
                    <p></p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        @foreach ($social_network as $social)
                            @php
                                $social_logo = null;
                                if (stripos($social->social_network, 'Facebook') !== false) {
                                    $social_logo = 'icon-facebook';
                                } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                    $social_logo = 'icon-instagram';
                                } elseif (stripos($social->social_network, 'Twitter') !== false) {
                                    $social_logo = 'icon-twitter';
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
                            <li class="ftco-animate"><a target="blank" href="{{ url($social->url) }}"><span
                                        class="{{ $social_logo }}"></span></a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md">
                <div class="ftco-footer-widget mb-4 ml-md-5">
                    <h2 class="ftco-heading-2">Información</h2>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/#about_us') }}" class="py-2 d-block">Acerca de</a></li>
                        <li><a href="#" class="py-2 d-block">Terminos y condiciones</a></li>
                        <li><a href="#" class="py-2 d-block">Politicas de privacidad</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">¿Tienes alguna consulta?</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">350 metros este de la
                                    Gasolinera Delta de Grecia , Grecia, Costa Rica</span></li>
                            <li><a href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"><span
                                        class="icon icon-phone"></span><span
                                        class="text">{{ $tenantinfo->whatsapp }}</span></a></li>
                            <li><a href="#"><span class="icon icon-envelope"></span><span
                                        class="text">{{ $tenantinfo->email }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">

                <p> 
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
    </div>
</footer>
