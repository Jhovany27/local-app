<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Repartidor;
use App\Models\Tienda;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Usuario')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('persona.per_telefono')
                    ->label('Teléfono')
                    ->default('—'),

                BadgeColumn::make('roles_nombres')
                    ->label('Roles')
                    ->getStateUsing(
                        fn($record) =>
                        $record->roles->pluck('rol_nombre')->join(', ') ?: 'Sin rol'
                    )
                    ->colors(['primary']),

                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('rol')
                    ->label('Filtrar por rol')
                    ->options([
                        1 => 'Admin',
                        2 => 'Cliente',
                        3 => 'Repartidor',
                        4 => 'Tienda',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            $query->whereHas(
                                'roles',
                                fn($q) => $q->where('rol_id', $data['value'])
                            );
                        }
                    }),
            ])

            ->recordActions([
                Action::make('editar_roles')
                    ->label('Roles')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->fillForm(fn($record) => [
                        'roles' => $record->roles->pluck('rol_id')->map(fn($id) => (int)$id)->toArray(),
                    ])
                    ->form([
                        CheckboxList::make('roles')
                            ->label('Roles asignados')
                            ->options([
                                1 => 'Admin',
                                2 => 'Cliente',
                                3 => 'Repartidor',
                                4 => 'Tienda',
                            ]),
                    ])
                    ->action(function ($record, array $data) {
                        $rolesNuevos   = collect($data['roles'] ?? [])->map(fn($v) => (int)$v);
                        $rolesActuales = $record->roles->pluck('rol_id')->map(fn($v) => (int)$v);

                        $agregados = $rolesNuevos->diff($rolesActuales);
                        $quitados  = $rolesActuales->diff($rolesNuevos);

                        // Sincronizar roles en tabla pivot
                        \App\Models\RoleUser::where('user_id', $record->id)->delete();
                        foreach ($rolesNuevos as $rolId) {
                            \App\Models\RoleUser::create([
                                'user_id'    => $record->id,
                                'usr_fk_rol' => $rolId,
                            ]);
                        }

                        // ── ROL TIENDA (id=4) ─────────────────────────
                        if ($quitados->contains(4)) {
                            $record->tiendas()->update(['tie_estado' => Tienda::ESTADO_RECHAZADA]);
                            Notification::make()
                                ->title('Rol tienda quitado')
                                ->body('Todas sus tiendas fueron desactivadas.')
                                ->warning()->send();
                        }

                        if ($agregados->contains(4)) {
                            $record->tiendas()->update(['tie_estado' => Tienda::ESTADO_APROBADA]);
                            Notification::make()
                                ->title('Rol tienda asignado')
                                ->body('Sus tiendas fueron reactivadas.')
                                ->success()->send();
                        }

                        // ── ROL REPARTIDOR (id=3) ─────────────────────
                        if ($quitados->contains(3)) {
                            Repartidor::where('user_id', $record->id)->update(['rep_estado' => 0]);
                            Notification::make()
                                ->title('Rol repartidor quitado')
                                ->body('Su cuenta de repartidor fue desactivada.')
                                ->warning()->send();
                        }

                        if ($agregados->contains(3)) {
                            Repartidor::where('user_id', $record->id)->update(['rep_estado' => 1]);
                            Notification::make()
                                ->title('Rol repartidor asignado')
                                ->body('Su cuenta de repartidor fue activada.')
                                ->success()->send();
                        }

                        if ($agregados->isEmpty() && $quitados->isEmpty()) {
                            Notification::make()->title('Sin cambios')->send();
                        }
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
