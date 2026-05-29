<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva dirección</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    {{-- Leaflet CSS --}}
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

        /* ── HEADER ── */
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

        /* ── MAPA ── */
        .mapa-wrap {
            position: relative;
        }

        #mapa {
            width: 100%;
            height: 280px;
            z-index: 1;
        }

        /* Pin central fijo */
        .mapa-pin-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -100%);
            z-index: 999;
            pointer-events: none;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.25));
        }

        .mapa-pin-center svg {
            width: 36px;
            height: 36px;
        }

        /* Botón mi ubicación */
        .btn-mi-ubicacion {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            z-index: 999;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-mi-ubicacion:hover {
            background: #f0fde0;
        }

        .btn-mi-ubicacion svg {
            width: 20px;
            height: 20px;
            color: #4a8a06;
        }

        /* Barra de búsqueda sobre el mapa */
        .mapa-search {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            right: 0.75rem;
            z-index: 999;
            display: flex;
            gap: 0.5rem;
        }

        .mapa-search input {
            flex: 1;
            background: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.65rem 1rem;
            font-family: inherit;
            font-size: 0.82rem;
            color: #111;
            outline: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12);
        }

        .mapa-search input::placeholder {
            color: #bbb;
        }

        .mapa-search-btn {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            background: #a8df11;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 12px rgba(168, 223, 17, 0.35);
            flex-shrink: 0;
        }

        .mapa-search-btn svg {
            width: 18px;
            height: 18px;
            color: white;
        }

        /* Dirección detectada */
        .direccion-detectada {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1.25rem;
            background: #f8fdf0;
            border-bottom: 1.5px solid #e8f5d0;
        }

        .direccion-detectada svg {
            width: 16px;
            height: 16px;
            color: #a8df11;
            flex-shrink: 0;
        }

        .direccion-detectada p {
            font-size: 0.78rem;
            color: #555;
            line-height: 1.4;
        }

        .direccion-detectada p strong {
            color: #111;
            font-weight: 700;
        }

        /* ── FORMULARIO ── */
        .form-body {
            padding: 1.25rem 1.25rem 2rem;
            flex: 1;
        }

        .form-titulo {
            font-size: 0.9rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 1.1rem;
        }

        .field {
            margin-bottom: 1rem;
        }

        .field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.4rem;
        }

        .field input,
        .field textarea {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 0.85rem;
            padding: 0.75rem 1rem;
            font-family: inherit;
            font-size: 0.88rem;
            color: #111;
            background: white;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            resize: none;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: #a8df11;
            box-shadow: 0 0 0 4px rgba(168, 223, 17, 0.12);
        }

        .field input.readonly {
            background: #f8fdf0;
            border-color: #e8f5d0;
            color: #555;
        }

        .field textarea {
            height: 80px;
        }

        .field-error {
            font-size: 0.72rem;
            color: #d41b11;
            font-weight: 600;
            margin-top: 0.3rem;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        /* Checkbox */
        .check-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 0.85rem;
            margin: 1rem 0 1.25rem;
        }

        .check-wrap input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #a8df11;
            cursor: pointer;
        }

        .check-wrap label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            cursor: pointer;
        }

        /* Botón */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 800;
            padding: 0.9rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Coordenadas ocultas visibles para debug */
        .coords-info {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .coord-tag {
            flex: 1;
            background: #f0fde0;
            border: 1px solid #d4f0a0;
            border-radius: 0.65rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.72rem;
            color: #4a8a06;
            font-weight: 600;
            text-align: center;
        }

        .coord-tag span {
            display: block;
            font-size: 0.65rem;
            color: #aaa;
            font-weight: 500;
            margin-bottom: 0.1rem;
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

        {{-- MAPA --}}
        <div class="mapa-wrap">

            {{-- Buscador sobre el mapa --}}
            <div class="mapa-search">
                <input type="text" id="buscar-direccion" placeholder="Buscar dirección...">
                <button type="button" class="mapa-search-btn" onclick="buscarDireccion()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                </button>
            </div>

            <div id="mapa"></div>

            {{-- Pin fijo en el centro --}}
            <div class="mapa-pin-center">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5" />
                    <circle cx="12" cy="9" r="2.5" fill="white" />
                </svg>
            </div>

            {{-- Botón mi ubicación --}}
            <button type="button" class="btn-mi-ubicacion" onclick="irAMiUbicacion()" title="Mi ubicación">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            </button>

        </div>

        {{-- DIRECCIÓN DETECTADA --}}
        <div class="direccion-detectada">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <p id="texto-direccion">Mueve el mapa para ubicar tu dirección</p>
        </div>

        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('cliente.direcciones.store') }}" class="form-body" id="form-direccion">
            @csrf

            <p class="form-titulo">Detalles de dirección</p>

            {{-- Coordenadas visibles --}}
            <div class="coords-info">
                <div class="coord-tag">
                    <span>Latitud</span>
                    <span id="lat-display">—</span>
                </div>
                <div class="coord-tag">
                    <span>Longitud</span>
                    <span id="lng-display">—</span>
                </div>
            </div>

            {{-- Campos ocultos para coordenadas --}}
            <input type="hidden" name="drc_latitud" id="input-lat">
            <input type="hidden" name="drc_longitud" id="input-lng">

            <div class="field-grid">
                <div class="field">
                    <label>Código postal</label>
                    <input type="text" name="drc_codigo_postal" id="input-cp"
                        value="{{ old('drc_codigo_postal') }}"
                        placeholder="Ej. 86203" maxlength="5" required>
                    @error('drc_codigo_postal')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label>Estado</label>
                    <input type="text" name="drc_estado" id="input-estado"
                        value="{{ old('drc_estado') }}"
                        placeholder="Ej. Tabasco" required>
                    @error('drc_estado')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field">
                <label>Ciudad</label>
                <input type="text" name="drc_ciudad" id="input-ciudad"
                    value="{{ old('drc_ciudad') }}"
                    placeholder="Ej. Villahermosa">
            </div>

            <div class="field">
                <label>Calle</label>
                <input type="text" name="drc_calle" id="input-calle"
                    value="{{ old('drc_calle') }}"
                    placeholder="Nombre de la calle" required>
                @error('drc_calle')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>Número / Depto / Piso</label>
                <input type="text" name="drc_numero"
                    value="{{ old('drc_numero') }}"
                    placeholder="Ej. #42, Depto 3B">
            </div>

            <div class="field">
                <label>Colonia</label>
                <input type="text" name="drc_colonia" id="input-colonia"
                    value="{{ old('drc_colonia') }}"
                    placeholder="Nombre de la colonia">
            </div>

            <div class="field">
                <label>Referencias</label>
                <textarea name="drc_referencias"
                    placeholder="Entre calles, color de fachada, punto de referencia...">{{ old('drc_referencias') }}</textarea>
            </div>

            <div class="check-wrap">
                <input type="checkbox" id="predeterminada" name="predeterminada" value="1"
                    {{ old('predeterminada') ? 'checked' : '' }}>
                <label for="predeterminada">Usar como predeterminada</label>
            </div>

            <button type="submit" class="btn-submit" id="btn-guardar">
                Guardar dirección
            </button>

        </form>

    </div>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ── BANDERA: no geocodificar hasta tener GPS ──────────
    let gpsListo = false;

    // ── INICIALIZAR MAPA ──────────────────────────────────
    let lat = 17.9869;
    let lng = -92.9303;

    const mapa = L.map('mapa', {
        zoomControl: false,
        attributionControl: false,
    }).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(mapa);

    L.control.zoom({ position: 'bottomleft' }).addTo(mapa);

    // ── ACTUALIZAR COORDS AL MOVER ────────────────────────
    function actualizarCoordsDelCentro() {
        const centro = mapa.getCenter();
        lat = centro.lat.toFixed(7);
        lng = centro.lng.toFixed(7);

        document.getElementById('input-lat').value = lat;
        document.getElementById('input-lng').value = lng;
        document.getElementById('lat-display').textContent = parseFloat(lat).toFixed(5);
        document.getElementById('lng-display').textContent = parseFloat(lng).toFixed(5);

        geocodificarInverso(lat, lng);
    }

    // Debounce — solo actuar si GPS ya está listo
    let geocodeTimer;
    mapa.on('moveend', function() {
        if (!gpsListo) return;
        clearTimeout(geocodeTimer);
        geocodeTimer = setTimeout(actualizarCoordsDelCentro, 600);
    });

    // ── GEOCODIFICACIÓN INVERSA ───────────────────────────
    async function geocodificarInverso(lat, lng) {
        try {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`,
                { headers: { 'Accept-Language': 'es' } }
            );
            const data = await res.json();
            const addr = data.address || {};

            const texto = data.display_name
                ? data.display_name.split(',').slice(0, 3).join(', ')
                : 'Dirección no encontrada';
            document.getElementById('texto-direccion').innerHTML =
                `<strong>Ubicación:</strong> ${texto}`;

            // Siempre sobreescribir los campos
            setValue('input-calle',   addr.road || addr.pedestrian || addr.path || '');
            setValue('input-colonia', addr.suburb || addr.neighbourhood || addr.quarter || '');
            setValue('input-ciudad',  addr.city || addr.town || addr.village || addr.municipality || '');
            setValue('input-estado',  addr.state || '');
            setValue('input-cp',      addr.postcode || '');

        } catch (e) {
            document.getElementById('texto-direccion').textContent =
                'Mueve el mapa para ubicar tu dirección';
        }
    }

    function setValue(id, value) {
        const el = document.getElementById(id);
        if (el && value) el.value = value;
    }

    // ── MI UBICACIÓN ─────────────────────────────────────
    function irAMiUbicacion() {
        if (!navigator.geolocation) {
            alert('Tu navegador no soporta geolocalización');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                gpsListo = true;
                mapa.setView([pos.coords.latitude, pos.coords.longitude], 17);
                actualizarCoordsDelCentro();
            },
            () => alert('No se pudo obtener tu ubicación. Verifica los permisos.'),
            { timeout: 8000, enableHighAccuracy: true }
        );
    }

    // ── BUSCAR DIRECCIÓN ─────────────────────────────────
    async function buscarDireccion() {
        const query = document.getElementById('buscar-direccion').value.trim();
        if (!query) return;
        try {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1&accept-language=es`
            );
            const data = await res.json();
            if (data.length > 0) {
                mapa.setView([parseFloat(data[0].lat), parseFloat(data[0].lon)], 17);
                actualizarCoordsDelCentro();
            } else {
                alert('No se encontró esa dirección. Intenta con otra búsqueda.');
            }
        } catch (e) {
            alert('Error al buscar la dirección.');
        }
    }

    document.getElementById('buscar-direccion').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') { e.preventDefault(); buscarDireccion(); }
    });

    // ── INICIALIZAR con GPS ───────────────────────────────
    window.addEventListener('load', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    gpsListo = true;
                    mapa.setView([pos.coords.latitude, pos.coords.longitude], 16);
                    actualizarCoordsDelCentro();
                },
                () => {
                    // Sin permiso → usar coordenadas por defecto
                    gpsListo = true;
                    actualizarCoordsDelCentro();
                },
                { timeout: 8000, enableHighAccuracy: true }
            );
        } else {
            gpsListo = true;
            actualizarCoordsDelCentro();
        }
    });
</script>

</body>

</html>