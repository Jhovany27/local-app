<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('foto_principal')
                    ->label('Foto principal')
                    ->disk('public'),
                TextEntry::make('pro_codigo'),
                TextEntry::make('pro_nombre'),
                TextEntry::make('pro_marca'),
                TextEntry::make('pro_detalles')
                    ->columnSpanFull(),
                TextEntry::make('pro_precio_prove')
                    ->numeric(),
                TextEntry::make('pro_precio_venta')
                    ->numeric(),
                TextEntry::make('pro_estado')
                    ->numeric(),
                TextEntry::make('pro_fk_tienda')
                    ->numeric(),
                TextEntry::make('pro_fk_categoria')
                    ->numeric(),
            ]);
    }
}
