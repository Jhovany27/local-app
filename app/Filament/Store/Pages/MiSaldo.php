<?php

namespace App\Filament\Store\Pages;

use App\Models\Liquidacion;
use App\Models\MovimientoWallet;
use App\Models\Wallet;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MiSaldo extends Page
{
    protected string $view = 'filament.store.pages.mi-saldo';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Mi saldo';

    protected static ?string $title = 'Mi saldo';

    protected static ?int $navigationSort = 8;

    public string $filtroDesde = '';
    public string $filtroHasta = '';
    public string $filtroTipo  = '';

    public function mount(): void
    {
        // Por defecto: el día de hoy
        $this->filtroDesde = now()->toDateString();
        $this->filtroHasta = now()->toDateString();
    }

    private function getTiendaId(): int
    {
        return (int) session('store_tienda_id');
    }

    private function validaTienda(): bool
    {
        $tiendaId = $this->getTiendaId();
        if (! $tiendaId) return false;
        return Auth::user()->tiendas()->where('tie_id', $tiendaId)->exists();
    }

    // ── WALLET (totales globales de toda la vida) ──────────
    public function getWalletProperty(): ?Wallet
    {
        if (! $this->validaTienda()) return null;
        return Wallet::where('wal_tipo', 'tienda')
            ->where('wal_fk_tienda', $this->getTiendaId())
            ->first();
    }

    // ── RESUMEN DEL PERÍODO SELECCIONADO ──────────────────
    public function getResumenPeriodoProperty(): array
    {
        $wallet = $this->wallet;
        if (! $wallet) {
            return ['ventas' => 0, 'comisiones' => 0, 'neto' => 0, 'liquidaciones' => 0, 'num_pedidos' => 0];
        }

        $desde = Carbon::parse($this->filtroDesde)->startOfDay();
        $hasta = Carbon::parse($this->filtroHasta)->endOfDay();

        $ventas = MovimientoWallet::where('mwl_fk_wallet', $wallet->wal_id)
            ->where('mwl_tipo', 'venta')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->sum('mwl_monto');

        $comisiones = MovimientoWallet::where('mwl_fk_wallet', $wallet->wal_id)
            ->where('mwl_tipo', 'comision')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->sum('mwl_monto');

        $liquidaciones = MovimientoWallet::where('mwl_fk_wallet', $wallet->wal_id)
            ->where('mwl_tipo', 'liquidacion')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->sum('mwl_monto');

        $numPedidos = MovimientoWallet::where('mwl_fk_wallet', $wallet->wal_id)
            ->where('mwl_tipo', 'venta')
            ->whereBetween('mwl_fecha', [$desde, $hasta])
            ->whereNotNull('mwl_fk_pedido')
            ->distinct('mwl_fk_pedido')
            ->count('mwl_fk_pedido');

        return [
            'ventas'       => (float) $ventas,
            'comisiones'   => (float) $comisiones,
            'neto'         => (float) ($ventas - $comisiones),
            'liquidaciones'=> (float) $liquidaciones,
            'num_pedidos'  => $numPedidos,
        ];
    }

    // ── MOVIMIENTOS DEL PERÍODO ────────────────────────────
    public function getMovimientosProperty()
    {
        $wallet = $this->wallet;
        if (! $wallet) return collect();

        $desde = Carbon::parse($this->filtroDesde)->startOfDay();
        $hasta = Carbon::parse($this->filtroHasta)->endOfDay();

        $query = $wallet->movimientos()
            ->with('pedido')
            ->whereBetween('mwl_fecha', [$desde, $hasta]);

        if ($this->filtroTipo) {
            $query->where('mwl_tipo', $this->filtroTipo);
        }

        return $query->orderBy('mwl_fecha', 'desc')->limit(100)->get();
    }

    // ── LIQUIDACIONES PENDIENTES ───────────────────────────
    public function getLiquidacionesPendientesProperty()
    {
        if (! $this->validaTienda()) return collect();

        return Liquidacion::where('liq_tipo', 'tienda')
            ->where('liq_fk_tienda', $this->getTiendaId())
            ->where('liq_estado', Liquidacion::ESTADO_PENDIENTE)
            ->orderBy('liq_fecha_creacion', 'desc')
            ->get();
    }

    // ── ÚLTIMA LIQUIDACIÓN PAGADA ──────────────────────────
    public function getUltimaLiquidacionProperty(): ?Liquidacion
    {
        if (! $this->validaTienda()) return null;

        return Liquidacion::where('liq_tipo', 'tienda')
            ->where('liq_fk_tienda', $this->getTiendaId())
            ->where('liq_estado', Liquidacion::ESTADO_PAGADA)
            ->latest('liq_fecha_pago')
            ->first();
    }

    // Atajos de período para los botones rápidos
    public function setHoy(): void
    {
        $this->filtroDesde = now()->toDateString();
        $this->filtroHasta = now()->toDateString();
    }

    public function setSemana(): void
    {
        $this->filtroDesde = now()->startOfWeek()->toDateString();
        $this->filtroHasta = now()->toDateString();
    }

    public function setMes(): void
    {
        $this->filtroDesde = now()->startOfMonth()->toDateString();
        $this->filtroHasta = now()->toDateString();
    }
}
