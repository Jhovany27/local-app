<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors\Pages;

use App\Filament\Resources\TipoDocumentoRepartidors\TipoDocumentoRepartidorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoDocumentoRepartidor extends EditRecord
{
    protected static string $resource = TipoDocumentoRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
