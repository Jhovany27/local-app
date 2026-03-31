<?php

namespace App\Filament\Resources\DocumentoRepartidors\Pages;

use App\Filament\Resources\DocumentoRepartidors\DocumentoRepartidorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDocumentoRepartidor extends ViewRecord
{
    protected static string $resource = DocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
