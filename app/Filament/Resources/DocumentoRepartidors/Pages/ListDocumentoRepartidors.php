<?php

namespace App\Filament\Resources\DocumentoRepartidors\Pages;

use App\Filament\Resources\DocumentoRepartidors\DocumentoRepartidorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoRepartidors extends ListRecords
{
    protected static string $resource = DocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
