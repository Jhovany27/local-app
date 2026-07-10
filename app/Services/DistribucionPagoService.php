<?php

namespace App\Services;

use App\Models\ConfiguracionComision;
use App\Models\DeudaRepartidor;
use App\Models\MovimientoWallet;
use App\Models\Pedido;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class DistribucionPagoService
{
    public static function distribuir(Pedido $pedido): void
    {
        // Idempotente: no distribuir dos veces el mismo pedido
        if (MovimientoWallet::where('mwl_fk_pedido', $pedido->ped_id)->exists()) {
            return;
        }

        $pedido->loadMissing(['pago', 'asignacion']);

        $metodoPago = strtolower($pedido->pago?->pag_metodo_pago ?? 'efectivo');
        $esTarjeta  = $metodoPago === 'tarjeta';

        $subtotalProductos = max(0, $pedido->ped_total - ($pedido->ped_costo_envio ?? 0));
        $costoEnvio        = (float) ($pedido->ped_costo_envio ?? 0);

        $pctComision     = ConfiguracionComision::porcentajeActual();
        $montoComision   = round($subtotalProductos * $pctComision / 100, 2);
        $gananciasTienda = round($subtotalProductos - $montoComision, 2);
        $gananciasRepar  = $costoEnvio;

        $repartidorId = $pedido->asignacion?->asr_fk_repartidor;

        DB::transaction(function () use (
            $pedido, $esTarjeta, $subtotalProductos, $pctComision,
            $gananciasTienda, $gananciasRepar, $montoComision,
            $repartidorId
        ) {
            // ── WALLET TIENDA ───────────────────────────────────────
            $walletTienda = Wallet::deTienda($pedido->ped_fk_tienda);

            // Registrar venta bruta y comisión como movimientos separados
            static::registrarMovimiento(
                $walletTienda->wal_id, 'venta', $subtotalProductos, $pedido->ped_id,
                "Venta #{$pedido->ped_codigo} — subtotal productos"
            );
            static::registrarMovimiento(
                $walletTienda->wal_id, 'comision', $montoComision, $pedido->ped_id,
                "Comisión plataforma ({$pctComision}%) — #{$pedido->ped_codigo}"
            );

            // Actualizar totales acumulados
            $walletTienda->increment('wal_total_ventas',    $subtotalProductos);
            $walletTienda->increment('wal_total_comisiones', $montoComision);

            // Acreditar ganancia neta al saldo
            if ($esTarjeta) {
                $walletTienda->increment('wal_saldo_pendiente', $gananciasTienda);
            } else {
                $walletTienda->increment('wal_saldo_disponible', $gananciasTienda);
            }

            // ── WALLET REPARTIDOR ───────────────────────────────────
            if ($repartidorId && $gananciasRepar > 0) {
                $walletRepar = Wallet::deRepartidor($repartidorId);

                static::registrarMovimiento(
                    $walletRepar->wal_id, 'venta', $gananciasRepar, $pedido->ped_id,
                    "Envío pedido #{$pedido->ped_codigo}"
                );
                $walletRepar->increment('wal_total_ventas', $gananciasRepar);

                if ($esTarjeta) {
                    $walletRepar->increment('wal_saldo_pendiente', $gananciasRepar);
                } else {
                    $walletRepar->increment('wal_saldo_disponible', $gananciasRepar);
                }
            }

            // ── DEUDA REPARTIDOR → PLATAFORMA (solo efectivo) ──────
            if (! $esTarjeta && $repartidorId && $montoComision > 0) {
                DeudaRepartidor::create([
                    'dre_fk_repartidor' => $repartidorId,
                    'dre_fk_pedido'     => $pedido->ped_id,
                    'dre_monto'         => $montoComision,
                    'dre_estado'        => DeudaRepartidor::ESTADO_PENDIENTE,
                    'dre_fecha'         => now(),
                ]);
            }
        });
    }

    private static function registrarMovimiento(
        int $walletId, string $tipo, float $monto, int $pedidoId, string $descripcion
    ): void {
        MovimientoWallet::create([
            'mwl_fk_wallet'   => $walletId,
            'mwl_tipo'        => $tipo,
            'mwl_monto'       => $monto,
            'mwl_descripcion' => $descripcion,
            'mwl_fk_pedido'   => $pedidoId,
            'mwl_fecha'       => now(),
        ]);
    }
}
