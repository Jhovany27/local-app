<?php

namespace App\Filament\Resources\Repartidors\Pages;

use App\Filament\Resources\Repartidors\RepartidorResource;
use Filament\Resources\Pages\ListRecords;

class ListRepartidors extends ListRecords
{
    protected static string $resource = RepartidorResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
