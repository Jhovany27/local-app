<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use App\Models\Pedido;
use Filament\Resources\Pages\ViewRecord;

class ViewPedido extends ViewRecord
{
    protected static string $resource = PedidoResource::class;

    protected string $view = 'filament.resources.pedidos.pages.view-pedido';

    public function getPedidoDetalladoProperty(): ?Pedido
    {
        return Pedido::with([
            'cliente.user.persona',
            'tienda',
            'detalles.producto',
            'pago',
            'asignacion.repartidor.user.persona',
            'direccion',
            'estados',
        ])->find($this->record->ped_id);
    }
}