<x-filament-panels::page>

    <div class="ped-wrap">

        {{-- ══ PENDIENTES ══════════════════════════════════════ --}}
        <div class="ped-seccion">
            <div class="ped-seccion-header">
                <span class="ped-seccion-dot pendiente"></span>
                <h2 class="ped-seccion-titulo">Nuevos pedidos</h2>
                <span class="ped-badge-count">{{ $this->pedidosPendientes->count() }}</span>
            </div>

            @forelse($this->pedidosPendientes as $pedido)
                <div class="ped-card pendiente-card">
                    @include('filament.store.pages.partials.pedido-header', ['pedido' => $pedido])

                    <div class="ped-productos">
                        @foreach ($pedido->detalles as $det)
                            <div class="ped-prod-row">
                                <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                                <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                                <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="ped-total-row">
                        <span>Total productos</span>
                        <span
                            class="ped-total-val">${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}</span>
                    </div>

                    <div class="ped-acciones">
                        <button wire:click="verDetalle({{ $pedido->ped_id }})" class="ped-btn-detalle" title="Ver detalle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>
                        <button wire:click="rechazar({{ $pedido->ped_id }})" wire:confirm="¿Rechazar este pedido?"
                            class="ped-btn-rechazar">
                            Rechazar
                        </button>
                        <button wire:click="aceptar({{ $pedido->ped_id }})" class="ped-btn-aceptar">
                            Aceptar pedido
                        </button>
                    </div>
                </div>
            @empty
                <div class="ped-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p>Sin pedidos nuevos</p>
                </div>
            @endforelse
        </div>

        {{-- ══ EN PREPARACIÓN ══════════════════════════════════ --}}
        <div class="ped-seccion">
            <div class="ped-seccion-header">
                <span class="ped-seccion-dot preparacion"></span>
                <h2 class="ped-seccion-titulo">En preparación</h2>
                <span class="ped-badge-count">{{ $this->pedidosEnPreparacion->count() }}</span>
            </div>

            @forelse($this->pedidosEnPreparacion as $pedido)
                <div class="ped-card preparacion-card">
                    @include('filament.store.pages.partials.pedido-header', ['pedido' => $pedido])

                    <div class="ped-productos">
                        @foreach ($pedido->detalles as $det)
                            <div class="ped-prod-row">
                                <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                                <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                                @php $inv = $det->producto?->inventario; @endphp
                                @if ($inv && $inv->inv_stock_actual < $det->det_cantidad)
                                    <span class="ped-stock-alerta">⚠ Stock insuf.</span>
                                @endif
                                <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="ped-total-row">
                        <span>Total</span>
                        <span
                            class="ped-total-val">${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}</span>

                    </div>

                    <div class="ped-acciones">
                        <button wire:click="verDetalle({{ $pedido->ped_id }})" class="ped-btn-detalle" title="Ver detalle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>
                        <button wire:click="marcarListo({{ $pedido->ped_id }})"
                            wire:confirm="¿Marcar como listo? Esto descontará el inventario." class="ped-btn-listo">
                            Listo para entregar
                        </button>
                    </div>
                </div>
            @empty
                <div class="ped-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p>Ninguno en preparación</p>
                </div>
            @endforelse
        </div>

        {{-- ══ LISTOS PARA ENTREGAR ════════════════════════════ --}}
        <div class="ped-seccion">
            <div class="ped-seccion-header">
                <span class="ped-seccion-dot listo"></span>
                <h2 class="ped-seccion-titulo">Listos para entregar</h2>
                <span class="ped-badge-count">{{ $this->pedidosListos->count() }}</span>
            </div>

            @forelse($this->pedidosListos as $pedido)
                <div class="ped-card listo-card">
                    @include('filament.store.pages.partials.pedido-header', ['pedido' => $pedido])

                    <div class="ped-productos">
                        @foreach ($pedido->detalles as $det)
                            <div class="ped-prod-row">
                                <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                                <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                                <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="ped-total-row">
                        <span>Total</span>
                        <span class="ped-total-val">${{ number_format($pedido->detalles->sum('det_subtotal'), 2) }}</span>
                    </div>

                    <div class="ped-acciones">
                        <button wire:click="verDetalle({{ $pedido->ped_id }})" class="ped-btn-detalle" title="Ver detalle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>
                        <button wire:click="marcarEntregado({{ $pedido->ped_id }})"
                            wire:confirm="¿Confirmar entrega? El pago se marcará como recibido."
                            class="ped-btn-entregado">
                            Confirmar entrega
                        </button>
                    </div>
                </div>
            @empty
                <div class="ped-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p>Ninguno listo aún</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ══ MODAL DETALLE ══════════════════════════════════════ --}}
    @if ($this->pedidoDetalle)
        @php $p = $this->pedidoDetalle; $persona = $p->cliente?->user?->persona; @endphp
        <div class="det-backdrop" wire:click.self="cerrarDetalle">
            <div class="det-modal">

                {{-- HEADER --}}
                <div class="det-header">
                    <div>
                        <p class="det-codigo">#{{ $p->ped_codigo }}</p>
                        <p class="det-fecha">{{ $p->ped_fecha_pedido->format('d/m/Y H:i') }}</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        @php
                            $estadoBadge = match($p->ped_estado) {
                                'pendiente'      => ['txt' => 'Pendiente',      'cls' => 'badge-pendiente'],
                                'en_preparacion' => ['txt' => 'En preparación', 'cls' => 'badge-preparacion'],
                                'listo'          => ['txt' => 'Listo',          'cls' => 'badge-listo'],
                                'completado'     => ['txt' => 'Completado',     'cls' => 'badge-completado'],
                                'cancelado'      => ['txt' => 'Cancelado',      'cls' => 'badge-cancelado'],
                                default          => ['txt' => $p->ped_estado,   'cls' => ''],
                            };
                        @endphp
                        <span class="det-badge {{ $estadoBadge['cls'] }}">{{ $estadoBadge['txt'] }}</span>
                        <button wire:click="cerrarDetalle" class="det-close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="det-body">

                    {{-- CLIENTE --}}
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Cliente</p>
                        <div class="det-filas">
                            <div class="det-fila">
                                <span class="det-key">Nombre</span>
                                <span class="det-val">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</span>
                            </div>
                            @if ($persona?->per_telefono)
                            <div class="det-fila">
                                <span class="det-key">Teléfono</span>
                                <span class="det-val">{{ $persona->per_telefono }}</span>
                            </div>
                            @endif
                            <div class="det-fila">
                                <span class="det-key">Correo</span>
                                <span class="det-val">{{ $p->cliente?->user?->email ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ENTREGA --}}
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Entrega</p>
                        <div class="det-filas">
                            <div class="det-fila">
                                <span class="det-key">Tipo</span>
                                <span class="det-val">{{ $p->ped_tipo_entrega === 'domicilio' ? '🛵 Domicilio' : '🏪 Recoger en tienda' }}</span>
                            </div>
                            @if ($p->ped_tipo_entrega === 'domicilio' && $p->direccion)
                            <div class="det-fila">
                                <span class="det-key">Dirección</span>
                                <span class="det-val">{{ $p->direccion->drc_calle }}, {{ $p->direccion->drc_ciudad }}, {{ $p->direccion->drc_estado }}</span>
                            </div>
                            @if ($p->direccion->drc_referencias)
                            <div class="det-fila">
                                <span class="det-key">Referencias</span>
                                <span class="det-val">{{ $p->direccion->drc_referencias }}</span>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>

                    {{-- PRODUCTOS --}}
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Productos</p>
                        <div class="det-productos">
                            @foreach ($p->detalles as $det)
                            <div class="det-prod-row">
                                <span class="det-prod-qty">×{{ $det->det_cantidad }}</span>
                                <span class="det-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                                <span class="det-prod-unit">${{ number_format($det->det_precio_unitario, 2) }} c/u</span>
                                <span class="det-prod-sub">${{ number_format($det->det_subtotal, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- TOTALES --}}
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Resumen</p>
                        <div class="det-filas">
                            <div class="det-fila">
                                <span class="det-key">Subtotal</span>
                                <span class="det-val">${{ number_format($p->detalles->sum('det_subtotal'), 2) }}</span>
                            </div>
                            @if ($p->ped_costo_envio > 0)
                            <div class="det-fila">
                                <span class="det-key">Envío</span>
                                <span class="det-val">${{ number_format($p->ped_costo_envio, 2) }}</span>
                            </div>
                            @endif
                            <div class="det-fila" style="border-top:1px solid #e8f5d0;padding-top:0.5rem;margin-top:0.25rem;">
                                <span class="det-key" style="font-weight:800;color:#111;">Total</span>
                                <span class="det-val" style="font-size:1rem;font-weight:900;color:#4a8a06;">${{ number_format($p->ped_total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- PAGO --}}
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Pago</p>
                        <div class="det-filas">
                            <div class="det-fila">
                                <span class="det-key">Método</span>
                                <span class="det-val">{{ ucfirst(strtolower($p->pago?->pag_metodo_pago ?? '—')) }}</span>
                            </div>
                            <div class="det-fila">
                                <span class="det-key">Estado</span>
                                <span class="det-val">{{ $p->pago?->pag_estado ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <style>
        .ped-wrap {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .ped-wrap {
                grid-template-columns: 1fr;
            }
        }

        /* Sección */
        .ped-seccion {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ped-seccion-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .ped-seccion-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ped-seccion-dot.pendiente {
            background: #f59e0b;
            box-shadow: 0 0 0 3px #fef3c7;
        }

        .ped-seccion-dot.preparacion {
            background: #3b82f6;
            box-shadow: 0 0 0 3px #dbeafe;
        }

        .ped-seccion-dot.listo {
            background: #a8df11;
            box-shadow: 0 0 0 3px #f0fde0;
        }

        .ped-seccion-titulo {
            font-size: 0.85rem;
            font-weight: 800;
            color: #111;
            flex: 1;
        }

        .ped-badge-count {
            background: #f0f0f0;
            color: #555;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
        }

        /* Card */
        .ped-card {
            background: white;
            border-radius: 1rem;
            border: 1.5px solid #e8f5d0;
            overflow: hidden;
        }

        .pendiente-card {
            border-color: #fde68a;
        }

        .preparacion-card {
            border-color: #93c5fd;
        }

        .listo-card {
            border-color: #a8df11;
        }

        /* Header del pedido */
        .ped-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1rem;
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .ped-codigo {
            font-size: 0.78rem;
            font-weight: 800;
            color: #111;
        }

        .ped-fecha {
            font-size: 0.65rem;
            color: #aaa;
            margin-top: 0.1rem;
        }

        .ped-cliente {
            font-size: 0.72rem;
            color: #555;
            text-align: right;
        }

        .ped-entrega {
            font-size: 0.65rem;
            color: #888;
            margin-top: 0.1rem;
        }

        /* Productos */
        .ped-productos {
            padding: 0.75rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            border-bottom: 1px solid #f5f5f5;
        }

        .ped-prod-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .ped-prod-qty {
            font-weight: 800;
            color: #4a8a06;
            min-width: 28px;
        }

        .ped-prod-nombre {
            flex: 1;
            color: #333;
        }

        .ped-stock-alerta {
            font-size: 0.65rem;
            background: #fff7e0;
            color: #92400e;
            border: 1px solid #fcd34d;
            padding: 0.1rem 0.4rem;
            border-radius: 0.35rem;
            font-weight: 700;
        }

        .ped-prod-precio {
            font-weight: 700;
            color: #111;
        }

        /* Total */
        .ped-total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            color: #888;
            border-bottom: 1px solid #f5f5f5;
        }

        .ped-total-val {
            font-weight: 900;
            color: #4a8a06;
            font-size: 0.95rem;
        }

        /* Acciones */
        .ped-acciones {
            display: flex;
            gap: 0.5rem;
            padding: 0.75rem;
        }

        .ped-btn-aceptar,
        .ped-btn-listo,
        .ped-btn-entregado {
            flex: 1;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.6rem;
            border-radius: 0.65rem;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .ped-btn-aceptar:hover,
        .ped-btn-listo:hover,
        .ped-btn-entregado:hover {
            opacity: 0.85;
        }

        .ped-btn-rechazar {
            background: white;
            color: #d41b11;
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.6rem 0.85rem;
            border-radius: 0.65rem;
            border: 1.5px solid #fca5a5;
            cursor: pointer;
            transition: background 0.2s;
        }

        .ped-btn-rechazar:hover {
            background: #fff1f0;
        }

        /* Empty */
        .ped-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 2rem;
            text-align: center;
            color: #ccc;
            background: #fafafa;
            border-radius: 1rem;
            border: 1.5px dashed #e8e8e8;
        }

        .ped-empty svg {
            width: 32px;
            height: 32px;
        }

        .ped-empty p {
            font-size: 0.78rem;
            font-weight: 600;
        }

        /* ── Botón ojo ──────────────────────────────── */
        .ped-btn-detalle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.1rem;
            height: 2.1rem;
            border-radius: 8px;
            border: 1.5px solid #c8e6a0;
            background: #f5fbef;
            color: #5a9e10;
            cursor: pointer;
            transition: background .15s, border-color .15s;
            flex-shrink: 0;
        }
        .ped-btn-detalle:hover {
            background: #e4f6cc;
            border-color: #8ec83a;
        }
        .ped-btn-detalle svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        /* ── Modal backdrop ─────────────────────────── */
        .det-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,.45);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* ── Modal box ───────────────────────────────── */
        .det-modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,.18);
            overflow: hidden;
        }

        /* ── Header ──────────────────────────────────── */
        .det-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 1.25rem 1.5rem 1rem;
            border-bottom: 1.5px solid #e8f5d0;
            flex-shrink: 0;
        }
        .det-codigo {
            font-size: 1.05rem;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 .15rem;
        }
        .det-fecha {
            font-size: .78rem;
            color: #888;
            margin: 0;
        }
        .det-badge {
            font-size: .7rem;
            font-weight: 700;
            padding: .25rem .65rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .badge-pendiente     { background:#fff7e6; color:#b45309; }
        .badge-preparacion   { background:#e0f2fe; color:#0369a1; }
        .badge-listo         { background:#f0fdf4; color:#15803d; }
        .badge-completado    { background:#e4f6cc; color:#4a8a06; }
        .badge-cancelado     { background:#fee2e2; color:#b91c1c; }

        .det-close {
            width: 2rem;
            height: 2rem;
            border: none;
            background: #f5f5f5;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            transition: background .15s;
            flex-shrink: 0;
        }
        .det-close:hover { background: #e5e5e5; }
        .det-close svg { width: 1rem; height: 1rem; }

        /* ── Body scrollable ─────────────────────────── */
        .det-body {
            overflow-y: auto;
            padding: 1rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        /* ── Sección ─────────────────────────────────── */
        .det-seccion {
            border: 1.5px solid #e8f5d0;
            border-radius: 10px;
            padding: .85rem 1rem;
        }
        .det-seccion-titulo {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6aab0f;
            margin: 0 0 .6rem;
        }

        /* ── Filas clave-valor ───────────────────────── */
        .det-filas { display: flex; flex-direction: column; gap: .4rem; }
        .det-fila  { display: flex; justify-content: space-between; align-items: baseline; gap: .5rem; }
        .det-key   { font-size: .8rem; color: #888; flex-shrink: 0; }
        .det-val   { font-size: .85rem; font-weight: 600; color: #1a1a1a; text-align: right; }

        /* ── Lista productos ─────────────────────────── */
        .det-productos { display: flex; flex-direction: column; gap: .45rem; }
        .det-prod-row  { display: grid; grid-template-columns: 2rem 1fr auto auto; align-items: baseline; gap: .4rem; }
        .det-prod-qty  { font-size: .78rem; font-weight: 700; color: #6aab0f; }
        .det-prod-nombre { font-size: .85rem; color: #1a1a1a; }
        .det-prod-unit { font-size: .72rem; color: #aaa; }
        .det-prod-sub  { font-size: .85rem; font-weight: 700; color: #333; min-width: 4rem; text-align: right; }
    </style>

</x-filament-panels::page>
