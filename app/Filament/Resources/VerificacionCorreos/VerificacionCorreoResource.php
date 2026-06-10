<?php

namespace App\Filament\Resources\VerificacionCorreos;

use App\Filament\Resources\VerificacionCorreos\Pages\ListVerificacionCorreos;
use App\Filament\Resources\VerificacionCorreos\Tables\VerificacionCorreoTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VerificacionCorreoResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;
    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';
    protected static ?string $navigationLabel = 'Verificación de Correos';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Verificación de Correos';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return VerificacionCorreoTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['roles', 'persona']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVerificacionCorreos::route('/'),
        ];
    }
}
