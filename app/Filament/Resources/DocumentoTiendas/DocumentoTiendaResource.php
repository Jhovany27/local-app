<?php

namespace App\Filament\Resources\DocumentoTiendas;

use App\Filament\Resources\DocumentoTiendas\Pages\CreateDocumentoTienda;
use App\Filament\Resources\DocumentoTiendas\Pages\EditDocumentoTienda;
use App\Filament\Resources\DocumentoTiendas\Pages\ListDocumentoTiendas;
use App\Filament\Resources\DocumentoTiendas\Pages\ViewDocumentoTienda;
use App\Filament\Resources\DocumentoTiendas\Schemas\DocumentoTiendaForm;
use App\Filament\Resources\DocumentoTiendas\Schemas\DocumentoTiendaInfolist;
use App\Filament\Resources\DocumentoTiendas\Tables\DocumentoTiendasTable;
use App\Models\DocumentoTienda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentoTiendaResource extends Resource
{
    protected static ?string $model = DocumentoTienda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return DocumentoTiendaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentoTiendaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentoTiendasTable::configure($table);
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
            'index' => ListDocumentoTiendas::route('/'),
            'create' => CreateDocumentoTienda::route('/create'),
            'view' => ViewDocumentoTienda::route('/{record}'),
            'edit' => EditDocumentoTienda::route('/{record}/edit'),
        ];
    }
}
