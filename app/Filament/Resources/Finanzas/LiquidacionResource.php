<?php

namespace App\Filament\Resources\Finanzas;

use App\Models\Liquidacion;
use App\Models\MovimientoWallet;
use App\Models\Repartidor;
use App\Models\Tienda;
use App\Models\Wallet;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LiquidacionResource extends Resource
{
    protected static ?string $model = Liquidacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Liquidaciones';

    protected static ?int $navigationSort = 23;

    public static function getNavigationGroup(): ?string { return 'Finanzas'; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('liq_tipo')
                ->label('Tipo')
                ->options(['tienda' => 'Tienda', 'repartidor' => 'Repartidor'])
                ->required()
                ->live(),

            Select::make('liq_fk_tienda')
                ->label('Tienda')
                ->options(fn() => Tienda::pluck('tie_nombre', 'tie_id'))
                ->searchable()
                ->visible(fn($get) => $get('liq_tipo') === 'tienda')
                ->required(fn($get) => $get('liq_tipo') === 'tienda'),

            Select::make('liq_fk_repartidor')
                ->label('Repartidor')
                ->options(fn() => Repartidor::with('user.persona')
                    ->get()
                    ->mapWithKeys(fn($r) => [
                        $r->rep_id => trim(($r->user?->persona?->per_nombre ?? '') . ' ' . ($r->user?->persona?->per_paterno ?? ''))
                    ]))
                ->searchable()
                ->visible(fn($get) => $get('liq_tipo') === 'repartidor')
                ->required(fn($get) => $get('liq_tipo') === 'repartidor'),

            TextInput::make('liq_monto')
                ->label('Monto a liquidar')
                ->numeric()
                ->prefix('$')
                ->required(),

            DatePicker::make('liq_periodo_inicio')
                ->label('Inicio del período')
                ->required(),

            DatePicker::make('liq_periodo_fin')
                ->label('Fin del período')
                ->required(),

            Textarea::make('liq_notas')
                ->label('Notas')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('liq_tipo')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => $state === 'tienda' ? 'success' : 'info'),

                TextColumn::make('propietario')
                    ->label('Beneficiario')
                    ->getStateUsing(function ($record) {
                        if ($record->liq_tipo === 'tienda') {
                            return $record->tienda?->tie_nombre ?? '—';
                        }
                        $p = $record->repartidor?->user?->persona;
                        return trim(($p?->per_nombre ?? '') . ' ' . ($p?->per_paterno ?? '')) ?: '—';
                    }),

                TextColumn::make('liq_monto')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('liq_periodo_inicio')
                    ->label('Período')
                    ->formatStateUsing(fn($state, $record) =>
                        $record->liq_periodo_inicio->format('d/m/Y') . ' — ' . $record->liq_periodo_fin->format('d/m/Y')
                    ),

                TextColumn::make('liq_estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match($state) {
                        'pendiente' => 'warning',
                        'pagada'    => 'success',
                        default     => 'gray',
                    }),

                TextColumn::make('liq_fecha_creacion')
                    ->label('Creada')
                    ->dateTime('d/m/Y'),
            ])

            ->recordActions([
                Action::make('marcar_pagada')
                    ->label('Pagar con Stripe')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar transferencia')
                    ->modalDescription(fn($record) => "Se enviará $" . number_format($record->liq_monto, 2) . " MXN via Stripe a la cuenta conectada.")
                    ->visible(fn($record) => $record->liq_estado === Liquidacion::ESTADO_PENDIENTE)
                    ->action(function ($record) {
                        // Obtener stripe_account_id del destinatario
                        $stripeAccountId = null;
                        if ($record->liq_tipo === 'tienda' && $record->liq_fk_tienda) {
                            $stripeAccountId = \App\Models\Tienda::find($record->liq_fk_tienda)?->stripe_account_id;
                        } elseif ($record->liq_tipo === 'repartidor' && $record->liq_fk_repartidor) {
                            $stripeAccountId = \App\Models\Repartidor::find($record->liq_fk_repartidor)?->stripe_account_id;
                        }

                        if (! $stripeAccountId) {
                            \Filament\Notifications\Notification::make()
                                ->title('Sin cuenta conectada')
                                ->body('El destinatario aún no ha completado su onboarding de Stripe Connect.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Ejecutar transferencia en Stripe
                        try {
                            $descripcion = "Liquidación LocalApp — {$record->liq_periodo_inicio->format('d/m/Y')} al {$record->liq_periodo_fin->format('d/m/Y')}";
                            $transferId  = \App\Http\Controllers\StripeConnectController::transferir(
                                $stripeAccountId,
                                $record->liq_monto,
                                $descripcion,
                                ['liquidacion_id' => $record->liq_id, 'tipo' => $record->liq_tipo]
                            );
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error al transferir')
                                ->body('Stripe: ' . $e->getMessage())
                                ->danger()
                                ->send();
                            return;
                        }

                        // Actualizar BD dentro de transacción
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $transferId) {
                            $record->update([
                                'liq_estado'         => Liquidacion::ESTADO_PAGADA,
                                'liq_fecha_pago'     => now(),
                                'stripe_transfer_id' => $transferId,
                            ]);

                            $wallet = null;
                            if ($record->liq_tipo === 'tienda' && $record->liq_fk_tienda) {
                                $wallet = Wallet::deTienda($record->liq_fk_tienda);
                            } elseif ($record->liq_tipo === 'repartidor' && $record->liq_fk_repartidor) {
                                $wallet = Wallet::deRepartidor($record->liq_fk_repartidor);
                            }

                            if ($wallet) {
                                $mover = min($wallet->wal_saldo_pendiente, $record->liq_monto);
                                if ($mover > 0) {
                                    $wallet->decrement('wal_saldo_pendiente', $mover);
                                    $wallet->increment('wal_total_liquidado', $mover);
                                }

                                MovimientoWallet::create([
                                    'mwl_fk_wallet'   => $wallet->wal_id,
                                    'mwl_tipo'        => 'liquidacion',
                                    'mwl_monto'       => $record->liq_monto,
                                    'mwl_descripcion' => "Transferencia Stripe ({$transferId})",
                                    'mwl_fk_pedido'   => null,
                                    'mwl_fecha'       => now(),
                                ]);
                            }
                        });

                        // Notificar a la tienda en su panel (bell) cuando se liquida su pago
                        if ($record->liq_tipo === 'tienda' && $record->liq_fk_tienda) {
                            \App\Services\TiendaNotificacion::enviar(
                                $record->liq_fk_tienda,
                                'Liquidación recibida',
                                'Se transfirieron $' . number_format($record->liq_monto, 2) . ' MXN a tu cuenta bancaria conectada.',
                                'success',
                                'heroicon-o-banknotes'
                            );
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Transferencia enviada')
                            ->body("$" . number_format($record->liq_monto, 2) . " enviados. Transfer: {$transferId}")
                            ->success()
                            ->send();
                    }),
            ])

            ->filters([
                SelectFilter::make('liq_estado')
                    ->label('Estado')
                    ->options(['pendiente' => 'Pendiente', 'pagada' => 'Pagada'])
                    ->default('pendiente'),

                SelectFilter::make('liq_tipo')
                    ->label('Tipo')
                    ->options(['tienda' => 'Tienda', 'repartidor' => 'Repartidor']),
            ])

            ->defaultSort('liq_fecha_creacion', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Resources\Finanzas\Pages\ListLiquidaciones::route('/'),
            'create' => \App\Filament\Resources\Finanzas\Pages\CreateLiquidacion::route('/create'),
        ];
    }
}
