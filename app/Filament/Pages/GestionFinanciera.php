<?php

namespace App\Filament\Pages;

use App\Models\DeudaRepartidor;
use App\Models\Liquidacion;
use App\Models\MovimientoWallet;
use App\Models\Wallet;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class GestionFinanciera extends Page
{
    protected string $view = 'filament.pages.gestion-financiera';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|\UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?string $navigationLabel = 'Gestión financiera';

    protected static ?string $title = 'Gestión financiera';

    protected static ?int $navigationSort = 30;

    public string $filtroDesde = '';
    public string $filtroHasta = '';

    public function mount(): void
    {
        $this->filtroDesde = now()->startOfMonth()->toDateString();
        $this->filtroHasta = now()->toDateString();
    }

    public function getResumenProperty(): array
    {
        $desde = Carbon::parse($this->filtroDesde)->startOfDay();
        $hasta = Carbon::parse($this->filtroHasta)->endOfDay();

        $comisionesGeneradas = MovimientoWallet::where('mwl_tipo', 'comision')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->sum('mwl_monto');

        $ventasBrutas = MovimientoWallet::where('mwl_tipo', 'venta')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->sum('mwl_monto');

        $deudasPendientes = DeudaRepartidor::where('dre_estado', 'pendiente')
            ->sum('dre_monto');

        $liqPendienteTiendas = Liquidacion::where('liq_tipo', 'tienda')
            ->where('liq_estado', 'pendiente')
            ->sum('liq_monto');

        $liqPendienteRepartidores = Liquidacion::where('liq_tipo', 'repartidor')
            ->where('liq_estado', 'pendiente')
            ->sum('liq_monto');

        $liqPagadasTotal = Liquidacion::where('liq_estado', 'pagada')
            ->whereBetween('liq_fecha_pago', [$desde, $hasta])
            ->sum('liq_monto');

        return compact(
            'comisionesGeneradas', 'ventasBrutas',
            'deudasPendientes',
            'liqPendienteTiendas', 'liqPendienteRepartidores',
            'liqPagadasTotal'
        );
    }

    public function getLiquidacionesPendientesProperty()
    {
        return Liquidacion::with(['tienda', 'repartidor.user.persona'])
            ->where('liq_estado', 'pendiente')
            ->orderBy('liq_fecha_creacion', 'desc')
            ->get();
    }

    public function getLiquidacionesPagadasProperty()
    {
        $desde = Carbon::parse($this->filtroDesde)->startOfDay();
        $hasta = Carbon::parse($this->filtroHasta)->endOfDay();

        return Liquidacion::with(['tienda', 'repartidor.user.persona'])
            ->where('liq_estado', 'pagada')
            ->whereBetween('liq_fecha_pago', [$desde, $hasta])
            ->orderBy('liq_fecha_pago', 'desc')
            ->limit(30)
            ->get();
    }

    // Datos para gráfica mensual (últimos 6 meses) — SQL puro para no cargar en PHP
    public function getChartDataProperty(): array
    {
        $rows = DB::select("
            SELECT
                DATE_FORMAT(mwl_fecha, '%Y-%m') AS mes,
                SUM(CASE WHEN mwl_tipo = 'venta'    THEN mwl_monto ELSE 0 END) AS ventas,
                SUM(CASE WHEN mwl_tipo = 'comision' THEN mwl_monto ELSE 0 END) AS comisiones
            FROM movimiento_wallet
            WHERE mwl_fecha >= ?
            GROUP BY mes
            ORDER BY mes
        ", [now()->subMonths(6)->startOfMonth()]);

        return [
            'labels'     => array_column($rows, 'mes'),
            'ventas'     => array_map(fn($r) => (float) $r->ventas,     $rows),
            'comisiones' => array_map(fn($r) => (float) $r->comisiones, $rows),
        ];
    }

    public function getWalletsResumenProperty()
    {
        return Wallet::with(['tienda', 'repartidor.user.persona'])
            ->where('wal_saldo_pendiente', '>', 0)
            ->orderBy('wal_saldo_pendiente', 'desc')
            ->limit(20)
            ->get();
    }
}
