<x-filament-panels::page>
<div class="gf-wrap">

    {{-- FILTRO PERÍODO --}}
    <div class="gf-filtros">
        <div class="gf-filtro-field">
            <label>Desde</label>
            <input type="date" wire:model.live="filtroDesde" class="gf-input">
        </div>
        <div class="gf-filtro-field">
            <label>Hasta</label>
            <input type="date" wire:model.live="filtroHasta" class="gf-input">
        </div>
        <a href="{{ route('admin.export.financiero', ['desde' => $filtroDesde, 'hasta' => $filtroHasta, 'formato' => 'csv']) }}"
           target="_blank" class="gf-btn-export gf-btn-csv">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Exportar CSV
        </a>
        <a href="{{ route('admin.export.financiero', ['desde' => $filtroDesde, 'hasta' => $filtroHasta, 'formato' => 'pdf']) }}"
           target="_blank" class="gf-btn-export gf-btn-pdf">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            Imprimir PDF
        </a>
    </div>

    @php $r = $this->resumen; @endphp

    {{-- TARJETAS RESUMEN --}}
    <div class="gf-stats-grid">
        <div class="gf-stat-card gf-stat-green">
            <p class="gf-stat-label">Ventas brutas procesadas</p>
            <p class="gf-stat-val">${{ number_format($r['ventasBrutas'], 2) }}</p>
            <p class="gf-stat-hint">Total de productos vendidos en el período</p>
        </div>
        <div class="gf-stat-card gf-stat-yellow">
            <p class="gf-stat-label">Comisiones generadas</p>
            <p class="gf-stat-val">${{ number_format($r['comisionesGeneradas'], 2) }}</p>
            <p class="gf-stat-hint">Ingresos de la plataforma en el período</p>
        </div>
        <div class="gf-stat-card gf-stat-orange">
            <p class="gf-stat-label">Comisiones por recuperar</p>
            <p class="gf-stat-val">${{ number_format($r['deudasPendientes'], 2) }}</p>
            <p class="gf-stat-hint">Deudas pendientes de repartidores (efectivo)</p>
        </div>
        <div class="gf-stat-card">
            <p class="gf-stat-label">Por pagar a tiendas</p>
            <p class="gf-stat-val">${{ number_format($r['liqPendienteTiendas'], 2) }}</p>
            <p class="gf-stat-hint">Liquidaciones pendientes de tiendas</p>
        </div>
        <div class="gf-stat-card">
            <p class="gf-stat-label">Por pagar a repartidores</p>
            <p class="gf-stat-val">${{ number_format($r['liqPendienteRepartidores'], 2) }}</p>
            <p class="gf-stat-hint">Liquidaciones pendientes de repartidores</p>
        </div>
        <div class="gf-stat-card gf-stat-blue">
            <p class="gf-stat-label">Total liquidado en período</p>
            <p class="gf-stat-val">${{ number_format($r['liqPagadasTotal'], 2) }}</p>
            <p class="gf-stat-hint">Liquidaciones pagadas en el período seleccionado</p>
        </div>
    </div>

    {{-- GRÁFICA MENSUAL --}}
    @php $chart = $this->chartData; @endphp
    @if (count($chart['labels']) > 0)
        <div class="gf-chart-card">
            <p class="gf-section-title">Tendencia mensual (últimos 6 meses)</p>
            <canvas id="gf-chart" style="max-height:280px;"></canvas>
        </div>
    @endif

    <div class="gf-two-col">

        {{-- LIQUIDACIONES PENDIENTES --}}
        <div class="gf-card">
            <p class="gf-section-title">Liquidaciones pendientes ({{ $this->liquidacionesPendientes->count() }})</p>
            @forelse ($this->liquidacionesPendientes as $liq)
                @php
                    $nombre = $liq->liq_tipo === 'tienda'
                        ? ($liq->tienda?->tie_nombre ?? '—')
                        : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
                @endphp
                <div class="gf-liq-row">
                    <div class="gf-liq-tipo gf-tipo-{{ $liq->liq_tipo }}">{{ ucfirst($liq->liq_tipo) }}</div>
                    <span class="gf-liq-nombre">{{ $nombre }}</span>
                    <span class="gf-liq-periodo">{{ $liq->liq_periodo_fin->format('d/m/Y') }}</span>
                    <span class="gf-liq-monto">${{ number_format($liq->liq_monto, 2) }}</span>
                </div>
            @empty
                <p class="gf-empty">No hay liquidaciones pendientes.</p>
            @endforelse
        </div>

        {{-- SALDOS PENDIENTES POR WALLET --}}
        <div class="gf-card">
            <p class="gf-section-title">Saldos por cobrar a plataforma</p>
            @forelse ($this->walletsResumen as $w)
                @php
                    $nombre = $w->wal_tipo === 'tienda'
                        ? ($w->tienda?->tie_nombre ?? '—')
                        : trim(($w->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($w->repartidor?->user?->persona?->per_paterno ?? ''));
                @endphp
                <div class="gf-liq-row">
                    <div class="gf-liq-tipo gf-tipo-{{ $w->wal_tipo }}">{{ ucfirst($w->wal_tipo) }}</div>
                    <span class="gf-liq-nombre">{{ $nombre }}</span>
                    <span class="gf-liq-monto">${{ number_format($w->wal_saldo_pendiente, 2) }}</span>
                </div>
            @empty
                <p class="gf-empty">Sin saldos pendientes.</p>
            @endforelse
        </div>

    </div>

    {{-- LIQUIDACIONES PAGADAS EN PERÍODO --}}
    @if ($this->liquidacionesPagadas->count() > 0)
        <div class="gf-card">
            <p class="gf-section-title">Liquidaciones pagadas en el período ({{ $this->liquidacionesPagadas->count() }})</p>
            <div class="gf-table-wrap">
                <table class="gf-table">
                    <thead>
                        <tr>
                            <th>Tipo</th><th>Beneficiario</th><th>Período</th><th>Monto</th><th>Fecha pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->liquidacionesPagadas as $liq)
                            @php
                                $nombre = $liq->liq_tipo === 'tienda'
                                    ? ($liq->tienda?->tie_nombre ?? '—')
                                    : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
                            @endphp
                            <tr>
                                <td><span class="gf-liq-tipo gf-tipo-{{ $liq->liq_tipo }}">{{ ucfirst($liq->liq_tipo) }}</span></td>
                                <td>{{ $nombre }}</td>
                                <td>{{ $liq->liq_periodo_inicio->format('d/m/Y') }} — {{ $liq->liq_periodo_fin->format('d/m/Y') }}</td>
                                <td style="font-weight:800;">${{ number_format($liq->liq_monto, 2) }}</td>
                                <td>{{ $liq->liq_fecha_pago?->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@if (count($chart['labels']) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('gf-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chart['labels']),
            datasets: [
                {
                    label: 'Ventas brutas',
                    data: @json($chart['ventas']),
                    backgroundColor: 'rgba(168,223,17,.6)',
                    borderColor: '#7cc10a',
                    borderWidth: 1.5,
                    borderRadius: 6,
                },
                {
                    label: 'Comisiones',
                    data: @json($chart['comisiones']),
                    backgroundColor: 'rgba(239,68,68,.5)',
                    borderColor: '#ef4444',
                    borderWidth: 1.5,
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } }
            }
        }
    });
});
</script>
@endif

