<?php

namespace App\Filament\Resources\Finanzas\Pages;

use App\Filament\Resources\Finanzas\DeudaRepartidorResource;
use Filament\Resources\Pages\ListRecords;

class ListDeudas extends ListRecords
{
    protected static string $resource = DeudaRepartidorResource::class;

    protected function getHeaderActions(): array { return []; }
}
