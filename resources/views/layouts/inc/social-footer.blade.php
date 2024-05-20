<aside class="social-sharing">
    <ul class="menu-social">
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
            <li class="social-item"><a data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $social->social_network }}" href="{{ url($social->url) }}" target="blank"><span
                        class="screen-reader-text">{{ $social->social_network }}</span></a></li>
        @endforeach
        <li class="social-item"><a target="blank" data-bs-toggle="tooltip" data-bs-placement="top" title="WhatsApp" href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}"
                data-action="share/whatsapp/share"><span class="screen-reader-text">Whatsapp</span></a></li>
        <li class="social-item newsletter"><a data-bs-toggle="tooltip" data-bs-placement="top" title="Blog" target="blank" href="{{ url('blog/index') }}"
                data-action="share/whatsapp/share"><span class="screen-reader-text">Whatsapp</span></a></li>
        <li class="social-item"><a type="button"
                data-bs-toggle="modal" data-bs-target="#add-comment-modal" href="#"
                data-action="share/whatsapp/share"><span class="screen-reader-text">Whatsapp</span></a></li>
    </ul>
</aside>
