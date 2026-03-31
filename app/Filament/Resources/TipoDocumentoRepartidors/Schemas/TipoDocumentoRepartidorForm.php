<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TipoDocumentoRepartidorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tid_nombre')
                    ->required(),
                TextInput::make('tid_descripcion')
                    ->required(),
            ]);
    }
}
