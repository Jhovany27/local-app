<?php

namespace App\Filament\Resources\Finanzas;

use App\Models\DeudaRepartidor;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeudaRepartidorResource extends Resource
{
    protected static ?string $model = DeudaRepartidor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Deudas repartidores';

    protected static ?int $navigationSort = 22;

    public static function getNavigationGroup(): ?string { return 'Finanzas'; }

    public static function canCreate(): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('repartidor.user.persona.per_nombre')
                    ->label('Repartidor')
                    ->formatStateUsing(function ($state, $record) {
                        $p = $record->repartidor?->user?->persona;
                        return trim(($p?->per_nombre ?? '') . ' ' . ($p?->per_paterno ?? '')) ?: '—';
                    })
                    ->searchable(),

                TextColumn::make('pedido.ped_codigo')
                    ->label('Pedido')
                    ->formatStateUsing(fn($state) => $state ? "#{$state}" : '—'),

                TextColumn::make('dre_monto')
                    ->label('Monto adeudado')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('dre_estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match($state) {
                        'pendiente' => 'warning',
                        'pagada'    => 'success',
                        default     => 'gray',
                    }),

                TextColumn::make('dre_fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y'),

                TextColumn::make('dre_fecha_pago')
                    ->label('Pagada el')
                    ->dateTime('d/m/Y')
                    ->placeholder('—'),
            ])

            ->recordActions([
                Action::make('marcar_pagada')
                    ->label('Marcar pagada')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->dre_estado === DeudaRepartidor::ESTADO_PENDIENTE)
                    ->action(function ($record) {
                        $record->update([
                            'dre_estado'      => DeudaRepartidor::ESTADO_PAGADA,
                            'dre_fecha_pago'  => now(),
                        ]);
                    }),
            ])

            ->filters([
                SelectFilter::make('dre_estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagada'    => 'Pagada',
                    ])
                    ->default('pendiente'),
            ])

            ->defaultSort('dre_fecha', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Finanzas\Pages\ListDeudas::route('/'),
        ];
    }
}
