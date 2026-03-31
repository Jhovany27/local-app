<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TipoDocumentoRepartidorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tid_nombre'),
                TextEntry::make('tid_descripcion'),
            ]);
    }
}
