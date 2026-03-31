<?php

namespace App\Filament\Resources\DocumentoRepartidors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentoRepartidorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dor_ruta')
                    ->required(),
                DateTimePicker::make('dor_fecha')
                    ->required(),
                TextInput::make('dor_fk_repartidor')
                    ->required()
                    ->numeric(),
                TextInput::make('dor_fk_tipo_documento')
                    ->required()
                    ->numeric(),
            ]);
    }
}
