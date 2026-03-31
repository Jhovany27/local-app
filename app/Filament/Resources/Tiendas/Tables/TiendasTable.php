<?php

namespace App\Filament\Resources\Tiendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TiendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tie_nombre')
                    ->label('Nombre de la tienda')
                    ->searchable(),

                TextColumn::make('tie_telefono')
                    ->label('Teléfono')
                    ->searchable(),

                TextColumn::make('tie_direccion')
                    ->label('Dirección')
                    ->searchable(),

                TextColumn::make('tie_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo')
                    ->sortable(),

                TextColumn::make('tie_fecha_registro')
                    ->label('Fecha de registro')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Usuario')
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