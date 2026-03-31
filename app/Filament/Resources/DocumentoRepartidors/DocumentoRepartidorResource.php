<?php

namespace App\Filament\Resources\DocumentoRepartidors;

use App\Filament\Resources\DocumentoRepartidors\Pages\CreateDocumentoRepartidor;
use App\Filament\Resources\DocumentoRepartidors\Pages\EditDocumentoRepartidor;
use App\Filament\Resources\DocumentoRepartidors\Pages\ListDocumentoRepartidors;
use App\Filament\Resources\DocumentoRepartidors\Pages\ViewDocumentoRepartidor;
use App\Filament\Resources\DocumentoRepartidors\Schemas\DocumentoRepartidorForm;
use App\Filament\Resources\DocumentoRepartidors\Schemas\DocumentoRepartidorInfolist;
use App\Filament\Resources\DocumentoRepartidors\Tables\DocumentoRepartidorsTable;
use App\Models\DocumentoRepartidor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentoRepartidorResource extends Resource
{
    protected static ?string $model = DocumentoRepartidor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DocumentoRepartidorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DocumentoRepartidorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentoRepartidorsTable::configure($table);
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
            'index' => ListDocumentoRepartidors::route('/'),
            'create' => CreateDocumentoRepartidor::route('/create'),
            'view' => ViewDocumentoRepartidor::route('/{record}'),
            'edit' => EditDocumentoRepartidor::route('/{record}/edit'),
        ];
    }
}
