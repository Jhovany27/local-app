<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dirección</title>
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

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            position: sticky;
            top: 0;
            background: white;
            z-index: 100;
        }

        .btn-back {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .btn-back svg {
            width: 22px;
            height: 22px;
        }

        .header-logo img {
            height: 36px;
        }

        /* Mapa solo lectura */
        #mapa-view {
            width: 100%;
            height: 220px;
            z-index: 1;
        }

        /* Body */
        .body {
            padding: 1.25rem;
            flex: 1;
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: 900;
            color: #111;
            margin-bottom: 1.25rem;
        }

        /* Badge activa */
        .badge-activa {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #f0fde0;
            border: 1.5px solid #c6f135;
            color: #4a8a06;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #a8df11;
        }

        /* Info card */
        .info-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
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

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.62rem;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.15rem;
        }

        .info-value {
            font-size: 0.88rem;
            font-weight: 600;
            color: #111;
            line-height: 1.4;
        }

        .info-value.muted {
            color: #aaa;
            font-weight: 400;
            font-style: italic;
        }

        /* Coords */
        .coords-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .coord-box {
            flex: 1;
            background: #f8fdf0;
            border: 1px solid #e8f5d0;
            border-radius: 0.75rem;
            padding: 0.65rem 0.85rem;
            text-align: center;
        }

        .coord-box span:first-child {
            display: block;
            font-size: 0.6rem;
            color: #aaa;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.15rem;
        }

        .coord-box span:last-child {
            font-size: 0.8rem;
            font-weight: 700;
            color: #4a8a06;
        }

        /* Botones acción */
        .acciones {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn-usar {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-usar:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-editar {
            display: block;
            width: 100%;
            text-align: center;
            background: white;
            color: #111;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 999px;
            border: 2px solid #e8f5d0;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .btn-editar:hover {
            border-color: #a8df11;
            background: #f8fdf0;
        }

        .btn-eliminar {
            display: block;
            width: 100%;
            text-align: center;
            background: transparent;
            color: #d41b11;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 999px;
            border: 2px solid #fca5a530;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-eliminar:hover {
            background: #fff1f0;
            border-color: #d41b11;
        }
    </style>
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.direcciones') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="header-logo">
                <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
            </div>
            <div style="width:22px"></div>
        </div>

        {{-- MAPA SOLO LECTURA --}}
        <div id="mapa-view"></div>

        {{-- BODY --}}
        <div class="body">

            <h1 class="page-title">Detalles de dirección</h1>

            @if(session('direccion_id') == $direccion->drc_id)
            <div class="badge-activa">
                <span class="badge-dot"></span>
                Usando esta dirección
            </div>
            @endif

            {{-- INFO --}}
            <div class="info-card">
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Calle</p>
                        <p class="info-value">{{ $direccion->drc_calle }}{{ $direccion->drc_numero ? ' #'.$direccion->drc_numero : '' }}</p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Colonia</p>
                        <p class="info-value {{ !$direccion->drc_colonia ? 'muted' : '' }}">
                            {{ $direccion->drc_colonia ?: 'No especificada' }}
                        </p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Ciudad / Estado</p>
                        <p class="info-value">{{ $direccion->drc_ciudad }}, {{ $direccion->drc_estado }}</p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Código postal</p>
                        <p class="info-value">{{ $direccion->drc_codigo_postal }}</p>
                    </div>
                </div>

                @if($direccion->drc_referencias)
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Referencias</p>
                        <p class="info-value">{{ $direccion->drc_referencias }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- COORDENADAS --}}
            @if($direccion->drc_latitud && $direccion->drc_longitud)
            <div class="coords-row">
                <div class="coord-box">
                    <span>Latitud</span>
                    <span>{{ $direccion->drc_latitud }}</span>
                </div>
                <div class="coord-box">
                    <span>Longitud</span>
                    <span>{{ $direccion->drc_longitud }}</span>
                </div>
            </div>
            @endif

            {{-- ACCIONES --}}
            <div class="acciones">

                @if(session('direccion_id') != $direccion->drc_id)
<form method="POST" action="{{ route('cliente.direcciones.seleccionar', $direccion->drc_id) }}">
    @csrf
    <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
    <button type="submit" class="btn-usar">Usar esta dirección</button>
</form>
                @endif

                <a href="{{ route('cliente.direcciones.edit', $direccion->drc_id) }}" class="btn-editar">
                    Editar dirección
                </a>

                <form method="POST" action="{{ route('cliente.direcciones.destroy', $direccion->drc_id) }}"
                    onsubmit="return confirm('¿Eliminar esta dirección?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-eliminar">Eliminar dirección</button>
                </form>

            </div>

        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
const lat = {{ floatval($direccion->drc_latitud) ?: 17.9869 }};
const lng = {{ floatval($direccion->drc_longitud) ?: -92.9303 }};

        const mapa = L.map('mapa-view', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false,
            attributionControl: false,
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

        //  Más limpio
        const pinSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none">' +
            '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/>' +
            '<circle cx="12" cy="9" r="2.5" fill="white"/>' +
            '</svg>';

        const pinIcon = L.divIcon({
            className: '',
            html: pinSvg,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
        });

        L.marker([lat, lng], {
            icon: pinIcon
        }).addTo(mapa);
    </script>
</body>

</html>