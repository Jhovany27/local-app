<x-filament-panels::page>

    <div class="rt-wrap">

        {{-- HEADER --}}
        <div class="rt-header">
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="rt-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Volver al dashboard
            </a>

        </div>

        <div class="rt-grid">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="rt-left">

                <div class="rt-card">
                    <p class="rt-card-label">Fachada de la tienda</p>
                    @if ($tienda->fachada?->fac_ruta)
                        <img src="{{ asset('storage/' . $tienda->fachada->fac_ruta) }}" alt="Fachada"
                            class="rt-fachada-img">
                    @else
                        <div class="rt-fachada-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                            </svg>
                            <p>Sin fachada registrada</p>
                        </div>
                    @endif
                </div>

                <div class="rt-card">
                    <p class="rt-card-label">Datos de la tienda</p>
                    <div class="rt-info-list">
                        <div class="rt-info-row">
                            <span class="rt-info-key">Nombre</span>
                            <span class="rt-info-val">{{ $tienda->tie_nombre }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Descripción</span>
                            <span class="rt-info-val">{{ $tienda->tie_descripcion }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Teléfono</span>
                            <span class="rt-info-val">{{ $tienda->tie_telefono }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Dirección</span>
                            <span class="rt-info-val">{{ $tienda->tie_direccion }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Fecha solicitud</span>
                            <span class="rt-info-val">{{ $tienda->tie_fecha_registro->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="rt-right">

                <div class="rt-card">
                    <p class="rt-card-label">Datos del solicitante</p>
                    @php $persona = $tienda->user?->persona; @endphp
                    <div class="rt-info-list">
                        <div class="rt-info-row">
                            <span class="rt-info-key">Nombre completo</span>
                            <span class="rt-info-val">
                                {{ $persona?->per_nombre }} {{ $persona?->per_paterno }} {{ $persona?->per_materno }}
                            </span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Correo</span>
                            <span class="rt-info-val">{{ $tienda->user?->email }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Teléfono</span>
                            <span class="rt-info-val">{{ $persona?->per_telefono ?? '—' }}</span>
                        </div>
                        <div class="rt-info-row">
                            <span class="rt-info-key">Registro</span>
                            <span
                                class="rt-info-val">{{ $persona?->per_fecha_registro?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="rt-card">
                    <p class="rt-card-label">Documentos</p>
                    @if ($tienda->documentos->isEmpty())
                        <p class="rt-no-docs">Sin documentos adjuntos</p>
                    @else
                        <div class="rt-docs-list">
                            @foreach ($tienda->documentos as $doc)
                                <div class="rt-doc-item">
                                    <div class="rt-doc-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                    <div class="rt-doc-info">
                                        <p class="rt-doc-tipo">{{ $doc->tipo_documento?->tdt_nombre ?? 'Documento' }}
                                        </p>
                                        <p class="rt-doc-fecha">{{ $doc->dot_fecha?->format('d/m/Y') ?? '—' }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $doc->dot_ruta) }}" target="_blank"
                                        class="rt-doc-ver">
                                        Ver PDF
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- UBICACIÓN --}}
            <div class="rt-card">
                <p class="rt-card-label">Ubicación</p>
                <div style="border-radius:0.75rem;overflow:hidden;border:1.5px solid #e8f5d0;margin-bottom:0.85rem;">
                    <div id="mapa-tienda" style="width:100%;height:220px;z-index:1;"></div>
                </div>
                <div
                    style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.8rem;color:#555;margin-bottom:0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#a8df11" style="width:16px;height:16px;flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span>{{ $tienda->tie_direccion }}</span>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.65rem;padding:0.5rem 0.75rem;text-align:center;">
                        <span
                            style="display:block;font-size:0.6rem;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.1rem;">Latitud</span>
                        <span
                            style="font-size:0.78rem;font-weight:700;color:#4a8a06;">{{ $tienda->tie_latitud }}</span>
                    </div>
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.65rem;padding:0.5rem 0.75rem;text-align:center;">
                        <span
                            style="display:block;font-size:0.6rem;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.1rem;">Longitud</span>
                        <span
                            style="font-size:0.78rem;font-weight:700;color:#4a8a06;">{{ $tienda->tie_longitud }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{--  el script del mapa --}}
    <script>
        const lat = {{ floatval($tienda->tie_latitud) ?: 17.9869 }};
        const lng = {{ floatval($tienda->tie_longitud) ?: -92.9303 }};

        const mapa = L.map('mapa-tienda', {
                attributionControl: false
            })
            .setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapa);

        const pinSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none">' +
            '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="#a8df11" stroke="white" stroke-width="1.5"/>' +
            '<circle cx="12" cy="9" r="2.5" fill="white"/>' +
            '</svg>';

        L.marker([lat, lng], {
            icon: L.divIcon({
                className: '',
                html: pinSvg,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            })
        }).addTo(mapa);

        //  Forzar recálculo de dimensiones después de renderizar
        setTimeout(() => {
            mapa.invalidateSize();
            mapa.setView([lat, lng], 16);
        }, 300);

        function confirmarRechazo() {
            const motivo = document.getElementById('motivo-rechazo').value.trim();
            if (!motivo) {
                alert('Por favor escribe el motivo del rechazo.');
                return;
            }
            @this.call('rechazar', {
                motivo: motivo
            });
            document.getElementById('modal-rechazo').classList.add('hidden');
        }
    </script>

    <style>
        .rt-wrap {
            font-family: 'Sora', sans-serif;
            padding: 0.5rem 0 2rem;
        }

        .rt-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .rt-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #7ab80e;
            text-decoration: none;
        }

        .rt-back svg {
            width: 14px;
            height: 14px;
        }

        .rt-back:hover {
            color: #4a8a06;
        }

        .rt-header-actions {
            display: flex;
            gap: 0.75rem;
        }

        .rt-btn-aprobar {
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.65rem 1.4rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s;
        }

        .rt-btn-aprobar:hover {
            opacity: 0.9;
        }

        .rt-btn-rechazar {
            background: white;
            color: #d41b11;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.65rem 1.4rem;
            border-radius: 0.75rem;
            border: 2px solid #fca5a5;
            cursor: pointer;
            transition: background 0.2s;
        }

        .rt-btn-rechazar:hover {
            background: #fff1f0;
        }

        .rt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 768px) {
            .rt-grid {
                grid-template-columns: 1fr;
            }
        }

        .rt-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .rt-card-label {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 1rem;
        }

        .rt-fachada-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.75rem;
        }

        .rt-fachada-empty {
            width: 100%;
            height: 160px;
            background: #f8fdf0;
            border-radius: 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #aaa;
            font-size: 0.78rem;
        }

        .rt-fachada-empty svg {
            width: 40px;
            height: 40px;
            color: #c6f135;
        }

        .rt-info-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .rt-info-row {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .rt-info-key {
            font-size: 0.72rem;
            font-weight: 700;
            color: #aaa;
            min-width: 100px;
            flex-shrink: 0;
        }

        .rt-info-val {
            font-size: 0.82rem;
            font-weight: 600;
            color: #111;
            line-height: 1.4;
        }

        .rt-no-docs {
            font-size: 0.82rem;
            color: #aaa;
        }

        .rt-docs-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .rt-doc-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fdf0;
            border: 1px solid #e8f5d0;
            border-radius: 0.75rem;
        }

        .rt-doc-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.6rem;
            background: white;
            border: 1px solid #e8f5d0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rt-doc-icon svg {
            width: 18px;
            height: 18px;
            color: #7ab80e;
        }

        .rt-doc-info {
            flex: 1;
        }

        .rt-doc-tipo {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
        }

        .rt-doc-fecha {
            font-size: 0.7rem;
            color: #aaa;
        }

        .rt-doc-ver {
            font-size: 0.75rem;
            font-weight: 700;
            color: #4a8a06;
            background: white;
            border: 1.5px solid #d4f0a0;
            border-radius: 0.5rem;
            padding: 0.3rem 0.75rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .rt-doc-ver:hover {
            background: #f0fde0;
        }
    </style>

</x-filament-panels::page>
