<?php

namespace App\Filament\Resources\TipoDocumentoTiendas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TipoDocumentoTiendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tdt_nombre')
                    ->required(),
                TextInput::make('tdt_descripcion')
                    ->required(),
            ]);
    }
}
