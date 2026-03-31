<?php

namespace App\Filament\Resources\CategoriaProductos\Pages;

use App\Filament\Resources\CategoriaProductos\CategoriaProductoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoriaProductos extends ListRecords
{
    protected static string $resource = CategoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
