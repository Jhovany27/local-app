<?php

namespace App\Filament\Resources\Repartidors\Pages;

use App\Filament\Resources\Repartidors\RepartidorResource;
use App\Models\AsignacionRepartidor;
use App\Models\DeudaRepartidor;
use App\Models\Liquidacion;
use App\Models\LogAuditoria;
use App\Models\MovimientoWallet;
use App\Models\Repartidor;
use App\Models\Wallet;
use App\Services\RepartidorDeudaService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewRepartidor extends ViewRecord
{
    protected static string $resource = RepartidorResource::class;

    protected string $view = 'filament.resources.repartidors.pages.view-repartidor';

    // ── PROPIEDADES FINANCIERAS ───────────────────────────────
    public function getWalletRepartidorProperty(): ?Wallet
    {
        return Wallet::where('wal_tipo', 'repartidor')
            ->where('wal_fk_repartidor', $this->record->rep_id)
            ->first();
    }

    public function getDeudasRepartidorProperty()
    {
        return DeudaRepartidor::where('dre_fk_repartidor', $this->record->rep_id)
            ->with('pedido')
            ->orderByRaw("FIELD(dre_estado, 'pendiente', 'pagada')")
            ->orderBy('dre_fecha', 'desc')
            ->limit(20)
            ->get();
    }

    public function getLiquidacionesRepartidorProperty()
    {
        return Liquidacion::where('liq_tipo', 'repartidor')
            ->where('liq_fk_repartidor', $this->record->rep_id)
            ->orderBy('liq_fecha_creacion', 'desc')
            ->limit(10)
            ->get();
    }

    public function getDeudaResumenProperty(): array
    {
        return RepartidorDeudaService::resumen($this->record);
    }

    public function getViajesTotalesProperty(): int
    {
        return AsignacionRepartidor::where('asr_fk_repartidor', $this->record->rep_id)
            ->where('asr_estado', 3)
            ->count();
    }

    // ── ACCIONES DE HEADER ────────────────────────────────────
    protected function getHeaderActions(): array
    {
        return [
            Action::make('aprobar')
                ->label('Aprobar repartidor')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => (int) $this->record->rep_estado !== 1)
                ->requiresConfirmation()
                ->modalHeading('¿Aprobar este repartidor?')
                ->modalDescription('Se asignará el rol de repartidor al usuario si no lo tiene.')
                ->action(function () {
                    $this->record->rep_estado         = 1;
                    $this->record->rep_motivo_rechazo = null;
                    $this->record->save();
                    $user = $this->record->user;
                    if ($user && !$user->hasRol('repartidor')) {
                        $user->roles()->attach(3);
                    }
                    Notification::make()->title('Repartidor aprobado')->success()->send();
                    $this->refreshFormData([]);
                }),

            Action::make('pendiente')
                ->label('Marcar pendiente')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->visible(fn () => (int) $this->record->rep_estado !== 0)
                ->requiresConfirmation()
                ->modalHeading('¿Poner en revisión?')
                ->action(function () {
                    $this->record->rep_estado = 0;
                    $this->record->save();
                    $this->record->user?->roles()->detach(3);
                    Notification::make()->title('Repartidor en revisión')->warning()->send();
                    $this->refreshFormData([]);
                }),

            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn () => (int) $this->record->rep_estado !== 2)
                ->form([
                    Textarea::make('motivo')->label('Motivo del rechazo')->required()->rows(4),
                ])
                ->modalHeading('Rechazar repartidor')
                ->modalSubmitActionLabel('Confirmar rechazo')
                ->action(function (array $data) {
                    $this->record->rep_estado         = 2;
                    $this->record->rep_motivo_rechazo = $data['motivo'];
                    $this->record->save();
                    $this->record->user?->roles()->detach(3);
                    Notification::make()->title('Repartidor rechazado')->danger()->send();
                    $this->refreshFormData([]);
                }),

            // ── LIQUIDAR DEUDAS MANUALMENTE ───────────────────
            Action::make('liquidar_deudas')
                ->label('Liquidar deudas')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Liquidar todas las deudas pendientes')
                ->modalDescription('Se marcarán como pagadas todas las deudas pendientes de este repartidor. Esta acción quedará registrada en el log de auditoría.')
                ->visible(fn () => DeudaRepartidor::where('dre_fk_repartidor', $this->record->rep_id)->where('dre_estado', 'pendiente')->exists())
                ->action(function () {
                    DB::transaction(function () {
                        $deudas = DeudaRepartidor::where('dre_fk_repartidor', $this->record->rep_id)
                            ->where('dre_estado', DeudaRepartidor::ESTADO_PENDIENTE)
                            ->get();
                        $total = $deudas->sum('dre_monto');
                        $deudas->each(fn($d) => $d->update(['dre_estado' => DeudaRepartidor::ESTADO_PAGADA, 'dre_fecha_pago' => now()]));

                        LogAuditoria::registrar(
                            'liquidar_deudas_manual',
                            "Admin liquidó manualmente \${$total} en deudas del repartidor #{$this->record->rep_id}",
                            ['repartidor_id' => $this->record->rep_id, 'total' => $total, 'deudas' => $deudas->count()]
                        );
                    });

                    Notification::make()->title('Deudas liquidadas')->body('Todas las deudas pendientes fueron marcadas como pagadas.')->success()->send();
                }),

            // ── AJUSTAR SALDO ─────────────────────────────────
            Action::make('ajustar_saldo')
                ->label('Ajustar saldo')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->form([
                    Select::make('tipo_ajuste')
                        ->label('Tipo')->options(['abono' => 'Abono (+)', 'cargo' => 'Cargo (−)'])->required(),
                    TextInput::make('monto')
                        ->label('Monto')->numeric()->minValue(0.01)->prefix('$')->required(),
                    Textarea::make('razon')
                        ->label('Razón del ajuste')->required()->rows(2),
                ])
                ->modalHeading('Ajuste manual de saldo')
                ->modalDescription('Este ajuste quedará registrado en el log de auditoría.')
                ->action(function (array $data) {
                    DB::transaction(function () use ($data) {
                        $wallet = Wallet::deRepartidor($this->record->rep_id);
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
                            'ajuste_saldo_repartidor',
                            "Ajuste de saldo en repartidor #{$this->record->rep_id}: " . ($esAbono ? '+' : '−') . "\${$monto}",
                            ['repartidor_id' => $this->record->rep_id, 'tipo' => $data['tipo_ajuste'], 'monto' => $monto, 'razon' => $data['razon']]
                        );
                    });

                    Notification::make()->title('Saldo ajustado')->success()->send();
                }),
        ];
    }
}
