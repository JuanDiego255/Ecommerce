{{-- Anti-bot: honeypot + time token. No external dependencies.
     Pair with ChecksBotProtection trait in the controller. --}}
<input
    type="text"
    name="_hp_website"
    value=""
    style="display:none!important;opacity:0;position:absolute;top:-9999px;left:-9999px;"
    tabindex="-1"
    autocomplete="off"
    aria-hidden="true"
>
<input type="hidden" name="_form_token" value="{{ encrypt(now()->timestamp) }}">
