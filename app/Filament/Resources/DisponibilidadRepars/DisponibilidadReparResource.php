<?php

namespace App\Filament\Resources\DisponibilidadRepars;

use App\Filament\Resources\DisponibilidadRepars\Pages\CreateDisponibilidadRepar;
use App\Filament\Resources\DisponibilidadRepars\Pages\EditDisponibilidadRepar;
use App\Filament\Resources\DisponibilidadRepars\Pages\ListDisponibilidadRepars;
use App\Filament\Resources\DisponibilidadRepars\Pages\ViewDisponibilidadRepar;
use App\Filament\Resources\DisponibilidadRepars\Schemas\DisponibilidadReparForm;
use App\Filament\Resources\DisponibilidadRepars\Schemas\DisponibilidadReparInfolist;
use App\Filament\Resources\DisponibilidadRepars\Tables\DisponibilidadReparsTable;
use App\Models\DisponibilidadRepar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DisponibilidadReparResource extends Resource
{
    protected static ?string $model = DisponibilidadRepar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return DisponibilidadReparForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DisponibilidadReparInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DisponibilidadReparsTable::configure($table);
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
            'index' => ListDisponibilidadRepars::route('/'),
            'create' => CreateDisponibilidadRepar::route('/create'),
            'view' => ViewDisponibilidadRepar::route('/{record}'),
            'edit' => EditDisponibilidadRepar::route('/{record}/edit'),
        ];
    }
}
