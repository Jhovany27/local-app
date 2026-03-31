<?php

namespace App\Filament\Resources\DocumentoTiendas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DocumentoTiendaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('dot_ruta'),
                TextEntry::make('dot_fecha')
                    ->dateTime(),
                TextEntry::make('dot_fk_tienda')
                    ->numeric(),
                TextEntry::make('dot_fk_tipo_documento')
                    ->numeric(),
            ]);
    }
}
