<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Realizar pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f0f2f0;
            display: flex;
            justify-content: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        .app {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #edf3e3;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-back {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
        }

        .header-back svg {
            width: 22px;
            height: 22px;
        }

        .header h1 {
            font-size: 1rem;
            font-weight: 800;
            color: #111;
        }

        .body {
            flex: 1;
            padding: 1.25rem 1.25rem 8rem;
        }

        .seccion-titulo {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.75rem;
        }

        .resumen-card {
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }

        .resumen-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f9e0;
        }

        .resumen-row:last-child {
            border-bottom: none;
        }

        .resumen-nombre {
            font-size: 0.82rem;
            color: #555;
        }

        .resumen-precio {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
        }

        .resumen-total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0 0;
        }

        .resumen-total-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
        }

        .resumen-total-val {
            font-size: 1.1rem;
            font-weight: 900;
            color: #4a8a06;
        }

        .dir-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 0.85rem;
            margin-bottom: 1.25rem;
            text-decoration: none;
            transition: border-color 0.2s;
        }

        .dir-card:hover {
            border-color: #a8df11;
        }

        .dir-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f0fde0;
            border: 1.5px solid #d4f0a0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dir-icon svg {
            width: 18px;
            height: 18px;
            color: #a8df11;
        }

        .dir-texto {
            flex: 1;
        }

        .dir-texto p:first-child {
            font-size: 0.8rem;
            font-weight: 700;
            color: #111;
        }

        .dir-texto p:last-child {
            font-size: 0.72rem;
            color: #888;
            margin-top: 0.1rem;
        }

        .dir-arrow svg {
            width: 16px;
            height: 16px;
            color: #ccc;
        }

        .entrega-opts {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .entrega-opt {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem;
            border: 2px solid #e8f5d0;
            border-radius: 1rem;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }

        .entrega-opt:has(input:checked) {
            border-color: #a8df11;
            background: #f8fdf0;
        }

        .entrega-opt input[type="radio"] {
            accent-color: #a8df11;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .entrega-opt-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.65rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .entrega-opt-icon svg {
            width: 18px;
            height: 18px;
            color: #4a8a06;
        }

        .entrega-opt-text p:first-child {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
        }

        .entrega-opt-text p:last-child {
            font-size: 0.7rem;
            color: #888;
        }

        .pago-card {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem;
            border: 2px solid #a8df11;
            background: #f8fdf0;
            border-radius: 1rem;
            margin-bottom: 1.25rem;
        }

        .pago-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.65rem;
            background: white;
            border: 1px solid #d4f0a0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pago-icon svg {
            width: 18px;
            height: 18px;
            color: #4a8a06;
        }

        .pago-text p:first-child {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
        }

        .pago-text p:last-child {
            font-size: 0.7rem;
            color: #888;
        }

        .footer-fixed {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: white;
            border-top: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
            z-index: 10;
        }

        .footer-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .footer-total-label {
            font-size: 0.78rem;
            color: #888;
            font-weight: 600;
        }

        .footer-total-val {
            font-size: 1.2rem;
            font-weight: 900;
            color: #111;
        }

        .btn-pagar {
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 800;
            padding: 0.9rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s;
        }

        .btn-pagar:hover {
            opacity: 0.9;
        }

        /* ── OVERLAY DE PAGO ── */
        .pago-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: white;
            z-index: 999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            max-width: 430px;
            left: 50%;
            transform: translateX(-50%);
        }

        .pago-overlay.visible {
            display: flex;
        }

        /* Círculo animado */
        .pago-circulo {
            width: 90px;
            height: 90px;
            position: relative;
        }

        .pago-circulo svg.spinner {
            width: 90px;
            height: 90px;
            animation: girar 1.2s linear infinite;
            position: absolute;
            inset: 0;
        }

        @keyframes girar {
            to {
                transform: rotate(360deg);
            }
        }

        .pago-circulo svg.check {
            width: 90px;
            height: 90px;
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pago-circulo.listo svg.spinner {
            display: none;
        }

        .pago-circulo.listo svg.check {
            opacity: 1;
        }

        /* Línea de progreso */
        .pago-barra-wrap {
            width: 200px;
            height: 4px;
            background: #e8f5d0;
            border-radius: 999px;
            overflow: hidden;
        }

        .pago-barra {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #a8df11, #7cc10a);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .pago-texto {
            font-size: 0.88rem;
            font-weight: 700;
            color: #555;
            text-align: center;
            transition: opacity 0.3s;
        }

        /* Check animado */
        .check-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            transition: stroke-dashoffset 0.5s ease 0.1s;
        }

        .pago-circulo.listo .check-path {
            stroke-dashoffset: 0;
        }
    </style>
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('carrito.index') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1>Realizar pago</h1>
            <div style="width:22px"></div>
        </div>

        <form method="POST" action="{{ route('carrito.confirmar', $pedido->ped_id) }}" id="form-pago">
            @csrf

            <div class="body">

                <p class="seccion-titulo">Resumen del pedido</p>
                <div class="resumen-card">
                    @foreach ($pedido->detalles as $det)
                        <div class="resumen-row">
                            <span class="resumen-nombre">{{ $det->producto?->pro_nombre }} ×
                                {{ $det->det_cantidad }}</span>
                            <span class="resumen-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="resumen-total-row">
                        <span class="resumen-total-label">Importe</span>
                        <span class="resumen-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
                    </div>
                </div>

                <p class="seccion-titulo">Dirección de entrega</p>
                @if ($direccion)
                    <a href="{{ route('cliente.direcciones', ['redirect' => url()->current()]) }}" class="dir-card">
                        <div class="dir-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div class="dir-texto">
                            <p>{{ $direccion->drc_calle }}, {{ $direccion->drc_ciudad }}</p>
                            <p>{{ $direccion->drc_estado }} — Toca para cambiar</p>
                        </div>
                        <div class="dir-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>
                @else
                    <a href="{{ route('cliente.direcciones') }}" class="dir-card">
                        <div class="dir-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div class="dir-texto">
                            <p>Agregar dirección de entrega</p>
                            <p>Requerida para envío a domicilio</p>
                        </div>
                    </a>
                @endif

                <p class="seccion-titulo">Tipo de entrega</p>
                <div class="entrega-opts">
                    <label class="entrega-opt">
                        <input type="radio" name="tipo_entrega" value="domicilio" checked>
                        <div class="entrega-opt-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                            </svg>
                        </div>
                        <div class="entrega-opt-text">
                            <p>Envío a domicilio</p>
                            <p>Te lo llevamos a tu dirección</p>
                        </div>
                    </label>
                    <label class="entrega-opt">
                        <input type="radio" name="tipo_entrega" value="recoger">
                        <div class="entrega-opt-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                            </svg>
                        </div>
                        <div class="entrega-opt-text">
                            <p>Recoger en tienda</p>
                            <p>Pasa por tu pedido directamente</p>
                        </div>
                    </label>
                </div>

                <p class="seccion-titulo">Método de pago</p>
                <div class="pago-card">
                    <div class="pago-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                    <div class="pago-text">
                        <p>Pago en efectivo</p>
                        <p>Pagas al recibir tu pedido</p>
                    </div>
                </div>

            </div>

            <div class="footer-fixed">
                <div class="footer-total">
                    <span class="footer-total-label">Importe total</span>
                    <span class="footer-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
                </div>
                <button type="submit" class="btn-pagar" id="btn-pagar">Pagar</button>
            </div>

        </form>
    </div>

    {{-- OVERLAY ANIMACIÓN --}}
    <div class="pago-overlay" id="overlay">

        <div class="pago-circulo" id="circulo">
            {{-- Spinner --}}
            <svg class="spinner" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#e8f5d0" stroke-width="6" />
                <path d="M45 7 A38 38 0 0 1 83 45" stroke="#a8df11" stroke-width="6" stroke-linecap="round" />
            </svg>
            {{-- Check --}}
            <svg class="check" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#a8df11" stroke-width="6" />
                <path class="check-path" d="M28 46 L40 58 L63 34" stroke="#a8df11" stroke-width="5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <div class="pago-barra-wrap">
            <div class="pago-barra" id="barra"></div>
        </div>

        <p class="pago-texto" id="pago-txt">Procesando tu pedido...</p>

    </div>

    <script>
        document.getElementById('form-pago').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            // Mostrar overlay
            const overlay = document.getElementById('overlay');
            const barra = document.getElementById('barra');
            const txt = document.getElementById('pago-txt');
            const circulo = document.getElementById('circulo');

            overlay.classList.add('visible');

            // Animar barra progresivamente
            let pct = 0;
            const interval = setInterval(() => {
                pct = Math.min(pct + Math.random() * 18, 85);
                barra.style.width = pct + '%';
            }, 300);

            // Enviar el form en background
            const data = new FormData(form);
            fetch(form.action, {
                    method: 'POST',
                    body: data
                })
                .then(res => {
                    // Completar barra
                    clearInterval(interval);
                    barra.style.width = '100%';
                    barra.style.transition = 'width 0.3s ease';

                    // Cambiar a check
                    setTimeout(() => {
                        circulo.classList.add('listo');
                        txt.textContent = '¡Pedido realizado!';
                    }, 350);

                    // Redirigir
                    setTimeout(() => {
                        // Seguir la redirección del servidor
                        window.location.href = res.url;
                    }, 1400);
                })
                .catch(() => {
                    // Si falla, enviar normalmente
                    clearInterval(interval);
                    form.submit();
                });
        });
    </script>
</body>

</html>
