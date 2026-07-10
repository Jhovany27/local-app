<?php

namespace App\Filament\Store\Resources\Ventas\Pages;

use App\Filament\Store\Resources\Ventas\VentaResource;
use App\Models\Pedido;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewVenta extends ViewRecord
{
    protected static string $resource = VentaResource::class;
    protected string $view = 'filament.store.pages.ventas.view-venta';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->recargarRecord();

        abort_unless(
            (int) $this->record->ven_fk_tienda === (int) session('store_tienda_id'),
            403
        );
    }

    public function generarPin(): void
    {
        $pedido = $this->record->pedido;

        if (! $pedido || $pedido->ped_pin_liquidacion !== null) {
            return;
        }

        if ($pedido->ped_pin_intentos >= Pedido::PIN_MAX_INTENTOS) {
            Notification::make()
                ->title('Repartidor bloqueado')
                ->body('El repartidor agotó los intentos. Contacta con soporte.')
                ->danger()
                ->send();
            return;
        }

        $pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $pedido->update([
            'ped_pin_liquidacion' => $pin,
            'ped_pin_generado_at' => now(),
        ]);

        $this->recargarRecord();

        Notification::make()
            ->title("PIN de recogida: {$pin}")
            ->body("Díselo al repartidor cuando llegue a pagar.")
            ->success()
            ->persistent()
            ->send();
    }

    private function recargarRecord(): void
    {
        $this->record = \App\Models\Venta::with([
            'detalles.producto',
            'tienda',
            'pedido.pago',
            'pedido.asignacion.repartidor.user.persona',
        ])->where('ven_id', $this->record->ven_id)->firstOrFail();
    }
}
