<?php

namespace App\Filament\Resources\Repartidors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RepartidorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rep_tipo_vehiculo')
                    ->options(['Motocicleta' => 'Motocicleta', 'Automovil' => 'Automovil', 'Bicicleta' => 'Bicicleta'])
                    ->required(),
                TextInput::make('rep_estado')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
