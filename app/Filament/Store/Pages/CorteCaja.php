<?php

namespace App\Filament\Store\Pages;

use App\Models\Venta;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CorteCaja extends Page
{
    protected string $view = 'filament.store.pages.corte-caja';

    protected static ?string $slug = 'corte-caja';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Corte de caja';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public string $fecha_inicio = '';
    public string $fecha_fin    = '';

    public bool $generado = false;

    public function mount(): void
    {
        $this->fecha_inicio = now()->toDateString();
        $this->fecha_fin    = now()->toDateString();
    }

    private function getTiendaId(): int
    {
        return (int) session('store_tienda_id');
    }

    public function generar(): void
    {
        $this->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['required', 'date', 'gte:fecha_inicio'],
        ], [
            'fecha_fin.gte' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ]);

        $this->generado = true;
    }

    public function getCorteProperty(): ?array
    {
        if (! $this->generado) return null;

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $tiendaId = $this->getTiendaId();

        if (! $user->tiendas()->where('tie_id', $tiendaId)->exists()) return null;

        $ventas = Venta::with(['detalles.producto', 'pedido.pago'])
            ->where('ven_fk_tienda', $tiendaId)
            ->where('ven_estado', Venta::ESTADO_COMPLETADA)
            ->whereDate('ven_fecha', '>=', $this->fecha_inicio)
            ->whereDate('ven_fecha', '<=', $this->fecha_fin)
            ->orderBy('ven_fecha')
            ->get();

        $totalIngresos  = $ventas->sum('ven_total');
        $totalVentas    = $ventas->count();

        // Métodos de pago
        $porMetodo = [];
        foreach ($ventas as $venta) {
            $metodo = ucfirst(strtolower($venta->pedido?->pago?->pag_metodo_pago ?? 'Sin registro'));
            $porMetodo[$metodo] = ($porMetodo[$metodo] ?? 0) + $venta->ven_total;
        }
        arsort($porMetodo);

        // Productos más vendidos
        $productos = [];
        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $det) {
                $nombre = $det->producto?->pro_nombre ?? '(producto eliminado)';
                if (! isset($productos[$nombre])) {
                    $productos[$nombre] = ['cantidad' => 0, 'total' => 0];
                }
                $productos[$nombre]['cantidad'] += $det->vde_cantidad;
                $productos[$nombre]['total']    += $det->vde_subtotal;
            }
        }
        uasort($productos, fn($a, $b) => $b['total'] <=> $a['total']);

        return compact('ventas', 'totalIngresos', 'totalVentas', 'porMetodo', 'productos');
    }
}
