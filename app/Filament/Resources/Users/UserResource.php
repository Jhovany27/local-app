<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\RelationManagers\TiendasRelationManager;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\Repartidor;
use App\Models\Tienda;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Usuarios';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            ComponentsSection::make('Datos del usuario')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Usuario')
                        ->required(),

                    TextInput::make('email')
                        ->label('Correo')
                        ->email()
                        ->required(),
                ]),

            ComponentsSection::make('Roles')
                ->schema([
                    CheckboxList::make('roles')
                        ->label('Roles asignados')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'rol_nombre',
                        )
                        ->columns(2)
                        ->afterStateUpdated(function ($state, $oldState, $record) {
                            if (!$record) return;

                            // Convertir a arrays de IDs
                            $nuevos   = collect($state ?? [])->map(fn($v) => (int)$v);
                            $anteriores = collect($oldState ?? [])->map(fn($v) => (int)$v);

                            $agregados = $nuevos->diff($anteriores);
                            $quitados  = $anteriores->diff($nuevos);

                            // ── ROL TIENDA (id=4) ─────────────────────────
                            if ($quitados->contains(4)) {
                                // Quitar rol → desactivar todas sus tiendas
                                $record->tiendas()->update(['tie_estado' => Tienda::ESTADO_RECHAZADA]);
                                Notification::make()
                                    ->title('Rol tienda quitado')
                                    ->body('Todas sus tiendas fueron desactivadas.')
                                    ->warning()->send();
                            }

                            if ($agregados->contains(4)) {
                                // Poner rol → activar sus tiendas aprobadas (las que no fueron rechazadas manualmente)
                                $record->tiendas()
                                    ->where('tie_estado', Tienda::ESTADO_RECHAZADA)
                                    ->update(['tie_estado' => Tienda::ESTADO_APROBADA]);
                                Notification::make()
                                    ->title('Rol tienda asignado')
                                    ->body('Sus tiendas fueron reactivadas.')
                                    ->success()->send();
                            }

                            // ── ROL REPARTIDOR (id=3) ─────────────────────
                            if ($quitados->contains(3)) {
                                // Quitar rol → rep_estado = 0 (pendiente/inactivo)
                                Repartidor::where('user_id', $record->id)
                                    ->update(['rep_estado' => 0]);
                                Notification::make()
                                    ->title('Rol repartidor quitado')
                                    ->body('Su cuenta de repartidor fue desactivada.')
                                    ->warning()->send();
                            }

                            if ($agregados->contains(3)) {
                                // Poner rol → rep_estado = 1 (activo)
                                Repartidor::where('user_id', $record->id)
                                    ->update(['rep_estado' => 1]);
                                Notification::make()
                                    ->title('Rol repartidor asignado')
                                    ->body('Su cuenta de repartidor fue activada.')
                                    ->success()->send();
                            }
                        }),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['roles', 'persona', 'tiendas']);
    }

    public static function getRelations(): array
    {
        return [
            TiendasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit'  => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
