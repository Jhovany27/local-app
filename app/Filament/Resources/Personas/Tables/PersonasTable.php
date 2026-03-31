<?php

namespace App\Filament\Resources\Personas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('per_nombre')
                    ->searchable(),
                TextColumn::make('per_paterno')
                    ->searchable(),
                TextColumn::make('per_materno')
                    ->searchable(),
                TextColumn::make('per_telefono')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('per_fecha_registro')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('per_estado')
                    ->badge(),
                TextColumn::make('user_id')
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
