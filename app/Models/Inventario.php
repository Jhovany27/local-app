<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventario
 * 
 * @property int $inv_id
 * @property int $inv_stock_actual
 * @property int $inv_stock_minimo
 * @property Carbon $inv_actualizacion
 * @property int $inv_fk_producto
 * 
 * @property Producto $producto
 *
 * @package App\Models
 */
class Inventario extends Model
{
	protected $table = 'inventario';
	protected $primaryKey = 'inv_id';
	public $timestamps = false;

	protected $casts = [
		'inv_stock_actual' => 'int',
		'inv_stock_minimo' => 'int',
		'inv_actualizacion' => 'datetime',
		'inv_fk_producto' => 'int'
	];

	protected $fillable = [
		'inv_stock_actual',
		'inv_stock_minimo',
		'inv_actualizacion',
		'inv_fk_producto'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'inv_fk_producto');
	}
}
