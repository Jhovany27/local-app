<?php

namespace App\Filament\Resources\Inventarios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('inv_stock_actual')
                    ->required()
                    ->numeric(),
                TextInput::make('inv_stock_minimo')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('inv_actualizacion')
                    ->required(),
                TextInput::make('inv_fk_producto')
                    ->required()
                    ->numeric(),
            ]);
    }
}
