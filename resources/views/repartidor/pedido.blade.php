<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/pedido.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('repartidor.index') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <span class="header-title">Detalle del pedido</span>
            <div style="width:22px"></div>
        </div>

        {{-- MAPA TIENDA --}}
        <div id="mapa"></div>

        <div class="body">

            {{-- TIENDA --}}
            <p class="seccion-titulo">Tienda</p>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Nombre</p>
                        <p class="info-val">{{ $pedido->tienda->tie_nombre }}</p>
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
                    <div class="info-content">
                        <p class="info-label">Dirección</p>
                        <p class="info-val">{{ $pedido->tienda->tie_direccion }}</p>
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
                    <div class="info-content">
                        <p class="info-label">Teléfono</p>
                        <p class="info-val">{{ $pedido->tienda->tie_telefono }}</p>
                    </div>
                </div>
            </div>

            {{-- CLIENTE --}}
            <p class="seccion-titulo">Cliente</p>
            <div class="info-card">
                @php $persona = $pedido->cliente?->user?->persona; @endphp
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Nombre</p>
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
                    <div class="info-content">
                        <p class="info-label">Teléfono</p>
                        <p class="info-val">{{ $persona?->per_telefono ?? '—' }}</p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Método de pago</p>
                        <p class="info-val"
                            style="color:{{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? '#1d4ed8' : '#4a8a06' }}">
                            {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? 'Pago con tarjeta' : 'Pago en efectivo' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- PRODUCTOS --}}
            <p class="seccion-titulo">Productos ({{ $pedido->detalles->count() }})</p>
            <div class="prod-list">
                @foreach ($pedido->detalles as $det)
                    <div class="prod-row">
                        <span class="prod-qty">×{{ $det->det_cantidad }}</span>
                        <span class="prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                        <span class="prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="total-row" style="flex-direction:column;gap:0.4rem;">
                <div style="display:flex;justify-content:space-between;width:100%;">
                    <span class="total-label">Subtotal productos</span>
                    <span style="font-size:0.88rem;font-weight:700;color:#555;">
                        ${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}
                    </span>
                </div>
                @if ($pedido->ped_costo_envio > 0)
                    <div style="display:flex;justify-content:space-between;width:100%;">
                        <span class="total-label">Costo de envío</span>
                        <span style="font-size:0.88rem;font-weight:700;color:#1d4ed8;">
                            ${{ number_format($pedido->ped_costo_envio, 2) }}
                        </span>
                    </div>
                @endif
                <div
                    style="display:flex;justify-content:space-between;width:100%;border-top:1px solid #e8f5d0;padding-top:0.4rem;margin-top:0.1rem;">
                    <span class="total-label" style="font-weight:800;color:#111;">
                        {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? 'Pago con tarjeta' : 'Total a cobrar en efectivo' }}
                    </span>
                    <span class="total-val">
                        ${{ number_format($pedido->ped_total, 2) }}
                    </span>
                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="footer-fixed">
            <button type="button" class="btn-aceptar"
                onclick="document.getElementById('modal-aceptar').classList.add('open')">
                Aceptar pedido
            </button>
        </div>

        {{-- MODAL --}}
        <div id="modal-aceptar"
            style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;align-items:flex-end;justify-content:center;">
            <div
                style="background:white;border-radius:1.5rem 1.5rem 0 0;padding:2rem 1.5rem;width:100%;max-width:430px;">

                <div
                    style="width:56px;height:56px;background:#f0fde0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#4a8a06" style="width:28px;height:28px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                    </svg>
                </div>

                <p style="font-size:1rem;font-weight:900;color:#111;text-align:center;margin-bottom:0.5rem;">
                    ¿Aceptar este pedido?
                </p>
                <p style="font-size:0.82rem;color:#888;text-align:center;line-height:1.6;margin-bottom:1.5rem;">
                    Recuerda <strong>procurar tener cambio</strong> para el cliente y
                    <strong>manejar con cuidado</strong> los productos durante el trayecto.
                </p>

                <div style="display:flex;gap:0.75rem;">
                    <button type="button" onclick="document.getElementById('modal-aceptar').style.display='none'"
                        style="flex:1;background:#f0f0f0;color:#555;font-family:inherit;font-size:0.88rem;font-weight:700;padding:0.85rem;border-radius:999px;border:none;cursor:pointer;">
                        Cancelar
                    </button>
                    <form method="POST" action="{{ route('repartidor.aceptar', $pedido->ped_id) }}" style="flex:2">
                        @csrf
                        <button type="submit"
                            style="width:100%;background:linear-gradient(135deg,#a8df11,#7cc10a);color:#1a1a1a;font-family:inherit;font-size:0.88rem;font-weight:800;padding:0.85rem;border-radius:999px;border:none;cursor:pointer;box-shadow:0 6px 20px rgba(168,223,17,0.35);">
                            Sí, aceptar
                        </button>
                    </form>
                </div>

            </div>
        </div>


        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const latTienda = {{ floatval($pedido->tienda->tie_latitud) ?: 17.9869 }};
            const lngTienda = {{ floatval($pedido->tienda->tie_longitud) ?: -92.9303 }};
            const latCliente = {{ floatval($direccion?->drc_latitud) ?: 0 }};
            const lngCliente = {{ floatval($direccion?->drc_longitud) ?: 0 }};

            const mapa = L.map('mapa', {
                    attributionControl: false
                })
                .setView([latTienda, lngTienda], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(mapa);

            // Pin verde — tienda
            const pinTienda = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none">' +
                '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/>' +
                '<circle cx="12" cy="9" r="2.5" fill="white"/>' +
                '</svg>';

            L.marker([latTienda, lngTienda], {
                icon: L.divIcon({
                    className: '',
                    html: pinTienda,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(mapa).bindPopup('{{ $pedido->tienda->tie_nombre }}');

            // Pin azul — cliente (solo si tiene coordenadas)
            if (latCliente && lngCliente) {
                const pinCliente = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none">' +
                    '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#3b82f6" stroke="white" stroke-width="1.5"/>' +
                    '<circle cx="12" cy="9" r="2.5" fill="white"/>' +
                    '</svg>';

                L.marker([latCliente, lngCliente], {
                    icon: L.divIcon({
                        className: '',
                        html: pinCliente,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                }).addTo(mapa).bindPopup('Cliente');

                // Ajustar el mapa para mostrar ambos pines
                const bounds = L.latLngBounds(
                    [latTienda, lngTienda],
                    [latCliente, lngCliente]
                );
                mapa.fitBounds(bounds, {
                    padding: [40, 40]
                });
            }

            // Abrir modal
            document.getElementById('modal-aceptar').addEventListener('click', function(e) {
                if (e.target === this) this.style.display = 'none';
            });

            // Usar flex al abrir
            document.querySelector('.btn-aceptar').addEventListener('click', function() {
                document.getElementById('modal-aceptar').style.display = 'flex';
            });
        </script>
</body>

</html>
