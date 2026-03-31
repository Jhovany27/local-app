<?php

namespace App\Filament\Resources\FotoProductos\Pages;

use App\Filament\Resources\FotoProductos\FotoProductoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFotoProducto extends ViewRecord
{
    protected static string $resource = FotoProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
