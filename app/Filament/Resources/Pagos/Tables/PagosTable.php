<?php

namespace App\Filament\Resources\Pagos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pag_monto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pag_estado')
                    ->badge(),
                TextColumn::make('pag_metodo_pago')
                    ->badge(),
                TextColumn::make('pag_stripe_payment_intent')
                    ->searchable(),
                TextColumn::make('pag_stripe_charge_id')
                    ->searchable(),
                TextColumn::make('pag_fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('pag_fk_pedido')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
