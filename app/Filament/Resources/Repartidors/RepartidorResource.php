<?php

namespace App\Filament\Resources\Repartidors;

use App\Filament\Resources\Repartidors\Pages\ListRepartidors;
use App\Filament\Resources\Repartidors\Pages\ViewRepartidor;
use App\Models\Repartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RepartidorResource extends Resource
{
    protected static ?string $model = Repartidor::class;

    protected static string|BackedEnum|null $navigationIcon  = Heroicon::OutlinedTruck;
    protected static ?string $navigationLabel                 = 'Repartidores';
    protected static string|\UnitEnum|null $navigationGroup   = 'Gestión';
    protected static ?int $navigationSort                     = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Repartidors\Tables\RepartidorsTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['user.persona', 'documentos']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepartidors::route('/'),
            'view'  => ViewRepartidor::route('/{record}'),
        ];
    }
}
