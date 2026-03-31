<?php

namespace App\Filament\Resources\DocumentoRepartidors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentoRepartidorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dor_ruta')
                    ->searchable(),
                TextColumn::make('dor_fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('dor_fk_repartidor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dor_fk_tipo_documento')
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
