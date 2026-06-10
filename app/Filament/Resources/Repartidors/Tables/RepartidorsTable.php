<?php

namespace App\Filament\Resources\Repartidors\Tables;

use App\Filament\Resources\Repartidors\RepartidorResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RepartidorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.persona.per_nombre')
                    ->label('Nombre')
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
                    })
                    ->weight('bold'),

                TextColumn::make('user.email')
                    ->label('Correo')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('rep_tipo_vehiculo')
                    ->label('Vehículo')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('rep_entidad')
                    ->label('Estado')
                    ->placeholder('—'),

                TextColumn::make('rep_ciudad')
                    ->label('Municipio')
                    ->placeholder('—'),

                BadgeColumn::make('rep_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        0       => 'Pendiente',
                        1       => 'Aprobado',
                        2       => 'Rechazado',
                        default => 'Desconocido',
                    })
                    ->colors([
                        'success' => fn ($state) => (int) $state === 1,
                        'warning' => fn ($state) => (int) $state === 0,
                        'danger'  => fn ($state) => (int) $state === 2,
                    ]),
            ])

            ->filters([
                SelectFilter::make('rep_estado')
                    ->label('Estado')
                    ->options([
                        0 => 'Pendientes',
                        1 => 'Aprobados',
                        2 => 'Rechazados',
                    ]),
            ])

            ->recordActions([
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => (int) $record->rep_estado !== 1)
                    ->requiresConfirmation()
                    ->modalHeading('¿Aprobar este repartidor?')
                    ->modalDescription('Se asignará el rol de repartidor al usuario.')
                    ->action(function ($record) {
                        $record->rep_estado         = 1;
                        $record->rep_motivo_rechazo = null;
                        $record->save();

                        $user = $record->user;
                        if ($user && !$user->hasRol('repartidor')) {
                            $user->roles()->attach(3);
                        }

                        Notification::make()->title('Repartidor aprobado')->success()->send();
                    }),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => (int) $record->rep_estado !== 2)
                    ->form([
                        Textarea::make('motivo')
                            ->label('Motivo del rechazo')
                            ->placeholder('Explica al repartidor por qué se rechazó su solicitud...')
                            ->required()
                            ->minLength(10)
                            ->rows(4),
                    ])
                    ->modalHeading('Rechazar repartidor')
                    ->modalDescription('El repartidor verá este mensaje al iniciar sesión.')
                    ->modalSubmitActionLabel('Confirmar rechazo')
                    ->action(function ($record, array $data) {
                        $record->rep_estado         = 2;
                        $record->rep_motivo_rechazo = $data['motivo'];
                        $record->save();

                        Notification::make()->title('Repartidor rechazado')->danger()->send();
                    }),

                ViewAction::make()
                    ->label('Ver detalle'),
            ])

            ->recordUrl(fn ($record) => RepartidorResource::getUrl('view', ['record' => $record->rep_id]))

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('rep_id', 'desc');
    }
}
