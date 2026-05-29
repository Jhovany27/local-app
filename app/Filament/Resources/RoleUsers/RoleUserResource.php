<?php

namespace App\Filament\Resources\RoleUsers;

use App\Filament\Resources\RoleUsers\Pages\CreateRoleUser;
use App\Filament\Resources\RoleUsers\Pages\EditRoleUser;
use App\Filament\Resources\RoleUsers\Pages\ListRoleUsers;
use App\Filament\Resources\RoleUsers\Pages\ViewRoleUser;
use App\Filament\Resources\RoleUsers\Schemas\RoleUserForm;
use App\Filament\Resources\RoleUsers\Schemas\RoleUserInfolist;
use App\Filament\Resources\RoleUsers\Tables\RoleUsersTable;
use App\Models\RoleUser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleUserResource extends Resource
{
    protected static ?string $model = RoleUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Tablas';

    public static function form(Schema $schema): Schema
    {
        return RoleUserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleUserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleUsersTable::configure($table);
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
            'index' => ListRoleUsers::route('/'),
            'create' => CreateRoleUser::route('/create'),
            'view' => ViewRoleUser::route('/{record}'),
            'edit' => EditRoleUser::route('/{record}/edit'),
        ];
    }
}
