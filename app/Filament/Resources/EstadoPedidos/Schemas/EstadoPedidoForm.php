<?php

namespace App\Filament\Resources\EstadoPedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EstadoPedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('esp_nombre')
                    ->required(),
                DateTimePicker::make('esp_fecha_cambio')
                    ->required(),
                TextInput::make('esp_fk_pedido')
                    ->required()
                    ->numeric(),
            ]);
    }
}
