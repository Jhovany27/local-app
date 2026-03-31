<?php

namespace App\Filament\Resources\DocumentoRepartidors\Pages;

use App\Filament\Resources\DocumentoRepartidors\DocumentoRepartidorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentoRepartidor extends EditRecord
{
    protected static string $resource = DocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
