<?php

namespace App\Filament\Resources\AsignacionRepartidors\Pages;

use App\Filament\Resources\AsignacionRepartidors\AsignacionRepartidorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsignacionRepartidors extends ListRecords
{
    protected static string $resource = AsignacionRepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
