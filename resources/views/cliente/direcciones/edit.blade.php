<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar dirección</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/direcciones/edit.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.direcciones.show', $direccion->drc_id) }}" class="btn-back">
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

        {{-- MAPA --}}
        <div class="mapa-wrap">
            <div class="mapa-search">
                <input type="text" id="buscar-direccion" placeholder="Buscar dirección...">
                <button type="button" class="mapa-search-btn" onclick="buscarDireccion()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                </button>
            </div>
            <div id="mapa"></div>
            <div class="mapa-pin-center">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11"
                        stroke="white" stroke-width="1.5" />
                    <circle cx="12" cy="9" r="2.5" fill="white" />
                </svg>
            </div>
            <button type="button" class="btn-mi-ubicacion" onclick="irAMiUbicacion()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
            </button>
        </div>

        <div class="direccion-detectada">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <p id="texto-direccion">Cargando ubicación guardada...</p>
        </div>

        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('cliente.direcciones.update', $direccion->drc_id) }}" class="form-body">
            @csrf
            @method('PUT')

            <p class="form-titulo">Editar dirección</p>

            <div class="coords-info">
                <div class="coord-tag">
                    <span>Latitud</span>
                    <span id="lat-display">{{ $direccion->drc_latitud }}</span>
                </div>
                <div class="coord-tag">
                    <span>Longitud</span>
                    <span id="lng-display">{{ $direccion->drc_longitud }}</span>
                </div>
            </div>

            <input type="hidden" name="drc_latitud" id="input-lat" value="{{ $direccion->drc_latitud }}">
            <input type="hidden" name="drc_longitud" id="input-lng" value="{{ $direccion->drc_longitud }}">

            <div class="field-grid">
                <div class="field">
                    <label>Código postal</label>
                    <input type="text" name="drc_codigo_postal" id="input-cp"
                        value="{{ old('drc_codigo_postal', $direccion->drc_codigo_postal) }}" placeholder="Ej. 86203"
                        maxlength="5" required>
                    @error('drc_codigo_postal')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="field">
                    <label>Estado</label>
                    <input type="text" name="drc_estado" id="input-estado"
                        value="{{ old('drc_estado', $direccion->drc_estado) }}" placeholder="Ej. Tabasco" required>
                    @error('drc_estado')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field">
                <label>Ciudad</label>
                <input type="text" name="drc_ciudad" id="input-ciudad"
                    value="{{ old('drc_ciudad', $direccion->drc_ciudad) }}" placeholder="Ej. Villahermosa">
            </div>

            <div class="field">
                <label>Calle</label>
                <input type="text" name="drc_calle" id="input-calle"
                    value="{{ old('drc_calle', $direccion->drc_calle) }}" placeholder="Nombre de la calle" required>
                @error('drc_calle')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label>Número / Depto / Piso</label>
                <input type="text" name="drc_numero" value="{{ old('drc_numero', $direccion->drc_numero) }}"
                    placeholder="Ej. #42, Depto 3B">
            </div>

            <div class="field">
                <label>Colonia</label>
                <input type="text" name="drc_colonia" id="input-colonia"
                    value="{{ old('drc_colonia', $direccion->drc_colonia) }}" placeholder="Nombre de la colonia">
            </div>

            <div class="field">
                <label>Referencias</label>
                <textarea name="drc_referencias" placeholder="Entre calles, color de fachada...">{{ old('drc_referencias', $direccion->drc_referencias) }}</textarea>
            </div>

            <div class="check-wrap">
                <input type="checkbox" id="predeterminada" name="predeterminada" value="1"
                    {{ session('direccion_id') == $direccion->drc_id ? 'checked' : '' }}>
                <label for="predeterminada">Usar como predeterminada</label>
            </div>

            <button type="submit" class="btn-submit">Guardar cambios</button>

        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── BANDERA: no geocodificar hasta estar listo ────────
        let gpsListo = false;

        // Coordenadas guardadas de la dirección
        const initLat = @json(floatval($direccion->drc_latitud) ?: 17.9869);
        const initLng = @json(floatval($direccion->drc_longitud) ?: -92.9303);

        let lat = initLat;
        let lng = initLng;

        // ── INICIALIZAR MAPA ──────────────────────────────────
        const mapa = L.map('mapa', {
            zoomControl: false,
            attributionControl: false,
        }).setView([initLat, initLng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mapa);

        L.control.zoom({
            position: 'bottomleft'
        }).addTo(mapa);

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

        // Debounce — solo si ya está listo
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
                    `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`, {
                        headers: {
                            'Accept-Language': 'es'
                        }
                    }
                );
                const data = await res.json();
                const addr = data.address || {};

                const texto = data.display_name ?
                    data.display_name.split(',').slice(0, 3).join(', ') :
                    'Dirección no encontrada';
                document.getElementById('texto-direccion').innerHTML =
                    `<strong>Ubicación:</strong> ${texto}`;

                // Actualizar campos con la nueva ubicación
                setValue('input-calle', addr.road || addr.pedestrian || addr.path || '');
                setValue('input-colonia', addr.suburb || addr.neighbourhood || addr.quarter || '');
                setValue('input-ciudad', addr.city || addr.town || addr.village || addr.municipality || '');
                setValue('input-estado', addr.state || '');
                setValue('input-cp', addr.postcode || '');

            } catch (e) {
                document.getElementById('texto-direccion').textContent =
                    'Mueve el mapa para actualizar la ubicación';
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
                () => alert('No se pudo obtener tu ubicación. Verifica los permisos.'), {
                    timeout: 8000,
                    enableHighAccuracy: true
                }
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
                    gpsListo = true;
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
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarDireccion();
            }
        });

        // ── INICIALIZAR: mostrar ubicación guardada ───────────
        window.addEventListener('load', () => {
            // Mostrar la dirección guardada sin cambiar los campos
            geocodificarInverso(initLat, initLng).then(() => {
                // Activar moveend solo después de cargar la ubicación guardada
                gpsListo = true;
            });
        });
    </script>
</body>

</html>
