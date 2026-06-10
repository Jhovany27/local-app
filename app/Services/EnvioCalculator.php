<?php

namespace App\Services;

use App\Models\ConfiguracionEnvio;

class EnvioCalculator
{
    public static function distanciaKm(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): float {
        $radioTierra = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($radioTierra * $c, 2);
    }

    public static function costoEnvio(float $distanciaKm): float
    {
        $config = ConfiguracionEnvio::actual();
        $costo  = $config->tarifa_base + ($distanciaKm * $config->precio_por_km);
        return round($costo, 2);
    }

    public static function calcular(
        float $latTienda, float $lngTienda,
        float $latCliente, float $lngCliente
    ): array {
        $distancia = self::distanciaKm($latTienda, $lngTienda, $latCliente, $lngCliente);
        $costo     = self::costoEnvio($distancia);

        return [
            'distancia_km' => $distancia,
            'costo_envio'  => $costo,
        ];
    }
}
