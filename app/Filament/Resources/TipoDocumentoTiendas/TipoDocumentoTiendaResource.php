<?php

namespace App\Filament\Resources\TipoDocumentoTiendas;

use App\Filament\Resources\TipoDocumentoTiendas\Pages\CreateTipoDocumentoTienda;
use App\Filament\Resources\TipoDocumentoTiendas\Pages\EditTipoDocumentoTienda;
use App\Filament\Resources\TipoDocumentoTiendas\Pages\ListTipoDocumentoTiendas;
use App\Filament\Resources\TipoDocumentoTiendas\Pages\ViewTipoDocumentoTienda;
use App\Filament\Resources\TipoDocumentoTiendas\Schemas\TipoDocumentoTiendaForm;
use App\Filament\Resources\TipoDocumentoTiendas\Schemas\TipoDocumentoTiendaInfolist;
use App\Filament\Resources\TipoDocumentoTiendas\Tables\TipoDocumentoTiendasTable;
use App\Models\TipoDocumentoTienda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoDocumentoTiendaResource extends Resource
{
    protected static ?string $model = TipoDocumentoTienda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TipoDocumentoTiendaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TipoDocumentoTiendaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoDocumentoTiendasTable::configure($table);
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
            'index' => ListTipoDocumentoTiendas::route('/'),
            'create' => CreateTipoDocumentoTienda::route('/create'),
            'view' => ViewTipoDocumentoTienda::route('/{record}'),
            'edit' => EditTipoDocumentoTienda::route('/{record}/edit'),
        ];
    }
}
