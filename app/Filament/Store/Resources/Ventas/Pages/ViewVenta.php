<?php

namespace App\Filament\Store\Resources\Ventas\Pages;

use App\Filament\Store\Resources\Ventas\VentaResource;
use Filament\Resources\Pages\ViewRecord;

class ViewVenta extends ViewRecord
{
    protected static string $resource = VentaResource::class;
    protected string $view = 'filament.store.pages.ventas.view-venta';

    // ViewRecord resuelve el modelo automáticamente
    // Solo agrega el check de tienda
    public function mount(int|string $record): void
    {
        parent::mount($record);

        //  Recargar con las relaciones que necesita el blade
        $this->record = \App\Models\Venta::with(['detalles.producto', 'tienda'])
            ->where('ven_id', $this->record->ven_id)
            ->firstOrFail();

        abort_unless(
            (int) $this->record->ven_fk_tienda === (int) session('store_tienda_id'),
            403
        );
    }
}
