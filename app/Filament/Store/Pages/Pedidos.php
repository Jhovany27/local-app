<?php

namespace App\Filament\Store\Pages;

use App\Models\EstadoPedido;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Services\DistribucionPagoService;
use App\Services\TiendaNotificacion;
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

    public ?int $pedidoDetalleId    = null;
    public ?int $pedidoParaRechazar = null;
    public string $motivoRechazo    = '';
    public ?int $modalEntregaId     = null;

    public function getTiendaId(): int
    {
        return (int) session('store_tienda_id');
    }

    public function verDetalle(int $id): void
    {
        $this->pedidoDetalleId = $id;
    }

    public function cerrarDetalle(): void
    {
        $this->pedidoDetalleId = null;
    }

    public function abrirModalEntrega(int $pedidoId): void
    {
        $this->modalEntregaId = $pedidoId;
    }

    public function cerrarModalEntrega(): void
    {
        $this->modalEntregaId = null;
    }

    public function getPedidoModalEntregaProperty(): ?Pedido
    {
        if (! $this->modalEntregaId) return null;

        return Pedido::with(['pago', 'asignacion.repartidor.user.persona'])
            ->where('ped_id', $this->modalEntregaId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->first();
    }

    public function getPedidoDetalleProperty(): ?Pedido
    {
        if (! $this->pedidoDetalleId) return null;

        return Pedido::with([
            'detalles.producto',
            'cliente.user.persona',
            'pago',
            'direccion',
        ])->find($this->pedidoDetalleId);
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
        return Pedido::with(['detalles.producto', 'cliente.user.persona', 'asignacion.repartidor.user.persona', 'pago'])
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->whereIn('ped_estado', ['listo', 'completado'])
            ->where('ped_confirmado_tienda', false)
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

        $pedido->ped_estado = 'en_preparacion';
        $pedido->save();

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

    // ── RECHAZAR pedido → abrir modal con motivo ─────────
    public function abrirModalRechazo(int $pedidoId): void
    {
        $this->pedidoParaRechazar = $pedidoId;
        $this->motivoRechazo      = '';
    }

    public function cerrarModalRechazo(): void
    {
        $this->pedidoParaRechazar = null;
        $this->motivoRechazo      = '';
    }

    public function confirmarRechazo(): void
    {
        $this->validate([
            'motivoRechazo' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'motivoRechazo.required' => 'Debes indicar el motivo del rechazo.',
            'motivoRechazo.min'      => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $pedido = Pedido::where('ped_id', $this->pedidoParaRechazar)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'pendiente')
            ->firstOrFail();

        $pedido->update([
            'ped_estado'             => 'cancelado',
            'ped_motivo_cancelacion' => $this->motivoRechazo,
            'ped_cancelado_por'      => 'tienda',
        ]);

        EstadoPedido::create([
            'esp_nombre'       => 'cancelado',
            'esp_fecha_cambio' => now(),
            'esp_fk_pedido'    => $pedido->ped_id,
        ]);

        $this->cerrarModalRechazo();

        TiendaNotificacion::enviar(
            $this->getTiendaId(),
            'Pedido cancelado',
            "El pedido #{$pedido->ped_codigo} fue rechazado por tu tienda.",
            'danger',
            'heroicon-o-x-circle'
        );

        Notification::make()
            ->title('Pedido rechazado')
            ->body("Pedido #{$pedido->ped_codigo} fue rechazado.")
            ->danger()
            ->send();
    }

    // ── MARCAR LISTO → descuenta inventario ───────────────
    public function marcarListo(int $pedidoId): void
    {
        $pedido = Pedido::with(['detalles.producto.inventario', 'pago'])
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'en_preparacion')
            ->firstOrFail();

        $pin = null;

        $stockBajoAlertas = [];

        DB::transaction(function () use ($pedido, &$pin, &$stockBajoAlertas) {
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

                    // Detectar stock bajo (≤ 5 unidades restantes)
                    $inventario->refresh();
                    if ($inventario->inv_stock_actual <= 5 && $inventario->inv_stock_actual >= 0) {
                        $stockBajoAlertas[] = [
                            'nombre' => $detalle->producto->pro_nombre,
                            'stock'  => $inventario->inv_stock_actual,
                        ];
                    }
                }
            }

            $pedido->ped_estado = 'listo';

            // Generar PIN automáticamente para pedidos de domicilio en efectivo
            $esEfectivoDomicilio = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo'
                && strtolower($pedido->ped_tipo_entrega) === 'domicilio'
                && $pedido->ped_pin_liquidacion === null;

            if ($esEfectivoDomicilio) {
                $pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $pedido->ped_pin_liquidacion = $pin;
                $pedido->ped_pin_generado_at = now();
            }

            $pedido->save();

            EstadoPedido::create([
                'esp_nombre'       => 'listo',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);
        });

        // Notificaciones de stock bajo persistentes en el bell
        foreach ($stockBajoAlertas as $alerta) {
            $msg = $alerta['stock'] === 0
                ? "El producto \"{$alerta['nombre']}\" está agotado."
                : "El producto \"{$alerta['nombre']}\" tiene solo {$alerta['stock']} unidades restantes.";

            TiendaNotificacion::enviar(
                $this->getTiendaId(),
                'Stock bajo',
                $msg,
                'warning',
                'heroicon-o-exclamation-triangle'
            );
        }

        if ($pin !== null) {
            Notification::make()
                ->title("PIN de recogida generado: {$pin}")
                ->body("Díselo al repartidor cuando llegue a pagar. Pedido #{$pedido->ped_codigo}")
                ->success()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Pedido listo')
                ->body("El inventario fue actualizado.")
                ->success()
                ->send();
        }
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

        // Pedidos a domicilio requieren que un repartidor lo haya aceptado
        if (strtolower($pedido->ped_tipo_entrega) === 'domicilio') {
            $tieneRepartidor = \App\Models\AsignacionRepartidor::where('asr_fk_pedido', $pedidoId)->exists();

            if (! $tieneRepartidor) {
                Notification::make()
                    ->title('Pedido sin repartidor')
                    ->body('Este pedido aún no ha sido aceptado por ningún repartidor. No es posible confirmar la entrega.')
                    ->warning()
                    ->persistent()
                    ->send();
                return;
            }
        }

        DB::transaction(function () use ($pedido) {

            $pedido->ped_estado = 'completado';
            $pedido->ped_confirmado_tienda = true;
            $pedido->save();

            EstadoPedido::create([
                'esp_nombre'       => 'completado',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);

            // Para domicilio NO cerramos la asignación: el repartidor sigue en tránsito
            // y debe actualizar su propio estado al entregar al cliente.
            // Para recoger (sin repartidor activo), sí cerramos cualquier asignación residual.
            if (strtolower($pedido->ped_tipo_entrega) !== 'domicilio') {
                \App\Models\AsignacionRepartidor::where('asr_fk_pedido', $pedido->ped_id)
                    ->whereIn('asr_estado', [0, 1, 2])
                    ->update(['asr_estado' => 3]);
            }

            if ($pedido->pago?->pag_estado !== 'Aceptado') {
                $pedido->pago?->update(['pag_estado' => 'Aceptado']);
            }

            $venta = Venta::where('ven_fk_pedido', $pedido->ped_id)->first();
            if (! $venta) {
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
            }

            // Para efectivo+domicilio la distribución ocurre cuando el repartidor paga (validarPinTienda)
            $esEfectivoDomicilio = strtolower($pedido->pago?->pag_metodo_pago ?? '') === 'efectivo'
                && strtolower($pedido->ped_tipo_entrega) === 'domicilio';

            if (! $esEfectivoDomicilio && $pedido->ped_estado_liquidacion !== Pedido::LIQ_LIQUIDADO) {
                DistribucionPagoService::distribuir($pedido);
            }
        });

        $this->modalEntregaId = null;

        Notification::make()
            ->title('Pedido entregado')
            ->body("Venta registrada en el historial.")
            ->success()->send();
    }

    // ── PEDIDOS PENDIENTES DE LIQUIDACIÓN ─────────────────
    public function getPedidosPendienteLiquidacionProperty()
    {
        return Pedido::with(['pago', 'asignacion.repartidor.user.persona'])
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->where('ped_estado', 'completado')
            ->where('ped_estado_liquidacion', Pedido::LIQ_PENDIENTE)
            ->latest('ped_fecha_pedido')
            ->get();
    }

    // ── GENERAR PIN ───────────────────────────────────────
    public function generarPin(int $pedidoId): void
    {
        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_fk_tienda', $this->getTiendaId())
            ->firstOrFail();

        if ($pedido->ped_pin_liquidacion !== null) {
            Notification::make()
                ->title('El PIN ya fue generado')
                ->body("No puedes generar un nuevo PIN para este pedido.")
                ->warning()
                ->send();
            return;
        }

        if ($pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS) {
            Notification::make()
                ->title('Repartidor bloqueado')
                ->body("El repartidor agotó los intentos. Contacta con soporte.")
                ->danger()
                ->send();
            return;
        }

        $pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $pedido->update([
            'ped_pin_liquidacion' => $pin,
            'ped_pin_generado_at' => now(),
        ]);

        Notification::make()
            ->title("PIN de recogida: {$pin}")
            ->body("Díselo al repartidor cuando llegue a pagar. Pedido #{$pedido->ped_codigo}")
            ->success()
            ->persistent()
            ->send();
    }
}
