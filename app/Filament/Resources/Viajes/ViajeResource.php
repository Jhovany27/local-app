<?php

namespace App\Filament\Resources\Viajes;

use App\Filament\Resources\Viajes\Tables\ViajesTable;
use App\Models\AsignacionRepartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ViajeResource extends Resource
{
    protected static ?string $model = AsignacionRepartidor::class;
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-truck";
    protected static ?string $navigationLabel = "Viajes";
    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';
    protected static ?int $navigationSort = 6;
    protected static ?string $modelLabel = "Viaje";
    protected static ?string $pluralModelLabel = "Viajes";

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return ViajesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(["repartidor.user.persona", "pedido.tienda", "pedido.cliente.user.persona", "pedido.pago"])
            ->latest("asr_fecha");
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return ["index" => Pages\ListViajes::route("/")];
    }
}