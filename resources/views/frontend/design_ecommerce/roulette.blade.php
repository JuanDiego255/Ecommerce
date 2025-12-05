@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <style>
        .roulette-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            padding: 20px;
        }

        .roulette-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.15),
                0 0 0 2px rgba(255, 255, 255, 0.8);
            max-width: 480px;
            width: 100%;
            padding: 24px 24px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .roulette-card::before {
            content: "";
            position: absolute;
            inset: -80px;
            background: conic-gradient(from 180deg,
                    rgba(255, 99, 132, 0.18),
                    rgba(54, 162, 235, 0.18),
                    rgba(255, 206, 86, 0.18),
                    rgba(75, 192, 192, 0.18),
                    rgba(153, 102, 255, 0.18),
                    rgba(255, 159, 64, 0.18),
                    rgba(255, 99, 132, 0.18));
            opacity: 0.5;
            z-index: -1;
            filter: blur(50px);
        }

        .roulette-title {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 4px;
            background: linear-gradient(90deg, #ff4b5c, #ff9a3c);
            -webkit-background-clip: text;
            color: transparent;
        }

        .roulette-subtitle {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 18px;
        }

        .roulette-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }

        .roulette-wheel-container {
            position: relative;
            width: 260px;
            height: 260px;
        }

        .roulette-wheel {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 6px solid #ffffff;
            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.2),
                0 0 0 6px rgba(255, 255, 255, 0.9);
            background: radial-gradient(circle at center, #2b2b3d 0%, #111322 55%, #000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 4s cubic-bezier(0.33, 1, 0.68, 1);
            transform: rotate(0deg);
            overflow: hidden;
        }

        /* Luces tipo casino alrededor */
        .roulette-lights-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            background:
                radial-gradient(circle at 0 50%, #ffd95e 0 4px, transparent 4px) 0 0 / 22px 22px repeat-x,
                radial-gradient(circle at 100% 50%, #ffd95e 0 4px, transparent 4px) 0 0 / 22px 22px repeat-x;
            mix-blend-mode: screen;
            pointer-events: none;
            animation: roulette-lights 1.4s linear infinite;
            opacity: 0.8;
        }

        @keyframes roulette-lights {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .roulette-inner-circle {
            width: 40%;
            height: 40%;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 20%, #fff7d6, #ffd86f);
            box-shadow:
                0 0 0 3px rgba(255, 255, 255, 0.9),
                inset 0 4px 8px rgba(255, 255, 255, 0.9),
                inset 0 -4px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #a05a00;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-align: center;
            padding: 6px;
            z-index: 3;
        }

        .roulette-pointer {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 16px solid transparent;
            border-right: 16px solid transparent;
            border-bottom: 30px solid #ff1744;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.4));
            z-index: 5;
        }

        .roulette-glow {
            position: absolute;
            inset: 16px;
            border-radius: 50%;
            pointer-events: none;
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.9);
            transition: box-shadow 0.4s ease-out;
            z-index: 1;
        }

        .roulette-wheel.spinning+.roulette-glow {
            box-shadow:
                0 0 25px 10px rgba(255, 255, 255, 0.85),
                0 0 80px 30px rgba(255, 111, 145, 0.6);
        }

        /* Segmentos dinámicos */
        .roulette-segment {
            position: absolute;
            width: 50%;
            height: 50%;
            top: 0;
            left: 50%;
            transform-origin: 0% 100%;
            transform: rotate(calc((360deg / var(--total)) * var(--i)));
            display: flex;
            align-items: center;
            justify-content: center;
            padding-left: 10px;
            z-index: 2;
            background: hsla(calc(360deg / var(--total) * var(--i)), 80%, 55%, 0.9);
            border-right: 1px solid rgba(0, 0, 0, 0.2);
        }

        .roulette-segment span {
            position: absolute;
            transform: rotate(calc(360deg / var(--total) / -2));
            font-size: 0.78rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
            padding-right: 10px;
        }

        .roulette-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(76, 175, 80, 0.08);
            color: #2e7d32;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .roulette-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4caf50;
        }

        .roulette-button {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            outline: none;
            border-radius: 999px;
            padding: 12px 24px;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            cursor: pointer;
            background: linear-gradient(135deg, #ff4b5c, #ff9a3c);
            color: #fff;
            box-shadow:
                0 10px 20px rgba(255, 75, 92, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.6);
            transition:
                transform 0.15s ease-out,
                box-shadow 0.15s ease-out,
                filter 0.15s ease-out,
                opacity 0.15s ease-out;
        }

        .roulette-button:hover:not(:disabled) {
            transform: translateY(-2px) scale(1.02);
            box-shadow:
                0 16px 30px rgba(255, 75, 92, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.8);
            filter: brightness(1.05);
        }

        .roulette-button:active:not(:disabled) {
            transform: translateY(1px) scale(0.98);
            box-shadow:
                0 6px 16px rgba(255, 75, 92, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.7);
        }

        .roulette-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow:
                0 6px 14px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.6);
        }

        .roulette-button-icon {
            font-size: 1.1rem;
        }

        .roulette-result {
            margin-top: 18px;
            font-size: 0.95rem;
            color: #333;
            min-height: 40px;
        }

        .roulette-result strong {
            font-weight: 800;
            color: #e53935;
        }

        .roulette-code {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 10px;
            background: rgba(25, 118, 210, 0.06);
            border: 1px dashed rgba(25, 118, 210, 0.6);
            font-family: "SF Mono", ui-monospace, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.85rem;
            color: #0d47a1;
            margin-top: 6px;
        }

        .roulette-status {
            font-size: 0.8rem;
            margin-top: 8px;
            color: #777;
        }

        .roulette-status.error {
            color: #c62828;
        }

        .roulette-small-text {
            margin-top: 12px;
            font-size: 0.78rem;
            color: #777;
        }

        @media (max-width: 480px) {
            .roulette-card {
                padding: 18px 16px 24px;
            }

            .roulette-wheel-container {
                width: 220px;
                height: 220px;
            }

            .roulette-title {
                font-size: 1.5rem;
            }

            .roulette-segment span {
                font-size: 0.7rem;
            }
        }
    </style>

    <div class="roulette-page">
        <div class="roulette-card">
            <div class="roulette-badge">
                <span class="roulette-badge-dot"></span>
                ¡Juega y gana al instante!
            </div>

            <h1 class="roulette-title">Ruleta de Descuentos</h1>

            {{-- Subtítulo dinámico con los porcentajes reales --}}
            <p class="roulette-subtitle">
                Gira la ruleta y descubre si ganas
                @php
                    $discounts = $prizes
                        ->where('discount_percent', '>', 0)
                        ->pluck('discount_percent')
                        ->unique()
                        ->values();
                @endphp
                @foreach ($discounts as $i => $d)
                    <strong>{{ $d }}%</strong>
                    @if ($i < $discounts->count() - 1)
                        ,
                    @endif
                @endforeach
                ¡o más sorpresas!
            </p>

            <div class="roulette-wrapper">
                <div class="roulette-wheel-container">
                    <div class="roulette-pointer"></div>

                    <div class="roulette-lights-ring"></div>

                    <div id="rouletteWheel" class="roulette-wheel">
                        @foreach ($prizes as $index => $prize)
                            @php
                                $icon = $prize->discount_percent > 0 ? '🎁' : '😅';
                            @endphp
                            <div class="roulette-segment" data-index="{{ $index }}"
                                data-prize-id="{{ $prize->id }}" {{-- 👈 aquí --}}
                                style="--i: {{ $index }}; --total: {{ count($prizes) }};">
                                <span>{{ $icon }} {{ $prize->label }}</span>
                            </div>
                        @endforeach

                        <div id="rouletteCenterText" class="roulette-inner-circle">
                            ¡Gira<br>y gana!
                        </div>
                    </div>

                    <div class="roulette-glow"></div>
                </div>
            </div>

            <button id="spinButton" class="roulette-button">
                <span class="roulette-button-icon">🎯</span>
                <span>Girar ruleta</span>
            </button>

            <div id="rouletteResult" class="roulette-result"></div>
            <div id="rouletteStatus" class="roulette-status"></div>

            <p class="roulette-small-text">
                * Promoción limitada. Un giro por usuario.
            </p>
        </div>
    </div>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    <script>
        (function() {
            const wheel = document.getElementById('rouletteWheel');
            const button = document.getElementById('spinButton');
            const resultEl = document.getElementById('rouletteResult');
            const statusEl = document.getElementById('rouletteStatus');
            const centerText = document.getElementById('rouletteCenterText');

            const soundSpin = document.getElementById('rouletteSoundSpin');
            const soundWin = document.getElementById('rouletteSoundWin');

            let isSpinning = false;
            let currentRotation = 0;

            function playSound(audio) {
                if (!audio) return;
                audio.currentTime = 0;
                audio.play().catch(() => {});
            }

            function spinToPrize(segmentIndex, segmentsCount, onFinish) {
                const extraSpins = 5; // giros completos extra para que se vea bonito
                const segmentAngle = 360 / segmentsCount;

                // Ángulo central del segmento ganador en el sistema de la rueda
                const centerAngle = segmentIndex * segmentAngle + segmentAngle / 2;

                // Normalizar la rotación actual al rango [0, 360)
                let currentNorm = currentRotation % 360;
                if (currentNorm < 0) currentNorm += 360;

                // Pequeño "bias" para evitar caer exactamente en la línea (2 grados dentro del segmento)
                const biasDegrees = 2; // puedes subirlo a 3–4 si quieres más margen
                const adjustedCenter = centerAngle - biasDegrees;

                // Queremos que el centro del segmento quede en el puntero (arriba = 0°),
                // así que el ángulo objetivo de la rueda es 360 - adjustedCenter
                const targetAngle = 360 - adjustedCenter;

                // Diferencia desde donde estamos hasta donde queremos llegar,
                // sumando varios giros completos para la animación
                const delta = extraSpins * 360 + (targetAngle - currentNorm);

                const finalRotation = currentRotation + delta;

                wheel.classList.add('spinning');
                wheel.style.transition = 'transform 4s cubic-bezier(0.33, 1, 0.68, 1)';
                wheel.style.transform = `rotate(${finalRotation}deg)`;
                currentRotation = finalRotation;

                const handler = () => {
                    wheel.classList.remove('spinning');
                    wheel.removeEventListener('transitionend', handler);
                    if (typeof onFinish === 'function') {
                        onFinish();
                    }
                    isSpinning = false;
                    button.disabled = false;
                };

                wheel.addEventListener('transitionend', handler);
            }


            button.addEventListener('click', () => {
                if (isSpinning) return;

                isSpinning = true;
                button.disabled = true;
                resultEl.innerHTML = '';
                statusEl.textContent = 'Consultando tu premio...';
                statusEl.classList.remove('error');
                centerText.textContent = '¡Suerte!';

                //playSound(soundSpin);

                fetch("{{ route('roulette.spin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            statusEl.textContent = data.message || 'Error al girar la ruleta.';
                            statusEl.classList.add('error');
                            resultEl.innerHTML = '';
                            isSpinning = false;
                            button.disabled = false;
                            return;
                        }

                        // Buscar el segmento correcto por prize_id
                        const prizeId = data.prize_id;
                        const segments = Array.from(document.querySelectorAll('.roulette-segment'));

                        const segmentElement = segments.find(seg => seg.dataset.prizeId === String(
                            prizeId));

                        let segmentIndex;
                        let segmentsCount = segments.length;

                        if (segmentElement) {
                            segmentIndex = parseInt(segmentElement.dataset.index, 10);
                        } else {
                            // Fallback por si algo raro pasa: usamos lo que diga el backend o 0
                            segmentIndex = data.segment_index ?? 0;
                        }


                        let prizeHtml = `<div>🎉 ¡Has obtenido: <strong>${data.label}</strong>!</div>`;

                        if (data.discount_percent > 0 && data.coupon_code) {
                            prizeHtml += `
                        <div class="roulette-code">
                            <span>Tu código:</span>
                            <span><strong>${data.coupon_code}</strong></span>
                        </div>
                    `;
                        } else if (data.discount_percent === 0) {
                            prizeHtml +=
                                `<div>Gracias por participar, ¡inténtalo de nuevo pronto! 😉</div>`;
                        }

                        statusEl.textContent = 'Girando la ruleta...';

                        spinToPrize(segmentIndex, segmentsCount, () => {
                            resultEl.innerHTML = prizeHtml;
                            statusEl.textContent =
                                'Premio generado desde el servidor. Aplícalo en el checkout.';
                            centerText.textContent = data.discount_percent > 0 ?
                                `${data.discount_percent}% OFF` : '¡Gracias!';
                            //playSound(soundWin);
                        });

                    })
                    .catch(err => {
                        console.error(err);
                        statusEl.textContent = 'Ocurrió un error. Intenta de nuevo.';
                        statusEl.classList.add('error');
                        resultEl.innerHTML = '';
                        isSpinning = false;
                        button.disabled = false;
                    });
            });
        })();
    </script>
@endsection
