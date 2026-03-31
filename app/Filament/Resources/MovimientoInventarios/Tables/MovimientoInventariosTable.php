<?php

namespace App\Filament\Resources\MovimientoInventarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MovimientoInventariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mov_tipo')
                    ->badge(),
                TextColumn::make('mov_cantidad')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mov_fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('mov_fk_producto')
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