<style>
    .gf-wrap { font-family:'Sora',sans-serif; display:flex; flex-direction:column; gap:1.25rem; }

    .gf-filtros { display:flex; gap:.75rem; align-items:flex-end; flex-wrap:wrap; background:#fff; border:1.5px solid #e8f5d0; border-radius:12px; padding:1rem 1.25rem; }
    .gf-filtro-field { display:flex; flex-direction:column; gap:.3rem; }
    .gf-filtro-field label { font-size:.68rem; font-weight:700; color:#7ab80e; text-transform:uppercase; letter-spacing:.07em; }
    .gf-input { padding:.5rem .75rem; border:1.5px solid #d1d5db; border-radius:8px; font-size:.82rem; background:#f8fdf0; }
    .gf-input:focus { outline:none; border-color:#a8df11; }
    .gf-btn-export { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem .95rem; border-radius:8px; font-size:.78rem; font-weight:700; text-decoration:none; cursor:pointer; }
    .gf-btn-csv { background:#f0fde0; border:1.5px solid #a8df11; color:#4a8a06; }
    .gf-btn-pdf { background:#eff6ff; border:1.5px solid #93c5fd; color:#1d4ed8; }
    .gf-btn-export svg { width:14px; height:14px; }

    .gf-stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
    @media(max-width:900px){ .gf-stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:600px){ .gf-stats-grid { grid-template-columns:1fr; } }
    .gf-stat-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:12px; padding:1rem 1.25rem; }
    .gf-stat-green  { background:#f0fde0; border-color:#a8df11; }
    .gf-stat-yellow { background:#fefce8; border-color:#fde047; }
    .gf-stat-orange { background:#fff7ed; border-color:#fed7aa; }
    .gf-stat-blue   { background:#eff6ff; border-color:#93c5fd; }
    .gf-stat-label { font-size:.62rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#7ab80e; margin-bottom:.35rem; }
    .gf-stat-yellow .gf-stat-label { color:#a16207; }
    .gf-stat-orange .gf-stat-label { color:#b45309; }
    .gf-stat-blue   .gf-stat-label { color:#1d4ed8; }
    .gf-stat-val  { font-size:1.4rem; font-weight:900; color:#1a1a1a; }
    .gf-stat-hint { font-size:.68rem; color:#aaa; margin-top:.2rem; }

    .gf-chart-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:12px; padding:1.25rem 1.5rem; }

    .gf-two-col { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
    @media(max-width:768px){ .gf-two-col { grid-template-columns:1fr; } }

    .gf-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:12px; padding:1.1rem 1.25rem; }
    .gf-section-title { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#7ab80e; margin-bottom:.85rem; }

    .gf-liq-row { display:flex; align-items:center; gap:.65rem; padding:.5rem 0; border-bottom:1px solid #f5f5f5; font-size:.82rem; }
    .gf-liq-row:last-child { border-bottom:none; }
    .gf-liq-tipo { font-size:.65rem; font-weight:700; padding:.15rem .55rem; border-radius:999px; flex-shrink:0; }
    .gf-tipo-tienda     { background:#f0fde0; color:#4a8a06; }
    .gf-tipo-repartidor { background:#eff6ff; color:#2563eb; }
    .gf-liq-nombre  { flex:1; color:#333; font-weight:600; }
    .gf-liq-periodo { color:#aaa; font-size:.72rem; flex-shrink:0; }
    .gf-liq-monto   { font-weight:800; color:#1a1a1a; flex-shrink:0; min-width:5rem; text-align:right; }

    .gf-table-wrap { overflow-x:auto; }
    .gf-table { width:100%; border-collapse:collapse; font-size:.82rem; }
    .gf-table th { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#aaa; padding:.5rem .75rem; border-bottom:2px solid #e8f5d0; text-align:left; }
    .gf-table td { padding:.55rem .75rem; border-bottom:1px solid #f5f5f5; color:#333; }
    .gf-table tr:last-child td { border-bottom:none; }

    .gf-empty { font-size:.82rem; color:#aaa; padding:.5rem 0; }
</style>
</x-filament-panels::page>
