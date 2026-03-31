<?php

namespace App\Filament\Resources\EstadoPedidos\Pages;

use App\Filament\Resources\EstadoPedidos\EstadoPedidoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEstadoPedido extends ViewRecord
{
    protected static string $resource = EstadoPedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
