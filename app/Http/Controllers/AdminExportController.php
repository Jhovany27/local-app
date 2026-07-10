<?php

namespace App\Http\Controllers;

use App\Models\DeudaRepartidor;
use App\Models\Liquidacion;
use App\Models\MovimientoWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminExportController extends Controller
{
    public function financiero(Request $request)
    {
        // Solo admin
        abort_unless(Auth::check() && Auth::user()->hasRol('admin'), 403);

        $desde = Carbon::parse($request->desde ?? now()->startOfMonth())->startOfDay();
        $hasta = Carbon::parse($request->hasta ?? now())->endOfDay();
        $formato = $request->formato ?? 'csv';

        // ── Construir datos ─────────────────────────────────
        $ventas = MovimientoWallet::where('mwl_tipo', 'venta')
            ->whereBetween('mwl_fecha', [$desde, $hasta])->sum('mwl_monto');

        $comisiones = MovimientoWallet::where('mwl_tipo', 'comision')
            ->whereBetween('mwl_fecha', [$desde, $hasta])->sum('mwl_monto');

        $deudasPendientes = DeudaRepartidor::where('dre_estado', 'pendiente')->sum('dre_monto');

        $liqPagadas = Liquidacion::with(['tienda', 'repartidor.user.persona'])
            ->where('liq_estado', 'pagada')
            ->whereBetween('liq_fecha_pago', [$desde, $hasta])
            ->orderBy('liq_fecha_pago')
            ->get();

        $liqPendientes = Liquidacion::with(['tienda', 'repartidor.user.persona'])
            ->where('liq_estado', 'pendiente')
            ->orderBy('liq_fecha_creacion')
            ->get();

        if ($formato === 'csv') {
            return $this->exportCsv($desde, $hasta, $ventas, $comisiones, $deudasPendientes, $liqPagadas, $liqPendientes);
        }

        return $this->exportPdf($desde, $hasta, $ventas, $comisiones, $deudasPendientes, $liqPagadas, $liqPendientes);
    }

    private function exportCsv($desde, $hasta, $ventas, $comisiones, $deudasPendientes, $liqPagadas, $liqPendientes)
    {
        $filename = 'reporte-financiero-' . $desde->format('Y-m-d') . '-al-' . $hasta->format('Y-m-d') . '.csv';

        $lines = [];
        $lines[] = ['Reporte financiero LocalApp'];
        $lines[] = ['Período:', $desde->format('d/m/Y'), 'al', $hasta->format('d/m/Y')];
        $lines[] = [];
        $lines[] = ['=== RESUMEN ==='];
        $lines[] = ['Ventas brutas procesadas:', number_format($ventas, 2)];
        $lines[] = ['Comisiones generadas:', number_format($comisiones, 2)];
        $lines[] = ['Deudas repartidores (pendientes):', number_format($deudasPendientes, 2)];
        $lines[] = ['Total liquidaciones pendientes:', number_format($liqPendientes->sum('liq_monto'), 2)];
        $lines[] = ['Total liquidado en período:', number_format($liqPagadas->sum('liq_monto'), 2)];
        $lines[] = [];
        $lines[] = ['=== LIQUIDACIONES PAGADAS ==='];
        $lines[] = ['Tipo', 'Beneficiario', 'Período inicio', 'Período fin', 'Monto', 'Fecha pago'];
        foreach ($liqPagadas as $liq) {
            $nombre = $liq->liq_tipo === 'tienda'
                ? ($liq->tienda?->tie_nombre ?? '—')
                : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
            $lines[] = [
                ucfirst($liq->liq_tipo),
                $nombre,
                $liq->liq_periodo_inicio->format('d/m/Y'),
                $liq->liq_periodo_fin->format('d/m/Y'),
                number_format($liq->liq_monto, 2),
                $liq->liq_fecha_pago?->format('d/m/Y'),
            ];
        }
        $lines[] = [];
        $lines[] = ['=== LIQUIDACIONES PENDIENTES ==='];
        $lines[] = ['Tipo', 'Beneficiario', 'Período fin', 'Monto'];
        foreach ($liqPendientes as $liq) {
            $nombre = $liq->liq_tipo === 'tienda'
                ? ($liq->tienda?->tie_nombre ?? '—')
                : trim(($liq->repartidor?->user?->persona?->per_nombre ?? '') . ' ' . ($liq->repartidor?->user?->persona?->per_paterno ?? ''));
            $lines[] = [ucfirst($liq->liq_tipo), $nombre, $liq->liq_periodo_fin->format('d/m/Y'), number_format($liq->liq_monto, 2)];
        }

        $csv = implode("\n", array_map(fn($row) => implode(',', array_map(fn($cell) => '"' . str_replace('"', '""', $cell) . '"', $row)), $lines));

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function exportPdf($desde, $hasta, $ventas, $comisiones, $deudasPendientes, $liqPagadas, $liqPendientes)
    {
        return view('admin.export.financiero-pdf', compact(
            'desde', 'hasta', 'ventas', 'comisiones',
            'deudasPendientes', 'liqPagadas', 'liqPendientes'
        ));
    }
}
