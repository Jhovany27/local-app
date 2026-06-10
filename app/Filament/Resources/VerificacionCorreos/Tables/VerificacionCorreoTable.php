<?php

namespace App\Filament\Resources\VerificacionCorreos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class VerificacionCorreoTable
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

                TextColumn::make('roles_nombres')
                    ->label('Rol')
                    ->getStateUsing(
                        fn($record) => $record->roles->pluck('rol_nombre')->join(', ') ?: 'Sin rol'
                    )
                    ->badge()
                    ->color('primary'),

                IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn($record) => !is_null($record->email_verified_at)),

                TextColumn::make('email_verified_at')
                    ->label('Fecha de verificación')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Sin verificar')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('verificado')
                    ->label('Estado de verificación')
                    ->options([
                        'verificado'   => 'Verificados',
                        'sin_verificar' => 'Sin verificar',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'verificado') {
                            $query->whereNotNull('email_verified_at');
                        } elseif ($data['value'] === 'sin_verificar') {
                            $query->whereNull('email_verified_at');
                        }
                    }),

                SelectFilter::make('rol')
                    ->label('Filtrar por rol')
                    ->options([
                        2 => 'Cliente',
                        3 => 'Repartidor',
                        4 => 'Tienda',
                    ])
                    ->query(fn($query, $data) =>
                        $data['value']
                            ? $query->whereHas('roles', fn($q) => $q->where('rol_id', $data['value']))
                            : $query
                    ),
            ])

            ->recordActions([
                Action::make('verificar_dominio')
                    ->label('Verificar dominio')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('gray')
                    ->action(function ($record) {
                        $email  = $record->email;
                        $domain = substr(strrchr($email, '@'), 1);

                        if (empty($domain)) {
                            Notification::make()
                                ->title('Correo inválido')
                                ->body("No se pudo extraer el dominio de «{$email}».")
                                ->danger()
                                ->send();
                            return;
                        }

                        $tieneMx = checkdnsrr($domain, 'MX');

                        if ($tieneMx) {
                            Notification::make()
                                ->title('Dominio válido')
                                ->body("El dominio «{$domain}» tiene registros MX — el correo puede existir.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Dominio sin registros MX')
                                ->body("El dominio «{$domain}» no tiene servidores de correo configurados.")
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('enviar_verificacion')
                    ->label('Enviar verificación')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->visible(fn($record) => is_null($record->email_verified_at))
                    ->requiresConfirmation()
                    ->modalHeading('Enviar correo de verificación')
                    ->modalDescription(fn($record) => "Se enviará un correo de verificación a {$record->email}.")
                    ->modalSubmitActionLabel('Enviar')
                    ->action(function ($record) {
                        $record->sendEmailVerificationNotification();

                        Notification::make()
                            ->title('Correo enviado')
                            ->body("Se envió el correo de verificación a {$record->email}.")
                            ->success()
                            ->send();
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('enviar_verificacion_masiva')
                        ->label('Enviar verificación')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Enviar verificación masiva')
                        ->modalDescription('Solo se enviará a los usuarios que aún no han verificado su correo.')
                        ->modalSubmitActionLabel('Enviar a todos')
                        ->action(function (Collection $records) {
                            $enviados = 0;
                            foreach ($records as $user) {
                                if (is_null($user->email_verified_at)) {
                                    $user->sendEmailVerificationNotification();
                                    $enviados++;
                                }
                            }

                            Notification::make()
                                ->title("Correos enviados: {$enviados}")
                                ->body($enviados === 0 ? 'Todos los seleccionados ya estaban verificados.' : "Se enviaron {$enviados} correos de verificación.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
