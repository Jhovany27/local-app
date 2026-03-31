<?php

namespace App\Filament\Resources\FotoProductos\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;

class FotoProductoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('fop_ruta')
                    ->label('Foto')
                    ->disk('public'),

                TextEntry::make('fop_ruta')
                    ->label('Ruta'),

                TextEntry::make('producto.pro_nombre')
                    ->label('Producto'),
            ]);
    }
}