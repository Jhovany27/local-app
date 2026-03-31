<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MovimientoInventario
 * 
 * @property int $mov_id
 * @property string $mov_tipo
 * @property int $mov_cantidad
 * @property Carbon $mov_fecha
 * @property int $mov_fk_producto
 * 
 * @property Producto $producto
 *
 * @package App\Models
 */
class MovimientoInventario extends Model
{
	protected $table = 'movimiento_inventario';
	protected $primaryKey = 'mov_id';
	public $timestamps = false;

	protected $casts = [
		'mov_cantidad' => 'int',
		'mov_fecha' => 'datetime',
		'mov_fk_producto' => 'int'
	];

	protected $fillable = [
		'mov_tipo',
		'mov_cantidad',
		'mov_fecha',
		'mov_fk_producto'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'mov_fk_producto');
	}
}
