<x-filament-panels::page>
<div class="ms-wrap">

    @php
        $w   = $this->wallet;
        $res = $this->resumenPeriodo;
        $esHoy = $filtroDesde === $filtroHasta && $filtroDesde === now()->toDateString();
        $labelPeriodo = $filtroDesde === $filtroHasta
            ? \Carbon\Carbon::parse($filtroDesde)->isoFormat('D [de] MMMM')
            : \Carbon\Carbon::parse($filtroDesde)->format('d/m/Y') . ' — ' . \Carbon\Carbon::parse($filtroHasta)->format('d/m/Y');
    @endphp

    {{-- ── FILTRO DE FECHAS ────────────────────────────── --}}
    <div class="ms-filtro-card">
        <div class="ms-filtro-atajos">
            <button wire:click="setHoy"    class="ms-atajo {{ $esHoy ? 'ms-atajo-active' : '' }}">Hoy</button>
            <button wire:click="setSemana" class="ms-atajo {{ !$esHoy && $filtroDesde === now()->startOfWeek()->toDateString() ? 'ms-atajo-active' : '' }}">Esta semana</button>
            <button wire:click="setMes"    class="ms-atajo {{ !$esHoy && $filtroDesde === now()->startOfMonth()->toDateString() ? 'ms-atajo-active' : '' }}">Este mes</button>
        </div>
        <div class="ms-filtro-fechas">
            <div class="ms-filtro-field">
                <label>Desde</label>
                <input type="date" wire:model.live="filtroDesde" class="ms-date-input" max="{{ now()->toDateString() }}">
            </div>
            <div class="ms-filtro-sep">→</div>
            <div class="ms-filtro-field">
                <label>Hasta</label>
                <input type="date" wire:model.live="filtroHasta" class="ms-date-input" max="{{ now()->toDateString() }}">
            </div>
        </div>
    </div>

    {{-- ── RESUMEN DEL PERÍODO ─────────────────────────── --}}
    <div class="ms-periodo-header">
        <span class="ms-periodo-kicker">{{ $esHoy ? 'Hoy' : 'Período seleccionado' }}</span>
        <span class="ms-periodo-label">{{ $labelPeriodo }}</span>
    </div>

    <div class="ms-stats-grid">
        <div class="ms-stat-card ms-stat-green">
            <p class="ms-stat-label">Ventas del período</p>
            <p class="ms-stat-val">${{ number_format($res['ventas'], 2) }}</p>
            <p class="ms-stat-hint">{{ $res['num_pedidos'] }} pedido{{ $res['num_pedidos'] !== 1 ? 's' : '' }}</p>
        </div>
        <div class="ms-stat-card ms-stat-red">
            <p class="ms-stat-label">Comisiones descontadas</p>
            <p class="ms-stat-val">${{ number_format($res['comisiones'], 2) }}</p>
            <p class="ms-stat-hint">Retenidas por la plataforma</p>
        </div>
        <div class="ms-stat-card ms-stat-blue">
            <p class="ms-stat-label">Ganancias netas</p>
            <p class="ms-stat-val">${{ number_format($res['neto'], 2) }}</p>
            <p class="ms-stat-hint">Ventas − comisiones</p>
        </div>
        @if ($res['liquidaciones'] > 0)
        <div class="ms-stat-card">
            <p class="ms-stat-label">Liquidaciones recibidas</p>
            <p class="ms-stat-val">${{ number_format($res['liquidaciones'], 2) }}</p>
            <p class="ms-stat-hint">Pagadas en el período</p>
        </div>
        @endif
    </div>

    {{-- ── SALDO GLOBAL (siempre visible) ─────────────── --}}
    @if ($w)
    <div class="ms-global-row">
        <div class="ms-global-item">
            <p class="ms-global-label">Saldo pendiente de cobro</p>
            <p class="ms-global-val {{ $w->wal_saldo_pendiente > 0 ? 'ms-val-green' : '' }}">${{ number_format($w->wal_saldo_pendiente, 2) }}</p>
        </div>
        <div class="ms-global-div"></div>
        <div class="ms-global-item">
            <p class="ms-global-label">Total ventas acumuladas</p>
            <p class="ms-global-val">${{ number_format($w->wal_total_ventas, 2) }}</p>
        </div>
        <div class="ms-global-div"></div>
        <div class="ms-global-item">
            <p class="ms-global-label">Total liquidado</p>
            <p class="ms-global-val">${{ number_format($w->wal_total_liquidado, 2) }}</p>
        </div>
    </div>
    @endif

    {{-- ── LIQUIDACIÓN PRÓXIMA / ÚLTIMA ────────────────── --}}
    @if ($this->liquidacionesPendientes->count() > 0)
        <div class="ms-liq-banner ms-liq-pendiente">
            <div>
                <p class="ms-liq-title">Liquidación pendiente</p>
                @foreach ($this->liquidacionesPendientes as $liq)
                    <p class="ms-liq-sub">{{ $liq->liq_periodo_inicio->format('d/m/Y') }} — {{ $liq->liq_periodo_fin->format('d/m/Y') }}</p>
                @endforeach
            </div>
            <p class="ms-liq-monto">${{ number_format($this->liquidacionesPendientes->sum('liq_monto'), 2) }}</p>
        </div>
    @elseif ($this->ultimaLiquidacion)
        @php $ul = $this->ultimaLiquidacion; @endphp
        <div class="ms-liq-banner ms-liq-pagada">
            <div>
                <p class="ms-liq-title">Última liquidación recibida</p>
                <p class="ms-liq-sub">{{ $ul->liq_periodo_inicio->format('d/m/Y') }} — {{ $ul->liq_periodo_fin->format('d/m/Y') }} · {{ $ul->liq_fecha_pago->format('d/m/Y') }}</p>
            </div>
            <p class="ms-liq-monto">${{ number_format($ul->liq_monto, 2) }}</p>
        </div>
    @endif

    {{-- ── MOVIMIENTOS DEL PERÍODO ─────────────────────── --}}
    <div class="ms-section">
        <div class="ms-section-header">
            <p class="ms-section-title">Movimientos · {{ $labelPeriodo }}</p>
            <select wire:model.live="filtroTipo" class="ms-select">
                <option value="">Todos</option>
                <option value="venta">Ventas</option>
                <option value="comision">Comisiones</option>
                <option value="liquidacion">Liquidaciones</option>
                <option value="ajuste">Ajustes</option>
            </select>
        </div>

        @forelse ($this->movimientos as $mov)
            <div class="ms-mov-row">
                <div class="ms-mov-badge ms-badge-{{ $mov->mwl_tipo }}">
                    {{ match($mov->mwl_tipo) {
                        'venta'       => 'Venta',
                        'comision'    => 'Comisión',
                        'liquidacion' => 'Liquidación',
                        'ajuste'      => 'Ajuste',
                        default       => ucfirst($mov->mwl_tipo),
                    } }}
                </div>
                <div class="ms-mov-info">
                    <p class="ms-mov-desc">{{ $mov->mwl_descripcion ?: '—' }}</p>
                    <p class="ms-mov-hora">{{ $mov->mwl_fecha->format('H:i') }}</p>
                </div>
                <span class="ms-mov-monto {{ in_array($mov->mwl_tipo, ['comision','liquidacion']) ? 'ms-neg' : 'ms-pos' }}">
                    {{ in_array($mov->mwl_tipo, ['comision']) ? '−' : '+' }}${{ number_format($mov->mwl_monto, 2) }}
                </span>
            </div>
        @empty
            <div class="ms-empty">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <p>Sin movimientos en este período.</p>
            </div>
        @endforelse
    </div>

