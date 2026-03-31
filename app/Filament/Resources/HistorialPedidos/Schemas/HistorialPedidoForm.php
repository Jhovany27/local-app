<?php

namespace App\Filament\Resources\HistorialPedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class HistorialPedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('hip_fecha')
                    ->required(),
                TextInput::make('hip_fk_estado')
                    ->required()
                    ->numeric(),
            ]);
    }
}
