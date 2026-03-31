<?php

namespace App\Filament\Resources\Calificacions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CalificacionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cal_puntuacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cal_fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cal_fk_tienda')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cal_fk_cliente')
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
