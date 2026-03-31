<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoriaProducto
 * 
 * @property int $cat_id
 * @property string $cat_nombre
 * @property string $cat_descripcion
 *
 * @package App\Models
 */
class CategoriaProducto extends Model
{
	protected $table = 'categoria_producto';
	protected $primaryKey = 'cat_id';
	public $timestamps = false;

	protected $fillable = [
		'cat_nombre',
		'cat_descripcion'
	];
}
