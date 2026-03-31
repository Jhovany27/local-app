<?php

namespace App\Filament\Resources\Direccions\Pages;

use App\Filament\Resources\Direccions\DireccionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDireccion extends ViewRecord
{
    protected static string $resource = DireccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
