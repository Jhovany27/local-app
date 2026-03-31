<?php

namespace App\Filament\Resources\AsignacionRepartidors;

use App\Filament\Resources\AsignacionRepartidors\Pages\CreateAsignacionRepartidor;
use App\Filament\Resources\AsignacionRepartidors\Pages\EditAsignacionRepartidor;
use App\Filament\Resources\AsignacionRepartidors\Pages\ListAsignacionRepartidors;
use App\Filament\Resources\AsignacionRepartidors\Pages\ViewAsignacionRepartidor;
use App\Filament\Resources\AsignacionRepartidors\Schemas\AsignacionRepartidorForm;
use App\Filament\Resources\AsignacionRepartidors\Schemas\AsignacionRepartidorInfolist;
use App\Filament\Resources\AsignacionRepartidors\Tables\AsignacionRepartidorsTable;
use App\Models\AsignacionRepartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AsignacionRepartidorResource extends Resource
{
    protected static ?string $model = AsignacionRepartidor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AsignacionRepartidorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AsignacionRepartidorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AsignacionRepartidorsTable::configure($table);
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
            'index' => ListAsignacionRepartidors::route('/'),
            'create' => CreateAsignacionRepartidor::route('/create'),
            'view' => ViewAsignacionRepartidor::route('/{record}'),
            'edit' => EditAsignacionRepartidor::route('/{record}/edit'),
        ];
    }
}
