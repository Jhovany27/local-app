<?php

namespace App\Filament\Resources\Tiendas\Pages;

use App\Filament\Resources\Tiendas\TiendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiendas extends ListRecords
{
    protected static string $resource = TiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
