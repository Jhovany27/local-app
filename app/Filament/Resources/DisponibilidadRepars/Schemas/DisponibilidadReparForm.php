<?php

namespace App\Filament\Resources\DisponibilidadRepars\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DisponibilidadReparForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dir_estado')
                    ->options(['Disponible' => 'Disponible', 'Ocupado' => 'Ocupado', 'No disponible' => 'No disponible'])
                    ->required(),
                DateTimePicker::make('dir_actualizacion')
                    ->required(),
                TextInput::make('dir_fk_repartidor')
                    ->required()
                    ->numeric(),
            ]);
    }
}
