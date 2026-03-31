<?php

namespace App\Filament\Resources\CategoriaProductos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoriaProductoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cat_nombre'),
                TextEntry::make('cat_descripcion'),
            ]);
    }
}
