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
                        <button wire:click="abrirModalRechazo({{ $pedido->ped_id }})"
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

                    @php
                        $lstSubtotal = $pedido->detalles->sum('det_subtotal');
                        $lstPct      = \App\Models\ConfiguracionComision::porcentajeActual();
                        $lstComision = round($lstSubtotal * $lstPct / 100, 2);
                        $lstRecibira = round($lstSubtotal - $lstComision, 2);
                    @endphp
                    <div style="padding:.5rem 1rem .25rem;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;justify-content:space-between;font-size:.78rem;color:#aaa;margin-bottom:.2rem;">
                            <span>Subtotal</span>
                            <span>${{ number_format($lstSubtotal, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.78rem;color:#d97706;margin-bottom:.35rem;">
                            <span>Comisión ({{ $lstPct }}%)</span>
                            <span>−${{ number_format($lstComision, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.85rem;font-weight:900;border-top:1px dashed #e8f5d0;padding-top:.3rem;">
                            <span style="color:#111;">Recibirás</span>
                            <span style="color:#4a8a06;">${{ number_format($lstRecibira, 2) }}</span>
                        </div>
                    </div>

                    @php
                        $sinRepartidor  = strtolower($pedido->ped_tipo_entrega) === 'domicilio' && ! $pedido->asignacion;
                        $esEfectivoListo = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo';
                        $pinBloqueado   = ($pedido->ped_pin_intentos ?? 0) >= 5;
                        $pinGenerado    = $pedido->ped_pin_liquidacion !== null;
                    @endphp

                    @if ($sinRepartidor)
                        <div class="ped-sin-repartidor">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                            Esperando que un repartidor acepte el pedido
                        </div>
                    @endif

                    {{-- PIN de recogida para pedidos en efectivo --}}
                    @if ($esEfectivoListo)
                        @if ($pinBloqueado)
                            <div class="ped-liq-alerta" style="margin-bottom:.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                El repartidor agotó los 5 intentos del PIN
                            </div>
                        @elseif ($pinGenerado)
                            <div class="ped-pin-listo">
                                <p class="ped-pin-listo-label">PIN para el repartidor</p>
                                <div class="ped-pin-listo-digits">
                                    @foreach(str_split($pedido->ped_pin_liquidacion) as $d)
                                        <span>{{ $d }}</span>
                                    @endforeach
                                </div>
                                <p class="ped-pin-listo-sub">{{ 5 - ($pedido->ped_pin_intentos ?? 0) }} intentos restantes</p>
                            </div>
                        @endif
                    @endif

                    <div class="ped-acciones">
                        <button wire:click="verDetalle({{ $pedido->ped_id }})" class="ped-btn-detalle" title="Ver detalle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>

                        @if ($sinRepartidor)
                            <button disabled class="ped-btn-entregado ped-btn-entregado--disabled" title="Esperando repartidor">
                                Confirmar entrega
                            </button>
                        @else
                            @if ($esEfectivoListo && !$pinBloqueado && !$pinGenerado)
                                <button wire:click="generarPin({{ $pedido->ped_id }})"
                                    class="ped-liq-btn-pin" style="flex:1;min-width:0;padding:.5rem .6rem;font-size:.75rem;border-radius:.65rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" /></svg>
                                    Generar PIN
                                </button>
                            @endif
                            <button wire:click="marcarEntregado({{ $pedido->ped_id }})"
                                wire:confirm="¿Confirmar entrega del pedido #{{ $pedido->ped_codigo }}?"
                                class="ped-btn-entregado">
                                Confirmar entrega
                            </button>
                        @endif
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

    {{-- ══ PENDIENTES DE LIQUIDACIÓN ════════════════════════ --}}
    @if ($this->pedidosPendienteLiquidacion->count() > 0)
    <div class="ped-liq-wrap">
        <div class="ped-seccion-header" style="margin-bottom:.75rem;">
            <span class="ped-seccion-dot" style="background:#f59e0b;"></span>
            <h2 class="ped-seccion-titulo">Pendientes de liquidación en efectivo</h2>
            <span class="ped-badge-count" style="background:#fef3c7;color:#b45309;">{{ $this->pedidosPendienteLiquidacion->count() }}</span>
        </div>

        @foreach ($this->pedidosPendienteLiquidacion as $pedido)
            @php
                $rep = $pedido->asignacion?->repartidor?->user?->persona;
                $repNombre = $rep ? trim($rep->per_nombre . ' ' . $rep->per_paterno) : 'Sin asignar';
                $bloqueado = $pedido->ped_pin_intentos >= 5;
                $pinGenerado = $pedido->ped_pin_liquidacion !== null;
            @endphp
            <div class="ped-liq-card {{ $bloqueado ? 'ped-liq-bloqueado' : '' }}">
                <div class="ped-liq-header">
                    <div>
                        <p class="ped-liq-codigo">#{{ $pedido->ped_codigo }}</p>
                        <p class="ped-liq-fecha">{{ $pedido->ped_fecha_pedido->format('d/m/Y H:i') }}</p>
                    </div>
                    @php
                        $subtliq  = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
                        $pctliq   = \App\Models\ConfiguracionComision::porcentajeActual();
                        $montoLiq = round($subtliq * (1 - $pctliq / 100), 2);
                    @endphp
                    <div style="text-align:right;">
                        <p class="ped-liq-total">${{ number_format($montoLiq, 2) }}</p>
                        <p class="ped-liq-hint">a recibir del repartidor (−{{ $pctliq }}% comisión)</p>
                    </div>
                </div>

                <div class="ped-liq-rep">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" /></svg>
                    <span>{{ $repNombre }}</span>
                </div>

                @if ($bloqueado)
                    <div class="ped-liq-alerta">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                        El repartidor agotó los 5 intentos. Contacta con soporte.
                    </div>
                @elseif ($pinGenerado)
                    <div class="ped-liq-pin-display">
                        <span class="ped-liq-pin-label">PIN generado</span>
                        <div class="ped-liq-pin-digits">
                            @foreach (str_split($pedido->ped_pin_liquidacion) as $d)
                                <span>{{ $d }}</span>
                            @endforeach
                        </div>
                        <p class="ped-liq-pin-sub">Díselo al repartidor · {{ 5 - $pedido->ped_pin_intentos }} intentos restantes</p>
                    </div>
                @else
                    <button wire:click="generarPin({{ $pedido->ped_id }})" class="ped-liq-btn-pin">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" /></svg>
                        Generar PIN
                    </button>
                @endif
            </div>
        @endforeach
    </div>
    @endif

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
                                <span class="det-val">{{ strtolower($p->ped_tipo_entrega) === 'domicilio' ? '🛵 Domicilio' : '🏪 Recoger en tienda' }}</span>
                            </div>
                            @if (strtolower($p->ped_tipo_entrega) === 'domicilio' && $p->direccion)
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
                    @php
                        $detSubtotal   = $p->detalles->sum('det_subtotal');
                        $detPct        = \App\Models\ConfiguracionComision::porcentajeActual();
                        $detComision   = round($detSubtotal * $detPct / 100, 2);
                        $detGanancia   = round($detSubtotal - $detComision, 2);
                    @endphp
                    <div class="det-seccion">
                        <p class="det-seccion-titulo">Resumen</p>
                        <div class="det-filas">
                            <div class="det-fila">
                                <span class="det-key">Subtotal</span>
                                <span class="det-val">${{ number_format($detSubtotal, 2) }}</span>
                            </div>
                            <div class="det-fila" style="color:#d97706;">
                                <span class="det-key">Comisión ({{ $detPct }}%)</span>
                                <span class="det-val">-${{ number_format($detComision, 2) }}</span>
                            </div>
                            <div class="det-fila" style="border-top:1px solid #e8f5d0;padding-top:0.5rem;margin-top:0.25rem;">
                                <span class="det-key" style="font-weight:800;color:#111;">Tu ganancia</span>
                                <span class="det-val" style="font-size:1rem;font-weight:900;color:#4a8a06;">${{ number_format($detGanancia, 2) }}</span>
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

        .ped-sin-repartidor {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: #b45309;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 0.4rem 0.65rem;
            margin-bottom: 0.5rem;
        }
        .ped-sin-repartidor svg { width: 13px; height: 13px; flex-shrink: 0; }

        .ped-btn-entregado--disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
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

        /* ── Pendientes liquidación ─────────────────── */
        .ped-liq-wrap {
            margin-top: 1.5rem;
            border: 1.5px solid #fde68a;
            border-radius: 14px;
            padding: 1.25rem;
            background: #fffbeb;
        }
        .ped-liq-card {
            background: #fff;
            border: 1.5px solid #fde68a;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: .85rem;
        }
        .ped-liq-card:last-child { margin-bottom: 0; }
        .ped-liq-bloqueado { border-color: #fca5a5; background: #fff1f0; }
        .ped-liq-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .65rem; }
        .ped-liq-codigo { font-size: .88rem; font-weight: 800; color: #1a1a1a; }
        .ped-liq-fecha  { font-size: .7rem; color: #aaa; }
        .ped-liq-total  { font-size: .95rem; font-weight: 900; color: #b45309; }
        .ped-liq-hint   { font-size: .65rem; color: #aaa; text-align: right; }
        .ped-liq-rep    { display: flex; align-items: center; gap: .4rem; font-size: .78rem; color: #555; margin-bottom: .75rem; }
        .ped-liq-rep svg { width: 14px; height: 14px; flex-shrink: 0; }
        .ped-liq-alerta { display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: #d41b11; background: #fff1f0; border: 1px solid #fca5a5; border-radius: 8px; padding: .55rem .75rem; }
        .ped-liq-alerta svg { width: 14px; height: 14px; flex-shrink: 0; }
        .ped-liq-btn-pin {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .65rem; background: linear-gradient(135deg,#fbbf24,#f59e0b); border: none;
            border-radius: 9px; font-family: 'Sora',sans-serif; font-size: .85rem; font-weight: 800;
            color: #1a1a1a; cursor: pointer;
        }
        .ped-liq-btn-pin svg { width: 16px; height: 16px; }
        .ped-liq-pin-display { text-align: center; padding: .5rem 0; }
        .ped-liq-pin-label { font-size: .62rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #b45309; }
        .ped-liq-pin-digits { display: flex; justify-content: center; gap: .5rem; margin: .5rem 0 .35rem; }
        .ped-liq-pin-digits span {
            width: 2.5rem; height: 3rem; background: #fffbeb; border: 2px solid #fbbf24;
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; font-weight: 900; color: #b45309;
        }
        .ped-liq-pin-sub { font-size: .68rem; color: #aaa; }

        /* ── PIN en tarjeta listo ───────────────────── */
        .ped-pin-listo { text-align: center; background: #fffbeb; border: 1.5px solid #fbbf24; border-radius: 10px; padding: .6rem .75rem; margin-bottom: .5rem; }
        .ped-pin-listo-label { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #92400e; margin-bottom: .35rem; }
        .ped-pin-listo-digits { display: flex; justify-content: center; gap: .4rem; margin-bottom: .3rem; }
        .ped-pin-listo-digits span { width: 2.1rem; height: 2.6rem; background: #fff; border: 2px solid #fbbf24; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 900; color: #b45309; }
        .ped-pin-listo-sub { font-size: .62rem; color: #aaa; }

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

        /* ── Modal entrega / PIN ─────────────────────────── */
        .modal-entrega-desc { font-size:.82rem; color:#555; text-align:center; line-height:1.5; margin-bottom:.25rem; }
        .modal-entrega-cancel { width:100%; margin-top:.65rem; padding:.6rem; background:#f3f4f6; border:none; border-radius:.65rem; font-family:inherit; font-size:.82rem; font-weight:700; color:#555; cursor:pointer; }
        .modal-entrega-cancel:hover { background:#e5e7eb; }

        /* ── Card PIN de recogida ────────────────────────── */
        .pin-card {
            background: #fffbf0;
            border: 1.5px solid #fde68a;
            border-radius: 1rem;
            padding: 1.1rem 1rem .9rem;
            text-align: center;
            margin: .25rem 0 .75rem;
        }
        .pin-card-titulo {
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .14em;
            color: #d97706;
            text-transform: uppercase;
            margin-bottom: .75rem;
        }
        .pin-card-digits {
            display: flex;
            gap: .5rem;
            justify-content: center;
            margin-bottom: .85rem;
        }
        .pin-card-digit {
            width: 2.9rem;
            height: 3.3rem;
            border: 2px solid #f59e0b;
            border-radius: .55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            font-weight: 900;
            color: #92400e;
            background: #fff;
            font-variant-numeric: tabular-nums;
        }
        .pin-card-info {
            border-top: 1px solid #fde68a;
            padding-top: .6rem;
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: .35rem;
        }
        .pin-info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: .8rem;
        }
        .pin-info-label { color: #9ca3af; font-weight: 500; }
        .pin-info-val   { color: #1a1a1a; font-weight: 700; }
        .pin-info-pending { color: #d97706; font-weight: 700; }
    </style>

    {{-- ══ MODAL ENTREGA / PIN ═══════════════════════════════════ --}}
    @if ($this->modalEntregaId)
        @php
            $pm         = $this->pedidoModalEntrega;
            $pmEfectivo = $pm && strtolower($pm->pago?->pag_metodo_pago ?? '') === 'efectivo'
                           && strtolower($pm->ped_tipo_entrega) === 'domicilio';
            $pmPin      = $pm?->ped_pin_liquidacion;
            $pmBloqueo  = ($pm?->ped_pin_intentos ?? 0) >= 5;
        @endphp
        @if ($pm)
        <div class="det-backdrop" wire:click.self="cerrarModalEntrega">
            <div class="det-modal" style="max-width:360px;">

                <div class="det-header">
                    <div>
                        <p class="det-codigo">#{{ $pm->ped_codigo }}</p>
                        <p class="det-fecha">${{ number_format($pm->ped_total, 2) }} · {{ strtolower($pm->ped_tipo_entrega) === 'domicilio' ? 'Domicilio' : 'Recoger' }}</p>
                    </div>
                    <button wire:click="cerrarModalEntrega" class="det-close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="det-body">
                    @if ($pmEfectivo)
                        @if ($pmBloqueo)
                            <div class="ped-liq-alerta">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                El repartidor agotó los 5 intentos. Contacta con soporte.
                            </div>
                        @elseif ($pmPin)
                            @php
                                $pmRep     = $pm->asignacion?->repartidor;
                                $pmRepNom  = $pmRep ? trim(($pmRep->user?->persona?->per_nombre ?? '') . ' ' . ($pmRep->user?->persona?->per_paterno ?? '')) : null;
                                $pmRepNom  = $pmRepNom ?: ($pmRep?->user?->email ?? null);
                            @endphp
                            <div class="pin-card">
                                <p class="pin-card-titulo">PIN de recogida</p>
                                <div class="pin-card-digits">
                                    @foreach (str_split($pmPin) as $d)
                                        <span class="pin-card-digit">{{ $d }}</span>
                                    @endforeach
                                </div>
                                <div class="pin-card-info">
                                    @if ($pmRepNom)
                                    <div class="pin-info-row">
                                        <span class="pin-info-label">Repartidor</span>
                                        <span class="pin-info-val">{{ $pmRepNom }}</span>
                                    </div>
                                    @endif
                                    @if ($pm->ped_liquidado_at)
                                    <div class="pin-info-row">
                                        <span class="pin-info-label">Pagado el</span>
                                        <span class="pin-info-val">{{ $pm->ped_liquidado_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @else
                                    <div class="pin-info-row">
                                        <span class="pin-info-label">Estado</span>
                                        <span class="pin-info-pending">Pendiente de pago · {{ 5 - ($pm->ped_pin_intentos ?? 0) }} intentos</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="marcarEntregado({{ $pm->ped_id }})"
                                class="ped-btn-entregado" style="width:100%;">
                                Confirmar entrega
                            </button>
                        @else
                            <div class="pin-card" style="text-align:left;margin-bottom:.75rem;">
                                <p class="pin-card-titulo">PIN de recogida</p>
                                <p style="font-size:.82rem;color:#78716c;line-height:1.5;margin-bottom:.9rem;">
                                    El PIN aún no fue generado para este pedido.
                                </p>
                                <button wire:click="generarPin({{ $pm->ped_id }})" class="ped-liq-btn-pin">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" /></svg>
                                    Generar PIN de recogida
                                </button>
                            </div>
                        @endif
                    @else
                        <p class="modal-entrega-desc">¿Confirmas que este pedido fue entregado correctamente?</p>
                        <button wire:click="marcarEntregado({{ $pm->ped_id }})"
                            class="ped-btn-entregado" style="width:100%;margin-top:.75rem;">
                            Confirmar entrega
                        </button>
                    @endif

                    <button wire:click="cerrarModalEntrega" class="modal-entrega-cancel">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
        @endif
    @endif

    {{-- MODAL RECHAZAR CON MOTIVO --}}
    @if($pedidoParaRechazar)
    <div style="position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;border-radius:1rem;padding:1.5rem;max-width:420px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                <div style="width:36px;height:36px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#dc2626" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.95rem;font-weight:800;color:#111;">Rechazar pedido</p>
                    <p style="font-size:0.75rem;color:#888;">El cliente recibirá el motivo</p>
                </div>
            </div>

            <label style="display:block;font-size:0.78rem;font-weight:700;color:#374151;margin-bottom:0.4rem;">
                Motivo del rechazo <span style="color:#dc2626;">*</span>
            </label>
            <textarea wire:model="motivoRechazo" rows="3"
                placeholder="Ej: Producto agotado, no tenemos capacidad en este momento..."
                style="width:100%;padding:0.7rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:0.65rem;font-family:inherit;font-size:0.85rem;resize:none;outline:none;color:#111;line-height:1.4;"
                oninput="this.style.borderColor='#a8df11'"></textarea>
            @error('motivoRechazo')
                <p style="color:#dc2626;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p>
            @enderror

            <div style="display:flex;gap:0.65rem;margin-top:1rem;">
                <button wire:click="cerrarModalRechazo"
                    style="flex:1;padding:0.7rem;background:#f3f4f6;border:none;border-radius:0.65rem;font-family:inherit;font-size:0.85rem;font-weight:700;color:#555;cursor:pointer;">
                    Cancelar
                </button>
                <button wire:click="confirmarRechazo"
                    style="flex:2;padding:0.7rem;background:#dc2626;border:none;border-radius:0.65rem;font-family:inherit;font-size:0.85rem;font-weight:800;color:#fff;cursor:pointer;">
                    Rechazar pedido
                </button>
            </div>
        </div>
    </div>
    @endif

</x-filament-panels::page>
