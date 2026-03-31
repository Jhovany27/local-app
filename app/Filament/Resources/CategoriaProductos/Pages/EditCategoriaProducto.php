<?php

namespace App\Filament\Resources\CategoriaProductos\Pages;

use App\Filament\Resources\CategoriaProductos\CategoriaProductoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoriaProducto extends EditRecord
{
    protected static string $resource = CategoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
