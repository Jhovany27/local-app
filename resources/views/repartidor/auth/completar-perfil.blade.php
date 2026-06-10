<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Completar perfil de repartidor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
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
            padding: 1.5rem;
        }

        .app {
            width: 100%;
            max-width: 430px;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            padding: 2.5rem 2rem;
        }

        .avatar {
            width: 64px;
            height: 64px;
            background: #f0fde0;
            border: 2px solid #d4f0a0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .avatar svg {
            width: 32px;
            height: 32px;
            color: #4a8a06;
        }

        h1 {
            font-size: 1.3rem;
            font-weight: 900;
            color: #111;
            text-align: center;
            margin-bottom: 0.35rem;
        }

        .subtitle {
            font-size: 0.8rem;
            color: #aaa;
            text-align: center;
            margin-bottom: 0.25rem;
        }

        .user-email {
            font-size: 0.82rem;
            font-weight: 700;
            color: #4a8a06;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        /* Progreso */
        .progress {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 2rem;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 14px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e8f5d0;
            z-index: 0;
        }

        .step-item.done::after {
            background: #a8df11;
        }

        .step-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .inactive {
            background: #f0f0f0;
            border: 2px solid #e0e0e0;
            color: #aaa;
        }

        .active-c {
            background: #a8df11;
            border: 2px solid #a8df11;
            color: white;
            box-shadow: 0 4px 12px rgba(168, 223, 17, 0.4);
        }

        .done-c {
            background: #a8df11;
            border: 2px solid #a8df11;
            color: white;
        }

        .step-label {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .lbl-inactive {
            color: #ccc;
        }

        .lbl-active {
            color: #4a8a06;
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
        .field select {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 0.85rem;
            padding: 0.75rem 1rem;
            font-family: inherit;
            font-size: 0.88rem;
            color: #111;
            outline: none;
            transition: border-color 0.2s;
            background: white;
        }

        .field input:focus,
        .field select:focus {
            border-color: #a8df11;
            box-shadow: 0 0 0 4px rgba(168, 223, 17, 0.1);
        }

        .upload-zone {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border: 2px dashed #d4f0a0;
            background: #f8fdf0;
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .upload-zone:hover {
            border-color: #a8df11;
            background: #f0fde0;
        }

        .upload-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.6rem;
            background: white;
            border: 1px solid #d4f0a0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .upload-icon svg {
            width: 16px;
            height: 16px;
            color: #a8df11;
        }

        .upload-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #4a8a06;
        }

        .upload-hint {
            font-size: 0.68rem;
            color: #aaa;
            margin-top: 0.1rem;
        }

        .upload-name {
            font-size: 0.72rem;
            color: #555;
            font-weight: 600;
            margin-top: 0.15rem;
        }

        .preview-foto {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #a8df11;
            margin: 0 auto 0.65rem;
            display: none;
        }

        .nav-btns {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-prev {
            flex: 1;
            background: #f0f0f0;
            color: #555;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
        }

        .btn-next,
        .btn-submit {
            flex: 2;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
        }

        .btn-radio {
            background: #f0f0f0;
            border: 2px solid #e0e0e0;
            border-radius: 999px;
            padding: 0.28rem 0.8rem;
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 700;
            color: #555;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-radio:hover {
            background: #f0fde0;
            border-color: #a8df11;
            color: #4a8a06;
        }
    </style>
</head>

<body>
    <div class="app">

        <div class="avatar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
            </svg>
        </div>

        <h1>¡Hola, {{ $user->persona?->per_nombre ?? $user->name }}!</h1>
        <p class="subtitle">Ingresaste con</p>
        <p class="user-email">{{ $user->email }}</p>

        @if (!empty($rechazado) && $rechazado)
            <div style="background:#fff1f0;border:1.5px solid #fca5a5;border-radius:0.85rem;padding:0.9rem 1.1rem;margin-bottom:1.5rem;">
                <p style="font-size:0.68rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#d41b11;margin-bottom:0.35rem;">Solicitud rechazada</p>
                <p style="font-size:0.82rem;color:#333;line-height:1.55;">Tu solicitud anterior fue rechazada. Por favor sube nuevamente tus documentos para volver a enviarla.</p>
            </div>
        @endif

        {{-- PROGRESO --}}
        <div class="progress">
            <div class="step-item" id="si1">
                <div class="step-circle active-c" id="sc1">1</div>
                <span class="step-label lbl-active" id="sl1">Vehículo</span>
            </div>
            <div class="step-item" id="si2">
                <div class="step-circle inactive" id="sc2">2</div>
                <span class="step-label lbl-inactive" id="sl2">Documentos</span>
            </div>
        </div>

        @if ($errors->any())
            <div
                style="background:#fff1f0;border:1px solid #fca5a5;color:#d41b11;font-size:0.8rem;font-weight:600;padding:0.65rem 1rem;border-radius:0.75rem;margin-bottom:1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('repartidor.completar-perfil.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- PASO 1: VEHÍCULO --}}
            <div id="step1">
                <div class="field">
                    <label>Tipo de vehículo</label>
                    <select name="rep_tipo_vehiculo" required>
                        <option value="">Seleccionar...</option>
                        <option value="Motocicleta">🏍 Motocicleta</option>
                        <option value="Automovil">🚗 Automóvil</option>
                        <option value="Bicicleta">🚲 Bicicleta</option>
                        <option value="Pie">🚶 A pie</option>
                    </select>
                </div>

                {{-- ZONA DE REPARTO --}}
                @php
                    $preLat      = old('rep_lat',      $repartidor->rep_lat      ?? '');
                    $preLng      = old('rep_lng',      $repartidor->rep_lng      ?? '');
                    $preRadio    = old('rep_radio_km', $repartidor->rep_radio_km ?? 10);
                    $preCp       = old('rep_cp',       $repartidor->rep_cp       ?? '');
                    $preColonia  = old('rep_colonia',  $repartidor->rep_colonia  ?? '');
                    $preMunicipio= old('rep_ciudad',   $repartidor->rep_ciudad   ?? '');
                    $preEstado   = old('rep_entidad',  $repartidor->rep_entidad  ?? '');
                @endphp

                <div class="field">
                    <label>Zona donde repartes</label>
                    <p style="font-size:0.73rem;color:#888;margin-bottom:0.65rem;line-height:1.5">
                        Mueve el mapa a tu zona y ajusta el radio. Los datos se llenarán solos.
                    </p>
                </div>

                <div style="position:relative;margin-bottom:0.65rem">
                    <input type="text" id="buscar-zona" placeholder="Busca tu ciudad o colonia..."
                        style="width:100%;border:2px solid #e5e7eb;border-radius:0.85rem;padding:0.7rem 3rem 0.7rem 1rem;font-family:inherit;font-size:0.85rem;color:#111;outline:none;"
                        onkeydown="if(event.key==='Enter'){event.preventDefault();buscarZona();}">
                    <button type="button" onclick="buscarZona()" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#a8df11" style="width:20px;height:20px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                    </button>
                </div>

                <div style="display:flex;gap:0.5rem;align-items:center;margin-bottom:0.65rem;flex-wrap:wrap">
                    <span style="font-size:0.75rem;font-weight:700;color:#333">Radio:</span>
                    <button type="button" class="btn-radio" data-km="5"  onclick="setRadio(5)">5 km</button>
                    <button type="button" class="btn-radio" data-km="10" onclick="setRadio(10)">10 km</button>
                    <button type="button" class="btn-radio" data-km="15" onclick="setRadio(15)">15 km</button>
                    <button type="button" class="btn-radio" data-km="20" onclick="setRadio(20)">20 km</button>
                </div>

                <div style="position:relative;border-radius:1rem;overflow:hidden;border:2px solid #e5e7eb;margin-bottom:0.5rem">
                    <div id="mapa-rep" style="height:260px"></div>
                    <button type="button" onclick="irAMiUbicacion()"
                        style="position:absolute;bottom:0.65rem;right:0.65rem;z-index:999;background:white;border:1.5px solid #e5e7eb;border-radius:0.65rem;padding:0.35rem 0.75rem;display:flex;align-items:center;gap:0.35rem;font-family:inherit;font-size:0.73rem;font-weight:700;color:#333;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#a8df11" style="width:16px;height:16px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                        Mi zona
                    </button>
                </div>

                <p id="texto-zona" style="font-size:0.73rem;color:#888;text-align:center;min-height:1.1rem;margin-bottom:0.65rem"></p>

                <div id="campos-zona" style="{{ $preEstado || $preMunicipio ? '' : 'display:none' }}">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem">
                        <div class="field">
                            <label>CP</label>
                            <input type="text" id="campo-cp" name="rep_cp"
                                value="{{ $preCp }}"
                                style="background:#f8fdf0" readonly placeholder="Auto">
                        </div>
                        <div class="field">
                            <label>Colonia</label>
                            <input type="text" id="campo-colonia" name="rep_colonia"
                                value="{{ $preColonia }}"
                                style="background:#f8fdf0" placeholder="Auto">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.75rem">
                        <div class="field">
                            <label>Municipio</label>
                            <input type="text" id="campo-municipio" name="rep_ciudad"
                                value="{{ $preMunicipio }}"
                                style="background:#f8fdf0" required placeholder="Auto">
                        </div>
                        <div class="field">
                            <label>Estado</label>
                            <input type="text" id="campo-estado" name="rep_entidad"
                                value="{{ $preEstado }}"
                                style="background:#f8fdf0" readonly placeholder="Auto">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="rep_lat"      id="campo-lat"   value="{{ $preLat }}">
                <input type="hidden" name="rep_lng"      id="campo-lng"   value="{{ $preLng }}">
                <input type="hidden" name="rep_radio_km" id="campo-radio" value="{{ $preRadio }}">

                @if (!$user->persona?->per_telefono)
                    <div class="field">
                        <label>Teléfono</label>
                        <input type="text" name="per_telefono" placeholder="10 dígitos">
                    </div>
                @endif

                <div class="nav-btns">
                    <button type="button" class="btn-next" style="flex:1" onclick="goTo(2)">Siguiente →</button>
                </div>
            </div>

            {{-- PASO 2: DOCUMENTOS --}}
            <div id="step2" style="display:none">

                {{-- FOTO PERFIL --}}
                <div class="field" style="text-align:center">
                    <label>Foto de perfil</label>
                    <img id="preview-foto" class="preview-foto">
                    <label for="input-foto" class="upload-zone">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                            </svg>
                        </div>
                        <div>
                            <p class="upload-label">Foto de perfil</p>
                            <p class="upload-hint">JPG, PNG — máx 2MB</p>
                            <p class="upload-name" id="foto-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-foto" name="foto_perfil" accept="image/*" style="display:none"
                        onchange="previewFoto(event)" required>
                </div>

                {{-- INE --}}
                <div class="field">
                    <label>Identificación Oficial (INE)</label>
                    <label for="input-ine" class="upload-zone">
                        <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg></div>
                        <div>
                            <p class="upload-label">Seleccionar PDF</p>
                            <p class="upload-hint">Solo PDF — máx 4MB</p>
                            <p class="upload-name" id="ine-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-ine" name="ine" accept="application/pdf" style="display:none"
                        onchange="showName(event,'ine-name')" required>
                </div>

                {{-- LICENCIA --}}
                <div class="field">
                    <label>Licencia de conducir</label>
                    <label for="input-licencia" class="upload-zone">
                        <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg></div>
                        <div>
                            <p class="upload-label">Seleccionar PDF</p>
                            <p class="upload-hint">Solo PDF — máx 4MB</p>
                            <p class="upload-name" id="licencia-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-licencia" name="licencia" accept="application/pdf"
                        style="display:none" onchange="showName(event,'licencia-name')" required>
                </div>

                {{-- CIRCULACIÓN --}}
                <div class="field">
                    <label>Tarjeta de circulación</label>
                    <label for="input-circ" class="upload-zone">
                        <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg></div>
                        <div>
                            <p class="upload-label">Seleccionar PDF</p>
                            <p class="upload-hint">Solo PDF — máx 4MB</p>
                            <p class="upload-name" id="circ-name"></p>
                        </div>
                    </label>
                    <input type="file" id="input-circ" name="circulacion" accept="application/pdf"
                        style="display:none" onchange="showName(event,'circ-name')" required>
                </div>

                <div class="nav-btns">
                    <button type="button" class="btn-prev" onclick="goTo(1)">← Volver</button>
                    <button type="submit" class="btn-submit">Enviar solicitud</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function goTo(step) {
            [1, 2].forEach(i => {
                document.getElementById('step' + i).style.display = 'none';
                const sc = document.getElementById('sc' + i);
                const sl = document.getElementById('sl' + i);
                const si = document.getElementById('si' + i);
                if (i < step) {
                    sc.className = 'step-circle done-c';
                    sl.className = 'step-label lbl-active';
                    si.classList.add('done');
                } else if (i === step) {
                    sc.className = 'step-circle active-c';
                    sl.className = 'step-label lbl-active';
                    si.classList.remove('done');
                } else {
                    sc.className = 'step-circle inactive';
                    sl.className = 'step-label lbl-inactive';
                    si.classList.remove('done');
                }
            });
            document.getElementById('step' + step).style.display = 'block';
            window.scrollTo(0, 0);
        }

        function previewFoto(e) {
            const file = e.target.files[0];
            if (!file) return;
            document.getElementById('foto-name').textContent = file.name;
            const r = new FileReader();
            r.onload = ev => {
                const img = document.getElementById('preview-foto');
                img.src = ev.target.result;
                img.style.display = 'block';
            };
            r.readAsDataURL(file);
        }

        function showName(e, id) {
            const f = e.target.files[0];
            if (f) document.getElementById(id).textContent = '📎 ' + f.name;
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── MAPA ZONA DE REPARTO ──────────────────────────────
        let radioKm  = parseInt('{{ $preRadio }}') || 10;
        let mapaRep, circulo, gpsListo = false, geocodeTimer;
        const initLat = {{ (float) $preLat }};
        const initLng = {{ (float) $preLng }};

        document.addEventListener('DOMContentLoaded', function () {
            mapaRep = L.map('mapa-rep', { zoomControl: false, attributionControl: false })
                .setView([initLat || 19.4326, initLng || -99.1332], initLat ? 13 : 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapaRep);
            L.control.zoom({ position: 'bottomleft' }).addTo(mapaRep);

            circulo = L.circle(mapaRep.getCenter(), {
                radius: radioKm * 1000,
                color: '#a8df11', fillColor: '#a8df11',
                fillOpacity: 0.13, weight: 2,
            }).addTo(mapaRep);

            mapaRep.on('move', () => circulo.setLatLng(mapaRep.getCenter()));
            mapaRep.on('moveend', function () {
                if (!gpsListo) return;
                clearTimeout(geocodeTimer);
                geocodeTimer = setTimeout(() => {
                    const c = mapaRep.getCenter();
                    geocodificarZona(c.lat, c.lng);
                }, 600);
            });

            if (initLat && initLng) {
                gpsListo = true;
                setRadio(radioKm);
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        gpsListo = true;
                        mapaRep.setView([pos.coords.latitude, pos.coords.longitude], 13);
                    },
                    () => { gpsListo = true; geocodificarZona(19.4326, -99.1332); },
                    { timeout: 8000, enableHighAccuracy: true }
                );
            } else {
                gpsListo = true;
                geocodificarZona(19.4326, -99.1332);
            }

            setRadio(radioKm);
        });

        function setRadio(km) {
            radioKm = km;
            if (circulo) circulo.setRadius(km * 1000);
            document.getElementById('campo-radio').value = km;
            document.querySelectorAll('.btn-radio').forEach(btn => {
                const a = parseInt(btn.dataset.km) === km;
                btn.style.background  = a ? '#f0fde0' : '';
                btn.style.borderColor = a ? '#a8df11' : '';
                btn.style.color       = a ? '#4a8a06' : '';
            });
        }

        async function geocodificarZona(lat, lng) {
            try {
                const res  = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`);
                const data = await res.json();
                const addr = data.address || {};
                setVal('campo-cp',        addr.postcode || '');
                setVal('campo-colonia',   addr.suburb || addr.neighbourhood || addr.quarter || '');
                setVal('campo-municipio', addr.city || addr.town || addr.village || addr.municipality || '');
                setVal('campo-estado',    addr.state || '');
                document.getElementById('campo-lat').value = lat;
                document.getElementById('campo-lng').value = lng;
                const lugar = addr.city || addr.town || addr.village || addr.municipality || '';
                document.getElementById('texto-zona').textContent = lugar ? `Zona: ${lugar}` : '';
                document.getElementById('campos-zona').style.display = 'block';
            } catch {}
        }

        function setVal(id, v) {
            const el = document.getElementById(id);
            if (el && v) el.value = v;
        }

        async function buscarZona() {
            const q = document.getElementById('buscar-zona').value.trim();
            if (!q) return;
            try {
                const r = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=1&accept-language=es`);
                const d = await r.json();
                if (d.length) { gpsListo = true; mapaRep.setView([+d[0].lat, +d[0].lon], 13); }
            } catch {}
        }

        function irAMiUbicacion() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                p => { gpsListo = true; mapaRep.setView([p.coords.latitude, p.coords.longitude], 14); },
                () => {},
                { timeout: 8000, enableHighAccuracy: true }
            );
        }
    </script>
</body>

</html>
