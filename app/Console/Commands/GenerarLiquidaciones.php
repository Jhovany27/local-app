<?php

namespace App\Console\Commands;

use App\Models\ConfiguracionComision;
use App\Models\DeudaRepartidor;
use App\Models\Liquidacion;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerarLiquidaciones extends Command
{
    protected $signature   = 'liquidaciones:generar {--dry-run : Solo mostrar sin guardar}';
    protected $description = 'Genera liquidaciones periódicas para tiendas y repartidores';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $config = ConfiguracionComision::activa();
        $dias   = $config?->frecuencia_liquidacion_dias ?? 7;

        $periodoFin    = Carbon::now()->startOfDay();
        $periodoInicio = $periodoFin->copy()->subDays($dias);

        $this->info("Generando liquidaciones — período: {$periodoInicio->format('d/m/Y')} al {$periodoFin->format('d/m/Y')}");

        $cntTiendas    = 0;
        $cntRepartidores = 0;

        // ── TIENDAS ─────────────────────────────────────────────────
        // Solo se liquida wal_saldo_pendiente: son los pagos con tarjeta
        // donde la plataforma retuvo el dinero (vía Stripe) y aún debe pagarlo a la tienda.
        // wal_saldo_disponible = efectivo que la tienda ya recibió del repartidor → no requiere liquidación.
        Wallet::where('wal_tipo', 'tienda')
            ->where('wal_saldo_pendiente', '>', 0)
            ->with('tienda')
            ->get()
            ->each(function (Wallet $wallet) use ($periodoInicio, $periodoFin, $dry, &$cntTiendas) {

                // Evitar duplicados: saltar si ya existe una liquidación pendiente para esta tienda
                $yaExiste = Liquidacion::where('liq_tipo', 'tienda')
                    ->where('liq_fk_tienda', $wallet->wal_fk_tienda)
                    ->where('liq_estado', Liquidacion::ESTADO_PENDIENTE)
                    ->exists();

                if ($yaExiste) {
                    $this->line("  Tienda [{$wallet->tienda?->tie_nombre}] → ya tiene liquidación pendiente, omitida.");
                    return;
                }

                $monto = round($wallet->wal_saldo_pendiente, 2);
                $this->line("  Tienda [{$wallet->tienda?->tie_nombre}] → \${$monto}");

                if (! $dry) {
                    DB::transaction(function () use ($wallet, $monto, $periodoInicio, $periodoFin) {
                        Liquidacion::create([
                            'liq_tipo'           => 'tienda',
                            'liq_fk_tienda'      => $wallet->wal_fk_tienda,
                            'liq_monto'          => $monto,
                            'liq_periodo_inicio' => $periodoInicio,
                            'liq_periodo_fin'    => $periodoFin,
                            'liq_estado'         => Liquidacion::ESTADO_PENDIENTE,
                            'liq_fecha_creacion' => now(),
                        ]);
                    });
                }
                $cntTiendas++;
            });

        // ── REPARTIDORES ─────────────────────────────────────────────
        // Solo wal_saldo_pendiente: pagos con tarjeta donde la plataforma tiene el dinero.
        // wal_saldo_disponible = el repartidor ya cobró el envío en efectivo → no requiere liquidación.
        Wallet::where('wal_tipo', 'repartidor')
            ->where('wal_saldo_pendiente', '>', 0)
            ->with('repartidor')
            ->get()
            ->each(function (Wallet $wallet) use ($periodoInicio, $periodoFin, $dry, &$cntRepartidores) {
                $repartidor = $wallet->repartidor;
                if (! $repartidor) return;

                // Evitar duplicados
                $yaExiste = Liquidacion::where('liq_tipo', 'repartidor')
                    ->where('liq_fk_repartidor', $repartidor->rep_id)
                    ->where('liq_estado', Liquidacion::ESTADO_PENDIENTE)
                    ->exists();

                if ($yaExiste) {
                    $nombre = trim($repartidor->user?->persona?->per_nombre . ' ' . $repartidor->user?->persona?->per_paterno);
                    $this->line("  Repartidor [{$nombre}] → ya tiene liquidación pendiente, omitido.");
                    return;
                }

                $ganancias = round($wallet->wal_saldo_pendiente, 2);

                // Deudas pendientes con la plataforma (comisiones de pedidos en efectivo)
                $deudas     = DeudaRepartidor::where('dre_fk_repartidor', $repartidor->rep_id)
                    ->where('dre_estado', DeudaRepartidor::ESTADO_PENDIENTE)
                    ->orderBy('dre_id')
                    ->get();
                $totalDeuda = round($deudas->sum('dre_monto'), 2);
                // Lo que la plataforma absorbe: hasta el tope de las ganancias disponibles
                $absorber   = min($ganancias, $totalDeuda);
                $montoLiq   = max(0, round($ganancias - $absorber, 2));

                $nombre = trim($repartidor->user?->persona?->per_nombre . ' ' . $repartidor->user?->persona?->per_paterno);
                $this->line("  Repartidor [{$nombre}] ganancias=\${$ganancias} deudas=\${$totalDeuda} absorbe=\${$absorber} → liquidación=\${$montoLiq}");

                if (! $dry) {
                    DB::transaction(function () use ($wallet, $repartidor, $ganancias, $montoLiq, $absorber, $deudas, $periodoInicio, $periodoFin) {

                        // 1. Siempre descontar del saldo pendiente la parte que absorbe la plataforma
                        if ($absorber > 0) {
                            $wallet->decrement('wal_saldo_pendiente', $absorber);
                            $wallet->increment('wal_total_liquidado',  $absorber);

                            \App\Models\MovimientoWallet::create([
                                'mwl_fk_wallet'   => $wallet->wal_id,
                                'mwl_tipo'        => 'comision',
                                'mwl_monto'       => $absorber,
                                'mwl_descripcion' => "Descuento de deudas en corte — plataforma retiene \${$absorber}",
                                'mwl_fk_pedido'   => null,
                                'mwl_fecha'       => now(),
                            ]);

                            // Saldar/reducir deudas en orden FIFO
                            $restante = $absorber;
                            foreach ($deudas as $deuda) {
                                if ($restante <= 0) break;
                                if ($restante >= $deuda->dre_monto) {
                                    // Deuda completamente saldada
                                    $restante -= $deuda->dre_monto;
                                    $deuda->update([
                                        'dre_estado'     => DeudaRepartidor::ESTADO_PAGADA,
                                        'dre_fecha_pago' => now(),
                                    ]);
                                } else {
                                    // Deuda parcialmente saldada: reducir el monto pendiente
                                    $deuda->update(['dre_monto' => round($deuda->dre_monto - $restante, 2)]);
                                    $restante = 0;
                                }
                            }
                        }

                        // 2. Crear liquidación solo si hay algo que pagarle al repartidor
                        if ($montoLiq > 0) {
                            Liquidacion::create([
                                'liq_tipo'           => 'repartidor',
                                'liq_fk_repartidor'  => $repartidor->rep_id,
                                'liq_monto'          => $montoLiq,
                                'liq_periodo_inicio' => $periodoInicio,
                                'liq_periodo_fin'    => $periodoFin,
                                'liq_estado'         => Liquidacion::ESTADO_PENDIENTE,
                                'liq_fecha_creacion' => now(),
                            ]);
                        }
                    });
                }

                if ($montoLiq <= 0) {
                    $this->line("    (deudas absorbieron todo el saldo — sin liquidación, saldo y deudas saldados)");
                } else {
                    $cntRepartidores++;
                }
            });

        $this->info("✓ Liquidaciones generadas: {$cntTiendas} tiendas, {$cntRepartidores} repartidores.");

        return self::SUCCESS;
    }
}
