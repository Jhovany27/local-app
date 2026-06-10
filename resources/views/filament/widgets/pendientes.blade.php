<x-filament-widgets::widget>
    <div style="font-family:'Sora',sans-serif; padding:0.25rem 0;">

        {{-- GRID 2 COLUMNAS --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start;">

            {{-- ══ TIENDAS ══════════════════════════════════════ --}}
            <div>
                <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:1.25rem;">
                    <h2 style="font-size:1rem; font-weight:800; color:#111;">Tiendas pendientes</h2>
                    <span class="cnt-badge">{{ $this->tiendas->count() }} solicitud(es)</span>
                </div>

                @if ($this->tiendas->isEmpty())
                    <div class="empty-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <p>No hay tiendas pendientes</p>
                    </div>
                @else
                    <div class="cards-list">
                        @foreach ($this->tiendas as $tienda)
                            <div class="pend-card">
                                <div class="pend-img-wrap">
                                    @if ($tienda->fachada?->fac_ruta)
                                        <img src="{{ asset('storage/' . $tienda->fachada->fac_ruta) }}" alt="">
                                    @else
                                        <div class="pend-img-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="badge-pend">Pendiente</span>
                                </div>
                                <div class="pend-body">
                                    <p class="sec-label">Tienda</p>
                                    <p class="pend-nombre">{{ $tienda->tie_nombre }}</p>
                                    <p class="pend-det">{{ $tienda->tie_direccion }}</p>
                                    <p class="pend-det">{{ $tienda->tie_telefono }}</p>
                                    <p class="pend-det">Solicitó el
                                        {{ $tienda->tie_fecha_registro->format('d/m/Y H:i') }}</p>

                                    <div class="divider"></div>

                                    @php $persona = $tienda->user?->persona; @endphp
                                    <p class="sec-label">Solicitante</p>
                                    <p class="pend-nombre" style="font-size:0.85rem">
                                        {{ $persona?->per_nombre }} {{ $persona?->per_paterno }}
                                        {{ $persona?->per_materno }}
                                    </p>
                                    <p class="pend-det">{{ $tienda->user?->email }}</p>
                                    <p class="pend-det">{{ $persona?->per_telefono ?? '—' }}</p>

                                    <div class="divider"></div>

                                    <a href="{{ \App\Filament\Pages\RevisionTienda::getUrl(['id' => $tienda->tie_id]) }}"
                                        class="btn-ver">
                                        Ver documentos
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ══ REPARTIDORES ═════════════════════════════════ --}}
            <div>
                <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:1.25rem;">
                    <h2 style="font-size:1rem; font-weight:800; color:#111;">Repartidores pendientes</h2>
                    <span class="cnt-badge">{{ $this->repartidores->count() }} solicitud(es)</span>
                </div>

                @if ($this->repartidores->isEmpty())
                    <div class="empty-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <p>No hay repartidores pendientes</p>
                    </div>
                @else
                    <div class="cards-list">
                        @foreach ($this->repartidores as $rep)
                            @php
                                $persona = $rep->user?->persona;
                                $fotoPerfil = $rep->documentos->firstWhere('dor_fk_tipo_documento', 4);
                            @endphp
                            <div class="pend-card">
                                <div class="pend-img-wrap">
                                    @if ($fotoPerfil?->dor_ruta)
                                        <img src="{{ asset('storage/' . $fotoPerfil->dor_ruta) }}"
                                            style="width:80px;height:80px;object-fit:cover;border-radius:50%;border:3px solid white;box-shadow:0 4px 12px rgba(0,0,0,0.1);margin:1rem auto;display:block;">
                                    @else
                                        <div class="pend-img-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="badge-pend">Pendiente</span>
                                </div>
                                <div class="pend-body">
                                    <p class="sec-label">Repartidor</p>
                                    <p class="pend-nombre">
                                        {{ $persona?->per_nombre }} {{ $persona?->per_paterno }}
                                        {{ $persona?->per_materno }}
                                    </p>
                                    <p class="pend-det">{{ $rep->user?->email }}</p>
                                    <p class="pend-det">{{ $persona?->per_telefono ?? '—' }}</p>
                                    <p class="pend-det">{{ $rep->rep_tipo_vehiculo }}</p>

                                    <div class="divider"></div>

                                    <p class="sec-label">Documentos</p>
                                    <div
                                        style="display:flex; flex-direction:column; gap:0.35rem; margin-bottom:0.85rem;">
                                        @foreach ($rep->documentos->whereNotIn('dor_fk_tipo_documento', [4]) as $doc)
                                            <a href="{{ asset('storage/' . $doc->dor_ruta) }}" target="_blank"
                                                class="doc-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                                {{ $doc->tipo_documento?->tid_nombre ?? 'Documento' }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <div class="divider"></div>

                                    <div style="display:flex; gap:0.5rem;">
                                        <button wire:click="abrirModalRechazo({{ $rep->rep_id }})"
                                            class="btn-rechazar">
                                            Rechazar
                                        </button>
                                        <button wire:click="aprobarRepartidor({{ $rep->rep_id }})"
                                            wire:confirm="¿Aprobar y asignar rol de repartidor?" class="btn-aprobar">
                                            Aprobar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .cnt-badge {
            background: #fff7e0;
            border: 1px solid #fcd34d;
            color: #92400e;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
        }

        .empty-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 2.5rem;
            text-align: center;
            color: #aaa;
            background: #fafafa;
            border-radius: 1rem;
            border: 1.5px dashed #e8e8e8;
        }

        .empty-box svg {
            width: 40px;
            height: 40px;
            color: #c6f135;
        }

        .empty-box p {
            font-size: 0.85rem;
        }

        .cards-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pend-card {
            background: white;
            border-radius: 1rem;
            border: 1.5px solid #e8f5d0;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .pend-card:hover {
            box-shadow: 0 8px 28px rgba(168, 223, 17, 0.15);
            border-color: #a8df11;
        }

        .pend-img-wrap {
            position: relative;
            min-height: 120px;
            background: #f8fdf0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pend-img-wrap img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        .pend-img-empty {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #e8f5d0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pend-img-empty svg {
            width: 30px;
            height: 30px;
            color: #7ab80e;
        }

        .badge-pend {
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

        .pend-body {
            padding: 1rem;
        }

        .sec-label {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #7ab80e;
            margin-bottom: 0.35rem;
        }

        .pend-nombre {
            font-size: 0.92rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.15rem;
        }

        .pend-det {
            font-size: 0.75rem;
            color: #888;
            margin-bottom: 0.1rem;
        }

        .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.75rem 0;
        }

        .btn-ver {
            display: block;
            text-align: center;
            background: #111;
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.65rem;
            border-radius: 0.65rem;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-ver:hover {
            background: #a8df11;
            color: #111;
        }

        .doc-link {
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

        .doc-link:hover {
            background: #e0f8c0;
        }

        .doc-link svg {
            width: 13px;
            height: 13px;
        }

        .btn-aprobar {
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
            width: 100%;
        }

        .btn-rechazar {
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
        }

        .btn-rechazar:hover {
            background: #fff1f0;
        }

        /* ── MODAL RECHAZO ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1.5rem;
        }

        .modal-box {
            background: white;
            border-radius: 1.25rem;
            padding: 1.75rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.35rem;
        }

        .modal-subtitle {
            font-size: 0.78rem;
            color: #888;
            margin-bottom: 1.25rem;
        }

        .modal-textarea {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            color: #111;
            resize: vertical;
            min-height: 100px;
            outline: none;
            transition: border-color 0.2s;
        }

        .modal-textarea:focus {
            border-color: #d41b11;
            box-shadow: 0 0 0 3px rgba(212,27,17,0.08);
        }

        .modal-error {
            font-size: 0.75rem;
            color: #d41b11;
            margin-top: 0.35rem;
            font-weight: 600;
        }

        .modal-btns {
            display: flex;
            gap: 0.65rem;
            margin-top: 1.25rem;
        }

        .btn-modal-cancel {
            flex: 1;
            background: #f0f0f0;
            color: #555;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.7rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
        }

        .btn-modal-rechazar {
            flex: 2;
            background: #d41b11;
            color: white;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.7rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
        }

        .btn-modal-rechazar:hover {
            background: #b91510;
        }
    </style>

    {{-- MODAL: motivo de rechazo --}}
    @if ($this->rechazandoId)
        <div class="modal-overlay" wire:click.self="cancelarRechazo">
            <div class="modal-box">
                <p class="modal-title">Motivo del rechazo</p>
                <p class="modal-subtitle">El repartidor verá este mensaje y podrá corregir su solicitud.</p>

                <textarea
                    wire:model="motivoRechazoInput"
                    class="modal-textarea"
                    placeholder="Ej: Tu INE está vencida. Por favor sube una identificación vigente."
                ></textarea>

                @error('motivoRechazoInput')
                    <p class="modal-error">{{ $message }}</p>
                @enderror

                <div class="modal-btns">
                    <button wire:click="cancelarRechazo" class="btn-modal-cancel">Cancelar</button>
                    <button wire:click="confirmarRechazo" class="btn-modal-rechazar">Confirmar rechazo</button>
                </div>
            </div>
        </div>
    @endif

</x-filament-widgets::widget>
