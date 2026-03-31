<?php

namespace App\Filament\Resources\DetallePedidos\Pages;

use App\Filament\Resources\DetallePedidos\DetallePedidoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDetallePedido extends EditRecord
{
    protected static string $resource = DetallePedidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
