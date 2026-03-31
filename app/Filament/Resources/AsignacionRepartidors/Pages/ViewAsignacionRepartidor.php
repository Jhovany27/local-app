<?php

namespace App\Filament\Resources\AsignacionRepartidors\Pages;

use App\Filament\Resources\AsignacionRepartidors\AsignacionRepartidorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAsignacionRepartidor extends ViewRecord
{
    protected static string $resource = AsignacionRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
