<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>En camino a la tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/en-camino.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
