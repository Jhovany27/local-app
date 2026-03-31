<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Direccion
 * 
 * @property int $drc_id
 * @property string $drc_calle
 * @property string $drc_numero
 * @property string $drc_colonia
 * @property string $drc_ciudad
 * @property string $drc_estado
 * @property int $drc_codigo_postal
 * @property string $drc_referencias
 * @property float $drc_latitud
 * @property float $drc_longitud
 * @property int $user_id
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Direccion extends Model
{
	protected $table = 'direccion';
	protected $primaryKey = 'drc_id';
	public $timestamps = false;

	protected $casts = [
		'drc_codigo_postal' => 'int',
		'drc_latitud' => 'float',
		'drc_longitud' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'drc_calle',
		'drc_numero',
		'drc_colonia',
		'drc_ciudad',
		'drc_estado',
		'drc_codigo_postal',
		'drc_referencias',
		'drc_latitud',
		'drc_longitud',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
