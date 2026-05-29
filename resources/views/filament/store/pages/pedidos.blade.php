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
                @foreach($pedido->detalles as $det)
                <div class="ped-prod-row">
                    <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                    <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                    <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="ped-total-row">
                <span>Total</span>
                <span class="ped-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
            </div>

            <div class="ped-acciones">
                <button wire:click="rechazar({{ $pedido->ped_id }})"
                        wire:confirm="¿Rechazar este pedido?"
                        class="ped-btn-rechazar">
                    Rechazar
                </button>
                <button wire:click="aceptar({{ $pedido->ped_id }})"
                        class="ped-btn-aceptar">
                    Aceptar pedido
                </button>
            </div>
        </div>
        @empty
        <div class="ped-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
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
                @foreach($pedido->detalles as $det)
                <div class="ped-prod-row">
                    <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                    <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                    @php $inv = $det->producto?->inventario; @endphp
                    @if($inv && $inv->inv_stock_actual < $det->det_cantidad)
                    <span class="ped-stock-alerta">⚠ Stock insuf.</span>
                    @endif
                    <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="ped-total-row">
                <span>Total</span>
                <span class="ped-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
            </div>

            <div class="ped-acciones">
                <button wire:click="marcarListo({{ $pedido->ped_id }})"
                        wire:confirm="¿Marcar como listo? Esto descontará el inventario."
                        class="ped-btn-listo">
                    Listo para entregar
                </button>
            </div>
        </div>
        @empty
        <div class="ped-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
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
                @foreach($pedido->detalles as $det)
                <div class="ped-prod-row">
                    <span class="ped-prod-qty">× {{ $det->det_cantidad }}</span>
                    <span class="ped-prod-nombre">{{ $det->producto?->pro_nombre }}</span>
                    <span class="ped-prod-precio">${{ number_format($det->det_subtotal, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="ped-total-row">
                <span>Total</span>
                <span class="ped-total-val">${{ number_format($pedido->ped_total, 2) }}</span>
            </div>

            <div class="ped-acciones">
                <button wire:click="marcarEntregado({{ $pedido->ped_id }})"
                        wire:confirm="¿Confirmar entrega? El pago se marcará como recibido."
                        class="ped-btn-entregado">
                    Confirmar entrega
                </button>
            </div>
        </div>
        @empty
        <div class="ped-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <p>Ninguno listo aún</p>
        </div>
        @endforelse
    </div>

</div>

<style>
.ped-wrap {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    align-items: start;
}

@media (max-width: 1024px) { .ped-wrap { grid-template-columns: 1fr; } }

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
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ped-seccion-dot.pendiente  { background: #f59e0b; box-shadow: 0 0 0 3px #fef3c7; }
.ped-seccion-dot.preparacion { background: #3b82f6; box-shadow: 0 0 0 3px #dbeafe; }
.ped-seccion-dot.listo      { background: #a8df11; box-shadow: 0 0 0 3px #f0fde0; }

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

.pendiente-card  { border-color: #fde68a; }
.preparacion-card { border-color: #93c5fd; }
.listo-card      { border-color: #a8df11; }

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

.ped-btn-aceptar, .ped-btn-listo, .ped-btn-entregado {
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
.ped-btn-entregado:hover { opacity: 0.85; }

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

.ped-btn-rechazar:hover { background: #fff1f0; }

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

.ped-empty svg { width: 32px; height: 32px; }
.ped-empty p { font-size: 0.78rem; font-weight: 600; }
</style>

</x-filament-panels::page>