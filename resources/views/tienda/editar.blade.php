<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/css/tienda/editar.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>

<body>

    {{-- BOTÓN VOLVER --}}
    <a href="{{ \App\Filament\Store\Pages\MiTienda::getUrl(panel: 'store') }}"
        class="fixed top-5 left-5 inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
          text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
          border border-gray-200 hover:bg-white hover:text-gray-900 transition-all z-50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Mi tienda
    </a>

    <div class="card">

        <h1 class="page-title">Editar tienda</h1>

        @if (session('success'))
            <div class="success-msg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- PROGRESO --}}
        <div class="progress">
            <div class="step-item" id="si1">
                <div class="step-circle active" id="sc1">1</div>
                <span class="step-label active" id="sl1">Datos</span>
            </div>
            <div class="step-item" id="si2">
                <div class="step-circle inactive" id="sc2">2</div>
                <span class="step-label inactive" id="sl2">Ubicación</span>
            </div>
            <div class="step-item" id="si3">
                <div class="step-circle inactive" id="sc3">3</div>
                <span class="step-label inactive" id="sl3">Archivos</span>
            </div>
        </div>

        <form action="{{ route('store.editar-tienda') }}" method="POST" enctype="multipart/form-data" id="form-editar">
            @csrf

            {{-- ══ PASO 1: DATOS ══ --}}
            <div id="step1">
                <div class="field">
                    <label>Nombre de la tienda</label>
                    <input type="text" name="tie_nombre" value="{{ $tienda->tie_nombre }}" required>
                </div>
                <div class="field">
                    <label>Descripción</label>
                    <textarea name="tie_descripcion">{{ $tienda->tie_descripcion }}</textarea>
                </div>
                <div class="field">
                    <label>Teléfono</label>
                    <input type="text" name="tie_telefono" value="{{ $tienda->tie_telefono }}">
                </div>
                <div class="nav-btns">
                    <button type="button" class="btn-next" onclick="goTo(2)">Siguiente →</button>
                </div>
            </div>

            {{-- ══ PASO 2: UBICACIÓN ══ --}}
            <div id="step2" style="display:none">
                <div class="mapa-wrap">
                    <div class="mapa-buscador">
                        <input type="text" id="buscar-dir" placeholder="Buscar dirección...">
                        <button type="button" onclick="buscarDir()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="white" style="width:14px;height:14px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                            </svg>
                        </button>
                    </div>
                    <div id="mapa"></div>
                    <div class="mapa-pin">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                fill="#a8df11" stroke="white" stroke-width="1.5" />
                            <circle cx="12" cy="9" r="2.5" fill="white" />
                        </svg>
                    </div>
                    <button type="button" class="mapa-gps" onclick="irAMiUbicacion()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#4a8a06" style="width:16px;height:16px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </button>
                </div>

                <p id="texto-dir" class="dir-detectada">Cargando ubicación guardada...</p>

                <div class="field">
                    <label>Dirección completa</label>
                    <input type="text" name="tie_direccion" id="input-direccion"
                        value="{{ $tienda->tie_direccion }}" required>
                </div>

                <input type="hidden" name="tie_latitud" id="input-lat" value="{{ $tienda->tie_latitud }}">
                <input type="hidden" name="tie_longitud" id="input-lng" value="{{ $tienda->tie_longitud }}">

                <div class="nav-btns">
                    <button type="button" class="btn-prev" onclick="goTo(1)">← Volver</button>
                    <button type="button" class="btn-next" onclick="goTo(3)">Siguiente →</button>
                </div>
            </div>

            {{-- ══ PASO 3: ARCHIVOS ══ --}}
            <div id="step3" style="display:none">

                @php
                    $ine = $tienda->documentos->firstWhere('dot_fk_tipo_documento', 1);
                    $comp = $tienda->documentos->firstWhere('dot_fk_tipo_documento', 2);
                @endphp

                {{-- FACHADA --}}
                <div class="field">
                    <label>Foto de la fachada</label>
                    @if ($tienda->fachada)
                        <img id="preview-fachada" src="{{ asset('storage/' . $tienda->fachada->fac_ruta) }}"
                            class="preview-fachada">
                    @else
                        <img id="preview-fachada" class="preview-fachada" style="display:none">
                    @endif
                    <label for="input-fachada" class="upload-zone">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                            </svg>
                        </div>
                        <div>
                            <p class="upload-label">{{ $tienda->fachada ? 'Cambiar imagen' : 'Subir fachada' }}</p>
                            <p class="upload-hint">JPG, PNG, WEBP — máx 2MB</p>
                            <p class="upload-name" id="fachada-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-fachada" name="fachada" accept="image/*" style="display:none"
                        onchange="previewFachada(event)">
                </div>

                {{-- INE --}}
                <div class="field">
                    <label>Identificación Oficial (INE)</label>
                    @if ($ine)
                        <a href="{{ asset('storage/' . $ine->dot_ruta) }}" target="_blank" class="doc-link">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Ver INE actual
                        </a>
                    @endif
                    <label for="input-ine" class="upload-zone">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="upload-label">{{ $ine ? 'Reemplazar INE' : 'Subir INE' }}</p>
                            <p class="upload-hint">Solo PDF — máx 4MB</p>
                            <p class="upload-name" id="ine-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-ine" name="ine" accept="application/pdf"
                        style="display:none" onchange="showName(event,'ine-name')">
                </div>

                {{-- COMPROBANTE --}}
                <div class="field">
                    <label>Comprobante de domicilio</label>
                    @if ($comp)
                        <a href="{{ asset('storage/' . $comp->dot_ruta) }}" target="_blank" class="doc-link">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Ver comprobante actual
                        </a>
                    @endif
                    <label for="input-comp" class="upload-zone">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                        <div>
                            <p class="upload-label">{{ $comp ? 'Reemplazar comprobante' : 'Subir comprobante' }}</p>
                            <p class="upload-hint">Solo PDF — máx 4MB</p>
                            <p class="upload-name" id="comp-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-comp" name="comprobante" accept="application/pdf"
                        style="display:none" onchange="showName(event,'comp-name')">
                </div>

                <div class="nav-btns">
                    <button type="button" class="btn-prev" onclick="goTo(2)">← Volver</button>
                    <button type="submit" class="btn-submit">Guardar cambios</button>
                </div>
            </div>

        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── NAVEGACIÓN ─────────────────────────────────────
        let mapaListo = false;

        function goTo(step) {
            [1, 2, 3].forEach(i => {
                document.getElementById('step' + i).style.display = 'none';
                const sc = document.getElementById('sc' + i);
                const sl = document.getElementById('sl' + i);
                const si = document.getElementById('si' + i);
                if (i < step) {
                    sc.className = 'step-circle done';
                    sl.className = 'step-label done';
                    si.classList.add('done');
                } else if (i === step) {
                    sc.className = 'step-circle active';
                    sl.className = 'step-label active';
                    si.classList.remove('done');
                } else {
                    sc.className = 'step-circle inactive';
                    sl.className = 'step-label inactive';
                    si.classList.remove('done');
                }
            });
            document.getElementById('step' + step).style.display = 'block';

            if (step === 2 && !mapaListo) {
                initMapa();
                mapaListo = true;
            }
        }

        // ── MAPA ───────────────────────────────────────────
        let mapa, gpsListo = false;
        const initLat = {{ floatval($tienda->tie_latitud) ?: 17.9869 }};
        const initLng = {{ floatval($tienda->tie_longitud) ?: -92.9303 }};

        function initMapa() {
            mapa = L.map('mapa', {
                    zoomControl: false,
                    attributionControl: false
                })
                .setView([initLat, initLng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(mapa);
            L.control.zoom({
                position: 'bottomleft'
            }).addTo(mapa);

            let timer;
            mapa.on('moveend', () => {
                if (!gpsListo) return;
                clearTimeout(timer);
                timer = setTimeout(actualizarCoords, 600);
            });

            geocodificarInverso(initLat, initLng).then(() => {
                gpsListo = true;
            });
            setTimeout(() => mapa.invalidateSize(), 100);
        }

        function actualizarCoords() {
            const c = mapa.getCenter();
            const lat = c.lat.toFixed(7);
            const lng = c.lng.toFixed(7);
            document.getElementById('input-lat').value = lat;
            document.getElementById('input-lng').value = lng;
            geocodificarInverso(lat, lng);
        }

        async function geocodificarInverso(lat, lng) {
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`
                );
                const data = await res.json();
                const addr = data.address || {};
                const texto = data.display_name ? data.display_name.split(',').slice(0, 3).join(', ') : '—';
                document.getElementById('texto-dir').textContent = texto;
                const partes = [
                    addr.road || addr.pedestrian || '',
                    addr.suburb || addr.neighbourhood || '',
                    addr.city || addr.town || addr.village || '',
                    addr.state || '', addr.postcode || '', 'México'
                ].filter(Boolean);
                const dir = partes.join(', ');
                if (dir) document.getElementById('input-direccion').value = dir;
            } catch (e) {
                document.getElementById('texto-dir').textContent = 'No se pudo detectar la dirección';
            }
        }

        function irAMiUbicacion() {
            if (!navigator.geolocation) return alert('Tu navegador no soporta geolocalización');
            navigator.geolocation.getCurrentPosition(
                pos => {
                    gpsListo = true;
                    mapa.setView([pos.coords.latitude, pos.coords.longitude], 17);
                    actualizarCoords();
                },
                () => alert('No se pudo obtener tu ubicación.'), {
                    timeout: 8000,
                    enableHighAccuracy: true
                }
            );
        }

        async function buscarDir() {
            const q = document.getElementById('buscar-dir').value.trim();
            if (!q) return;
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=1`);
                const data = await res.json();
                if (data.length > 0) {
                    gpsListo = true;
                    mapa.setView([parseFloat(data[0].lat), parseFloat(data[0].lon)], 17);
                    actualizarCoords();
                } else alert('No se encontró esa dirección.');
            } catch (e) {
                alert('Error al buscar.');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('buscar-dir')?.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarDir();
                }
            });
        });

        // ── ARCHIVOS ───────────────────────────────────────
        function previewFachada(event) {
            const file = event.target.files[0];
            if (!file) return;
            document.getElementById('fachada-name').textContent = file.name;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview-fachada');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function showName(event, id) {
            const file = event.target.files[0];
            if (file) document.getElementById(id).textContent = '📎 ' + file.name;
        }
    </script>
</body>

</html>
