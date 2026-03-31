<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ped_codigo')
                    ->required(),
                DateTimePicker::make('ped_fecha_pedido')
                    ->required(),
                TextInput::make('ped_total')
                    ->required()
                    ->numeric(),
                Select::make('ped_tipo_entrega')
                    ->options(['Domicilio' => 'Domicilio', 'Recoger' => 'Recoger'])
                    ->required(),
                TextInput::make('ped_fk_cliente')
                    ->required()
                    ->numeric(),
                TextInput::make('ped_fk_tienda')
                    ->required()
                    ->numeric(),
            ]);
    }
}
