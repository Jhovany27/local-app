<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Entregar pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        #mapa {
            width: 100%;
            height: 50vh;
            z-index: 1;
        }

        .body {
            flex: 1;
            padding: 1.25rem 1.25rem 8rem;
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #eff6ff;
            border: 1.5px solid #93c5fd;
            color: #1d4ed8;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        .estado-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #3b82f6;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.3
            }
        }

        .cliente-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            border-radius: 0.6rem;
            background: #f0fde0;
            border: 1px solid #d4f0a0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon svg {
            width: 15px;
            height: 15px;
            color: #4a8a06;
        }

        .info-label {
            font-size: 0.62rem;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.1rem;
        }

        .info-val {
            font-size: 0.85rem;
            font-weight: 600;
            color: #111;
        }

        .total-pill {
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .total-pill p:first-child {
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(0, 0, 0, 0.5);
        }

        .total-pill p:last-child {
            font-size: 1.1rem;
            font-weight: 900;
            color: #111;
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

        .btn-entregue {
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
        }

        .btn-hint {
            font-size: 0.68rem;
            color: #aaa;
            text-align: center;
            margin-top: 0.5rem;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: 1.5rem 1.5rem 0 0;
            padding: 2rem 1.5rem;
            width: 100%;
            max-width: 430px;
        }

        .modal-icon {
            width: 56px;
            height: 56px;
            background: #f0fde0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .modal-icon svg {
            width: 28px;
            height: 28px;
            color: #4a8a06;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 900;
            color: #111;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .modal-desc {
            font-size: 0.82rem;
            color: #888;
            text-align: center;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .modal-btns {
            display: flex;
            gap: 0.75rem;
        }

        .modal-cancel {
            flex: 1;
            background: #f0f0f0;
            color: #555;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }

        .modal-confirm {
            flex: 2;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }

        /* Overlay animación */
        .entrega-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: white;
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            max-width: 430px;
            left: 50%;
            transform: translateX(-50%);
        }

        .entrega-overlay.visible {
            display: flex;
        }

        .e-circulo {
            width: 90px;
            height: 90px;
            position: relative;
        }

        .e-spinner {
            width: 90px;
            height: 90px;
            animation: girar 1.2s linear infinite;
            position: absolute;
            inset: 0;
        }

        .e-check {
            width: 90px;
            height: 90px;
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        @keyframes girar {
            to {
                transform: rotate(360deg);
            }
        }

        .e-check-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            transition: stroke-dashoffset 0.5s ease 0.1s;
        }

        .e-barra-wrap {
            width: 200px;
            height: 4px;
            background: #e8f5d0;
            border-radius: 999px;
            overflow: hidden;
        }

        .e-barra {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #a8df11, #7cc10a);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .e-txt {
            font-size: 0.88rem;
            font-weight: 700;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="app">

        <div id="mapa"></div>

        <div class="body">
            <div class="estado-badge"><span class="estado-dot"></span>En camino al cliente</div>

            @php
                $persona = $pedido->cliente?->user?->persona;
                $direccion =
                    $pedido->cliente?->user?->direccions()->where('drc_id', session('direccion_id'))->first() ??
                    $pedido->cliente?->user?->direccions()->latest('drc_id')->first();
            @endphp

            <div class="cliente-card">
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="info-label">Cliente</p>
                        <p class="info-val">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="info-label">Teléfono</p>
                        <p class="info-val">{{ $persona?->per_telefono ?? '—' }}</p>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="info-label">Dirección de entrega</p>
                        <p class="info-val">{{ $direccion?->drc_calle }}, {{ $direccion?->drc_ciudad }}</p>
                    </div>
                </div>
                @if ($direccion?->drc_referencias)
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Referencias</p>
                            <p class="info-val">{{ $direccion->drc_referencias }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="total-pill">
                <p>Total a cobrar en efectivo</p>
                <p>${{ number_format($pedido->ped_total, 2) }}</p>
            </div>

        </div>

        <div class="footer-fixed">
            <button type="button" class="btn-entregue"
                onclick="document.getElementById('modal-entregue').classList.add('open')">
                Ya entregué el pedido
            </button>
            <p class="btn-hint">Presiona cuando el cliente haya recibido su pedido</p>
        </div>
    </div>

    {{-- MODAL CONFIRMACIÓN --}}
    <div class="modal-overlay" id="modal-entregue">
        <div class="modal">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <p class="modal-title">¿Ya entregaste el pedido?</p>
            <p class="modal-desc">
                Confirma que <strong>{{ $persona?->per_nombre }}</strong> recibió su pedido
                y el pago en efectivo de <strong>${{ number_format($pedido->ped_total, 2) }}</strong>.
            </p>
            <div class="modal-btns">
                <button type="button" class="modal-cancel"
                    onclick="document.getElementById('modal-entregue').classList.remove('open')">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('repartidor.entregue-pedido', $pedido->ped_id) }}"
                    id="form-entrega" style="flex:2">
                    @csrf
                    <button type="button" class="modal-confirm" style="width:100%" onclick="confirmarEntrega()">
                        Confirmar entrega
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- OVERLAY ANIMACIÓN --}}
    <div class="entrega-overlay" id="overlay-entrega">
        <div class="e-circulo">
            <svg class="e-spinner" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#e8f5d0" stroke-width="6" />
                <path d="M45 7 A38 38 0 0 1 83 45" stroke="#a8df11" stroke-width="6" stroke-linecap="round" />
            </svg>
            <svg class="e-check" id="e-check" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#a8df11" stroke-width="6" />
                <path class="e-check-path" id="e-check-path" d="M28 46 L40 58 L63 34" stroke="#a8df11"
                    stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="e-barra-wrap">
            <div class="e-barra" id="e-barra"></div>
        </div>
        <p class="e-txt" id="e-txt">Registrando entrega...</p>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── MAPA ─────────────────────────────────────────────
        const lat = {{ floatval($direccion?->drc_latitud) ?: 17.9869 }};
        const lng = {{ floatval($direccion?->drc_longitud) ?: -92.9303 }};
        const mapa = L.map('mapa', {
            attributionControl: false
        }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(mapa);
        const pinSvg =
            '<svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#3b82f6" stroke="white" stroke-width="1.5"/><circle cx="12" cy="9" r="2.5" fill="white"/></svg>';
        L.marker([lat, lng], {
            icon: L.divIcon({
                className: '',
                html: pinSvg,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(mapa);

        // ── ANIMACIÓN ─────────────────────────────────────────
        function confirmarEntrega() {
            document.getElementById('modal-entregue').classList.remove('open');

            const overlay = document.getElementById('overlay-entrega');
            const barra = document.getElementById('e-barra');
            const txt = document.getElementById('e-txt');
            const spinner = document.querySelector('.e-spinner');
            const check = document.getElementById('e-check');
            const path = document.getElementById('e-check-path');

            overlay.classList.add('visible');

            //  Animar barra sin esperar respuesta del servidor
            let pct = 0;
            const interval = setInterval(() => {
                pct = Math.min(pct + Math.random() * 20, 85);
                barra.style.width = pct + '%';
            }, 300);

            //  Esperar un momento, mostrar check y luego enviar el form normal
            setTimeout(() => {
                clearInterval(interval);
                barra.style.width = '100%';

                setTimeout(() => {
                    spinner.style.display = 'none';
                    check.style.opacity = '1';
                    path.style.strokeDashoffset = '0';
                    txt.textContent = '¡Pedido completado!';
                }, 350);

                //  Enviar form normalmente después de la animación
                setTimeout(() => {
                    document.getElementById('form-entrega').submit();
                }, 1400);

            }, 1200);
        }
    </script>
</body>

</html>
