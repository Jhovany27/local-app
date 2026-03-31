<?php

namespace App\Filament\Resources\AsignacionRepartidors\Pages;

use App\Filament\Resources\AsignacionRepartidors\AsignacionRepartidorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAsignacionRepartidor extends EditRecord
{
    protected static string $resource = AsignacionRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
