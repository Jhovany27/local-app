<?php

namespace App\Filament\Resources\Tiendas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TiendaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tie_nombre'),
                TextEntry::make('tie_descripcion'),
                TextEntry::make('tie_telefono'),
                TextEntry::make('tie_latitud')
                    ->numeric(),
                TextEntry::make('tie_longitud')
                    ->numeric(),
                TextEntry::make('tie_direccion'),
                TextEntry::make('tie_estado')
                    ->numeric(),
                TextEntry::make('tie_fecha_registro')
                    ->dateTime(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
