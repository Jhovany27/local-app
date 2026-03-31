<?php

namespace App\Filament\Resources\Direccions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DireccionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('drc_calle')
                    ->searchable(),
                TextColumn::make('drc_numero')
                    ->searchable(),
                TextColumn::make('drc_colonia')
                    ->searchable(),
                TextColumn::make('drc_ciudad')
                    ->searchable(),
                TextColumn::make('drc_estado')
                    ->searchable(),
                TextColumn::make('drc_codigo_postal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('drc_latitud')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('drc_longitud')
                    ->numeric()
                    ->sortable(),
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
