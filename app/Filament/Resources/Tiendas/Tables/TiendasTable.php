<?php

namespace App\Filament\Resources\Tiendas\Tables;

use App\Models\Tienda;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TiendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tie_nombre')
                    ->label('Tienda')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('user.persona.per_nombre')
                    ->label('Propietario')
                    ->formatStateUsing(function ($state, $record) {
                        $persona = $record->user?->persona;
                        if (!$persona) return $record->user?->email ?? '—';
                        return trim("{$persona->per_nombre} {$persona->per_paterno}");
                    })
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('user', function ($q) use ($search) {
                            $q->where('email', 'like', "%{$search}%")
                              ->orWhereHas('persona', function ($q2) use ($search) {
                                  $q2->where('per_nombre', 'like', "%{$search}%")
                                     ->orWhere('per_paterno', 'like', "%{$search}%");
                              });
                        });
                    }),

                TextColumn::make('user.email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('tie_telefono')
                    ->label('Teléfono'),

                BadgeColumn::make('tie_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match((int)$state) {
                        Tienda::ESTADO_APROBADA  => 'Activa',
                        Tienda::ESTADO_PENDIENTE => 'Pendiente',
                        Tienda::ESTADO_RECHAZADA => 'Rechazada',
                        default => 'Desconocido',
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

            ->filters([
                SelectFilter::make('tie_estado')
                    ->label('Estado')
                    ->options([
                        Tienda::ESTADO_APROBADA  => 'Activas',
                        Tienda::ESTADO_PENDIENTE => 'Pendientes',
                        Tienda::ESTADO_RECHAZADA => 'Rechazadas',
                    ]),
            ])

            ->recordActions([
                // Activar tienda
                Action::make('activar')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => (int)$record->tie_estado !== Tienda::ESTADO_APROBADA)
                    ->requiresConfirmation()
                    ->modalHeading('¿Activar esta tienda?')
                    ->modalDescription('Se asignará el rol de tienda al propietario.')
                    ->action(function ($record) {
                        $record->update(['tie_estado' => Tienda::ESTADO_APROBADA]);
                        $user = $record->user;
                        if ($user && !$user->hasRol('tienda')) {
                            $user->roles()->attach(4);
                        }
                        Notification::make()->title('Tienda activada')->success()->send();
                    }),

                // Desactivar tienda
                Action::make('desactivar')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => (int)$record->tie_estado === Tienda::ESTADO_APROBADA)
                    ->requiresConfirmation()
                    ->modalHeading('¿Desactivar esta tienda?')
                    ->modalDescription('Se quitará el rol de tienda al propietario si no tiene otras tiendas activas.')
                    ->action(function ($record) {
                        $record->update(['tie_estado' => Tienda::ESTADO_RECHAZADA]);

                        // Quitar rol tienda si no tiene otras tiendas activas
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

                // Ver propietario
                Action::make('ver_propietario')
                    ->label('Propietario')
                    ->icon('heroicon-o-user')
                    ->color('gray')
                    ->url(fn($record) => \App\Filament\Resources\Users\UserResource::getUrl('edit', ['record' => $record->user_id])),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('tie_fecha_registro', 'desc');
    }
}