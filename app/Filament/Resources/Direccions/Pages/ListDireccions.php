<?php

namespace App\Filament\Resources\Direccions\Pages;

use App\Filament\Resources\Direccions\DireccionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDireccions extends ListRecords
{
    protected static string $resource = DireccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
