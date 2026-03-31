<?php

namespace App\Filament\Resources\TipoDocumentoTiendas\Pages;

use App\Filament\Resources\TipoDocumentoTiendas\TipoDocumentoTiendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoDocumentoTiendas extends ListRecords
{
    protected static string $resource = TipoDocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
