<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Zona de entrega</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        #mapa-zona { width:100%; height:240px; border-radius:12px; border:1.5px solid #d4edaa; }

        .zona-buscar { display:flex; gap:.5rem; margin-bottom:.85rem; }
        .zona-buscar input {
            flex:1; padding:.55rem .75rem; border:1.5px solid #d1d5db; border-radius:8px;
            font-size:.83rem; font-family:'Instrument Sans',sans-serif; background:#f8fdf0;
        }
        .zona-buscar input:focus { outline:none; border-color:#a8df11; }
        .zona-buscar button {
            padding:.55rem 1rem; background:#a8df11; border:none; border-radius:8px;
            font-weight:700; cursor:pointer; font-size:.82rem;
        }

        #zona-coords { font-size:.68rem; color:#aaa; text-align:center; margin:.4rem 0 .85rem; }

        .zona-field { margin-bottom:.75rem; }
        .zona-field label {
            display:block; font-size:.68rem; font-weight:700; color:#7ab80e;
            text-transform:uppercase; letter-spacing:.07em; margin-bottom:.3rem;
        }
        .zona-field input {
            width:100%; padding:.55rem .75rem; border:1.5px solid #d1d5db; border-radius:8px;
            font-size:.85rem; font-family:'Instrument Sans',sans-serif; background:#f8fdf0;
            box-sizing:border-box;
        }
        .zona-field input:focus { outline:none; border-color:#a8df11; }

        .field-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:.65rem; }

        .radio-row { display:flex; align-items:center; gap:.75rem; margin-bottom:.85rem; }
        .radio-row label { font-size:.83rem; font-weight:600; color:#333; }
        .radio-row input[type=range] { flex:1; accent-color:#7cc10a; }
        .radio-val { font-size:.85rem; font-weight:800; color:#4a8a06; min-width:3rem; text-align:right; }

        .btn-guardar {
            width:100%; padding:.8rem; background:linear-gradient(135deg,#a8df11,#7cc10a);
            border:none; border-radius:12px; font-family:'Instrument Sans',sans-serif;
            font-size:.95rem; font-weight:800; color:#1a1a1a; cursor:pointer;
            box-shadow:0 4px 14px rgba(168,223,17,.3); margin-top:.5rem;
        }
        .btn-guardar:active { opacity:.85; }

        .alert-ok {
            background:#f0fde0; border:1.5px solid #c6f135; color:#3a6e04;
            border-radius:10px; padding:.7rem 1rem; font-size:.82rem; font-weight:700;
            margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;
        }
        .alert-ok svg { width:16px; height:16px; flex-shrink:0; }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <a href="{{ route('repartidor.perfil') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="body">

        @if(session('zona_ok'))
            <div class="alert-ok">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ session('zona_ok') }}
            </div>
        @endif

        <div class="seccion">
            <p class="seccion-titulo">Zona de entrega</p>

            <div class="info-card" style="padding:1rem;">

                <p style="font-size:.78rem;color:#888;margin-bottom:.85rem;line-height:1.4;">
                    Toca el mapa o busca una colonia para centrar tu zona de cobertura. Arrastra el marcador para ajustar la ubicación exacta.
                </p>

                {{-- Buscador --}}
                <div class="zona-buscar">
                    <input type="text" id="buscar-zona" placeholder="Buscar colonia o ciudad…">
                    <button type="button" onclick="buscarZona()">Buscar</button>
                </div>

                {{-- Mapa --}}
                <div id="mapa-zona"></div>
                <p id="zona-coords">
                    @if($repartidor->rep_lat)
                        Lat {{ number_format($repartidor->rep_lat, 5) }}, Lng {{ number_format($repartidor->rep_lng, 5) }}
                    @else
                        Sin ubicación guardada — toca el mapa para elegir
                    @endif
                </p>

                <form method="POST" action="{{ route('repartidor.zona.update') }}">
                    @csrf
                    <input type="hidden" name="rep_lat" id="f-lat" value="{{ $repartidor->rep_lat }}">
                    <input type="hidden" name="rep_lng" id="f-lng" value="{{ $repartidor->rep_lng }}">

                    <div class="radio-row">
                        <label for="f-radio">Radio de cobertura</label>
                        <input type="range" id="f-radio" name="rep_radio_km"
                            min="1" max="50" value="{{ $repartidor->rep_radio_km ?? 10 }}"
                            oninput="actualizarRadio(this.value)">
                        <span class="radio-val" id="radio-label">{{ $repartidor->rep_radio_km ?? 10 }} km</span>
                    </div>

                    <div class="field-grid2">
                        <div class="zona-field">
                            <label>CP</label>
                            <input type="text" name="rep_cp" id="f-cp" value="{{ $repartidor->rep_cp }}">
                        </div>
                        <div class="zona-field">
                            <label>Colonia</label>
                            <input type="text" name="rep_colonia" id="f-colonia" value="{{ $repartidor->rep_colonia }}">
                        </div>
                    </div>
                    <div class="field-grid2">
                        <div class="zona-field">
                            <label>Ciudad / Municipio</label>
                            <input type="text" name="rep_ciudad" id="f-ciudad" value="{{ $repartidor->rep_ciudad }}" required>
                        </div>
                        <div class="zona-field">
                            <label>Estado</label>
                            <input type="text" name="rep_entidad" id="f-entidad" value="{{ $repartidor->rep_entidad }}">
                        </div>
                    </div>

                    <button type="submit" class="btn-guardar">Guardar zona</button>
                </form>

            </div>
        </div>

    </div>

    <nav class="bottom-nav">
        <a href="{{ route('repartidor.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
            </svg>
        </a>
        <a href="{{ route('repartidor.historial') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
            </svg>
        </a>
        <a href="{{ route('repartidor.perfil') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
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
