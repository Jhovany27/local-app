<?php

namespace App\Filament\Resources\Fachadas\Pages;

use App\Filament\Resources\Fachadas\FachadaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFachadas extends ListRecords
{
    protected static string $resource = FachadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
