<?php

namespace App\Filament\Resources\TipoDocumentoTiendas\Pages;

use App\Filament\Resources\TipoDocumentoTiendas\TipoDocumentoTiendaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoDocumentoTienda extends EditRecord
{
    protected static string $resource = TipoDocumentoTiendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
