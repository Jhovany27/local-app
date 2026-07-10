<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table      = 'liquidacion';
    protected $primaryKey = 'liq_id';
    public $timestamps    = false;

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADA    = 'pagada';

    protected $casts = [
        'liq_monto'           => 'float',
        'liq_periodo_inicio'  => 'date',
        'liq_periodo_fin'     => 'date',
        'liq_fecha_creacion'  => 'datetime',
        'liq_fecha_pago'      => 'datetime',
    ];

    protected $fillable = [
        'liq_tipo',
        'liq_fk_tienda',
        'liq_fk_repartidor',
        'liq_monto',
        'liq_periodo_inicio',
        'liq_periodo_fin',
        'liq_estado',
        'liq_fecha_creacion',
        'liq_fecha_pago',
        'liq_notas',
        'stripe_transfer_id',
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'liq_fk_tienda', 'tie_id');
    }

    public function repartidor()
    {
        return $this->belongsTo(Repartidor::class, 'liq_fk_repartidor', 'rep_id');
    }
}
