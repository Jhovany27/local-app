<?php

namespace App\Filament\Resources\Calificacions;

use App\Filament\Resources\Calificacions\Pages\CreateCalificacion;
use App\Filament\Resources\Calificacions\Pages\EditCalificacion;
use App\Filament\Resources\Calificacions\Pages\ListCalificacions;
use App\Filament\Resources\Calificacions\Pages\ViewCalificacion;
use App\Filament\Resources\Calificacions\Schemas\CalificacionForm;
use App\Filament\Resources\Calificacions\Schemas\CalificacionInfolist;
use App\Filament\Resources\Calificacions\Tables\CalificacionsTable;
use App\Models\Calificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CalificacionResource extends Resource
{
    protected static ?string $model = Calificacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return CalificacionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CalificacionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CalificacionsTable::configure($table);
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
            'index' => ListCalificacions::route('/'),
            'create' => CreateCalificacion::route('/create'),
            'view' => ViewCalificacion::route('/{record}'),
            'edit' => EditCalificacion::route('/{record}/edit'),
        ];
    }
}
