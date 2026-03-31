<?php

namespace App\Filament\Resources\Pagos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PagoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pag_monto')
                    ->numeric(),
                TextEntry::make('pag_estado')
                    ->badge(),
                TextEntry::make('pag_metodo_pago')
                    ->badge(),
                TextEntry::make('pag_stripe_payment_intent')
                    ->placeholder('-'),
                TextEntry::make('pag_stripe_charge_id')
                    ->placeholder('-'),
                TextEntry::make('pag_fecha')
                    ->dateTime(),
                TextEntry::make('pag_fk_pedido')
                    ->numeric(),
            ]);
    }
}
