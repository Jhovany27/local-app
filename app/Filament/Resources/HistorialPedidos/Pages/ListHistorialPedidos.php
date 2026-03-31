<?php

namespace App\Filament\Resources\HistorialPedidos\Pages;

use App\Filament\Resources\HistorialPedidos\HistorialPedidoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistorialPedidos extends ListRecords
{
    protected static string $resource = HistorialPedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
