<?php

namespace App\Filament\Resources\Calificacions\Pages;

use App\Filament\Resources\Calificacions\CalificacionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCalificacion extends ViewRecord
{
    protected static string $resource = CalificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
