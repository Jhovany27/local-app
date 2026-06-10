<?php

namespace App\Filament\Resources\VerificacionCorreos\Pages;

use App\Filament\Resources\VerificacionCorreos\VerificacionCorreoResource;
use Filament\Resources\Pages\ListRecords;

class ListVerificacionCorreos extends ListRecords
{
    protected static string $resource = VerificacionCorreoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