</div>

<style>
    .ms-wrap { font-family:'Sora',sans-serif; display:flex; flex-direction:column; gap:1.1rem; }

    /* Filtro */
    .ms-filtro-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.85rem; }
    .ms-filtro-atajos { display:flex; gap:.5rem; }
    .ms-atajo { padding:.35rem .85rem; border-radius:999px; border:1.5px solid #d1d5db; background:#fff; font-size:.75rem; font-weight:700; color:#555; cursor:pointer; font-family:'Sora',sans-serif; transition:all .15s; }
    .ms-atajo:hover { border-color:#a8df11; color:#4a8a06; }
    .ms-atajo-active { background:#f0fde0; border-color:#a8df11; color:#4a8a06; }
    .ms-filtro-fechas { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
    .ms-filtro-field { display:flex; flex-direction:column; gap:.2rem; }
    .ms-filtro-field label { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#aaa; }
    .ms-date-input { padding:.45rem .75rem; border:1.5px solid #d1d5db; border-radius:8px; font-size:.82rem; font-family:'Sora',sans-serif; background:#f8fdf0; }
    .ms-date-input:focus { outline:none; border-color:#a8df11; }
    .ms-filtro-sep { font-size:.85rem; color:#ccc; }

    /* Header período */
    .ms-periodo-header { display:flex; align-items:center; gap:.65rem; }
    .ms-periodo-kicker { font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#7ab80e; background:#f0fde0; border:1px solid #d4edaa; border-radius:999px; padding:.2rem .65rem; }
    .ms-periodo-label  { font-size:.88rem; font-weight:700; color:#555; }

    /* Stats */
    .ms-stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.85rem; }
    @media(max-width:900px){ .ms-stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:500px){ .ms-stats-grid { grid-template-columns:1fr; } }
    .ms-stat-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:1rem 1.15rem; }
    .ms-stat-green { background:#f0fde0; border-color:#a8df11; }
    .ms-stat-red   { background:#fff8f8; border-color:#fecaca; }
    .ms-stat-blue  { background:#f0f9ff; border-color:#bae6fd; }
    .ms-stat-label { font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#7ab80e; margin-bottom:.3rem; }
    .ms-stat-red  .ms-stat-label { color:#ef4444; }
    .ms-stat-blue .ms-stat-label { color:#0284c7; }
    .ms-stat-val  { font-size:1.35rem; font-weight:900; color:#1a1a1a; }
    .ms-stat-hint { font-size:.68rem; color:#aaa; margin-top:.2rem; }

    /* Saldo global */
    .ms-global-row { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:.9rem 1.25rem; display:flex; align-items:center; gap:0; }
    .ms-global-item { flex:1; text-align:center; }
    .ms-global-div  { width:1.5px; height:2.5rem; background:#e8f5d0; flex-shrink:0; }
    .ms-global-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#aaa; margin-bottom:.25rem; }
    .ms-global-val   { font-size:1rem; font-weight:900; color:#1a1a1a; }
    .ms-val-green    { color:#4a8a06; }

    /* Banner liquidación */
    .ms-liq-banner { display:flex; justify-content:space-between; align-items:center; border-radius:12px; padding:.85rem 1.15rem; gap:1rem; }
    .ms-liq-pendiente { background:#fefce8; border:1.5px solid #fde047; }
    .ms-liq-pagada    { background:#f0fde0; border:1.5px solid #a8df11; }
    .ms-liq-title { font-size:.8rem; font-weight:800; color:#1a1a1a; }
    .ms-liq-sub   { font-size:.7rem; color:#888; margin-top:.15rem; }
    .ms-liq-monto { font-size:1.05rem; font-weight:900; color:#4a8a06; white-space:nowrap; }

    /* Sección movimientos */
    .ms-section { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:1rem 1.25rem; }
    .ms-section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:.85rem; gap:.75rem; }
    .ms-section-title { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#7ab80e; }
    .ms-select { padding:.4rem .65rem; border:1.5px solid #d1d5db; border-radius:8px; font-size:.75rem; font-family:'Sora',sans-serif; background:#f8fdf0; }

    /* Movimiento */
    .ms-mov-row  { display:flex; align-items:center; gap:.75rem; padding:.65rem 0; border-bottom:1px solid #f5f5f5; }
    .ms-mov-row:last-child { border-bottom:none; }
    .ms-mov-badge { font-size:.62rem; font-weight:700; padding:.18rem .55rem; border-radius:999px; flex-shrink:0; white-space:nowrap; }
    .ms-badge-venta       { background:#f0fde0; color:#4a8a06; }
    .ms-badge-comision    { background:#fff8f8; color:#ef4444; }
    .ms-badge-liquidacion { background:#eff6ff; color:#2563eb; }
    .ms-badge-ajuste      { background:#fef9c3; color:#854d0e; }
    .ms-mov-info { flex:1; min-width:0; }
    .ms-mov-desc { font-size:.8rem; font-weight:600; color:#1a1a1a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .ms-mov-hora { font-size:.65rem; color:#aaa; margin-top:.1rem; }
    .ms-mov-monto { font-size:.88rem; font-weight:800; flex-shrink:0; }
    .ms-pos { color:#4a8a06; }
    .ms-neg { color:#ef4444; }

    /* Empty */
    .ms-empty { display:flex; flex-direction:column; align-items:center; gap:.5rem; padding:2rem; color:#bbb; font-size:.82rem; text-align:center; }
    .ms-empty svg { width:36px; height:36px; color:#d4edaa; }
</style>
</x-filament-panels::page>
