<?php

namespace App\Filament\Resources\TipoDocumentoRepartidors;

use App\Filament\Resources\TipoDocumentoRepartidors\Pages\CreateTipoDocumentoRepartidor;
use App\Filament\Resources\TipoDocumentoRepartidors\Pages\EditTipoDocumentoRepartidor;
use App\Filament\Resources\TipoDocumentoRepartidors\Pages\ListTipoDocumentoRepartidors;
use App\Filament\Resources\TipoDocumentoRepartidors\Pages\ViewTipoDocumentoRepartidor;
use App\Filament\Resources\TipoDocumentoRepartidors\Schemas\TipoDocumentoRepartidorForm;
use App\Filament\Resources\TipoDocumentoRepartidors\Schemas\TipoDocumentoRepartidorInfolist;
use App\Filament\Resources\TipoDocumentoRepartidors\Tables\TipoDocumentoRepartidorsTable;
use App\Models\TipoDocumentoRepartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoDocumentoRepartidorResource extends Resource
{
    protected static ?string $model = TipoDocumentoRepartidor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return TipoDocumentoRepartidorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TipoDocumentoRepartidorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoDocumentoRepartidorsTable::configure($table);
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
            'index' => ListTipoDocumentoRepartidors::route('/'),
            'create' => CreateTipoDocumentoRepartidor::route('/create'),
            'view' => ViewTipoDocumentoRepartidor::route('/{record}'),
            'edit' => EditTipoDocumentoRepartidor::route('/{record}/edit'),
        ];
    }
}
