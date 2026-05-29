<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/filament/store/theme.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body class="bg-[#edf3e3] min-h-screen flex items-center justify-center p-4">
    {{-- BOTÓN PORTAL (esquina superior izquierda) --}}
<a href="{{ route('portal') }}"
   class="fixed top-5 left-5 inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
          text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
          border border-gray-200 hover:bg-white hover:text-gray-900 transition-all duration-200 z-50">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
         stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Portal
</a>

{{-- BOTÓN LOGOUT (esquina superior derecha) --}}
<form method="POST" action="{{ route('logout') }}" class="fixed top-5 right-5 z-50">
    @csrf
    <button type="submit"
            class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
                   text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
                   border border-gray-200 hover:bg-white hover:text-red-600 transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
        </svg>
        Salir
    </button>
</form>

<div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

    {{-- FORM --}}
    <div class="p-6 md:p-12 flex flex-col justify-center">

        {{-- PROGRESO --}}
        <div class="flex justify-between mb-6 text-sm font-bold">
            <span id="lbl1" class="text-[#a8df11]">Datos</span>
            <span id="lbl2" class="text-gray-400">Dirección</span>
            <span id="lbl3" class="text-gray-400">Archivos</span>
        </div>

        <form action="{{ route('registro.tienda.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ══ PASO 1: DATOS ══ --}}
            <div id="step1">
                <h1 class="text-2xl md:text-3xl font-extrabold text-center mb-6">
                    Datos de la tienda
                </h1>

                <div class="space-y-4">
                    <input type="text" name="tie_nombre" value="{{ old('tie_nombre') }}"
                           placeholder="Nombre de la tienda"
                           class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 text-center focus:outline-none" required>
                    @error('tie_nombre')<p class="text-red-500 text-xs text-center">{{ $message }}</p>@enderror

                    <textarea name="tie_descripcion"
                              placeholder="Descripción"
                              class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 text-center focus:outline-none resize-none h-24" required>{{ old('tie_descripcion') }}</textarea>
                    @error('tie_descripcion')<p class="text-red-500 text-xs text-center">{{ $message }}</p>@enderror

                    <input type="text" name="tie_telefono" value="{{ old('tie_telefono') }}"
                           placeholder="Teléfono"
                           class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 text-center focus:outline-none" required>
                    @error('tie_telefono')<p class="text-red-500 text-xs text-center">{{ $message }}</p>@enderror
                </div>

                <button type="button" onclick="goTo(2)"
                        class="w-full mt-6 bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-3 rounded-lg transition">
                    Siguiente →
                </button>
            </div>

            {{-- ══ PASO 2: DIRECCIÓN ══ --}}
            <div id="step2" class="hidden">
                <h1 class="text-2xl md:text-3xl font-extrabold text-center mb-6">
                    Ubicación
                </h1>

                {{-- Mapa --}}
                <div class="mapa-wrap mb-3">
                    <div class="mapa-buscador">
                        <input type="text" id="buscar-dir" placeholder="Buscar dirección...">
                        <button type="button" onclick="buscarDir()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                        </button>
                    </div>
                    <div id="mapa" class="border-2 border-gray-800 rounded-lg"></div>
                    <div class="mapa-pin">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/>
                            <circle cx="12" cy="9" r="2.5" fill="white"/>
                        </svg>
                    </div>
                    <button type="button" class="mapa-gps" onclick="irAMiUbicacion()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4a8a06" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                    </button>
                </div>

                <p id="texto-dir" class="text-xs text-gray-500 text-center mb-3">Mueve el mapa para detectar la dirección</p>

                <input type="text" name="tie_direccion" id="input-direccion"
                       value="{{ old('tie_direccion') }}"
                       placeholder="Dirección (se rellena automáticamente)"
                       class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 text-center focus:outline-none mb-1" required>
                @error('tie_direccion')<p class="text-red-500 text-xs text-center">{{ $message }}</p>@enderror

                <input type="hidden" name="tie_latitud" id="input-lat">
                <input type="hidden" name="tie_longitud" id="input-lng">

                <div class="flex gap-3 mt-5">
                    <button type="button" onclick="goTo(1)"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg transition">
                        ← Volver
                    </button>
                    <button type="button" onclick="goTo(3)"
                            class="flex-[2] bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-3 rounded-lg transition">
                        Siguiente →
                    </button>
                </div>
            </div>

            {{-- ══ PASO 3: ARCHIVOS ══ --}}
            <div id="step3" class="hidden space-y-5">
                <h1 class="text-2xl md:text-3xl font-extrabold text-center mb-2">
                    Documentación
                </h1>

                {{-- FACHADA --}}
                <div>
                    <label class="block font-semibold mb-2">Foto de la fachada</label>
                    <input type="file" name="fachada" accept="image/*"
                           onchange="previewFachada(event)"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-2">
                    <img id="preview-fachada" class="preview-img">
                    @error('fachada')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>

                {{-- INE --}}
                <div>
                    <label class="block font-semibold mb-2">Identificación Oficial (INE)</label>
                    <input type="file" name="ine" accept="application/pdf"
                           onchange="showName(event,'ine-name')"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-2">
                    <p id="ine-name" class="text-xs text-gray-500 mt-1"></p>
                    @error('ine')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>

                {{-- COMPROBANTE --}}
                <div>
                    <label class="block font-semibold mb-2">Comprobante de domicilio</label>
                    <input type="file" name="comprobante" accept="application/pdf"
                           onchange="showName(event,'comp-name')"
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-2">
                    <p id="comp-name" class="text-xs text-gray-500 mt-1"></p>
                    @error('comprobante')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="goTo(2)"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg transition">
                        ← Volver
                    </button>
                    <button type="submit"
                            class="flex-[2] bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-3 rounded-lg transition">
                        Registrar tienda
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- IMAGEN --}}
    <div class="hidden md:flex bg-[#a8df11] items-center justify-center relative overflow-hidden">
        <img src="{{ asset('images/Logo_local_app.png') }}"
             class="max-w-md w-full z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── NAVEGACIÓN ────────────────────────────────────────
    const labels = { 1: 'lbl1', 2: 'lbl2', 3: 'lbl3' };
    let mapaListo = false;

    function goTo(step) {
        [1, 2, 3].forEach(i => {
            document.getElementById('step' + i).classList.add('hidden');
            document.getElementById(labels[i]).className = 'text-gray-400';
        });
        document.getElementById('step' + step).classList.remove('hidden');
        document.getElementById(labels[step]).className = 'text-[#a8df11]';

        if (step === 2 && !mapaListo) {
            initMapa();
            mapaListo = true;
        }
    }

    // ── MAPA ─────────────────────────────────────────────
    let mapa, gpsListo = false;

    function initMapa() {
        mapa = L.map('mapa', { zoomControl: false, attributionControl: false })
            .setView([17.9869, -92.9303], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapa);
        L.control.zoom({ position: 'bottomleft' }).addTo(mapa);

        let timer;
        mapa.on('moveend', () => {
            if (!gpsListo) return;
            clearTimeout(timer);
            timer = setTimeout(actualizarCoords, 600);
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => { gpsListo = true; mapa.setView([pos.coords.latitude, pos.coords.longitude], 16); actualizarCoords(); },
                () => { gpsListo = true; actualizarCoords(); },
                { timeout: 8000, enableHighAccuracy: true }
            );
        } else {
            gpsListo = true;
            actualizarCoords();
        }

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
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`);
            const data = await res.json();
            const addr = data.address || {};
            const texto = data.display_name ? data.display_name.split(',').slice(0,3).join(', ') : '—';
            document.getElementById('texto-dir').textContent = texto;
            const partes = [
                addr.road || addr.pedestrian || '',
                addr.suburb || addr.neighbourhood || '',
                addr.city || addr.town || addr.village || '',
                addr.state || '',
                addr.postcode || '',
                'México'
            ].filter(Boolean);
            const dir = partes.join(', ');
            if (dir) document.getElementById('input-direccion').value = dir;
        } catch(e) {
            document.getElementById('texto-dir').textContent = 'No se pudo detectar la dirección';
        }
    }

    function irAMiUbicacion() {
        if (!navigator.geolocation) return alert('Tu navegador no soporta geolocalización');
        navigator.geolocation.getCurrentPosition(
            pos => { gpsListo = true; mapa.setView([pos.coords.latitude, pos.coords.longitude], 17); actualizarCoords(); },
            () => alert('No se pudo obtener tu ubicación.'),
            { timeout: 8000, enableHighAccuracy: true }
        );
    }

    async function buscarDir() {
        const q = document.getElementById('buscar-dir').value.trim();
        if (!q) return;
        try {
            const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=1`);
            const data = await res.json();
            if (data.length > 0) { gpsListo = true; mapa.setView([parseFloat(data[0].lat), parseFloat(data[0].lon)], 17); actualizarCoords(); }
            else alert('No se encontró esa dirección.');
        } catch(e) { alert('Error al buscar.'); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('buscar-dir')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); buscarDir(); }
        });
    });

    // ── ARCHIVOS ─────────────────────────────────────────
    function previewFachada(event) {
        const file = event.target.files[0];
        if (!file) return;
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