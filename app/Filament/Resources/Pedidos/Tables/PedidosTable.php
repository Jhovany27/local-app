<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ped_codigo')
                    ->searchable(),
                TextColumn::make('ped_fecha_pedido')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ped_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ped_tipo_entrega')
                    ->badge(),
                TextColumn::make('ped_fk_cliente')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ped_fk_tienda')
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
