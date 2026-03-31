<?php

namespace App\Filament\Resources\DocumentoTiendas\Pages;

use App\Filament\Resources\DocumentoTiendas\DocumentoTiendaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoTienda extends EditRecord
{
    protected static string $resource = DocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
