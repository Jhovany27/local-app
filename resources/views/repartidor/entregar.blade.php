<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Entregar pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/entregar.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body>
    <div class="app">

        <div id="mapa"></div>

        <div class="body">
            <div class="estado-badge"><span class="estado-dot"></span>En camino al cliente</div>

            @php
                $persona = $pedido->cliente?->user?->persona;
                $direccion = $pedido->direccion ?? $pedido->cliente?->user?->direccions()->latest('drc_id')->first();
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
