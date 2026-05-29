<?php

namespace App\Filament\Resources\Direccions;

use App\Filament\Resources\Direccions\Pages\CreateDireccion;
use App\Filament\Resources\Direccions\Pages\EditDireccion;
use App\Filament\Resources\Direccions\Pages\ListDireccions;
use App\Filament\Resources\Direccions\Pages\ViewDireccion;
use App\Filament\Resources\Direccions\Schemas\DireccionForm;
use App\Filament\Resources\Direccions\Schemas\DireccionInfolist;
use App\Filament\Resources\Direccions\Tables\DireccionsTable;
use App\Models\Direccion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DireccionResource extends Resource
{
    protected static ?string $model = Direccion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return DireccionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DireccionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DireccionsTable::configure($table);
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
            'index' => ListDireccions::route('/'),
            'create' => CreateDireccion::route('/create'),
            'view' => ViewDireccion::route('/{record}'),
            'edit' => EditDireccion::route('/{record}/edit'),
        ];
    }
}
