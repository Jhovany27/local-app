<?php

namespace App\Filament\Resources\DocumentoTiendas\Pages;

use App\Filament\Resources\DocumentoTiendas\DocumentoTiendaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentoTienda extends ViewRecord
{
    protected static string $resource = DocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
