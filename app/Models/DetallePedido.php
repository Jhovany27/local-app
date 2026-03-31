<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallePedido
 * 
 * @property int $det_id
 * @property int $det_cantidad
 * @property float $det_precio_unitario
 * @property float $det_subtotal
 * @property int $det_fk_producto
 * @property int $det_fk_pedido
 * 
 * @property Producto $producto
 * @property Pedido $pedido
 *
 * @package App\Models
 */
class DetallePedido extends Model
{
	protected $table = 'detalle_pedido';
	protected $primaryKey = 'det_id';
	public $timestamps = false;

	protected $casts = [
		'det_cantidad' => 'int',
		'det_precio_unitario' => 'float',
		'det_subtotal' => 'float',
		'det_fk_producto' => 'int',
		'det_fk_pedido' => 'int'
	];

	protected $fillable = [
		'det_cantidad',
		'det_precio_unitario',
		'det_subtotal',
		'det_fk_producto',
		'det_fk_pedido'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'det_fk_producto');
	}

	public function pedido()
	{
		return $this->belongsTo(Pedido::class, 'det_fk_pedido');
	}
}
