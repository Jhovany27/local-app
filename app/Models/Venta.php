<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 *
 * @property int $ven_id
 * @property Carbon $ven_fecha
 * @property float $ven_total
 * @property int $ven_estado
 * @property int $ven_fk_tienda
 *
 * @property Tienda $tienda
 * @property Collection|VentaDetalle[] $detalles
 */
class Venta extends Model
{
    const ESTADO_PENDIENTE  = 0;
    const ESTADO_COMPLETADA = 1;
    const ESTADO_CANCELADA  = 2;

    protected $table      = 'venta';
    protected $primaryKey = 'ven_id';
    public $timestamps    = false;

    protected $casts = [
        'ven_fecha'  => 'datetime',
        'ven_total'  => 'float',
        'ven_estado' => 'integer',
    ];

    protected $fillable = [
        'ven_fecha',
        'ven_total',
        'ven_estado',
        'ven_fk_tienda',
            'ven_fk_pedido', 
    ];

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'ven_fk_tienda', 'tie_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'vde_fk_venta', 'ven_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'ven_fk_pedido', 'ped_id');
    }
}