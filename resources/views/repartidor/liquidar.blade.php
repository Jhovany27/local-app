<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Liquidar pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .liq-body { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1.5rem; gap: 1.75rem; }

        .liq-icon { width: 64px; height: 64px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .liq-icon svg { width: 32px; height: 32px; color: #d97706; }

        .liq-titulo    { font-size: 1.1rem; font-weight: 900; color: #1a1a1a; text-align: center; }
        .liq-subtitulo { font-size: .82rem; color: #888; text-align: center; line-height: 1.5; }

        .liq-info { background: #f8fdf0; border: 1.5px solid #d4edaa; border-radius: 12px; padding: .85rem 1.25rem; width: 100%; display: flex; justify-content: space-between; align-items: center; }
        .liq-info-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #7ab80e; }
        .liq-info-val   { font-size: .88rem; font-weight: 700; color: #1a1a1a; margin-top: .1rem; }
        .liq-info-monto { font-size: 1.1rem; font-weight: 900; color: #b45309; }

        .liq-btn-ubicacion {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .7rem 1rem; background: #fff; border: 1.5px solid #d1d5db;
            border-radius: 12px; font-family: 'Instrument Sans', sans-serif;
            font-size: .88rem; font-weight: 700; color: #333; cursor: pointer;
        }
        .liq-btn-ubicacion:active { background: #f5f5f5; }
        .liq-btn-ubicacion svg { width: 18px; height: 18px; color: #4a8a06; flex-shrink: 0; }

        .liq-pin-label { font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #555; }

        .liq-pin-inputs { display: flex; gap: .75rem; justify-content: center; }
        .liq-pin-inputs input {
            width: 3rem; height: 3.75rem; text-align: center;
            font-size: 1.5rem; font-weight: 900; color: #1a1a1a;
            border: 2px solid #d1d5db; border-radius: 12px;
            background: #f8fdf0; outline: none;
            font-family: 'Instrument Sans', sans-serif;
            transition: border-color .15s;
        }
        .liq-pin-inputs input:focus { border-color: #a8df11; }
        .liq-pin-inputs input.pin-error { border-color: #fca5a5; background: #fff1f0; }

        .liq-error { display: flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 600; color: #d41b11; background: #fff1f0; border: 1px solid #fca5a5; border-radius: 8px; padding: .55rem .85rem; width: 100%; }
        .liq-error svg { width: 15px; height: 15px; flex-shrink: 0; }

        .liq-btn {
            width: 100%; padding: .85rem; background: linear-gradient(135deg,#a8df11,#7cc10a);
            border: none; border-radius: 12px; font-family: 'Instrument Sans', sans-serif;
            font-size: 1rem; font-weight: 800; color: #1a1a1a; cursor: pointer;
            box-shadow: 0 4px 16px rgba(168,223,17,.3);
        }
        .liq-btn:active { opacity: .85; }
        .liq-btn:disabled { opacity: .5; cursor: not-allowed; }

        .liq-bloqueado { background: #fff1f0; border: 1.5px solid #fca5a5; border-radius: 14px; padding: 1.5rem; text-align: center; width: 100%; }
        .liq-bloqueado svg { width: 40px; height: 40px; color: #fca5a5; margin-bottom: .75rem; }
        .liq-bloqueado-title { font-size: .95rem; font-weight: 800; color: #d41b11; margin-bottom: .4rem; }
        .liq-bloqueado-txt   { font-size: .8rem; color: #888; line-height: 1.4; }

        /* ── Modal ubicación ─────────────────────────── */
        .ubi-backdrop {
            display: none;
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.5);
            align-items: flex-end;
            justify-content: center;
        }
        .ubi-backdrop.open { display: flex; }

        .ubi-sheet {
            background: #fff;
            border-radius: 20px 20px 0 0;
            width: 100%; max-width: 430px;
            padding-bottom: env(safe-area-inset-bottom, 1rem);
            animation: slideUp .25s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to   { transform: translateY(0); }
        }

        .ubi-handle { width: 40px; height: 4px; background: #e0e0e0; border-radius: 2px; margin: .85rem auto .6rem; }

        .ubi-header { padding: .5rem 1.25rem 1rem; }
        .ubi-nombre { font-size: 1rem; font-weight: 900; color: #1a1a1a; }
        .ubi-dir    { font-size: .78rem; color: #888; margin-top: .2rem; line-height: 1.4; }

        #ubi-mapa { width: 100%; height: 220px; }

        .ubi-footer { padding: 1rem 1.25rem; display: flex; gap: .75rem; }
        .ubi-btn-maps {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: .45rem;
            padding: .7rem; background: linear-gradient(135deg,#a8df11,#7cc10a);
            border: none; border-radius: 10px; font-family: 'Instrument Sans', sans-serif;
            font-size: .88rem; font-weight: 800; color: #1a1a1a; cursor: pointer; text-decoration: none;
        }
        .ubi-btn-maps svg { width: 16px; height: 16px; }
        .ubi-btn-cerrar {
            padding: .7rem 1.1rem; background: #f5f5f5; border: none; border-radius: 10px;
            font-family: 'Instrument Sans', sans-serif; font-size: .88rem; font-weight: 700;
            color: #555; cursor: pointer;
        }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <div style="width:22px"></div>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="liq-body">

        <div class="liq-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
            </svg>
        </div>

        <div>
            <p class="liq-titulo">Liquidar con la tienda</p>
            <p class="liq-subtitulo" style="margin-top:.4rem;">
                Entrega el efectivo a la tienda y pídeles el PIN de confirmación.
            </p>
        </div>

        <div class="liq-info">
            <div>
                <p class="liq-info-label">Pedido</p>
                <p class="liq-info-val">#{{ $pedido->ped_codigo }}</p>
            </div>
            <div style="text-align:right;">
                <p class="liq-info-label">Entrega a la tienda</p>
                <p class="liq-info-monto">${{ number_format($montoParaTienda, 2) }}</p>
                <p style="font-size:.65rem;color:#aaa;margin-top:.1rem;">Subtotal ${{ number_format($pedido->ped_total - $pedido->ped_costo_envio, 2) }} − {{ $pctComision }}% comisión</p>
            </div>
        </div>

        {{-- BOTÓN VER UBICACIÓN --}}
        <button type="button" class="liq-btn-ubicacion" onclick="abrirUbicacion()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
            </svg>
            Ver ubicación de la tienda
        </button>

        @if ($bloqueado)
            <div class="liq-bloqueado">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <p class="liq-bloqueado-title">Demasiados intentos fallidos</p>
                <p class="liq-bloqueado-txt">Has agotado los 5 intentos permitidos. Contacta con la tienda o con soporte para resolver esta situación.</p>
            </div>
        @else
            @if ($errors->has('pin'))
                <div class="liq-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    {{ $errors->first('pin') }}
                </div>
            @endif

            <div style="width:100%;display:flex;flex-direction:column;gap:.75rem;align-items:center;">
                <p class="liq-pin-label">Ingresa el PIN de 4 dígitos</p>
                <form method="POST" action="{{ route('repartidor.validar-pin', $pedido->ped_id) }}" id="pin-form" style="width:100%;display:flex;flex-direction:column;gap:1.25rem;align-items:center;">
                    @csrf
                    <input type="hidden" name="pin" id="pin-hidden">
                    <div class="liq-pin-inputs">
                        <input type="number" inputmode="numeric" maxlength="1" id="p0" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9" autofocus>
                        <input type="number" inputmode="numeric" maxlength="1" id="p1" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p2" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p3" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                    </div>
                    <button type="submit" class="liq-btn" id="btn-submit" disabled>Confirmar PIN</button>
                </form>
            </div>
        @endif

    </div>

</div>

{{-- MODAL UBICACIÓN TIENDA --}}
@php
    $tienda   = $pedido->tienda;
    $tLat     = (float) ($tienda?->tie_latitud  ?? 0);
    $tLng     = (float) ($tienda?->tie_longitud ?? 0);
    $tNombre  = e($tienda?->tie_nombre   ?? 'Tienda');
    $tDir     = e($tienda?->tie_direccion ?? '');
    $hasMapa  = $tLat !== 0.0 && $tLng !== 0.0;
    $mapsUrl  = $hasMapa
        ? "https://www.google.com/maps/dir/?api=1&destination={$tLat},{$tLng}"
        : "https://www.google.com/maps/search/" . urlencode($tienda?->tie_nombre . ' ' . $tienda?->tie_direccion);
@endphp

<div class="ubi-backdrop" id="ubi-backdrop" onclick="cerrarUbicacion(event)">
    <div class="ubi-sheet">
        <div class="ubi-handle"></div>
        <div class="ubi-header">
            <p class="ubi-nombre">{{ $tNombre }}</p>
            @if ($tDir)
                <p class="ubi-dir">{{ $tDir }}</p>
            @endif
        </div>

        @if ($hasMapa)
            <div id="ubi-mapa"></div>
        @else
            <div style="height:120px;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:.82rem;padding:1rem;text-align:center;">
                Esta tienda no tiene coordenadas guardadas. Usa el botón de abajo para buscarla en Maps.
            </div>
        @endif

        <div class="ubi-footer">
            <a href="{{ $mapsUrl }}" target="_blank" class="ubi-btn-maps">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                Cómo llegar
            </a>
            <button type="button" class="ubi-btn-cerrar" onclick="cerrarUbicacion()">Cerrar</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── PIN inputs ─────────────────────────────────────────
    const inputs = [document.getElementById('p0'), document.getElementById('p1'), document.getElementById('p2'), document.getElementById('p3')];
    const btn    = document.getElementById('btn-submit');
    const hidden = document.getElementById('pin-hidden');

    function updatePin() {
        const pin = inputs.map(i => i.value.replace(/\D/g, '').slice(0,1)).join('');
        hidden.value = pin;
        if (btn) btn.disabled = pin.length < 4;
    }

    inputs.forEach((inp, idx) => {
        if (!inp) return;
        inp.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(-1);
            updatePin();
            if (this.value && idx < 3) inputs[idx + 1].focus();
        });
        inp.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                inputs[idx - 1].focus();
                inputs[idx - 1].value = '';
                updatePin();
            }
        });
    });

    // ── Modal ubicación ────────────────────────────────────
    const tLat    = {{ $tLat }};
    const tLng    = {{ $tLng }};
    const hasMapa = {{ $hasMapa ? 'true' : 'false' }};
    let mapaInit  = false;

    function abrirUbicacion() {
        document.getElementById('ubi-backdrop').classList.add('open');
        if (hasMapa && !mapaInit) {
            mapaInit = true;
            setTimeout(() => {
                const mapa = L.map('ubi-mapa', { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false })
                    .setView([tLat, tLng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);
                const icon = L.divIcon({
                    className: '',
                    html: '<div style="width:16px;height:16px;background:#4a8a06;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.35)"></div>',
                    iconAnchor: [8, 8],
                });
                L.marker([tLat, tLng], { icon }).addTo(mapa);
            }, 50);
        }
    }

    function cerrarUbicacion(e) {
        if (e && e.target !== document.getElementById('ubi-backdrop')) return;
        document.getElementById('ubi-backdrop').classList.remove('open');
    }
</script>
</body>
</html>
