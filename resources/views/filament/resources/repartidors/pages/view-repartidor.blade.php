<x-filament-panels::page>

    @php
        $estado = (int) $record->rep_estado;
        $badgeClass = match ($estado) {
            1 => 'vr-badge-aprobado',
            0 => 'vr-badge-pendiente',
            2 => 'vr-badge-rechazado',
            default => '',
        };
        $badgeLabel = match ($estado) {
            1 => 'Aprobado',
            0 => 'Pendiente',
            2 => 'Rechazado',
            default => '—',
        };
        $persona = $record->user?->persona;
        $docs    = $record->documentos->keyBy('dor_fk_tipo_documento');
        $fotoPerfil  = $docs[4] ?? null;
        $docIne      = $docs[1] ?? null;
        $docLicencia = $docs[2] ?? null;
        $docCirc     = $docs[3] ?? null;
    @endphp

    <div class="vr-wrap">

        {{-- TOPBAR: estado + motivo --}}
        <div class="vr-topbar">
            <span class="vr-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            @if ($record->rep_motivo_rechazo && $estado !== 1)
                <div class="vr-motivo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>Motivo: {{ $record->rep_motivo_rechazo }}</span>
                </div>
            @endif
        </div>

        <div class="vr-grid">

            {{-- COLUMNA IZQUIERDA --}}
            <div>

                {{-- FOTO PERFIL --}}
                <div class="vr-card">
                    <p class="vr-card-label">Foto de perfil</p>
                    @if ($fotoPerfil)
                        <img src="{{ asset('storage/' . $fotoPerfil->dor_ruta) }}"
                            alt="Foto de perfil" class="vr-foto-img">
                    @else
                        <div class="vr-foto-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <p>Sin foto</p>
                        </div>
                    @endif
                </div>

                {{-- DATOS PERSONALES --}}
                <div class="vr-card">
                    <p class="vr-card-label">Datos personales</p>
                    <div class="vr-info-list">
                        <div class="vr-info-row">
                            <span class="vr-info-key">Nombre</span>
                            <span class="vr-info-val">
                                {{ $persona?->per_nombre }} {{ $persona?->per_paterno }} {{ $persona?->per_materno }}
                            </span>
                        </div>
                        <div class="vr-info-row">
                            <span class="vr-info-key">Correo</span>
                            <span class="vr-info-val">{{ $record->user?->email ?? '—' }}</span>
                        </div>
                        <div class="vr-info-row">
                            <span class="vr-info-key">Teléfono</span>
                            <span class="vr-info-val">{{ $persona?->per_telefono ?? '—' }}</span>
                        </div>
                        <div class="vr-info-row">
                            <span class="vr-info-key">Estado</span>
                            <span class="vr-info-val">{{ $record->rep_entidad ?? '—' }}</span>
                        </div>
                        <div class="vr-info-row">
                            <span class="vr-info-key">Municipio</span>
                            <span class="vr-info-val">{{ $record->rep_ciudad ?? '—' }}</span>
                        </div>
                        @if ($record->rep_colonia)
                        <div class="vr-info-row">
                            <span class="vr-info-key">Colonia</span>
                            <span class="vr-info-val">{{ $record->rep_colonia }}</span>
                        </div>
                        @endif
                        @if ($record->rep_cp)
                        <div class="vr-info-row">
                            <span class="vr-info-key">CP</span>
                            <span class="vr-info-val">{{ $record->rep_cp }}</span>
                        </div>
                        @endif
                        @if ($record->rep_radio_km)
                        <div class="vr-info-row">
                            <span class="vr-info-key">Radio reparto</span>
                            <span class="vr-info-val">{{ $record->rep_radio_km }} km</span>
                        </div>
                        @endif
                        <div class="vr-info-row">
                            <span class="vr-info-key">Vehículo</span>
                            <span class="vr-info-val">
                                <span class="vr-vehiculo-badge">{{ $record->rep_tipo_vehiculo }}</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUMNA DERECHA --}}
            <div>

                {{-- DOCUMENTOS --}}
                <div class="vr-card">
                    <p class="vr-card-label">Documentos</p>

                    @if (!$docIne && !$docLicencia && !$docCirc)
                        <p class="vr-empty-txt">Sin documentos adjuntos.</p>
                    @else
                        <div class="vr-docs-list">

                            @foreach ([
                                ['label' => 'INE / ID Oficial', 'doc' => $docIne],
                                ['label' => 'Licencia de conducir', 'doc' => $docLicencia],
                                ['label' => 'Tarjeta de circulación', 'doc' => $docCirc],
                            ] as $item)
                                @if ($item['doc'])
                                    <div class="vr-doc-item">
                                        <div class="vr-doc-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                        </div>
                                        <div class="vr-doc-info">
                                            <p class="vr-doc-tipo">{{ $item['label'] }}</p>
                                            <p class="vr-doc-fecha">
                                                {{ $item['doc']->dor_fecha?->format('d/m/Y') ?? '—' }}
                                            </p>
                                        </div>
                                        <a href="{{ asset('storage/' . $item['doc']->dor_ruta) }}"
                                            target="_blank" class="vr-doc-ver">
                                            Ver PDF
                                        </a>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <style>
        .vr-wrap { font-family: 'Sora', sans-serif; padding: 0.5rem 0 2rem; }

        .vr-topbar {
            display: flex; align-items: center; flex-wrap: wrap;
            gap: 0.75rem; margin-bottom: 1.5rem;
        }

        .vr-badge {
            display: inline-flex; align-items: center;
            font-size: 0.72rem; font-weight: 700;
            padding: 0.3rem 0.85rem; border-radius: 999px;
            letter-spacing: 0.06em;
        }
        .vr-badge-aprobado  { background:#f0fde0; border:1.5px solid #a8df11; color:#4a8a06; }
        .vr-badge-pendiente { background:#fff7e0; border:1.5px solid #fcd34d; color:#92400e; }
        .vr-badge-rechazado { background:#fff1f0; border:1.5px solid #fca5a5; color:#d41b11; }

        .vr-motivo {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; color: #d41b11;
            background: #fff1f0; border: 1px solid #fca5a5;
            padding: 0.4rem 0.85rem; border-radius: 0.65rem;
        }
        .vr-motivo svg { width:14px; height:14px; flex-shrink:0; }

        .vr-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 1.25rem; align-items: start;
        }
        @media (max-width: 768px) { .vr-grid { grid-template-columns: 1fr; } }

        .vr-card {
            background: white; border: 1.5px solid #e8f5d0;
            border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.25rem;
        }

        .vr-card-label {
            font-size: 0.62rem; font-weight: 800;
            letter-spacing: 0.14em; text-transform: uppercase;
            color: #7ab80e; margin-bottom: 1rem;
        }

        .vr-foto-img {
            width: 120px; height: 120px; object-fit: cover;
            border-radius: 50%; border: 3px solid #a8df11;
            display: block; margin: 0 auto;
        }

        .vr-foto-empty {
            width: 120px; height: 120px; background: #f8fdf0;
            border-radius: 50%; border: 2px dashed #d4f0a0;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 0.35rem; color: #aaa; font-size: 0.72rem;
            margin: 0 auto;
        }
        .vr-foto-empty svg { width: 40px; height: 40px; color: #c6f135; }

        .vr-info-list { display: flex; flex-direction: column; gap: 0.65rem; }
        .vr-info-row  { display: flex; gap: 1rem; align-items: flex-start; }
        .vr-info-key  { font-size:0.72rem; font-weight:700; color:#aaa; min-width:90px; flex-shrink:0; }
        .vr-info-val  { font-size:0.82rem; font-weight:600; color:#111; line-height:1.4; }

        .vr-vehiculo-badge {
            display: inline-block; background: #f0fde0;
            border: 1px solid #d4f0a0; border-radius: 0.5rem;
            padding: 0.15rem 0.6rem; font-size: 0.75rem;
            font-weight: 700; color: #4a8a06;
        }

        .vr-empty-txt { font-size:0.82rem; color:#aaa; }

        .vr-docs-list { display: flex; flex-direction: column; gap: 0.75rem; }

        .vr-doc-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem; background: #f8fdf0;
            border: 1px solid #e8f5d0; border-radius: 0.75rem;
        }

        .vr-doc-icon {
            width: 36px; height: 36px; border-radius: 0.6rem;
            background: white; border: 1px solid #e8f5d0;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .vr-doc-icon svg { width:18px; height:18px; color:#7ab80e; }

        .vr-doc-info  { flex: 1; }
        .vr-doc-tipo  { font-size:0.82rem; font-weight:700; color:#111; }
        .vr-doc-fecha { font-size:0.7rem; color:#aaa; }

        .vr-doc-ver {
            font-size: 0.75rem; font-weight: 700; color: #4a8a06;
            background: white; border: 1.5px solid #d4f0a0;
            border-radius: 0.5rem; padding: 0.3rem 0.75rem;
            text-decoration: none; flex-shrink: 0;
        }
        .vr-doc-ver:hover { background: #f0fde0; }
    </style>

</x-filament-panels::page>
