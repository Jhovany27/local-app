<?php

namespace App\Filament\Resources\Repartidors;

use App\Filament\Resources\Repartidors\Pages\CreateRepartidor;
use App\Filament\Resources\Repartidors\Pages\EditRepartidor;
use App\Filament\Resources\Repartidors\Pages\ListRepartidors;
use App\Filament\Resources\Repartidors\Pages\ViewRepartidor;
use App\Filament\Resources\Repartidors\Schemas\RepartidorForm;
use App\Filament\Resources\Repartidors\Schemas\RepartidorInfolist;
use App\Filament\Resources\Repartidors\Tables\RepartidorsTable;
use App\Models\Repartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RepartidorResource extends Resource
{
    protected static ?string $model = Repartidor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return RepartidorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RepartidorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RepartidorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepartidors::route('/'),
            'create' => CreateRepartidor::route('/create'),
            'view' => ViewRepartidor::route('/{record}'),
            'edit' => EditRepartidor::route('/{record}/edit'),
        ];
    }
}
