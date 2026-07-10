<?php

namespace App\Filament\Resources\Tiendas\Pages;

use App\Filament\Resources\Tiendas\TiendaResource;
use App\Models\Liquidacion;
use App\Models\LogAuditoria;
use App\Models\MovimientoWallet;
use App\Models\Tienda;
use App\Models\Wallet;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewTienda extends ViewRecord
{
    protected static string $resource = TiendaResource::class;

    protected string $view = 'filament.resources.tiendas.pages.view-tienda';

    // ── PROPIEDADES FINANCIERAS ───────────────────────────────
    public function getWalletFinancieroProperty(): ?Wallet
    {
        return Wallet::where('wal_tipo', 'tienda')
            ->where('wal_fk_tienda', $this->record->tie_id)
            ->first();
    }

    public function getLiquidacionesTiendaProperty()
    {
        return Liquidacion::where('liq_tipo', 'tienda')
            ->where('liq_fk_tienda', $this->record->tie_id)
            ->orderBy('liq_fecha_creacion', 'desc')
            ->limit(10)
            ->get();
    }

    public function getMovimientosTiendaProperty()
    {
        $wallet = $this->walletFinanciero;
        if (! $wallet) return collect();

        return MovimientoWallet::where('mwl_fk_wallet', $wallet->wal_id)
            ->orderBy('mwl_fecha', 'desc')
            ->limit(15)
            ->get();
    }

    // ── ACCIONES DE HEADER ────────────────────────────────────
    protected function getHeaderActions(): array
    {
        return [
            // ── ACTIVAR ──────────────────────────────────────
            Action::make('activar')
                ->label('Activar tienda')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_APROBADA)
                ->requiresConfirmation()
                ->modalHeading('¿Activar esta tienda?')
                ->modalDescription('Se asignará el rol de tienda al propietario si no lo tiene.')
                ->action(function () {
                    DB::transaction(function () {
                        $this->record->update([
                            'tie_estado'         => Tienda::ESTADO_APROBADA,
                            'tie_motivo_rechazo' => null,
                        ]);
                        $user = $this->record->user;
                        if ($user && !$user->hasRol('tienda')) {
                            $user->roles()->attach(4);
                        }
                    });
                    Notification::make()->title('Tienda activada')->success()->send();
                    $this->refreshFormData([]);
                }),

            // ── PONER PENDIENTE ───────────────────────────────
            Action::make('pendiente')
                ->label('Marcar pendiente')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_PENDIENTE)
                ->form([
                    Textarea::make('motivo')->label('Motivo (opcional)')->rows(3),
                ])
                ->modalHeading('¿Poner en revisión?')
                ->modalSubmitActionLabel('Confirmar')
                ->action(function (array $data) {
                    $this->record->update([
                        'tie_estado'         => Tienda::ESTADO_PENDIENTE,
                        'tie_motivo_rechazo' => $data['motivo'] ?? null,
                    ]);
                    $user = $this->record->user;
                    if ($user) {
                        $otrasActivas = $user->tiendas()
                            ->where('tie_id', '!=', $this->record->tie_id)
                            ->where('tie_estado', Tienda::ESTADO_APROBADA)
                            ->exists();
                        if (!$otrasActivas) $user->roles()->detach(4);
                    }
                    Notification::make()->title('Tienda en revisión')->warning()->send();
                    $this->refreshFormData([]);
                }),

            // ── RECHAZAR ─────────────────────────────────────
            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_RECHAZADA)
                ->form([
                    Textarea::make('motivo')->label('Motivo del rechazo')->required()->rows(4),
                ])
                ->modalHeading('Rechazar tienda')
                ->modalSubmitActionLabel('Confirmar rechazo')
                ->action(function (array $data) {
                    $this->record->update([
                        'tie_estado'         => Tienda::ESTADO_RECHAZADA,
                        'tie_motivo_rechazo' => $data['motivo'],
                    ]);
                    $user = $this->record->user;
                    if ($user) {
                        $otrasActivas = $user->tiendas()
                            ->where('tie_id', '!=', $this->record->tie_id)
                            ->where('tie_estado', Tienda::ESTADO_APROBADA)
                            ->exists();
                        if (!$otrasActivas) $user->roles()->detach(4);
                    }
                    Notification::make()->title('Tienda rechazada')->danger()->send();
                    $this->refreshFormData([]);
                }),

            // ── AJUSTAR SALDO (corrección manual) ─────────────
            Action::make('ajustar_saldo')
                ->label('Ajustar saldo')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->form([
                    Select::make('tipo_ajuste')
                        ->label('Tipo de ajuste')
                        ->options(['abono' => 'Abono (+)', 'cargo' => 'Cargo (−)'])
                        ->required(),
                    TextInput::make('monto')
                        ->label('Monto (MXN)')
                        ->numeric()->minValue(0.01)->prefix('$')->required(),
                    Textarea::make('razon')
                        ->label('Razón del ajuste')
                        ->required()->rows(2)
                        ->placeholder('Corrección de error, ajuste manual, etc.'),
                ])
                ->modalHeading('Ajuste manual de saldo')
                ->modalDescription('Este ajuste quedará registrado en el log de auditoría.')
                ->requiresConfirmation(false)
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $wallet = Wallet::deTienda($this->record->tie_id);
                        $monto  = (float) $data['monto'];
                        $esAbono = $data['tipo_ajuste'] === 'abono';

                        if ($esAbono) {
                            $wallet->increment('wal_saldo_disponible', $monto);
                        } else {
                            $wallet->decrement('wal_saldo_disponible', $monto);
                        }

                        MovimientoWallet::create([
                            'mwl_fk_wallet'   => $wallet->wal_id,
                            'mwl_tipo'        => 'ajuste',
                            'mwl_monto'       => $monto,
                            'mwl_descripcion' => ($esAbono ? 'Ajuste manual +' : 'Ajuste manual −') . " — {$data['razon']}",
                            'mwl_fk_pedido'   => null,
                            'mwl_fecha'       => now(),
                        ]);

                        LogAuditoria::registrar(
                            'ajuste_saldo_tienda',
                            "Ajuste de saldo en tienda #{$this->record->tie_id} ({$this->record->tie_nombre}): " .
                            ($esAbono ? '+' : '−') . "\${$monto}",
                            ['tienda_id' => $this->record->tie_id, 'tipo' => $data['tipo_ajuste'], 'monto' => $monto, 'razon' => $data['razon']]
                        );
                    });

                    Notification::make()
                        ->title('Saldo ajustado')
                        ->body("Se registró el ajuste en el wallet de {$this->record->tie_nombre}.")
                        ->success()->send();
                }),
        ];
    }
}
