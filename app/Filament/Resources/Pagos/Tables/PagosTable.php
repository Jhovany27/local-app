<?php

namespace App\Filament\Resources\Pagos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pag_fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('pedido.cliente.user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pedido.tienda.tie_nombre')
                    ->label('Tienda')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pag_metodo_pago')
                    ->label('Método de pago')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'tarjeta' => 'Tarjeta',
                        'efectivo' => 'Efectivo',
                        default    => $state ?? '—',
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'tarjeta' => 'info',
                        'efectivo' => 'success',
                        default    => 'gray',
                    }),

                TextColumn::make('pag_monto')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('pag_estado')
                    ->label('Estado del pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aceptado'   => 'success',
                        'En proceso' => 'warning',
                        'Rechazado'  => 'danger',
                        default      => 'gray',
                    }),

                TextColumn::make('pedido.ped_estado')
                    ->label('Estado del pedido')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completado'  => 'success',
                        'Cancelado'   => 'danger',
                        'En camino'   => 'info',
                        'Pendiente'   => 'warning',
                        default       => 'gray',
                    })
                    ->placeholder('Sin estado'),
            ])
            ->defaultSort('pag_fecha', 'desc')
            ->filters([
                SelectFilter::make('pag_metodo_pago')
                    ->label('Método de pago')
                    ->options([
                        'Efectivo' => 'Efectivo',
                        'Tarjeta'  => 'Tarjeta',
                    ]),

                SelectFilter::make('pag_estado')
                    ->label('Estado del pago')
                    ->options([
                        'En proceso' => 'En proceso',
                        'Aceptado'   => 'Aceptado',
                        'Rechazado'  => 'Rechazado',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
