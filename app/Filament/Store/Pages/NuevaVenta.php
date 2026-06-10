<?php

namespace App\Filament\Store\Pages;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class NuevaVenta extends Page
{
    protected string $view = 'filament.store.pages.nueva-venta';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;
    protected static ?string $navigationLabel = 'Nueva Venta';
    protected static ?string $title = 'Nueva Venta';
    protected static ?int $navigationSort = 3;

    // Búsqueda
    public string $busqueda = '';

    // Carrito: array de ['producto' => Producto, 'cantidad' => int, 'subtotal' => float]
    public array $carrito = [];

    // Resultados de búsqueda
    public array $resultados = [];

    public function getTiendaId(): int
    {
        return session('store_tienda_id');
    }

    public function updatedBusqueda(): void
    {
        if (strlen($this->busqueda) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Producto::where('pro_fk_tienda', $this->getTiendaId())
            ->where('pro_estado', true)
            ->where('pro_nombre', 'like', "%{$this->busqueda}%")
            ->with('inventario')
            ->limit(8)
            ->get()
            ->toArray();
    }

    public function agregarProducto(int $productoId): void
    {
        $producto = Producto::with('inventario')->find($productoId);

        if (!$producto) return;

        $stock = $producto->inventario?->inv_stock_actual ?? 0;

        // Verificar si ya está en el carrito
        foreach ($this->carrito as $i => $item) {
            if ($item['pro_id'] === $productoId) {
                $nuevaCantidad = $item['cantidad'] + 1;

                if ($nuevaCantidad > $stock) {
                    Notification::make()
                        ->title('Stock insuficiente')
                        ->body("Solo hay {$stock} unidades disponibles.")
                        ->warning()
                        ->send();
                    return;
                }

                $this->carrito[$i]['cantidad']  = $nuevaCantidad;
                $this->carrito[$i]['subtotal']  = $nuevaCantidad * $item['pro_precio_venta'];
                $this->busqueda   = '';
                $this->resultados = [];
                return;
            }
        }

        if ($stock < 1) {
            Notification::make()
                ->title('Sin stock')
                ->body('Este producto no tiene stock disponible.')
                ->warning()
                ->send();
            return;
        }

        $this->carrito[] = [
            'pro_id'          => $producto->pro_id,
            'pro_nombre'      => $producto->pro_nombre,
            'pro_marca'       => $producto->pro_marca,
            'pro_precio_venta' => (float) $producto->pro_precio_venta,
            'cantidad'        => 1,
            'subtotal'        => (float) $producto->pro_precio_venta,
            'stock'           => $stock,
        ];

        $this->busqueda   = '';
        $this->resultados = [];
    }

    public function incrementar(int $index): void
    {
        $item = $this->carrito[$index];

        if ($item['cantidad'] >= $item['stock']) {
            Notification::make()
                ->title('Stock insuficiente')
                ->body("Solo hay {$item['stock']} unidades disponibles.")
                ->warning()
                ->send();
            return;
        }

        $this->carrito[$index]['cantidad']++;
        $this->carrito[$index]['subtotal'] = $this->carrito[$index]['cantidad'] * $item['pro_precio_venta'];
    }

    public function decrementar(int $index): void
    {
        if ($this->carrito[$index]['cantidad'] <= 1) {
            $this->eliminar($index);
            return;
        }

        $this->carrito[$index]['cantidad']--;
        $this->carrito[$index]['subtotal'] = $this->carrito[$index]['cantidad'] * $this->carrito[$index]['pro_precio_venta'];
    }

    public function eliminar(int $index): void
    {
        array_splice($this->carrito, $index, 1);
        $this->carrito = array_values($this->carrito);
    }

    public function getTotal(): float
    {
        return array_sum(array_column($this->carrito, 'subtotal'));
    }

    public function confirmarVenta(): void
    {
        if (empty($this->carrito)) {
            Notification::make()
                ->title('Carrito vacío')
                ->body('Agrega al menos un producto para registrar la venta.')
                ->warning()
                ->send();
            return;
        }

        DB::transaction(function () {
            // Crear cabecera
            $venta = Venta::create([
                'ven_fecha'     => now(),
                'ven_total'     => $this->getTotal(),
                'ven_estado'    => Venta::ESTADO_COMPLETADA,
                'ven_fk_tienda' => $this->getTiendaId(),
            ]);

            foreach ($this->carrito as $item) {
                // Crear detalle
                VentaDetalle::create([
                    'vde_cantidad'         => $item['cantidad'],
                    'vde_precio_unitario'  => $item['pro_precio_venta'],
                    'vde_subtotal'         => $item['subtotal'],
                    'vde_fk_venta'         => $venta->ven_id,
                    'vde_fk_producto'      => $item['pro_id'],
                ]);

                // Descontar inventario
                Inventario::where('inv_fk_producto', $item['pro_id'])
                    ->decrement('inv_stock_actual', $item['cantidad']);
            }
        });

        $this->carrito = [];

        Notification::make()
            ->title('¡Venta registrada!')
            ->body('La venta se completó y el inventario fue actualizado.')
            ->success()
            ->send();
    }

    public function limpiarCarrito(): void
    {
        $this->carrito    = [];
        $this->busqueda   = '';
        $this->resultados = [];
    }
}
