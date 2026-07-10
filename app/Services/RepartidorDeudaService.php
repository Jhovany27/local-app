<?php

namespace App\Services;

use App\Models\ConfiguracionComision;
use App\Models\DeudaRepartidor;
use App\Models\Repartidor;

class RepartidorDeudaService
{
    const UMBRAL_ALERTA = 0.80; // Avisar al 80% del límite

    public static function deudaTotal(Repartidor $repartidor): float
    {
        return (float) DeudaRepartidor::where('dre_fk_repartidor', $repartidor->rep_id)
            ->where('dre_estado', DeudaRepartidor::ESTADO_PENDIENTE)
            ->sum('dre_monto');
    }

    public static function limiteActual(): float
    {
        return ConfiguracionComision::limiteDeuda();
    }

    public static function superaLimite(Repartidor $repartidor): bool
    {
        return static::deudaTotal($repartidor) > static::limiteActual();
    }

    public static function porcentaje(Repartidor $repartidor): float
    {
        $limite = static::limiteActual();
        if ($limite <= 0) return 0;
        return min(100, (static::deudaTotal($repartidor) / $limite) * 100);
    }

    public static function enAlerta(Repartidor $repartidor): bool
    {
        return static::porcentaje($repartidor) >= (static::UMBRAL_ALERTA * 100);
    }

    public static function montoParaDesbloqueo(Repartidor $repartidor): float
    {
        return max(0, round(static::deudaTotal($repartidor) - static::limiteActual(), 2));
    }

    /** Devuelve un array con toda la info de deuda para la vista */
    public static function resumen(Repartidor $repartidor): array
    {
        $total      = static::deudaTotal($repartidor);
        $limite     = static::limiteActual();
        $pct        = static::porcentaje($repartidor);
        $bloqueado  = $total > $limite;
        $alerta     = !$bloqueado && $pct >= (static::UMBRAL_ALERTA * 100);

        return [
            'total'             => $total,
            'limite'            => $limite,
            'porcentaje'        => $pct,
            'bloqueado'         => $bloqueado,
            'alerta'            => $alerta,
            'para_desbloqueo'   => static::montoParaDesbloqueo($repartidor),
        ];
    }
}
