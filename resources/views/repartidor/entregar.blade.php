<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Entregar pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/entregar.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .btn-gmaps { width: 100%; display: flex; align-items: center; justify-content: center; gap: .5rem; padding: .65rem 1rem; background: #fff; border: 1.5px solid #d1d5db; border-radius: 10px; font-family: inherit; font-size: .85rem; font-weight: 700; color: #333; cursor: pointer; margin-bottom: 1rem; }
        .btn-gmaps svg { width: 17px; height: 17px; flex-shrink: 0; }
        .btn-gmaps:active { background: #f5f5f5; }
    </style>
</head>

<body>
    <div class="app">

        <div id="mapa"></div>

        <div class="body">
            <div class="estado-badge"><span class="estado-dot"></span>En camino al cliente</div>

            @php
                $persona   = $pedido->cliente?->user?->persona;
                $direccion = $pedido->direccion ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();
                $hasCoords = $direccion && floatval($direccion->drc_latitud) && floatval($direccion->drc_longitud);
                $searchQuery = urlencode(trim(($direccion?->drc_calle ?? '') . ' ' . ($direccion?->drc_colonia ?? '') . ' ' . ($direccion?->drc_ciudad ?? '')));
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

            <button type="button" class="btn-gmaps" onclick="trazarRuta()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/>
                </svg>
                Trazar ruta en Google Maps
            </button>

            <div class="total-pill">
                <p>
                    {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta'
                        ? 'Pago con tarjeta — ya pagado'
                        : 'Total a cobrar en efectivo' }}
                </p>
                <p>${{ number_format($pedido->ped_total, 2) }}</p>
            </div>

        </div>

        <div class="footer-fixed">
            <button type="button" class="btn-entregue"
                onclick="document.getElementById('modal-llegue').classList.add('open')">
                Ya llegué al domicilio
            </button>
            <p class="btn-hint">Presiona cuando estés en la puerta del cliente</p>
        </div>
    </div>

    {{-- MODAL CONFIRMACIÓN LLEGADA --}}
    <div class="modal-overlay" id="modal-llegue">
        <div class="modal">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            </div>
            <p class="modal-title">¿Ya llegaste al domicilio?</p>
            <p class="modal-desc">
                Confirma que estás en la puerta de <strong>{{ $persona?->per_nombre }}</strong>.<br>
                El cliente deberá darte un código de 4 dígitos.
            </p>
            <div class="modal-btns">
                <button type="button" class="modal-cancel"
                    onclick="document.getElementById('modal-llegue').classList.remove('open')">
                    Cancelar
                </button>
                <form method="POST" action="{{ route('repartidor.llegue-cliente', $pedido->ped_id) }}" style="flex:2">
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
        // ── MAPA ─────────────────────────────────────────────
        const lat = {{ floatval($direccion?->drc_latitud) ?: 17.9869 }};
        const lng = {{ floatval($direccion?->drc_longitud) ?: -92.9303 }};
        const mapa = L.map('mapa', { attributionControl: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapa);
        const pinSvg = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#3b82f6" stroke="white" stroke-width="1.5"/><circle cx="12" cy="9" r="2.5" fill="white"/></svg>';
        L.marker([lat, lng], { icon: L.divIcon({ className: '', html: pinSvg, iconSize: [40, 40], iconAnchor: [20, 40] }) }).addTo(mapa);

        // ── TRAZAR RUTA ───────────────────────────────────────
        const hasCoords = {{ $hasCoords ? 'true' : 'false' }};
        const destUrl = hasCoords
            ? `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving`
            : `https://www.google.com/maps/search/{{ $searchQuery }}`;

        function trazarRuta() {
            if (navigator.geolocation && hasCoords) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const url = `https://www.google.com/maps/dir/?api=1&origin=${pos.coords.latitude},${pos.coords.longitude}&destination=${lat},${lng}&travelmode=driving`;
                        window.open(url, '_blank');
                    },
                    () => window.open(destUrl, '_blank'),
                    { timeout: 4000, maximumAge: 30000 }
                );
            } else {
                window.open(destUrl, '_blank');
            }
        }

    </script>
</body>

</html>
