<?php

namespace App\Filament\Store\Pages;

use App\Models\EstadoPedido;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use App\Models\Venta;
use App\Models\VentaDetalle;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Pedidos extends Page
{
    protected string $view = 'filament.store.pages.pedidos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Pedidos';

    protected static ?string $title = 'Pedidos';

    protected static ?int $navigationSort = 2;

    public function getTiendaId(): int
    {
        return (int) session('store_tienda_id');
    }

    public function getPedidosPendientesProperty()
    {
        return Pedido::with(['detalles.producto.inventario', 'cliente.user.persona'])
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'pendiente')
            ->latest('ped_fecha_pedido')
            ->get();
    }

    public function getPedidosEnPreparacionProperty()
    {
        return Pedido::with(['detalles.producto', 'cliente.user.persona'])
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'en_preparacion')
            ->latest('ped_fecha_pedido')
            ->get();
    }

    public function getPedidosListosProperty()
    {
        return Pedido::with(['detalles.producto', 'cliente.user.persona'])
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->whereIn('ped_estado', ['listo', 'completado'])
            ->where('ped_confirmado_tienda', false) //  solo los no confirmados
            ->latest('ped_fecha_pedido')
            ->get();
    }

    // ── ACEPTAR pedido → en preparación ──────────────────
    public function aceptar(int $pedidoId): void
    {
        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'pendiente')
            ->firstOrFail();

        $pedido->update(['ped_estado' => 'en_preparacion']);

        EstadoPedido::create([
            'esp_nombre'       => 'en_preparacion',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedido->ped_id,
        ]);

        Notification::make()
            ->title('Pedido aceptado')
            ->body("Pedido #{$pedido->ped_codigo} en preparación.")
            ->success()
            ->send();
    }

    // ── RECHAZAR pedido ───────────────────────────────────
    public function rechazar(int $pedidoId): void
    {
        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'pendiente')
            ->firstOrFail();

        $pedido->update(['ped_estado' => 'cancelado']);

        EstadoPedido::create([
            'esp_nombre'       => 'cancelado',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedido->ped_id,
        ]);

        Notification::make()
            ->title('Pedido rechazado')
            ->danger()
            ->send();
    }

    // ── MARCAR LISTO → descuenta inventario ───────────────
    public function marcarListo(int $pedidoId): void
    {
        $pedido = Pedido::with('detalles.producto.inventario')
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'en_preparacion')
            ->firstOrFail();

        DB::transaction(function () use ($pedido) {
            foreach ($pedido->detalles as $detalle) {
                $inventario = $detalle->producto?->inventario;
                if ($inventario) {
                    $inventario->decrement('inv_stock_actual', $detalle->det_cantidad);
                    MovimientoInventario::create([
                        'mov_tipo'        => 'salida',
                        'mov_cantidad'    => $detalle->det_cantidad,
                        'mov_fecha'       => now(),
                        'mov_fk_producto' => $detalle->det_fk_producto,
                    ]);
                }
            }

            $pedido->update(['ped_estado' => 'listo']);

            EstadoPedido::create([
                'esp_nombre'       => 'listo',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);
        });

        Notification::make()
            ->title('Pedido listo')
            ->body("El inventario fue actualizado.")
            ->success()
            ->send();
    }

    // ── MARCAR ENTREGADO ──────────────────────────────────
    public function marcarEntregado(int $pedidoId): void
    {
        $pedido = Pedido::with(['detalles.producto', 'pago'])
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->whereIn('ped_estado', ['listo', 'completado'])
            ->where('ped_confirmado_tienda', false)
            ->firstOrFail();

        DB::transaction(function () use ($pedido) {

            //  Marcar como confirmado por tienda
            $pedido->update([
                'ped_estado'             => 'completado',
                'ped_confirmado_tienda'  => true,
            ]);

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);

            if ($pedido->pago?->pag_estado !== 'Aceptado') {
                $pedido->pago?->update(['pag_estado' => 'Aceptado']);
            }

            $venta = Venta::create([
                'ven_fecha'      => now(),
                'ven_total'      => $pedido->ped_total,
                'ven_estado'     => Venta::ESTADO_COMPLETADA,
                'ven_fk_tienda'  => $pedido->ped_fk_tienda,
                'ven_fk_pedido'  => $pedido->ped_id,
            ]);

            foreach ($pedido->detalles as $detalle) {
                VentaDetalle::create([
                    'vde_cantidad'        => $detalle->det_cantidad,
                    'vde_precio_unitario' => $detalle->det_precio_unitario,
                    'vde_subtotal'        => $detalle->det_subtotal,
                    'vde_fk_venta'        => $venta->ven_id,
                    'vde_fk_producto'     => $detalle->det_fk_producto,
                ]);
            }
        });

        Notification::make()
            ->title('Pedido entregado')
            ->body("Venta registrada en el historial.")
            ->success()->send();
    }
}
