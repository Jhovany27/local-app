<?php

namespace App\Filament\Store\Resources\Ventas\Tables;

use App\Models\ConfiguracionComision;
use App\Models\Venta;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class VentasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('ven_id')
                    ->label('# Venta')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '#' . str_pad($state, 5, '0', STR_PAD_LEFT)),

                TextColumn::make('ven_fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('detalles_count')
                    ->label('Productos')
                    ->counts('detalles')
                    ->suffix(' producto(s)'),

                TextColumn::make('ganancia_tienda')
                    ->label('Tu ganancia')
                    ->getStateUsing(function ($record) {
                        $subtotal = (float) ($record->detalles_sum_vde_subtotal ?? $record->detalles->sum('vde_subtotal'));
                        $pct = ConfiguracionComision::porcentajeActual();
                        return round($subtotal * (1 - $pct / 100), 2);
                    })
                    ->money('MXN')
                    ->weight('bold'),

                BadgeColumn::make('ven_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match ((int) $state) {
                        Venta::ESTADO_PENDIENTE  => 'Pendiente',
                        Venta::ESTADO_COMPLETADA => 'Completada',
                        Venta::ESTADO_CANCELADA  => 'Cancelada',
                        default                  => 'Desconocido',
                    })
                    ->colors([
                        'warning' => fn($state) => (int) $state === Venta::ESTADO_PENDIENTE,
                        'success' => fn($state) => (int) $state === Venta::ESTADO_COMPLETADA,
                        'danger'  => fn($state) => (int) $state === Venta::ESTADO_CANCELADA,
                    ]),

            ])

            ->recordActions([

                Action::make('ver')
                    ->label('Ver detalle')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => \App\Filament\Store\Resources\Ventas\VentaResource::getUrl('view', ['record' => $record->ven_id])),

            ])

            ->toolbarActions([])

            ->filters([

                SelectFilter::make('ven_estado')
                    ->label('Estado')
                    ->options([
                        Venta::ESTADO_PENDIENTE  => 'Pendiente',
                        Venta::ESTADO_COMPLETADA => 'Completada',
                        Venta::ESTADO_CANCELADA  => 'Cancelada',
                    ]),

                Filter::make('hoy')
                    ->label('Ventas de hoy')
                    ->query(fn(Builder $query) => $query->whereDate('ven_fecha', today())),

                Filter::make('esta_semana')
                    ->label('Esta semana')
                    ->query(fn(Builder $query) => $query->whereBetween('ven_fecha', [now()->startOfWeek(), now()->endOfWeek()])),

            ]);
    }
}