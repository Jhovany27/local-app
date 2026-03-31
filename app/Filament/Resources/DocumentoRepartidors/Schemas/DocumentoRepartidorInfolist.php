<?php

namespace App\Filament\Resources\DocumentoRepartidors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DocumentoRepartidorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('dor_ruta'),
                TextEntry::make('dor_fecha')
                    ->dateTime(),
                TextEntry::make('dor_fk_repartidor')
                    ->numeric(),
                TextEntry::make('dor_fk_tipo_documento')
                    ->numeric(),
            ]);
    }
}
