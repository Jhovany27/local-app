<?php

namespace App\Filament\Resources\Fachadas;

use App\Filament\Resources\Fachadas\Pages\CreateFachada;
use App\Filament\Resources\Fachadas\Pages\EditFachada;
use App\Filament\Resources\Fachadas\Pages\ListFachadas;
use App\Filament\Resources\Fachadas\Pages\ViewFachada;
use App\Filament\Resources\Fachadas\Schemas\FachadaForm;
use App\Filament\Resources\Fachadas\Schemas\FachadaInfolist;
use App\Filament\Resources\Fachadas\Tables\FachadasTable;
use App\Models\Fachada;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FachadaResource extends Resource
{
    protected static ?string $model = Fachada::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FachadaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FachadaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FachadasTable::configure($table);
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
            'index' => ListFachadas::route('/'),
            'create' => CreateFachada::route('/create'),
            'view' => ViewFachada::route('/{record}'),
            'edit' => EditFachada::route('/{record}/edit'),
        ];
    }
}
