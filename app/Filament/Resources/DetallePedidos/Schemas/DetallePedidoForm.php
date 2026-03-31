<?php

namespace App\Filament\Resources\DetallePedidos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DetallePedidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('det_cantidad')
                    ->required()
                    ->numeric(),
                TextInput::make('det_precio_unitario')
                    ->required()
                    ->numeric(),
                TextInput::make('det_subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('det_fk_producto')
                    ->required()
                    ->numeric(),
                TextInput::make('det_fk_pedido')
                    ->required()
                    ->numeric(),
            ]);
    }
}
