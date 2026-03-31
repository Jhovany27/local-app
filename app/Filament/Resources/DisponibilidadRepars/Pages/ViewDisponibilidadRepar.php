<?php

namespace App\Filament\Resources\DisponibilidadRepars\Pages;

use App\Filament\Resources\DisponibilidadRepars\DisponibilidadReparResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDisponibilidadRepar extends ViewRecord
{
    protected static string $resource = DisponibilidadReparResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
