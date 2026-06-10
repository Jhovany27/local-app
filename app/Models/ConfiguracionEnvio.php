<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEnvio extends Model
{
    protected $table = 'configuracion_envio';

    protected $casts = [
        'tarifa_base'   => 'float',
        'precio_por_km' => 'float',
    ];

    protected $fillable = [
        'tarifa_base',
        'precio_por_km',
    ];

    public static function actual(): self
    {
        return self::firstOrCreate([], [
            'tarifa_base'   => 15.00,
            'precio_por_km' => 5.00,
        ]);
    }
}
