<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>En camino a la tienda</title>
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
            height: 60vh;
            z-index: 1;
        }

        .info-panel {
            flex: 1;
            padding: 1.25rem 1.25rem 8rem;
        }

        .tienda-nombre {
            font-size: 1.1rem;
            font-weight: 900;
            color: #111;
        }

        .tienda-dir {
            font-size: 0.78rem;
            color: #888;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .tienda-dir svg {
            width: 14px;
            height: 14px;
            color: #a8df11;
            flex-shrink: 0;
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #fff7e0;
            border: 1.5px solid #fcd34d;
            color: #92400e;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            margin: 0.75rem 0;
        }

        .estado-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f59e0b;
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

        .pedido-resumen {
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 0.85rem 1rem;
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .resumen-label {
            font-size: 0.72rem;
            color: #888;
        }

        .resumen-val {
            font-size: 0.9rem;
            font-weight: 800;
            color: #111;
            margin-top: 0.1rem;
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

        .btn-llegue {
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

        /* Modal */
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
    </style>
</head>

<body>
    <div class="app">
        <div id="mapa"></div>
        <div class="info-panel">
            <p class="tienda-nombre">{{ $pedido->tienda->tie_nombre }}</p>
            <p class="tienda-dir">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                {{ $pedido->tienda->tie_direccion }}
            </p>
            <div class="estado-badge"><span class="estado-dot"></span>En camino a la tienda</div>
            <div class="pedido-resumen">
                <div>
                    <p class="resumen-label">Pedido</p>
                    <p class="resumen-val">#{{ $pedido->ped_codigo }}</p>
                </div>
                <div style="text-align:center">
                    <p class="resumen-label">Productos</p>
                    <p class="resumen-val">{{ $pedido->detalles->count() }}</p>
                </div>
                <div style="text-align:right">
                    <p class="resumen-label">Total</p>
                    <p class="resumen-val">${{ number_format($pedido->ped_total, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="footer-fixed">
            <button type="button" class="btn-llegue"
                onclick="document.getElementById('modal-llegue').classList.add('open')">
                Ya estoy en la tienda
            </button>
            <p class="btn-hint">Presiona cuando hayas llegado a recoger el pedido</p>
        </div>
    </div>

    {{-- MODAL CONFIRMACIÓN --}}
    <div class="modal-overlay" id="modal-llegue">
        <div class="modal">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                </svg>
            </div>
            <p class="modal-title">¿Ya llegaste a la tienda?</p>
            <p class="modal-desc">
                Confirma que estás en <strong>{{ $pedido->tienda->tie_nombre }}</strong>.<br>
                Empieza a recoger los productos del pedido.
            </p>
            <div class="modal-btns">
                <button type="button" class="modal-cancel"
                    onclick="document.getElementById('modal-llegue').classList.remove('open')">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('repartidor.llegue-tienda', $pedido->ped_id) }}" style="flex:2">
                    @csrf
                    <button type="submit" class="modal-confirm" style="width:100%">
                        Sí, estoy aquí
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = {{ floatval($pedido->tienda->tie_latitud) ?: 17.9869 }};
        const lng = {{ floatval($pedido->tienda->tie_longitud) ?: -92.9303 }};
        const mapa = L.map('mapa', {
            attributionControl: false
        }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(mapa);
        const pinSvg =
            '<svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/><circle cx="12" cy="9" r="2.5" fill="white"/></svg>';
        L.marker([lat, lng], {
            icon: L.divIcon({
                className: '',
                html: pinSvg,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        }).addTo(mapa);
    </script>
</body>

</html>
