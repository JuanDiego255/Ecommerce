<!DOCTYPE html>
<html>

<head>
    <title>Gift Card</title>
    <style>
        :root {
            --navbar: {{ $settings->navbar }};
            --navbar_text: {{ $settings->navbar_text }};
            --btn_cart: {{ $settings->btn_cart }};
            --btn_cart_text: {{ $settings->btn_cart_text }};
            --footer: {{ $settings->footer }};
            --title_text: {{ $settings->title_text }};
            --footer_text: {{ $settings->footer_text }};
            --sidebar: {{ $settings->sidebar }};
            --sidebar_text: {{ $settings->sidebar_text }};
            --hover: {{ $settings->hover }};
            --cart_icon: {{ $settings->cart_icon }};
            --cintillo: {{ $settings->cintillo }};
            --cintillo_text: {{ $settings->cintillo_text }};
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card-container {
            width: 600px;
            border: 2px solid #f0d3ac;
            border-radius: 10px;
            padding: 20px 20px;
            background: #fff8ea;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
        }

        .gift-title {
            font-size: 36px;
            margin-bottom: 20px;
            font-family: 'Pacifico', cursive;
            color: #f6c17b;
        }

        .input-group-gift {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group-gift label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 18px;
            color: #f6c17b;
        }

        .input-group-gift input {
            width: 95%;
            padding: 10px;
            padding-right: 10px;
            padding-left: 10px;
            border: none;
            background-color: #f0d3ac;
            font-size: 18px;
            border-radius: 5px;
        }

        .footer-gift {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            color: #888;
        }

        .text-muted {
            color: #f6c17b !important;
            font-size: 16px;
            font-family: 'Montserrat', sans-serif !important;
        }

        .card-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('path-to-your-background-image.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 10px;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="card-container">
        <div class="card-background"></div>
        <h1 class="gift-title">{{ $tenantinfo->title }}</h1>
        <div class="card">
            <div class="input-group-gift">
                <label for="for">Para:</label>
                <input readonly value="{{ $gift->for }}" type="text" id="for" name="for">
            </div>
            <div class="input-group-gift">
                <label for="by">De:</label>
                <input readonly type="text" value="{{ $gift->by }}" id="by" name="by">
            </div>
            <div class="input-group-gift">
                <label for="mount">Monto:</label>
                <input readonly type="text" value="{{ number_format($gift->mount) }}  colones" id="mount" name="mount">
            </div>
            <div class="input-group-gift">
                <label for="code">Código (No lo compartas con nadie)</label>
                <input readonly type="text" value="{{ $gift->code }}" id="code" name="code">
            </div>
        </div>
        <p class="text-muted">Has adquirido esta tarjeta de regalo para canjear en el sitio web
            {{ $tenantinfo->title }}.
            <br>Al ingresar al siguiente link podrás escoger los productos de tu gusto
            https://{{ $tenantinfo->tenant }}.safeworsolutions.com
        </p>
    </div>
</body>

</html>
