<?php

namespace App\Filament\Resources\DocumentoTiendas\Pages;

use App\Filament\Resources\DocumentoTiendas\DocumentoTiendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentoTiendas extends ListRecords
{
    protected static string $resource = DocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
