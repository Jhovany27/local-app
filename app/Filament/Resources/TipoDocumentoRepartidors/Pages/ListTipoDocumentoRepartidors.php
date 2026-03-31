<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors\Pages;

use App\Filament\Resources\TipoDocumentoRepartidors\TipoDocumentoRepartidorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoDocumentoRepartidors extends ListRecords
{
    protected static string $resource = TipoDocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
