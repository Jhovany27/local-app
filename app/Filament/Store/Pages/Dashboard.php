<?php

namespace App\Filament\Store\Pages;

use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Venta;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class Dashboard extends Page
{
    protected string $view = 'filament.store.pages.dashboard';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = 1;

    public int $tiendaId;

    public float $ventasHoy        = 0;
    public float $ventasMes        = 0;
    public int   $pedidosPendientes = 0;
    public int   $pedidosEnPrep     = 0;
    public int   $stockBajoCount    = 0;

    public array $ventasSemana     = [];
    public array $ventasPorHora    = [];

    public $alertasStock;
    public $pedidosActivos;

    public function mount(): void
    {
        $this->tiendaId = (int) session('store_tienda_id');
        abort_unless($this->tiendaId, 403);

        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        // ── Ventas ───────────────────────────────────────────
        $this->ventasHoy = Venta::where('ven_fk_tienda', $this->tiendaId)
            ->whereDate('ven_fecha', $hoy)
            ->sum('ven_total');

        $this->ventasMes = Venta::where('ven_fk_tienda', $this->tiendaId)
            ->where('ven_fecha', '>=', $inicioMes)
            ->sum('ven_total');

        // ── Pedidos ──────────────────────────────────────────
        $this->pedidosPendientes = Pedido::where('ped_fk_tienda', $this->tiendaId)
            ->where('ped_estado', 'pendiente')
            ->count();

        $this->pedidosEnPrep = Pedido::where('ped_fk_tienda', $this->tiendaId)
            ->where('ped_estado', 'en_preparacion')
            ->count();

        // ── Stock bajo ───────────────────────────────────────
        $this->stockBajoCount = Inventario::whereHas(
            'producto',
            fn($q) => $q->where('pro_fk_tienda', $this->tiendaId)
        )->whereColumn('inv_stock_actual', '<=', 'inv_stock_minimo')->count();

        // ── Gráfica: ventas últimos 7 días ───────────────────
        $ventas7dias = Venta::where('ven_fk_tienda', $this->tiendaId)
            ->whereDate('ven_fecha', '>=', $hoy->copy()->subDays(6))
            ->selectRaw('DATE(ven_fecha) as fecha, SUM(ven_total) as total')
            ->groupBy('fecha')
            ->pluck('total', 'fecha');

        $this->ventasSemana = collect(range(6, 0))->map(function ($i) use ($hoy, $ventas7dias) {
            $dia = $hoy->copy()->subDays($i);
            return [
                'label' => $dia->locale('es')->isoFormat('ddd D'),
                'total' => (float) ($ventas7dias[$dia->toDateString()] ?? 0),
            ];
        })->values()->toArray();

        // ── Gráfica: pedidos de hoy por hora ─────────────────
        $pedidosHora = Pedido::where('ped_fk_tienda', $this->tiendaId)
            ->whereDate('ped_fecha_pedido', $hoy)
            ->selectRaw('HOUR(ped_fecha_pedido) as hora, COUNT(*) as total')
            ->groupBy('hora')
            ->pluck('total', 'hora');

        $this->ventasPorHora = collect(range(0, 23))->map(fn($h) => [
            'label' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
            'total' => (int) ($pedidosHora[$h] ?? 0),
        ])->values()->toArray();

        // ── Alertas de stock (con detalle) ───────────────────
        $this->alertasStock = Inventario::with('producto')
            ->whereHas('producto', fn($q) => $q->where('pro_fk_tienda', $this->tiendaId))
            ->whereColumn('inv_stock_actual', '<=', 'inv_stock_minimo')
            ->orderBy('inv_stock_actual')
            ->get();

        // ── Pedidos activos (pendiente + en preparación) ─────
        $this->pedidosActivos = Pedido::with(['detalles', 'cliente.user'])
            ->where('ped_fk_tienda', $this->tiendaId)
            ->whereIn('ped_estado', ['pendiente', 'en_preparacion'])
            ->latest('ped_fecha_pedido')
            ->take(8)
            ->get();
    }
}
