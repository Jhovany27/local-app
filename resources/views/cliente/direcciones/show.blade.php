<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dirección</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/direcciones/show.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.direcciones') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
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

            @if (session('direccion_id') == $direccion->drc_id)
                <div class="badge-activa">
                    <span class="badge-dot"></span>
                    Usando esta dirección
                </div>
            @endif

            {{-- INFO --}}
            <div class="info-card">
                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Calle</p>
                        <p class="info-value">
                            {{ $direccion->drc_calle }}{{ $direccion->drc_numero ? ' #' . $direccion->drc_numero : '' }}
                        </p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Ciudad / Estado</p>
                        <p class="info-value">{{ $direccion->drc_ciudad }}, {{ $direccion->drc_estado }}</p>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                        </svg>
                    </div>
                    <div class="info-content">
                        <p class="info-label">Código postal</p>
                        <p class="info-value">{{ $direccion->drc_codigo_postal }}</p>
                    </div>
                </div>

                @if ($direccion->drc_referencias)
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
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
            @if ($direccion->drc_latitud && $direccion->drc_longitud)
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

                @if (session('direccion_id') != $direccion->drc_id)
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
