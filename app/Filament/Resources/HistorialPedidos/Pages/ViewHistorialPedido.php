<?php

namespace App\Filament\Resources\HistorialPedidos\Pages;

use App\Filament\Resources\HistorialPedidos\HistorialPedidoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHistorialPedido extends ViewRecord
{
    protected static string $resource = HistorialPedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
