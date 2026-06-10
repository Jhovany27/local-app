<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Models\Tienda;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class TiendasRelationManager extends RelationManager
{
    protected static string $relationship = 'tiendas';

    protected static ?string $title = 'Tiendas del usuario';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('tie_nombre')
                ->label('Nombre')
                ->required(),

            TextInput::make('tie_telefono')
                ->label('Teléfono'),

            TextInput::make('tie_direccion')
                ->label('Dirección'),

            Textarea::make('tie_descripcion')
                ->label('Descripción')
                ->columnSpanFull(),

            Select::make('tie_estado')
                ->label('Estado')
                ->options([
                    Tienda::ESTADO_PENDIENTE  => 'Pendiente',
                    Tienda::ESTADO_APROBADA   => 'Activa',
                    Tienda::ESTADO_RECHAZADA  => 'Rechazada',
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tie_nombre')
                    ->label('Tienda')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('tie_telefono')
                    ->label('Teléfono'),

                TextColumn::make('tie_direccion')
                    ->label('Dirección')
                    ->limit(30),

                BadgeColumn::make('tie_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match((int)$state) {
                        Tienda::ESTADO_APROBADA  => 'Activa',
                        Tienda::ESTADO_PENDIENTE => 'Pendiente',
                        Tienda::ESTADO_RECHAZADA => 'Rechazada',
                        default => '—',
                    })
                    ->colors([
                        'success' => fn($state) => (int)$state === Tienda::ESTADO_APROBADA,
                        'warning' => fn($state) => (int)$state === Tienda::ESTADO_PENDIENTE,
                        'danger'  => fn($state) => (int)$state === Tienda::ESTADO_RECHAZADA,
                    ]),

                TextColumn::make('tie_fecha_registro')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->recordActions([
                // Editar tienda — forceFill para campos fuera del fillable (tie_estado)
                \Filament\Actions\EditAction::make()
                    ->using(function ($record, array $data) {
                        $record->forceFill($data)->save();
                        return $record;
                    }),

                // Activar
                Action::make('activar')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => (int)$record->tie_estado !== Tienda::ESTADO_APROBADA)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->tie_estado = Tienda::ESTADO_APROBADA;
                        $record->save();
                        $user = $record->user;
                        if ($user && !$user->hasRol('tienda')) {
                            $user->roles()->attach(4);
                        }
                        Notification::make()->title('Tienda activada')->success()->send();
                    }),

                // Desactivar
                Action::make('desactivar')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => (int)$record->tie_estado === Tienda::ESTADO_APROBADA)
                    ->requiresConfirmation()
                    ->modalDescription('Si no tiene otras tiendas activas, se quitará el rol de tienda.')
                    ->action(function ($record) {
                        $record->tie_estado = Tienda::ESTADO_RECHAZADA;
                        $record->save();

                        $user = $record->user;
                        if ($user) {
                            $otrasTiendas = $user->tiendas()
                                ->where('tie_id', '!=', $record->tie_id)
                                ->where('tie_estado', Tienda::ESTADO_APROBADA)
                                ->exists();

                            if (!$otrasTiendas) {
                                $user->roles()->detach(4);
                            }
                        }

                        Notification::make()->title('Tienda desactivada')->danger()->send();
                    }),

                // Eliminar
                \Filament\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Si era la única tienda activa, quitar rol
                        $user = $record->user;
                        if ($user && (int)$record->tie_estado === Tienda::ESTADO_APROBADA) {
                            $otrasTiendas = $user->tiendas()
                                ->where('tie_id', '!=', $record->tie_id)
                                ->where('tie_estado', Tienda::ESTADO_APROBADA)
                                ->exists();
                            if (!$otrasTiendas) {
                                $user->roles()->detach(4);
                            }
                        }
                    }),
            ])

            ->toolbarActions([]);
    }
}