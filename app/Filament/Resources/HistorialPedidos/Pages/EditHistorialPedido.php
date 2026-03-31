<?php

namespace App\Filament\Resources\HistorialPedidos\Pages;

use App\Filament\Resources\HistorialPedidos\HistorialPedidoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHistorialPedido extends EditRecord
{
    protected static string $resource = HistorialPedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
