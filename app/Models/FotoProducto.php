<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FotoProducto
 * 
 * @property int $fop_id
 * @property string $fop_ruta
 * @property int $fop_fk_producto
 * 
 * @property Producto $producto
 *
 * @package App\Models
 */
class FotoProducto extends Model
{
	protected $table = 'foto_producto';
	protected $primaryKey = 'fop_id';
	public $timestamps = false;

	protected $casts = [
		'fop_fk_producto' => 'int'
	];

	protected $fillable = [
		'fop_ruta',
		'fop_fk_producto'
	];

	public function producto()
	{
		return $this->belongsTo(Producto::class, 'fop_fk_producto');
	}
}
