<?php

namespace App\Filament\Resources\TipoDocumentoTiendas\Pages;

use App\Filament\Resources\TipoDocumentoTiendas\TipoDocumentoTiendaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTipoDocumentoTienda extends ViewRecord
{
    protected static string $resource = TipoDocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
