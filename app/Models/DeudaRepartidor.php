<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeudaRepartidor extends Model
{
    protected $table      = 'deuda_repartidor';
    protected $primaryKey = 'dre_id';
    public $timestamps    = false;

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADA    = 'pagada';

    protected $casts = [
        'dre_monto'      => 'float',
        'dre_fecha'      => 'datetime',
        'dre_fecha_pago' => 'datetime',
    ];

    protected $fillable = [
        'dre_fk_repartidor',
        'dre_fk_pedido',
        'dre_monto',
        'dre_estado',
        'dre_fecha',
        'dre_fecha_pago',
    ];

    public function repartidor()
    {
        return $this->belongsTo(Repartidor::class, 'dre_fk_repartidor', 'rep_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'dre_fk_pedido', 'ped_id');
    }
}
