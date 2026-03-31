<?php

namespace App\Filament\Resources\DocumentoTiendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentoTiendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dot_ruta')
                    ->searchable(),
                TextColumn::make('dot_fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('dot_fk_tienda')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dot_fk_tipo_documento')
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
