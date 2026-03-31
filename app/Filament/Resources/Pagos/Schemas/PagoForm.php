<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PagoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pag_monto')
                    ->required()
                    ->numeric(),
                Select::make('pag_estado')
                    ->options(['En proceso' => 'En proceso', 'Aceptado' => 'Aceptado', 'Rechazado' => 'Rechazado'])
                    ->required(),
                Select::make('pag_metodo_pago')
                    ->options(['Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta'])
                    ->required(),
                TextInput::make('pag_stripe_payment_intent'),
                TextInput::make('pag_stripe_charge_id'),
                DateTimePicker::make('pag_fecha')
                    ->required(),
                TextInput::make('pag_fk_pedido')
                    ->required()
                    ->numeric(),
            ]);
    }
}
