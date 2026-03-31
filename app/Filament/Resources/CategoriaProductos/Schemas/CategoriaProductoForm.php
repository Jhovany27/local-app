<?php

namespace App\Filament\Resources\CategoriaProductos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoriaProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cat_nombre')
                    ->required(),
                TextInput::make('cat_descripcion')
                    ->required(),
            ]);
    }
}
