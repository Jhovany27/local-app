<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VentaDetalle
 *
 * @property int $vde_id
 * @property int $vde_cantidad
 * @property float $vde_precio_unitario
 * @property float $vde_subtotal
 * @property int $vde_fk_venta
 * @property int $vde_fk_producto
 *
 * @property Venta $venta
 * @property Producto $producto
 */
class VentaDetalle extends Model
{
    protected $table      = 'venta_detalle';
    protected $primaryKey = 'vde_id';
    public $timestamps    = false;

    protected $casts = [
        'vde_cantidad'         => 'integer',
        'vde_precio_unitario'  => 'float',
        'vde_subtotal'         => 'float',
    ];

    protected $fillable = [
        'vde_cantidad',
        'vde_precio_unitario',
        'vde_subtotal',
        'vde_fk_venta',
        'vde_fk_producto',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'vde_fk_venta', 'ven_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'vde_fk_producto', 'pro_id');
    }
}