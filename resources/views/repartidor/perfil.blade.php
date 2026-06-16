<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        #mapa-zona { width:100%; height:220px; border-radius:12px; border:1.5px solid #d4edaa; }
        .zona-form .field { margin-bottom:.75rem; }
        .zona-form label { display:block; font-size:.72rem; font-weight:700; color:#555; margin-bottom:.25rem; text-transform:uppercase; letter-spacing:.05em; }
        .zona-form input[type=text], .zona-form input[type=number] {
            width:100%; padding:.55rem .75rem; border:1.5px solid #d1d5db; border-radius:8px;
            font-size:.85rem; font-family:'Sora',sans-serif; background:#f8fdf0;
            box-sizing:border-box;
        }
        .zona-form input:focus { outline:none; border-color:#a8df11; }
        .field-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:.65rem; }
        .radio-row { display:flex; align-items:center; gap:.75rem; margin-bottom:.75rem; }
        .radio-row label { margin:0; font-size:.82rem; font-weight:600; color:#333; text-transform:none; letter-spacing:0; }
        .radio-row input[type=range] { flex:1; accent-color:#7cc10a; }
        .radio-val { font-size:.85rem; font-weight:800; color:#4a8a06; min-width:3rem; text-align:right; }
        .btn-guardar-zona {
            width:100%; padding:.7rem; background:linear-gradient(135deg,#a8df11,#7cc10a);
            border:none; border-radius:10px; font-family:'Sora',sans-serif;
            font-size:.88rem; font-weight:800; color:#1a1a1a; cursor:pointer;
            box-shadow:0 4px 14px rgba(168,223,17,.3); margin-top:.25rem;
        }
        .btn-guardar-zona:active { opacity:.85; }
        .zona-hint { font-size:.73rem; color:#888; text-align:center; margin-bottom:.65rem; }
        .alert-ok { background:#f0fde0; border:1.5px solid #c6f135; color:#3a6e04; border-radius:10px; padding:.65rem 1rem; font-size:.82rem; font-weight:700; margin-bottom:1rem; }
    </style>
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('repartidor.index') }}" class="btn-back">
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

        {{-- HERO --}}
        <div class="hero">
            <div class="avatar">
                @if ($fotoPerfil)
                    <img src="{{ asset('storage/' . $fotoPerfil->dor_ruta) }}"
                         alt="Foto de perfil"
                         style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                @endif
            </div>
            <p class="hero-nombre">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
            <p class="hero-email">{{ $user->email }}</p>
            <span class="hero-badge"> {{ $repartidor->rep_tipo_vehiculo }}</span>
        </div>

        <div class="body">

            {{-- DATOS --}}
            <div class="seccion">
                <p class="seccion-titulo">Datos personales</p>
                <div class="info-card">
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Nombre completo</p>
                            <p class="info-value">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Correo</p>
                            <p class="info-value">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="{{ $user->hasVerifiedEmail() ? 'background:#f0fde0;' : 'background:#fff7ed;' }}">
                            @if ($user->hasVerifiedEmail())
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="#4a8a06">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="#f59e0b">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                            @endif
                        </div>
                        <div class="info-content">
                            <p class="info-label">Verificación de correo</p>
                            @if ($user->hasVerifiedEmail())
                                <p class="info-value" style="color:#4a8a06;font-weight:700;">Verificado</p>
                            @else
                                <p class="info-value" style="color:#f59e0b;font-weight:700;">Pendiente de verificación</p>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Teléfono</p>
                            <p class="info-value">{{ $persona?->per_telefono ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Vehículo</p>
                            <p class="info-value">{{ $repartidor->rep_tipo_vehiculo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ZONA DE ENTREGA --}}
            <div class="seccion">
                <p class="seccion-titulo">Mi zona de entrega</p>

                @if(session('zona_ok'))
                    <div class="alert-ok">{{ session('zona_ok') }}</div>
                @endif

                <div class="info-card" style="padding:1rem;">
                    <p class="zona-hint">Mueve el mapa o usa el buscador para centrar tu zona de cobertura.</p>

                    {{-- Buscador --}}
                    <div style="display:flex;gap:.5rem;margin-bottom:.75rem;">
                        <input type="text" id="buscar-zona" placeholder="Buscar colonia o ciudad…"
                            style="flex:1;padding:.5rem .75rem;border:1.5px solid #d1d5db;border-radius:8px;font-size:.82rem;font-family:'Sora',sans-serif;background:#f8fdf0;">
                        <button type="button" onclick="buscarZona()"
                            style="padding:.5rem .9rem;background:#a8df11;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.82rem;">
                            Buscar
                        </button>
                    </div>

                    {{-- Mapa --}}
                    <div id="mapa-zona"></div>
                    <p id="zona-coords" style="font-size:.7rem;color:#aaa;text-align:center;margin:.4rem 0 .75rem;">
                        @if($repartidor->rep_lat)
                            Lat {{ number_format($repartidor->rep_lat,5) }}, Lng {{ number_format($repartidor->rep_lng,5) }}
                        @else
                            Sin ubicación guardada
                        @endif
                    </p>

                    <form method="POST" action="{{ route('repartidor.zona.update') }}" class="zona-form">
                        @csrf

                        <input type="hidden" name="rep_lat" id="f-lat" value="{{ $repartidor->rep_lat }}">
                        <input type="hidden" name="rep_lng" id="f-lng" value="{{ $repartidor->rep_lng }}">

                        {{-- Radio --}}
                        <div class="radio-row">
                            <label for="f-radio">Radio de cobertura</label>
                            <input type="range" id="f-radio" name="rep_radio_km"
                                min="1" max="50" value="{{ $repartidor->rep_radio_km ?? 10 }}"
                                oninput="actualizarRadio(this.value)">
                            <span class="radio-val" id="radio-label">{{ $repartidor->rep_radio_km ?? 10 }} km</span>
                        </div>

                        <div class="field-grid2">
                            <div class="field">
                                <label>CP</label>
                                <input type="text" name="rep_cp" id="f-cp" value="{{ $repartidor->rep_cp }}">
                            </div>
                            <div class="field">
                                <label>Colonia</label>
                                <input type="text" name="rep_colonia" id="f-colonia" value="{{ $repartidor->rep_colonia }}">
                            </div>
                        </div>
                        <div class="field-grid2">
                            <div class="field">
                                <label>Ciudad / Municipio</label>
                                <input type="text" name="rep_ciudad" id="f-ciudad" value="{{ $repartidor->rep_ciudad }}" required>
                            </div>
                            <div class="field">
                                <label>Estado</label>
                                <input type="text" name="rep_entidad" id="f-entidad" value="{{ $repartidor->rep_entidad }}">
                            </div>
                        </div>

                        <button type="submit" class="btn-guardar-zona">Guardar zona</button>
                    </form>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="seccion">
                <p class="seccion-titulo">Mi cuenta</p>
                <div class="acciones-card">
                    <form method="POST" action="{{ route('repartidor.logout') }}">
                        @csrf
                        <button type="submit" class="accion-row"
                            style="width:100%;background:none;border:none;text-align:left;">
                            <div class="accion-icon danger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                            </div>
                            <span class="accion-label danger">Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('repartidor.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                </svg>
            </a>
            <a href="{{ route('repartidor.historial') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('repartidor.perfil') }}" class="nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
        </nav>

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const initLat = {{ (float) ($repartidor->rep_lat ?? 17.9919) }};
        const initLng = {{ (float) ($repartidor->rep_lng ?? -92.9359) }};
        let radioKm   = {{ (int) ($repartidor->rep_radio_km ?? 10) }};

        let mapa, circulo, marcador;

        document.addEventListener('DOMContentLoaded', function () {
            mapa = L.map('mapa-zona', { zoomControl: false, attributionControl: false })
                .setView([initLat, initLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

            const icon = L.divIcon({
                className: '',
                html: '<div style="width:14px;height:14px;background:#7cc10a;border:2.5px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.3)"></div>',
                iconAnchor: [7, 7],
            });

            marcador = L.marker([initLat, initLng], { icon, draggable: true }).addTo(mapa);
            circulo  = L.circle([initLat, initLng], { radius: radioKm * 1000, color: '#7cc10a', fillColor: '#a8df11', fillOpacity: .15, weight: 2 }).addTo(mapa);

            marcador.on('dragend', function () {
                const pos = marcador.getLatLng();
                actualizarPosicion(pos.lat, pos.lng);
                geocodificar(pos.lat, pos.lng);
            });

            mapa.on('click', function (e) {
                marcador.setLatLng(e.latlng);
                circulo.setLatLng(e.latlng);
                actualizarPosicion(e.latlng.lat, e.latlng.lng);
                geocodificar(e.latlng.lat, e.latlng.lng);
            });
        });

        function actualizarPosicion(lat, lng) {
            document.getElementById('f-lat').value  = lat;
            document.getElementById('f-lng').value  = lng;
            document.getElementById('zona-coords').textContent =
                'Lat ' + lat.toFixed(5) + ', Lng ' + lng.toFixed(5);
        }

        function actualizarRadio(val) {
            radioKm = parseInt(val);
            document.getElementById('radio-label').textContent = val + ' km';
            if (circulo) circulo.setRadius(radioKm * 1000);
        }

        async function geocodificar(lat, lng) {
            try {
                const res  = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`);
                const data = await res.json();
                const a    = data.address || {};
                document.getElementById('f-cp').value      = a.postcode || '';
                document.getElementById('f-colonia').value = a.suburb || a.neighbourhood || a.quarter || '';
                document.getElementById('f-ciudad').value  = a.city || a.town || a.municipality || a.county || '';
                document.getElementById('f-entidad').value = a.state || '';
            } catch {}
        }

        async function buscarZona() {
            const q = document.getElementById('buscar-zona').value.trim();
            if (!q) return;
            try {
                const r = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=1&accept-language=es`);
                const d = await r.json();
                if (d.length) {
                    const lat = +d[0].lat, lng = +d[0].lon;
                    mapa.setView([lat, lng], 13);
                    marcador.setLatLng([lat, lng]);
                    circulo.setLatLng([lat, lng]);
                    actualizarPosicion(lat, lng);
                    geocodificar(lat, lng);
                }
            } catch {}
        }
    </script>
</body>

</html>
