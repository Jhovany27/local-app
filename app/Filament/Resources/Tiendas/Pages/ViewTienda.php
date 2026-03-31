<?php

namespace App\Filament\Resources\Tiendas\Pages;

use App\Filament\Resources\Tiendas\TiendaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTienda extends ViewRecord
{
    protected static string $resource = TiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
