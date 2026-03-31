<?php

namespace App\Filament\Resources\DocumentoTiendas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentoTiendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dot_ruta')
                    ->required(),
                DateTimePicker::make('dot_fecha')
                    ->required(),
                TextInput::make('dot_fk_tienda')
                    ->required()
                    ->numeric(),
                TextInput::make('dot_fk_tipo_documento')
                    ->required()
                    ->numeric(),
            ]);
    }
}
