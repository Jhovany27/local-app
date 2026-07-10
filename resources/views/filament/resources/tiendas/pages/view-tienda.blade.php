<x-filament-panels::page>

    <div class="vt-wrap">

        {{-- ESTADO BADGE --}}
        @php
            $estado = (int) $record->tie_estado;
            $badgeClass = match ($estado) {
                \App\Models\Tienda::ESTADO_APROBADA => 'vt-badge-activa',
                \App\Models\Tienda::ESTADO_PENDIENTE => 'vt-badge-pendiente',
                \App\Models\Tienda::ESTADO_RECHAZADA => 'vt-badge-rechazada',
                default => '',
            };
            $badgeLabel = match ($estado) {
                \App\Models\Tienda::ESTADO_APROBADA => 'Activa',
                \App\Models\Tienda::ESTADO_PENDIENTE => 'Pendiente',
                \App\Models\Tienda::ESTADO_RECHAZADA => 'Rechazada',
                default => '—',
            };
        @endphp

        <div class="vt-topbar">
            <span class="vt-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            @if ($record->tie_motivo_rechazo && $estado !== \App\Models\Tienda::ESTADO_APROBADA)
                <div class="vt-motivo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>Motivo: {{ $record->tie_motivo_rechazo }}</span>
                </div>
            @endif
        </div>

        <div class="vt-grid">

            {{-- COLUMNA IZQUIERDA --}}
            <div>

                {{-- FACHADA --}}
                <div class="vt-card">
                    <p class="vt-card-label">Fachada</p>
                    @if ($record->fachada?->fac_ruta)
                        <img src="{{ asset('storage/' . $record->fachada->fac_ruta) }}" alt="Fachada"
                            class="vt-fachada-img">
                    @else
                        <div class="vt-fachada-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                            </svg>
                            <p>Sin fachada</p>
                        </div>
                    @endif
                </div>

                {{-- DATOS TIENDA --}}
                <div class="vt-card">
                    <p class="vt-card-label">Datos de la tienda</p>
                    <div class="vt-info-list">
                        <div class="vt-info-row">
                            <span class="vt-info-key">Nombre</span>
                            <span class="vt-info-val">{{ $record->tie_nombre }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Descripción</span>
                            <span class="vt-info-val">{{ $record->tie_descripcion ?? '—' }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Teléfono</span>
                            <span class="vt-info-val">{{ $record->tie_telefono }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Dirección</span>
                            <span class="vt-info-val">{{ $record->tie_direccion }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Fecha solicitud</span>
                            <span
                                class="vt-info-val">{{ $record->tie_fecha_registro?->format('d/m/Y H:i') ?? '—' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMNA DERECHA --}}
            <div>

                {{-- SOLICITANTE --}}
                <div class="vt-card">
                    <p class="vt-card-label">Solicitante</p>
                    @php $persona = $record->user?->persona; @endphp
                    <div class="vt-info-list">
                        <div class="vt-info-row">
                            <span class="vt-info-key">Nombre</span>
                            <span class="vt-info-val">
                                {{ $persona?->per_nombre }} {{ $persona?->per_paterno }} {{ $persona?->per_materno }}
                            </span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Correo</span>
                            <span class="vt-info-val">{{ $record->user?->email ?? '—' }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Teléfono</span>
                            <span class="vt-info-val">{{ $persona?->per_telefono ?? '—' }}</span>
                        </div>
                        <div class="vt-info-row">
                            <span class="vt-info-key">Registro</span>
                            <span
                                class="vt-info-val">{{ $persona?->per_fecha_registro?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- DOCUMENTOS --}}
                <div class="vt-card">
                    <p class="vt-card-label">Documentos</p>
                    @if ($record->documentos->isEmpty())
                        <p class="vt-empty-txt">Sin documentos adjuntos.</p>
                    @else
                        <div class="vt-docs-list">
                            @foreach ($record->documentos as $doc)
                                <div class="vt-doc-item">
                                    <div class="vt-doc-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                    <div class="vt-doc-info">
                                        <p class="vt-doc-tipo">
                                            {{ $doc->tipo_documento_tienda?->tdt_nombre ?? 'Documento' }}</p>
                                        <p class="vt-doc-fecha">{{ $doc->dot_fecha?->format('d/m/Y') ?? '—' }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $doc->dot_ruta) }}" target="_blank"
                                        class="vt-doc-ver">
                                        Ver PDF
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- UBICACIÓN --}}
            <div class="vt-card">
                <p class="vt-card-label">Ubicación</p>
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
                    <span>{{ $record->tie_direccion }}</span>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.65rem;padding:0.5rem 0.75rem;text-align:center;">
                        <span
                            style="display:block;font-size:0.6rem;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.1rem;">Latitud</span>
                        <span
                            style="font-size:0.78rem;font-weight:700;color:#4a8a06;">{{ $record->tie_latitud }}</span>
                    </div>
                    <div
                        style="flex:1;background:#f8fdf0;border:1px solid #e8f5d0;border-radius:0.65rem;padding:0.5rem 0.75rem;text-align:center;">
                        <span
                            style="display:block;font-size:0.6rem;color:#aaa;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.1rem;">Longitud</span>
                        <span
                            style="font-size:0.78rem;font-weight:700;color:#4a8a06;">{{ $record->tie_longitud }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .vt-wrap {
            font-family: 'Sora', sans-serif;
            padding: 0.5rem 0 2rem;
        }

        .vt-topbar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .vt-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            letter-spacing: 0.06em;
        }

        .vt-badge-activa {
            background: #f0fde0;
            border: 1.5px solid #a8df11;
            color: #4a8a06;
        }

        .vt-badge-pendiente {
            background: #fff7e0;
            border: 1.5px solid #fcd34d;
            color: #92400e;
        }

        .vt-badge-rechazada {
            background: #fff1f0;
            border: 1.5px solid #fca5a5;
            color: #d41b11;
        }

        .vt-motivo {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: #d41b11;
            background: #fff1f0;
            border: 1px solid #fca5a5;
            padding: 0.4rem 0.85rem;
            border-radius: 0.65rem;
        }

        .vt-motivo svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .vt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 768px) {
            .vt-grid {
                grid-template-columns: 1fr;
            }
        }

        .vt-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .vt-card-label {
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 1rem;
        }

        .vt-fachada-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.75rem;
        }

        .vt-fachada-empty {
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

        .vt-fachada-empty svg {
            width: 40px;
            height: 40px;
            color: #c6f135;
        }

        .vt-info-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .vt-info-row {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .vt-info-key {
            font-size: 0.72rem;
            font-weight: 700;
            color: #aaa;
            min-width: 100px;
            flex-shrink: 0;
        }

        .vt-info-val {
            font-size: 0.82rem;
            font-weight: 600;
            color: #111;
            line-height: 1.4;
        }

        .vt-empty-txt {
            font-size: 0.82rem;
            color: #aaa;
        }

        .vt-docs-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .vt-doc-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fdf0;
            border: 1px solid #e8f5d0;
            border-radius: 0.75rem;
        }

        .vt-doc-icon {
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

        .vt-doc-icon svg {
            width: 18px;
            height: 18px;
            color: #7ab80e;
        }

        .vt-doc-info {
            flex: 1;
        }

        .vt-doc-tipo {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
        }

        .vt-doc-fecha {
            font-size: 0.7rem;
            color: #aaa;
        }

        .vt-doc-ver {
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

        .vt-doc-ver:hover {
            background: #f0fde0;
        }
    </style>

    {{-- ══ SECCIÓN FINANCIERA ════════════════════════════ --}}
    @php $wf = $this->walletFinanciero; @endphp
    <div class="vt-fin-wrap">
        <p class="vt-fin-titulo">Información financiera</p>

        @if ($wf)
            {{-- Tarjetas resumen --}}
            <div class="vt-fin-grid">
                <div class="vt-fin-card">
                    <p class="vt-fin-label">Ventas brutas</p>
                    <p class="vt-fin-val">${{ number_format($wf->wal_total_ventas, 2) }}</p>
                </div>
                <div class="vt-fin-card vt-fin-card-red">
                    <p class="vt-fin-label">Comisiones retenidas</p>
                    <p class="vt-fin-val">${{ number_format($wf->wal_total_comisiones, 2) }}</p>
                </div>
                <div class="vt-fin-card vt-fin-card-green">
                    <p class="vt-fin-label">Saldo pendiente de pago</p>
                    <p class="vt-fin-val">${{ number_format($wf->wal_saldo_pendiente, 2) }}</p>
                </div>
                <div class="vt-fin-card">
                    <p class="vt-fin-label">Total liquidado</p>
                    <p class="vt-fin-val">${{ number_format($wf->wal_total_liquidado, 2) }}</p>
                </div>
            </div>

            {{-- Últimos movimientos --}}
            <p class="vt-fin-sub">Últimos movimientos</p>
            @forelse ($this->movimientosTienda as $mov)
                <div class="vt-fin-mov">
                    <div class="vt-fin-mov-tipo vt-mov-{{ $mov->mwl_tipo }}">{{ ucfirst($mov->mwl_tipo) }}</div>
                    <span class="vt-fin-mov-desc">{{ $mov->mwl_descripcion ?? '—' }}</span>
                    <span class="vt-fin-mov-fecha">{{ $mov->mwl_fecha->format('d/m/Y') }}</span>
                    <span class="vt-fin-mov-monto {{ in_array($mov->mwl_tipo, ['venta','ajuste']) ? 'vt-pos' : 'vt-neg' }}">
                        {{ in_array($mov->mwl_tipo, ['comision','liquidacion']) ? '−' : '+' }}${{ number_format($mov->mwl_monto, 2) }}
                    </span>
                </div>
            @empty
                <p class="vt-fin-empty">Sin movimientos registrados.</p>
            @endforelse

            {{-- Liquidaciones --}}
            @if ($this->liquidacionesTienda->count() > 0)
                <p class="vt-fin-sub" style="margin-top:1.25rem;">Historial de liquidaciones</p>
                @foreach ($this->liquidacionesTienda as $liq)
                    <div class="vt-fin-liq">
                        <div>
                            <p class="vt-fin-liq-periodo">{{ $liq->liq_periodo_inicio->format('d/m/Y') }} — {{ $liq->liq_periodo_fin->format('d/m/Y') }}</p>
                            <p class="vt-fin-liq-estado {{ $liq->liq_estado === 'pagada' ? 'vt-liq-pagada' : 'vt-liq-pendiente' }}">
                                {{ ucfirst($liq->liq_estado) }}
                                @if ($liq->liq_fecha_pago) · {{ $liq->liq_fecha_pago->format('d/m/Y') }} @endif
                            </p>
                        </div>
                        <span class="vt-fin-liq-monto">${{ number_format($liq->liq_monto, 2) }}</span>
                    </div>
                @endforeach
            @endif
        @else
            <p class="vt-fin-empty">Esta tienda aún no tiene movimientos en su wallet.</p>
        @endif
    </div>

    <style>
        .vt-fin-wrap { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:1.25rem 1.5rem; margin-top:1.25rem; }
        .vt-fin-titulo { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#7ab80e; margin-bottom:1rem; }
        .vt-fin-sub { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#aaa; margin:.75rem 0 .5rem; }
        .vt-fin-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
        @media(max-width:900px){ .vt-fin-grid { grid-template-columns:repeat(2,1fr); } }
        .vt-fin-card { background:#f8fdf0; border:1px solid #e8f5d0; border-radius:10px; padding:.85rem 1rem; }
        .vt-fin-card-red   { background:#fff8f8; border-color:#fecaca; }
        .vt-fin-card-green { background:#f0fde0; border-color:#a8df11; }
        .vt-fin-label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#7ab80e; margin-bottom:.3rem; }
        .vt-fin-card-red .vt-fin-label { color:#ef4444; }
        .vt-fin-val   { font-size:1.15rem; font-weight:900; color:#1a1a1a; }
        .vt-fin-mov { display:flex; align-items:center; gap:.65rem; padding:.5rem 0; border-bottom:1px solid #f5f5f5; font-size:.82rem; }
        .vt-fin-mov:last-child { border-bottom:none; }
        .vt-fin-mov-tipo { font-size:.65rem; font-weight:700; padding:.15rem .5rem; border-radius:999px; flex-shrink:0; }
        .vt-mov-venta       { background:#f0fde0; color:#4a8a06; }
        .vt-mov-comision    { background:#fff8f8; color:#ef4444; }
        .vt-mov-liquidacion { background:#eff6ff; color:#2563eb; }
        .vt-mov-ajuste      { background:#fef9c3; color:#854d0e; }
        .vt-fin-mov-desc  { flex:1; color:#555; font-size:.78rem; }
        .vt-fin-mov-fecha { color:#aaa; font-size:.72rem; flex-shrink:0; }
        .vt-fin-mov-monto { font-weight:700; flex-shrink:0; }
        .vt-pos { color:#4a8a06; }
        .vt-neg { color:#ef4444; }
        .vt-fin-liq { display:flex; justify-content:space-between; align-items:center; padding:.6rem .75rem; background:#f8fdf0; border:1px solid #e8f5d0; border-radius:8px; margin-bottom:.5rem; }
        .vt-fin-liq-periodo { font-size:.8rem; font-weight:700; color:#1a1a1a; }
        .vt-fin-liq-estado  { font-size:.7rem; font-weight:600; }
        .vt-liq-pagada   { color:#4a8a06; }
        .vt-liq-pendiente{ color:#b45309; }
        .vt-fin-liq-monto { font-size:.9rem; font-weight:800; color:#4a8a06; }
        .vt-fin-empty { font-size:.82rem; color:#aaa; }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = {{ floatval($record->tie_latitud) ?: 17.9869 }};
        const lng = {{ floatval($record->tie_longitud) ?: -92.9303 }};

        const mapa = L.map('mapa-tienda', {
            attributionControl: false,
            //  Sin restricciones — puede moverse y hacer zoom
        }).setView([lat, lng], 16);

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
    </script>

</x-filament-panels::page>
