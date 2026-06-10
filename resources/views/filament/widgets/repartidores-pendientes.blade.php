<x-filament-widgets::widget>
    <div class="rp-wrap">

        <div class="rp-header">
            <h2 class="rp-title">Repartidores pendientes</h2>
            <span class="rp-count">{{ $this->repartidores->count() }} solicitud(es)</span>
        </div>

        @if ($this->repartidores->isEmpty())
            <div class="rp-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p>No hay repartidores pendientes</p>
            </div>
        @else
            <div class="rp-list">
                @foreach ($this->repartidores as $rep)
                    @php $persona = $rep->user?->persona; @endphp
                    <div class="rp-card">

                        {{-- Foto de perfil --}}
                        @php $fotoPerfil = $rep->documentos->firstWhere('dor_fk_tipo_documento', 4); @endphp
                        <div class="rp-foto">
                            @if ($fotoPerfil?->dor_ruta)
                                <img src="{{ asset('storage/' . $fotoPerfil->dor_ruta) }}" alt="Foto">
                            @else
                                <div class="rp-foto-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                                    </svg>
                                </div>
                            @endif
                            <span class="rp-badge-pendiente">Pendiente</span>
                        </div>

                        <div class="rp-body">

                            {{-- Datos personales --}}
                            <div class="rp-section">
                                <p class="rp-section-label">Repartidor</p>
                                <p class="rp-nombre">
                                    {{ $persona?->per_nombre }} {{ $persona?->per_paterno }}
                                    {{ $persona?->per_materno }}
                                </p>
                                <p class="rp-detalle">{{ $rep->user?->email }}</p>
                                <p class="rp-detalle">{{ $persona?->per_telefono ?? '—' }}</p>
                                <p class="rp-detalle">{{ $rep->rep_tipo_vehiculo }}</p>
                            </div>

                            <div class="rp-divider"></div>

                            {{-- Documentos --}}
                            <div class="rp-section">
                                <p class="rp-section-label">Documentos</p>
                                <div class="rp-docs">
                                    @foreach ($rep->documentos->whereNotIn('dor_fk_tipo_documento', [4]) as $doc)
                                        <a href="{{ asset('storage/' . $doc->dor_ruta) }}" target="_blank"
                                            class="rp-doc-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                            {{ $doc->tipo_documento?->tid_nombre ?? 'Documento' }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rp-divider"></div>

                            {{-- Acciones --}}
                            <div class="rp-actions">
                                <button wire:click="rechazar({{ $rep->rep_id }})"
                                    wire:confirm="¿Rechazar a este repartidor?" class="rp-btn-rechazar">
                                    Rechazar
                                </button>
                                <button wire:click="aprobar({{ $rep->rep_id }})"
                                    wire:confirm="¿Aprobar y asignar rol de repartidor?" class="rp-btn-aprobar">
                                    Aprobar
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <style>
        .rp-wrap {
            font-family: 'Sora', sans-serif;
            padding: 0.25rem 0;
        }

        .rp-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            gap: 0.75rem;
        }

        .rp-title {
            font-size: 1rem;
            font-weight: 800;
            color: #111;
        }

        .rp-count {
            display: inline-flex;
            align-items: center;
            background: #fff7e0;
            border: 1px solid #fcd34d;
            color: #92400e;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
        }

        .rp-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 3rem;
            text-align: center;
            color: #aaa;
        }

        .rp-empty svg {
            width: 48px;
            height: 48px;
            color: #c6f135;
        }

        .rp-empty p {
            font-size: 0.88rem;
        }

        .rp-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .rp-card {
            background: white;
            border-radius: 1rem;
            border: 1.5px solid #e8f5d0;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .rp-card:hover {
            box-shadow: 0 8px 28px rgba(168, 223, 17, 0.15);
            border-color: #a8df11;
        }

        .rp-foto {
            position: relative;
            height: 120px;
            background: #f8fdf0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rp-foto img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .rp-foto-empty {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e8f5d0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rp-foto-empty svg {
            width: 36px;
            height: 36px;
            color: #7ab80e;
        }

        .rp-badge-pendiente {
            position: absolute;
            top: 0.6rem;
            right: 0.6rem;
            background: #fff7e0;
            border: 1px solid #fcd34d;
            color: #92400e;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .rp-body {
            padding: 1rem;
        }

        .rp-section {
            margin-bottom: 0.75rem;
        }

        .rp-section-label {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.35rem;
        }

        .rp-nombre {
            font-size: 0.9rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.15rem;
        }

        .rp-detalle {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 0.1rem;
        }

        .rp-divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.75rem 0;
        }

        .rp-docs {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .rp-doc-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a8a06;
            text-decoration: none;
            background: #f0fde0;
            border: 1px solid #d4f0a0;
            border-radius: 0.5rem;
            padding: 0.25rem 0.65rem;
            transition: background 0.15s;
        }

        .rp-doc-link:hover {
            background: #e0f8c0;
        }

        .rp-doc-link svg {
            width: 13px;
            height: 13px;
        }

        .rp-actions {
            display: flex;
            gap: 0.5rem;
        }

        .rp-btn-aprobar {
            flex: 2;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: 'Sora', sans-serif;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 0.6rem;
            border-radius: 0.65rem;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .rp-btn-aprobar:hover {
            opacity: 0.9;
        }

        .rp-btn-rechazar {
            flex: 1;
            background: white;
            color: #d41b11;
            font-family: 'Sora', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.6rem;
            border-radius: 0.65rem;
            border: 2px solid #fca5a5;
            cursor: pointer;
            transition: background 0.2s;
        }

        .rp-btn-rechazar:hover {
            background: #fff1f0;
        }
    </style>
</x-filament-widgets::widget>
