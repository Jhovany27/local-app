<?php

namespace App\Filament\Resources\MovimientoInventarios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MovimientoInventarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mov_tipo')
                    ->options(['Entrada' => 'Entrada', 'Salida' => 'Salida', 'Devolucion' => 'Devolucion'])
                    ->required(),
                TextInput::make('mov_cantidad')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('mov_fecha')
                    ->required(),
                TextInput::make('mov_fk_producto')
                    ->required()
                    ->numeric(),
            ]);
    }
}
