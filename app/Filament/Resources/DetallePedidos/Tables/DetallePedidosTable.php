<?php

namespace App\Filament\Resources\DetallePedidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetallePedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('det_cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('det_precio_unitario')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('det_subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('det_fk_producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('det_fk_pedido')
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
