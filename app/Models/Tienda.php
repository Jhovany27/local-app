<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tienda
 * 
 * @property int $tie_id
 * @property string $tie_nombre
 * @property string $tie_descripcion
 * @property string $tie_telefono
 * @property float $tie_latitud
 * @property float $tie_longitud
 * @property string $tie_direccion
 * @property int $tie_estado
 * @property Carbon $tie_fecha_registro
 * @property int $user_id
 * 
 * @property User $user
 * @property Collection|DocumentoTienda[] $documento_tiendas
 *
 * @package App\Models
 */
class Tienda extends Model
{
	protected $table = 'tienda';
	protected $primaryKey = 'tie_id';
	public $timestamps = false;

	protected $casts = [
		'tie_latitud' => 'float',
		'tie_longitud' => 'float',
		'tie_estado' => 'boolean',
		'tie_fecha_registro' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'tie_nombre',
		'tie_descripcion',
		'tie_telefono',
		'tie_latitud',
		'tie_longitud',
		'tie_direccion',
		'tie_estado',
		'tie_fecha_registro',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function documento_tiendas()
	{
		return $this->hasMany(DocumentoTienda::class, 'dot_fk_tienda');
	}
}
