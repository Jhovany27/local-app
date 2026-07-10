<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero — {{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a1a; padding: 2rem; }
        h1 { font-size: 18px; margin-bottom: .25rem; }
        .periodo { color: #888; font-size: 11px; margin-bottom: 1.5rem; }
        .resumen-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat { border: 1px solid #e0e0e0; border-radius: 8px; padding: .75rem 1rem; }
        .stat-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #888; margin-bottom: .25rem; }
        .stat-val { font-size: 16px; font-weight: 900; }
        h2 { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #888; margin: 1.5rem 0 .5rem; border-bottom: 1px solid #e0e0e0; padding-bottom: .35rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #888; border-bottom: 2px solid #e0e0e0; padding: .4rem .5rem; text-align: left; }
        td { padding: .4rem .5rem; border-bottom: 1px solid #f0f0f0; font-size: 11px; }
        .badge { display: inline-block; font-size: 9px; font-weight: 700; padding: .1rem .4rem; border-radius: 999px; }
        .badge-tienda { background: #f0fde0; color: #4a8a06; }
        .badge-rep    { background: #eff6ff; color: #2563eb; }
        .footer { margin-top: 2rem; border-top: 1px solid #e0e0e0; padding-top: .75rem; font-size: 10px; color: #aaa; display: flex; justify-content: space-between; }
        @media print {
            body { padding: 1.5cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:1rem;">
        <button onclick="window.print()" style="padding:.5rem 1.25rem;background:#a8df11;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px;">
            🖨 Imprimir / Guardar como PDF
        </button>
    </div>

    <h1>Reporte Financiero — LocalApp</h1>
    <p class="periodo">Período: {{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }} · Generado: {{ now()->format('d/m/Y H:i') }}</p>

    <div class="resumen-grid">
        <div class="stat">
            <p class="stat-label">Ventas brutas</p>
            <p class="stat-val">${{ number_format($ventas, 2) }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Comisiones generadas</p>
            <p class="stat-val">${{ number_format($comisiones, 2) }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Deudas pendientes repartidores</p>
            <p class="stat-val">${{ number_format($deudasPendientes, 2) }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Liquidaciones pendientes</p>
            <p class="stat-val">${{ number_format($liqPendientes->sum('liq_monto'), 2) }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Total liquidado en período</p>
            <p class="stat-val">${{ number_format($liqPagadas->sum('liq_monto'), 2) }}</p>
        </div>
        <div class="stat">
            <p class="stat-label">Ganancia neta plataforma</p>
            <p class="stat-val">${{ number_format($comisiones - $deudasPendientes, 2) }}</p>
        </div>
    </div>

    @if ($liqPagadas->count() > 0)
        <h2>Liquidaciones pagadas en el período ({{ $liqPagadas->count() }})</h2>
        <table>
            <thead>
                <tr><th>Tipo</th><th>Beneficiario</th><th>Período cubierto</th><th>Monto</th><th>Fecha pago</th></tr>
            </thead>
            <tbody>
                @foreach ($liqPagadas as $liq)
                    @php
                        $nombre = $liq->liq_tipo === 'tienda'
                            ? ($liq->tienda?->tie_nombre ?? '—')
                            : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
                    @endphp
                    <tr>
                        <td><span class="badge {{ $liq->liq_tipo === 'tienda' ? 'badge-tienda' : 'badge-rep' }}">{{ ucfirst($liq->liq_tipo) }}</span></td>
                        <td>{{ $nombre }}</td>
                        <td>{{ $liq->liq_periodo_inicio->format('d/m/Y') }} — {{ $liq->liq_periodo_fin->format('d/m/Y') }}</td>
                        <td><strong>${{ number_format($liq->liq_monto, 2) }}</strong></td>
                        <td>{{ $liq->liq_fecha_pago?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-weight:700;text-align:right;">Total:</td>
                    <td colspan="2" style="font-weight:900;">${{ number_format($liqPagadas->sum('liq_monto'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if ($liqPendientes->count() > 0)
        <h2>Liquidaciones pendientes de pago ({{ $liqPendientes->count() }})</h2>
        <table>
            <thead>
                <tr><th>Tipo</th><th>Beneficiario</th><th>Período fin</th><th>Monto</th></tr>
            </thead>
            <tbody>
                @foreach ($liqPendientes as $liq)
                    @php
                        $nombre = $liq->liq_tipo === 'tienda'
                            ? ($liq->tienda?->tie_nombre ?? '—')
                            : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
                    @endphp
                    <tr>
                        <td><span class="badge {{ $liq->liq_tipo === 'tienda' ? 'badge-tienda' : 'badge-rep' }}">{{ ucfirst($liq->liq_tipo) }}</span></td>
                        <td>{{ $nombre }}</td>
                        <td>{{ $liq->liq_periodo_fin->format('d/m/Y') }}</td>
                        <td><strong>${{ number_format($liq->liq_monto, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <span>LocalApp — Panel Administrativo</span>
        <span>Generado el {{ now()->format('d/m/Y H:i') }}</span>
    </div>
</body>
</html>
