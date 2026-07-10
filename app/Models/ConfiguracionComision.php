<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionComision extends Model
{
    protected $table      = 'configuracion_comision';
    protected $primaryKey = 'com_id';
    public $timestamps    = false;

    protected $casts = [
        'com_porcentaje'             => 'float',
        'limite_deuda'               => 'float',
        'frecuencia_liquidacion_dias'=> 'int',
        'com_activa'                 => 'boolean',
        'com_fecha'                  => 'datetime',
    ];

    protected $fillable = [
        'com_porcentaje',
        'limite_deuda',
        'frecuencia_liquidacion_dias',
        'com_activa',
        'com_fecha',
    ];

    public static function activa(): ?self
    {
        return static::where('com_activa', true)->latest('com_id')->first();
    }

    public static function porcentajeActual(): float
    {
        return static::activa()?->com_porcentaje ?? 10.00;
    }

    public static function limiteDeuda(): float
    {
        return static::activa()?->limite_deuda ?? 500.00;
    }

    public static function frecuenciaDias(): int
    {
        return static::activa()?->frecuencia_liquidacion_dias ?? 7;
    }
}
