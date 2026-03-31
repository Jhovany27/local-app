<?php

namespace App\Filament\Resources\Fachadas\Pages;

use App\Filament\Resources\Fachadas\FachadaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFachada extends ViewRecord
{
    protected static string $resource = FachadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
