<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_principal')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('pro_nombre')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('pro_marca')
                    ->label('Marca')
                    ->searchable(),
                TextColumn::make('pro_precio_prove')
                    ->label('Precio de proveedor')
                     ->money('MXN')
                    ->sortable(),
                TextColumn::make('pro_precio_venta')
                    ->label('Precio de venta')
                     ->money('MXN')
                    ->sortable(),
                TextColumn::make('pro_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo')
                    ->sortable(),
                TextColumn::make('tienda.tie_nombre')
                    ->label('Tienda')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categoria_producto.cat_nombre')
                    ->label('Categoría')
                    ->searchable()
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
