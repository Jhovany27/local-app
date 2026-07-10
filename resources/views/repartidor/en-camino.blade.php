<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>En camino a la tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/en-camino.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .btn-gmaps { width: 100%; display: flex; align-items: center; justify-content: center; gap: .6rem; padding: .8rem 1rem; background: #f0fce6; border: 1.5px solid #a8df11; border-radius: 12px; font-family: inherit; font-size: .88rem; font-weight: 800; color: #3a6b00; cursor: pointer; margin-top: 1rem; transition: background .15s; }
        .btn-gmaps svg { width: 18px; height: 18px; flex-shrink: 0; }
        .btn-gmaps:active { background: #e2f5c0; }
        .cash-pill { display: flex; align-items: center; gap: .65rem; background: #fffbeb; border: 1.5px solid #fbbf24; border-radius: 10px; padding: .65rem .9rem; margin-top: .85rem; }
        .cash-pill svg { width: 18px; height: 18px; color: #d97706; flex-shrink: 0; }
        .cash-pill-text { font-size: .78rem; color: #92400e; line-height: 1.3; }
        .cash-pill-amount { font-size: 1rem; font-weight: 900; color: #78350f; }
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

            @if($esEfectivo && $montoParaTienda !== null)
            <div class="cash-pill">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
                <div>
                    <p class="cash-pill-text">Ten listo el pago para la tienda</p>
                    <p class="cash-pill-amount">${{ number_format($montoParaTienda, 2) }}</p>
                </div>
            </div>
            @endif

            <button type="button" class="btn-gmaps" onclick="trazarRuta()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"/>
                </svg>
                Trazar ruta en Google Maps
            </button>
        </div>

        <div class="footer-fixed">
            <button type="button" class="btn-llegue"
                onclick="document.getElementById('modal-llegue').classList.add('open')">
                Ya estoy en la tienda
            </button>
            <p class="btn-hint">Presiona cuando hayas llegado a recoger el pedido</p>
            <button type="button"
                onclick="document.getElementById('modal-cancelar').style.display='flex'"
                style="margin-top:0.5rem;width:100%;background:none;border:none;color:#ef4444;font-family:inherit;font-size:0.78rem;font-weight:700;cursor:pointer;padding:0.3rem;">
                Cancelar entrega
            </button>
        </div>
    </div>

    {{-- MODAL CANCELAR ENTREGA --}}
    <div id="modal-cancelar" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.55);align-items:flex-end;justify-content:center;"
        onclick="if(event.target===this)this.style.display='none'">
        <div style="background:#fff;border-radius:1.5rem 1.5rem 0 0;width:100%;max-width:430px;padding:1.5rem 1.25rem 2rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem;">
                <p style="font-size:0.95rem;font-weight:800;color:#111;">Cancelar entrega</p>
                <button onclick="document.getElementById('modal-cancelar').style.display='none'" style="background:none;border:none;cursor:pointer;color:#888;display:flex;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:0.75rem;padding:0.75rem 0.9rem;margin-bottom:1rem;font-size:0.78rem;color:#78350f;line-height:1.4;">
                El pedido quedará disponible para que otro repartidor lo acepte. Solo puedes cancelar antes de llegar a la tienda.
            </div>
            <form method="POST" action="{{ route('repartidor.cancelar-entrega', $pedido->ped_id) }}">
                @csrf
                <p style="font-size:0.78rem;font-weight:700;color:#444;margin-bottom:0.5rem;">Motivo de cancelación</p>
                <textarea name="motivo" rows="3" required minlength="5" maxlength="500"
                    placeholder="Ej: Tuve una emergencia, no puedo continuar..."
                    style="width:100%;padding:0.75rem;border:1.5px solid #e5e7eb;border-radius:0.75rem;font-family:inherit;font-size:0.85rem;resize:none;outline:none;color:#111;"></textarea>
                <div style="display:flex;gap:0.65rem;margin-top:0.85rem;">
                    <button type="button" onclick="document.getElementById('modal-cancelar').style.display='none'"
                        style="flex:1;padding:0.75rem;background:#f3f4f6;border:none;border-radius:0.85rem;font-family:inherit;font-size:0.85rem;font-weight:700;color:#555;cursor:pointer;">
                        Volver
                    </button>
                    <button type="submit"
                        style="flex:2;padding:0.75rem;background:#dc2626;border:none;border-radius:0.85rem;font-family:inherit;font-size:0.85rem;font-weight:800;color:#fff;cursor:pointer;">
                        Sí, cancelar entrega
                    </button>
                </div>
            </form>
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
                @if($esEfectivo && $montoParaTienda !== null)
                    Recuerda tener listos <strong>${{ number_format($montoParaTienda, 2) }}</strong> para pagar a la tienda.
                @else
                    Empieza a recoger los productos del pedido.
                @endif
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
        const mapa = L.map('mapa', { attributionControl: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapa);
        const pinSvg = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/><circle cx="12" cy="9" r="2.5" fill="white"/></svg>';
        L.marker([lat, lng], { icon: L.divIcon({ className: '', html: pinSvg, iconSize: [40, 40], iconAnchor: [20, 40] }) }).addTo(mapa);

        function trazarRuta() {
            const destUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving`;
            if (navigator.geolocation) {
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
