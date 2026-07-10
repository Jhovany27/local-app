<?php

namespace App\Filament\Resources\Finanzas;

use App\Models\Wallet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Wallets';

    protected static ?int $navigationSort = 20;

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
                TextColumn::make('wal_tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => $state === 'tienda' ? 'success' : 'info'),

                TextColumn::make('nombre_owner')
                    ->label('Propietario')
                    ->getStateUsing(fn($record) => $record->nombre_owner)
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('tienda', fn($q) => $q->where('tie_nombre', 'like', "%$search%"))
                              ->orWhereHas('repartidor.user.persona', fn($q) => $q->where('per_nombre', 'like', "%$search%"));
                    }),

                TextColumn::make('wal_saldo_disponible')
                    ->label('Disponible')
                    ->money('MXN')
                    ->sortable()
                    ->color('success'),

                TextColumn::make('wal_saldo_pendiente')
                    ->label('Pendiente')
                    ->money('MXN')
                    ->sortable()
                    ->color('warning'),

                TextColumn::make('saldo_total')
                    ->label('Total')
                    ->getStateUsing(fn($record) => $record->wal_saldo_disponible + $record->wal_saldo_pendiente)
                    ->money('MXN')
                    ->weight('bold'),
            ])
            ->defaultSort('wal_id', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Finanzas\Pages\ListWallets::route('/'),
        ];
    }
}
