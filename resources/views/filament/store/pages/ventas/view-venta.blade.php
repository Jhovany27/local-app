<x-filament-panels::page>
<div class="vv-wrap">

    {{-- HEADER --}}
    <div class="vv-header">
        <a href="{{ \App\Filament\Store\Resources\Ventas\VentaResource::getUrl('index') }}"
           class="vv-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Volver al historial
        </a>

        @php
            $badgeClass = match((int)$record->ven_estado) {
                \App\Models\Venta::ESTADO_COMPLETADA => 'vv-badge-completada',
                \App\Models\Venta::ESTADO_CANCELADA  => 'vv-badge-cancelada',
                default                              => 'vv-badge-pendiente',
            };
            $badgeLabel = match((int)$record->ven_estado) {
                \App\Models\Venta::ESTADO_COMPLETADA => 'Completada',
                \App\Models\Venta::ESTADO_CANCELADA  => 'Cancelada',
                default                              => 'Pendiente',
            };
        @endphp
        <span class="vv-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
    </div>

    <div class="vv-grid">

        {{-- COLUMNA IZQUIERDA --}}
        <div>

            {{-- INFO VENTA --}}
            <div class="vv-card">
                <p class="vv-card-label">Datos de la venta</p>
                <div class="vv-info-list">
                    <div class="vv-info-row">
                        <span class="vv-info-key">Número</span>
                        <span class="vv-info-val">#{{ str_pad($record->ven_id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="vv-info-row">
                        <span class="vv-info-key">Fecha</span>
                        <span class="vv-info-val">{{ $record->ven_fecha->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="vv-info-row">
                        <span class="vv-info-key">Tienda</span>
                        <span class="vv-info-val">{{ $record->tienda?->tie_nombre ?? '—' }}</span>
                    </div>
                    <div class="vv-info-row">
                        <span class="vv-info-key">Estado</span>
                        <span class="vv-info-val">{{ $badgeLabel }}</span>
                    </div>
                </div>
            </div>

            {{-- TOTAL --}}
            <div class="vv-total-card">
                <div>
                    <p class="vv-total-label">Total de la venta</p>
                    <p class="vv-total-val">${{ number_format($record->ven_total, 2) }}</p>
                </div>
                <div class="vv-total-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                    </svg>
                </div>
            </div>

        </div>

        {{-- COLUMNA DERECHA — PRODUCTOS --}}
        <div class="vv-card">
            <p class="vv-card-label">Productos vendidos ({{ $record->detalles->count() }})</p>

            <div class="vv-productos">
                @foreach($record->detalles as $det)
                <div class="vv-prod-row">

                    {{-- Imagen --}}
                    @if($det->producto?->foto_principal)
                        <img src="{{ asset('storage/'.$det->producto->foto_principal) }}"
                             class="vv-prod-img" alt="{{ $det->producto->pro_nombre }}">
                    @else
                        <div class="vv-prod-img-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="vv-prod-info">
                        <p class="vv-prod-nombre">{{ $det->producto?->pro_nombre ?? 'Producto eliminado' }}</p>
                        @if($det->producto?->pro_marca)
                        <p class="vv-prod-marca">{{ $det->producto->pro_marca }}</p>
                        @endif
                        <p class="vv-prod-precio">${{ number_format($det->vde_precio_unitario, 2) }} c/u</p>
                    </div>

                    {{-- Cantidad y subtotal --}}
                    <div class="vv-prod-right">
                        <span class="vv-prod-qty">× {{ $det->vde_cantidad }}</span>
                        <span class="vv-prod-subtotal">${{ number_format($det->vde_subtotal, 2) }}</span>
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Resumen --}}
            <div class="vv-resumen">
                <div class="vv-resumen-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($record->detalles->sum('vde_subtotal'), 2) }}</span>
                </div>
                <div class="vv-resumen-row total">
                    <span>Total</span>
                    <span>${{ number_format($record->ven_total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.vv-wrap { font-family: 'Sora', sans-serif; padding: 0.5rem 0 2rem; }

.vv-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem; }

.vv-back { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; font-weight: 600; color: #7ab80e; text-decoration: none; }
.vv-back svg { width: 14px; height: 14px; }
.vv-back:hover { color: #4a8a06; }

.vv-badge { display: inline-flex; align-items: center; font-size: 0.72rem; font-weight: 700; padding: 0.3rem 0.85rem; border-radius: 999px; letter-spacing: 0.06em; }
.vv-badge-completada { background: #f0fde0; border: 1.5px solid #a8df11; color: #4a8a06; }
.vv-badge-cancelada  { background: #fff1f0; border: 1.5px solid #fca5a5; color: #d41b11; }
.vv-badge-pendiente  { background: #fff7e0; border: 1.5px solid #fcd34d; color: #92400e; }

.vv-grid { display: grid; grid-template-columns: 1fr 1.6fr; gap: 1.25rem; align-items: start; }
@media (max-width: 768px) { .vv-grid { grid-template-columns: 1fr; } }

.vv-card { background: white; border: 1.5px solid #e8f5d0; border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.25rem; }

.vv-card-label { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.14em; text-transform: uppercase; color: #7ab80e; margin-bottom: 1rem; }

.vv-info-list { display: flex; flex-direction: column; gap: 0.65rem; }
.vv-info-row { display: flex; gap: 1rem; align-items: center; }
.vv-info-key { font-size: 0.72rem; font-weight: 700; color: #aaa; min-width: 80px; flex-shrink: 0; }
.vv-info-val { font-size: 0.82rem; font-weight: 600; color: #111; }

.vv-total-card { background: linear-gradient(135deg, #a8df11, #7cc10a); border-radius: 1rem; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; }
.vv-total-label { font-size: 0.7rem; font-weight: 700; color: rgba(255,255,255,0.8); margin-bottom: 0.25rem; }
.vv-total-val { font-size: 1.75rem; font-weight: 900; color: white; }
.vv-total-icon { width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; }
.vv-total-icon svg { width: 24px; height: 24px; color: white; }

.vv-productos { display: flex; flex-direction: column; gap: 0; border-top: 1px solid #f0f0f0; margin: 0 -1.25rem; }

.vv-prod-row { display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid #f5f5f5; }
.vv-prod-row:last-child { border-bottom: none; }

.vv-prod-img { width: 48px; height: 48px; object-fit: contain; border-radius: 0.5rem; background: #f8f8f8; flex-shrink: 0; }
.vv-prod-img-empty { width: 48px; height: 48px; border-radius: 0.5rem; background: #f0fde0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vv-prod-img-empty svg { width: 20px; height: 20px; color: #c6f135; }

.vv-prod-info { flex: 1; min-width: 0; }
.vv-prod-nombre { font-size: 0.82rem; font-weight: 700; color: #111; }
.vv-prod-marca { font-size: 0.68rem; color: #aaa; margin-top: 0.1rem; }
.vv-prod-precio { font-size: 0.72rem; color: #888; margin-top: 0.1rem; }

.vv-prod-right { display: flex; flex-direction: column; align-items: flex-end; gap: 0.2rem; flex-shrink: 0; }
.vv-prod-qty { font-size: 0.78rem; font-weight: 700; color: #4a8a06; }
.vv-prod-subtotal { font-size: 0.88rem; font-weight: 800; color: #111; }

.vv-resumen { border-top: 2px solid #f0fde0; margin: 0 -1.25rem -1.25rem; padding: 0.85rem 1.25rem; background: #f8fdf0; border-radius: 0 0 1rem 1rem; }
.vv-resumen-row { display: flex; justify-content: space-between; font-size: 0.82rem; color: #555; padding: 0.25rem 0; }
.vv-resumen-row.total { font-size: 1rem; font-weight: 900; color: #111; padding-top: 0.5rem; border-top: 1px solid #e8f5d0; margin-top: 0.25rem; }
</style>

</x-filament-panels::page>