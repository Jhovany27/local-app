<?php

namespace App\Filament\Store\Resources\Inventarios\Pages;

use App\Filament\Store\Resources\Inventarios\InventarioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventario extends EditRecord
{
    protected static string $resource = InventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
