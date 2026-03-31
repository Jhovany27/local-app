<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors\Pages;

use App\Filament\Resources\TipoDocumentoRepartidors\TipoDocumentoRepartidorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTipoDocumentoRepartidor extends ViewRecord
{
    protected static string $resource = TipoDocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
