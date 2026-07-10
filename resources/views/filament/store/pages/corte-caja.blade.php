<x-filament-panels::page>

    <div class="cc-wrap">

        {{-- FORMULARIO DE FECHAS --}}
        <div class="cc-card">
            <p class="cc-card-title">Selecciona el período del corte</p>
            <form wire:submit="generar" class="cc-form">
                <div class="cc-form-row">
                    <div class="cc-field">
                        <label for="fecha_inicio">Fecha inicio</label>
                        <input type="date" id="fecha_inicio" wire:model="fecha_inicio">
                        @error('fecha_inicio') <span class="cc-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="cc-field">
                        <label for="fecha_fin">Fecha fin</label>
                        <input type="date" id="fecha_fin" wire:model="fecha_fin">
                        @error('fecha_fin') <span class="cc-error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="cc-btn-generar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" /></svg>
                        Generar corte
                    </button>
                </div>
            </form>
        </div>

        @if ($this->generado)
            @php $corte = $this->corte; @endphp

            @if ($corte && $corte['totalVentas'] > 0)

                {{-- RESUMEN GENERAL --}}
                <div class="cc-summary-grid">
                    <div class="cc-summary-card">
                        <p class="cc-summary-label">Total de ventas</p>
                        <p class="cc-summary-val">{{ $corte['totalVentas'] }}</p>
                    </div>
                    <div class="cc-summary-card cc-summary-card--green">
                        <p class="cc-summary-label">Total ingresado</p>
                        <p class="cc-summary-val">${{ number_format($corte['totalIngresos'], 2) }}</p>
                    </div>
                    <div class="cc-summary-card">
                        <p class="cc-summary-label">Período</p>
                        <p class="cc-summary-val cc-summary-val--sm">
                            {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }}
                            @if ($fecha_inicio !== $fecha_fin)
                                — {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="cc-summary-card">
                        <p class="cc-summary-label">Productos distintos</p>
                        <p class="cc-summary-val">{{ count($corte['productos']) }}</p>
                    </div>
                </div>

                <div class="cc-two-col">

                    {{-- MÉTODOS DE PAGO --}}
                    <div class="cc-card">
                        <p class="cc-card-title">Métodos de pago</p>
                        <div class="cc-metodos">
                            @foreach ($corte['porMetodo'] as $metodo => $total)
                                <div class="cc-metodo-row">
                                    <div class="cc-metodo-info">
                                        <span class="cc-metodo-icon {{ strtolower($metodo) === 'efectivo' ? 'cc-icon-cash' : (strtolower($metodo) === 'tarjeta' ? 'cc-icon-card' : 'cc-icon-other') }}">
                                            @if (strtolower($metodo) === 'efectivo')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg>
                                            @elseif (strtolower($metodo) === 'tarjeta')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
                                            @endif
                                        </span>
                                        <span class="cc-metodo-nombre">{{ $metodo }}</span>
                                    </div>
                                    <div style="text-align:right;">
                                        <p class="cc-metodo-total">${{ number_format($total, 2) }}</p>
                                        <p class="cc-metodo-pct">{{ number_format($corte['totalIngresos'] > 0 ? ($total / $corte['totalIngresos'] * 100) : 0, 1) }}%</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PRODUCTOS MÁS VENDIDOS --}}
                    <div class="cc-card">
                        <p class="cc-card-title">Productos vendidos</p>
                        <div class="cc-prod-list">
                            <div class="cc-prod-header">
                                <span>Producto</span>
                                <span>Cantidad</span>
                                <span>Total</span>
                            </div>
                            @foreach ($corte['productos'] as $nombre => $data)
                                <div class="cc-prod-row">
                                    <span class="cc-prod-nombre">{{ $nombre }}</span>
                                    <span class="cc-prod-qty">×{{ $data['cantidad'] }}</span>
                                    <span class="cc-prod-total">${{ number_format($data['total'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- DETALLE DE VENTAS --}}
                <div class="cc-card">
                    <p class="cc-card-title">Detalle de ventas ({{ $corte['totalVentas'] }})</p>
                    <div class="cc-ventas-table">
                        <div class="cc-vt-header">
                            <span># Venta</span>
                            <span>Fecha</span>
                            <span>Productos</span>
                            <span>Método pago</span>
                            <span>Total</span>
                        </div>
                        @foreach ($corte['ventas'] as $venta)
                            <div class="cc-vt-row">
                                <span class="cc-vt-id">#{{ str_pad($venta->ven_id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="cc-vt-fecha">{{ $venta->ven_fecha->format('d/m/Y H:i') }}</span>
                                <span class="cc-vt-prods">{{ $venta->detalles->count() }} producto(s)</span>
                                <span>
                                    @php $mp = strtolower($venta->pedido?->pago?->pag_metodo_pago ?? ''); @endphp
                                    <span class="cc-badge {{ $mp === 'efectivo' ? 'cc-badge-cash' : ($mp === 'tarjeta' ? 'cc-badge-card' : 'cc-badge-other') }}">
                                        {{ ucfirst($mp ?: '—') }}
                                    </span>
                                </span>
                                <span class="cc-vt-total">${{ number_format($venta->ven_total, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="cc-vt-footer">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span style="font-weight:700;color:#555;">Total</span>
                            <span class="cc-vt-total" style="font-size:1rem;color:#4a8a06;">${{ number_format($corte['totalIngresos'], 2) }}</span>
                        </div>
                    </div>
                </div>

            @else
                <div class="cc-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <p>No hay ventas completadas en el período seleccionado.</p>
                </div>
            @endif
        @endif

    </div>

    <style>
        .cc-wrap { font-family: 'Sora', sans-serif; display: flex; flex-direction: column; gap: 1.25rem; }

        /* Card base */
        .cc-card {
            background: #fff; border: 1.5px solid #e8f5d0;
            border-radius: 14px; padding: 1.25rem 1.5rem;
        }
        .cc-card-title {
            font-size: .65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: .1em; color: #7ab80e; margin-bottom: 1rem;
        }

        /* Formulario */
        .cc-form-row { display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; }
        .cc-field { display: flex; flex-direction: column; gap: .3rem; }
        .cc-field label { font-size: .72rem; font-weight: 700; color: #555; }
        .cc-field input[type=date] {
            padding: .55rem .85rem; border: 1.5px solid #d1d5db; border-radius: 8px;
            font-size: .85rem; font-family: 'Sora', sans-serif; background: #f8fdf0;
        }
        .cc-field input[type=date]:focus { outline: none; border-color: #a8df11; }
        .cc-error { font-size: .7rem; color: #d41b11; }
        .cc-btn-generar {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .6rem 1.25rem; background: linear-gradient(135deg, #a8df11, #7cc10a);
            border: none; border-radius: 9px; font-family: 'Sora', sans-serif;
            font-size: .85rem; font-weight: 800; color: #1a1a1a; cursor: pointer;
            box-shadow: 0 4px 14px rgba(168,223,17,.3); transition: opacity .15s;
        }
        .cc-btn-generar:hover { opacity: .9; }
        .cc-btn-generar svg { width: 16px; height: 16px; }

        /* Summary grid */
        .cc-summary-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;
        }
        @media (max-width: 900px) { .cc-summary-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 500px) { .cc-summary-grid { grid-template-columns: 1fr; } }
        .cc-summary-card {
            background: #fff; border: 1.5px solid #e8f5d0; border-radius: 12px;
            padding: 1rem 1.25rem;
        }
        .cc-summary-card--green { background: #f0fde0; border-color: #a8df11; }
        .cc-summary-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #7ab80e; margin-bottom: .35rem; }
        .cc-summary-val { font-size: 1.4rem; font-weight: 900; color: #1a1a1a; }
        .cc-summary-val--sm { font-size: .95rem; }

        /* Two col */
        .cc-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media (max-width: 768px) { .cc-two-col { grid-template-columns: 1fr; } }

        /* Métodos de pago */
        .cc-metodos { display: flex; flex-direction: column; gap: .75rem; }
        .cc-metodo-row { display: flex; align-items: center; justify-content: space-between; padding: .75rem; background: #f8fdf0; border: 1px solid #e8f5d0; border-radius: 10px; }
        .cc-metodo-info { display: flex; align-items: center; gap: .65rem; }
        .cc-metodo-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cc-metodo-icon svg { width: 17px; height: 17px; }
        .cc-icon-cash  { background: #f0fde0; color: #4a8a06; }
        .cc-icon-card  { background: #eff6ff; color: #1d4ed8; }
        .cc-icon-other { background: #f5f5f5; color: #888; }
        .cc-metodo-nombre { font-size: .85rem; font-weight: 700; color: #111; }
        .cc-metodo-total { font-size: .95rem; font-weight: 800; color: #1a1a1a; }
        .cc-metodo-pct  { font-size: .7rem; color: #aaa; text-align: right; }

        /* Productos */
        .cc-prod-list { display: flex; flex-direction: column; gap: 0; }
        .cc-prod-header { display: grid; grid-template-columns: 1fr auto auto; gap: 1rem; padding: .4rem .6rem; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; color: #aaa; }
        .cc-prod-row { display: grid; grid-template-columns: 1fr auto auto; gap: 1rem; padding: .55rem .6rem; border-top: 1px solid #f0f0f0; align-items: center; }
        .cc-prod-row:hover { background: #f8fdf0; border-radius: 8px; }
        .cc-prod-nombre { font-size: .83rem; font-weight: 600; color: #1a1a1a; }
        .cc-prod-qty    { font-size: .8rem; font-weight: 700; color: #6aab0f; min-width: 2.5rem; text-align: center; }
        .cc-prod-total  { font-size: .83rem; font-weight: 700; color: #333; min-width: 5rem; text-align: right; }

        /* Tabla detalle ventas */
        .cc-ventas-table { overflow-x: auto; }
        .cc-vt-header, .cc-vt-row, .cc-vt-footer {
            display: grid; grid-template-columns: 80px 1fr 1fr 1fr 100px;
            gap: .75rem; padding: .55rem .5rem; align-items: center;
        }
        .cc-vt-header { font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; color: #aaa; border-bottom: 1.5px solid #e8f5d0; }
        .cc-vt-row { border-bottom: 1px solid #f5f5f5; font-size: .82rem; }
        .cc-vt-row:hover { background: #f8fdf0; border-radius: 8px; }
        .cc-vt-footer { border-top: 2px solid #e8f5d0; font-size: .85rem; }
        .cc-vt-id    { font-weight: 700; color: #7ab80e; }
        .cc-vt-fecha { color: #555; }
        .cc-vt-prods { color: #888; }
        .cc-vt-total { font-weight: 800; color: #1a1a1a; text-align: right; }

        /* Badges */
        .cc-badge { font-size: .68rem; font-weight: 700; padding: .2rem .6rem; border-radius: 999px; }
        .cc-badge-cash  { background: #f0fde0; color: #4a8a06; }
        .cc-badge-card  { background: #eff6ff; color: #1d4ed8; }
        .cc-badge-other { background: #f5f5f5; color: #888; }

        /* Empty */
        .cc-empty { display: flex; flex-direction: column; align-items: center; gap: .65rem; padding: 3rem; color: #bbb; font-size: .85rem; }
        .cc-empty svg { width: 40px; height: 40px; color: #d4edaa; }
    </style>

</x-filament-panels::page>
