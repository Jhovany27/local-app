<?php

namespace App\Filament\Resources\CategoriaProductos\Pages;

use App\Filament\Resources\CategoriaProductos\CategoriaProductoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCategoriaProducto extends ViewRecord
{
    protected static string $resource = CategoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
