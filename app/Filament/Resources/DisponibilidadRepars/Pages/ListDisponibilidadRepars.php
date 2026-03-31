<?php

namespace App\Filament\Resources\DisponibilidadRepars\Pages;

use App\Filament\Resources\DisponibilidadRepars\DisponibilidadReparResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDisponibilidadRepars extends ListRecords
{
    protected static string $resource = DisponibilidadReparResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
